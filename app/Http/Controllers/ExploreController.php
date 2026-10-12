<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk halaman eksplorasi fasilitas (daftar fasilitas dengan filter dan pencarian).
 * Menyediakan daftar fasilitas, filter kategori/kondisi, pencarian, dan detail fasilitas via AJAX.
 */
class ExploreController extends Controller
{
    /**
     * Menampilkan halaman eksplorasi fasilitas dengan filter dan paginasi.
     *
     * @param Request $request Objek request HTTP dengan query parameter: search, category, condition
     * @return View View eksplorasi dengan data fasilitas, filter, dan label
     * @description Mengambil fasilitas dengan relasi campus, latestReport.user, confirmations.
     *              Filter pencarian (nama/deskripsi), filter kategori. Paginasi 12 item per halaman.
     *              Transform data untuk kartu fasilitas dengan label kategori, status, freshness,
     *              warna, dan statistik konfirmasi. Kirim ke view 'explore' dengan data filter.
     */
    public function index(Request $request)
    {
        $categories = ['LIBRARY', 'STUDY_ROOM', 'LABORATORY', 'CANTEEN', 'WIFI', 'OTHER'];
        $conditions = ['FRESH' => 'Segar', 'AGED' => 'Mulai Usang', 'EXPIRED' => 'Kedaluwarsa'];

        $search = $request->query('search', '');
        $category = $request->query('category', '');
        $condition = $request->query('condition', '');

        $query = Place::with(['campus', 'latestReport.user', 'latestReport.confirmations']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $places = $query->paginate(12)->withQueryString();

        // Transform data for place cards
        $placesData = $places->getCollection()->map(function ($place) {
            $report = $place->latestReport;
            $freshness = $report?->freshness ?? 'FRESH';
            $freshnessLabels = [
                'FRESH' => 'Segar',
                'AGED' => 'Mulai Usang',
                'EXPIRED' => 'Kedaluwarsa',
            ];
            $freshnessColors = [
                'FRESH' => ['bg' => '#f0fdf4', 'text' => '#166534', 'border' => '#bbf7d0'],
                'AGED' => ['bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fde68a'],
                'EXPIRED' => ['bg' => '#fef2f2', 'text' => '#991b1b', 'border' => '#fecaca'],
            ];
            $statusLabels = [
                'PENDING' => 'Menunggu',
                'VERIFIED' => 'Terverifikasi',
                'RESOLVED' => 'Terselesaikan',
                'REJECTED' => 'Ditolak',
            ];
            $statusColors = [
                'PENDING' => ['bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fde68a'],
                'VERIFIED' => ['bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#bfdbfe'],
                'RESOLVED' => ['bg' => '#f0fdf4', 'text' => '#166534', 'border' => '#bbf7d0'],
                'REJECTED' => ['bg' => '#fef2f2', 'text' => '#991b1b', 'border' => '#fecaca'],
            ];

            return [
                'id' => $place->id,
                'name' => $place->name,
                'category' => $place->category,
                'category_label' => $this->getCategoryLabel($place->category),
                'description' => $place->description,
                'floor' => $place->floor,
                'latitude' => $place->latitude ? (float)$place->latitude : null,
                'longitude' => $place->longitude ? (float)$place->longitude : null,
                'is_demo' => (bool)$place->is_demo,
                'latest_report' => $report ? [
                    'id' => $report->id,
                    'category' => $report->category,
                    'description' => $report->description,
                    'status' => $report->status,
                    'status_label' => $statusLabels[$report->status] ?? $report->status,
                    'freshness' => $freshness,
                    'freshness_label' => $freshnessLabels[$freshness] ?? $freshness,
                    'freshness_bg' => $freshnessColors[$freshness]['bg'] ?? '#f9fafb',
                    'freshness_text' => $freshnessColors[$freshness]['text'] ?? '#374151',
                    'freshness_border' => $freshnessColors[$freshness]['border'] ?? '#d1d5db',
                    'status_bg' => $statusColors[$report->status]['bg'] ?? '#f9fafb',
                    'status_text' => $statusColors[$report->status]['text'] ?? '#374151',
                    'status_border' => $statusColors[$report->status]['border'] ?? '#d1d5db',
                    'confirmations_count' => $report->confirmations_count ?? 0,
                    'still_valid_count' => $report->still_valid_count ?? 0,
                    'created_at' => $report->created_at?->toISOString(),
                ] : null,
            ];
        });

        $categoryLabels = [
            'LIBRARY' => 'Perpustakaan',
            'STUDY_ROOM' => 'Ruang Belajar',
            'LABORATORY' => 'Laboratorium',
            'CANTEEN' => 'Kantin',
            'WIFI' => 'Wi-Fi',
            'OTHER' => 'Lainnya',
        ];

        return view('explore', compact(
            'places',
            'placesData',
            'categories',
            'conditions',
            'categoryLabels',
            'search',
            'category',
            'condition'
        ));
    }

    /**
     * Mengembalikan detail fasilitas sebagai HTML untuk modal (AJAX).
     *
     * @param Request $request Objek request HTTP
     * @param int|string $id ID fasilitas (Place)
     * @return \Illuminate\Http\JsonResponse JSON berisi HTML ter-render dari component place-detail
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika fasilitas tidak ditemukan
     * @description Mengambil fasilitas dengan relasi campus, reports.user, reports.confirmations.
     *              Siapkan label dan warna untuk freshness, status, kategori. Ambil laporan terbaru
     *              dan riwayat laporan. Render component 'components.place-detail' dan kembalikan
     *              sebagai JSON untuk modal detail di frontend.
     */
    public function detail(Request $request, $id)
    {
        $place = Place::with(['campus', 'reports.user', 'reports.confirmations'])->findOrFail($id);

        $categoryLabels = [
            'LIBRARY' => 'Perpustakaan',
            'STUDY_ROOM' => 'Ruang Belajar',
            'LABORATORY' => 'Laboratorium',
            'CANTEEN' => 'Kantin',
            'WIFI' => 'Wi-Fi',
            'OTHER' => 'Lainnya',
        ];

        $freshnessLabels = [
            'FRESH' => 'Segar',
            'AGED' => 'Mulai Usang',
            'EXPIRED' => 'Kedaluwarsa',
        ];
        $freshnessColors = [
            'FRESH' => ['bg' => '#f0fdf4', 'text' => '#166534', 'border' => '#bbf7d0'],
            'AGED' => ['bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fde68a'],
            'EXPIRED' => ['bg' => '#fef2f2', 'text' => '#991b1b', 'border' => '#fecaca'],
        ];
        $statusLabels = [
            'PENDING' => 'Menunggu',
            'VERIFIED' => 'Terverifikasi',
            'RESOLVED' => 'Terselesaikan',
            'REJECTED' => 'Ditolak',
        ];
        $statusColors = [
            'PENDING' => ['bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fde68a'],
            'VERIFIED' => ['bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#bfdbfe'],
            'RESOLVED' => ['bg' => '#f0fdf4', 'text' => '#166534', 'border' => '#bbf7d0'],
            'REJECTED' => ['bg' => '#fef2f2', 'text' => '#991b1b', 'border' => '#fecaca'],
        ];

        $latestReport = $place->reports->first();
        $history = $place->reports->skip(1);

        return response()->json([
            'html' => view('components.place-detail', compact(
                'place',
                'latestReport',
                'history',
                'categoryLabels',
                'freshnessLabels',
                'freshnessColors',
                'statusLabels',
                'statusColors'
            ))->render()
        ]);
    }

    /**
     * Mengembalikan label bahasa Indonesia untuk kategori fasilitas.
     *
     * @param string $category Kode kategori (LIBRARY, STUDY_ROOM, LABORATORY, CANTEEN, WIFI, OTHER)
     * @return string Label bahasa Indonesia untuk kategori
     * @description Helper private untuk mapping kode kategori ke label tampilan.
     */
    private function getCategoryLabel(string $category): string
    {
        $labels = [
            'LIBRARY' => 'Perpustakaan',
            'STUDY_ROOM' => 'Ruang Belajar',
            'LABORATORY' => 'Laboratorium',
            'CANTEEN' => 'Kantin',
            'WIFI' => 'Wi-Fi',
            'OTHER' => 'Lainnya',
        ];
        return $labels[$category] ?? $category;
    }
}