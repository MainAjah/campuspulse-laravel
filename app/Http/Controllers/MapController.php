<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Campus;
use Illuminate\Http\Request;

/**
 * Controller untuk halaman peta interaktif fasilitas kampus.
 * Menampilkan fasilitas pada peta dengan filter kategori dan detail laporan terkini.
 */
class MapController extends Controller
{
    /**
     * Menampilkan halaman peta dengan fasilitas dan filter kategori.
     *
     * @param Request $request Objek request HTTP dengan query parameter 'category' (default: 'ALL')
     * @return View View peta dengan data fasilitas, kategori, dan center map
     * @description Mengambil semua fasilitas dengan relasi campus, latestReport.user, dan
     *              confirmations. Filter berdasarkan kategori jika bukan 'ALL'. Transform
     *              data untuk JavaScript map dengan label kategori, status, freshness,
     *              warna, dan koordinat. Hitung center map dari rata-rata koordinat fasilitas
     *              valid (default Jakarta jika tidak ada). Kirim ke view 'map'.
     */
    public function index(Request $request)
    {
        $categories = ['LIBRARY', 'STUDY_ROOM', 'LABORATORY', 'CANTEEN', 'WIFI', 'OTHER'];
        $selectedCategory = $request->query('category', 'ALL');

        $query = Place::with(['campus', 'latestReport.user', 'latestReport.confirmations']);

        if ($selectedCategory !== 'ALL') {
            $query->where('category', $selectedCategory);
        }

        $places = $query->get();

        // Transform data for JavaScript map
        $placesData = $places->map(function ($place) {
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
                    'created_at' => $report->created_at?->toISOString(),
                ] : null,
            ];
        });

        // Compute map center from places
        $validPlaces = $placesData->filter(fn($p) => $p['latitude'] && $p['longitude']);
        $center = $validPlaces->isNotEmpty()
            ? [$validPlaces->avg('latitude'), $validPlaces->avg('longitude')]
            : [-6.2, 106.8]; // Default Jakarta

        return view('map', compact('places', 'placesData', 'categories', 'selectedCategory', 'center'));
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