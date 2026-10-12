<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk halaman feed laporan (daftar laporan terbaru dengan filter kategori).
 * Menampilkan laporan dengan status PENDING dan VERIFIED, serta detail laporan via AJAX.
 */
class FeedController extends Controller
{
    /**
     * Menampilkan halaman feed laporan dengan filter kategori dan paginasi.
     *
     * @param Request $request Objek request HTTP dengan query parameter 'category' (opsional)
     * @return View View feed dengan daftar laporan, filter kategori, dan label
     * @description Mengambil laporan dengan relasi place, user, confirmations.
     *              Filter status PENDING dan VERIFIED saja. Urutkan terbaru dulu.
     *              Filter kategori opsional. Paginasi 10 item per halaman.
     *              Siapkan label dan warna untuk freshness, status, kategori.
     *              Kirim ke view 'feed'.
     */
    public function index(Request $request)
    {
        $categories = ['CROWD', 'WIFI', 'DAMAGE', 'CLEANLINESS', 'AVAILABILITY', 'OTHER'];
        $selectedCategory = $request->query('category', '');

        $query = Report::with(['place', 'user', 'confirmations'])
            ->latest()
            ->whereIn('status', ['PENDING', 'VERIFIED']);

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }

        $reports = $query->paginate(10)->withQueryString();

        $categoryLabels = [
            'CROWD' => 'Kepadatan',
            'WIFI' => 'Wi-Fi',
            'DAMAGE' => 'Kerusakan',
            'CLEANLINESS' => 'Kebersihan',
            'AVAILABILITY' => 'Ketersediaan',
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

        return view('feed', compact(
            'reports',
            'categories',
            'categoryLabels',
            'selectedCategory',
            'freshnessLabels',
            'freshnessColors',
            'statusLabels',
            'statusColors'
        ));
    }

    /**
     * Mengembalikan detail laporan sebagai HTML untuk modal (AJAX).
     *
     * @param Request $request Objek request HTTP
     * @param int|string $id ID laporan (Report)
     * @return \Illuminate\Http\JsonResponse JSON berisi HTML ter-render dari component report-detail
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika laporan tidak ditemukan
     * @description Mengambil laporan dengan relasi place, user, confirmations.user.
     *              Siapkan label dan warna untuk freshness, status, kategori.
     *              Cek apakah user sudah mengonfirmasi laporan ini (alreadyConfirmed).
     *              Render component 'components.report-detail' dan kembalikan sebagai JSON.
     */
    public function detail(Request $request, $id)
    {
        $report = Report::with(['place', 'user', 'confirmations.user'])->findOrFail($id);

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
        $categoryLabels = [
            'CROWD' => 'Kepadatan',
            'WIFI' => 'Wi-Fi',
            'DAMAGE' => 'Kerusakan',
            'CLEANLINESS' => 'Kebersihan',
            'AVAILABILITY' => 'Ketersediaan',
            'OTHER' => 'Lainnya',
        ];

        $freshness = $report->freshness;
        $freshnessLabel = $freshnessLabels[$freshness] ?? $freshness;
        $freshnessColor = $freshnessColors[$freshness] ?? ['bg' => '#f9fafb', 'text' => '#374151', 'border' => '#d1d5db'];
        $statusLabel = $statusLabels[$report->status] ?? $report->status;
        $statusColor = $statusColors[$report->status] ?? ['bg' => '#f9fafb', 'text' => '#374151', 'border' => '#d1d5db'];
        $categoryLabel = $categoryLabels[$report->category] ?? $report->category;

        $confirmationsCount = $report->confirmations_count ?? 0;
        $stillValidCount = $report->still_valid_count ?? 0;

        $alreadyConfirmed = Auth::check() ? $report->alreadyConfirmedByUser(Auth::id()) : false;

        return response()->json([
            'html' => view('components.report-detail', compact(
                'report',
                'freshnessLabel',
                'freshnessColor',
                'statusLabel',
                'statusColor',
                'categoryLabel',
                'confirmationsCount',
                'stillValidCount',
                'alreadyConfirmed'
            ))->render()
        ]);
    }
}