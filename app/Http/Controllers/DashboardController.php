<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Report;
use Illuminate\View\View;

/**
 * Controller untuk halaman dashboard utama.
 * Menampilkan statistik ringkasan fasilitas, laporan, dan aktivitas terkini.
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan statistik ringkasan.
     *
     * @return View View dashboard dengan data statistik dan laporan terbaru
     * @description Menghitung dan menampilkan: total fasilitas, total laporan, laporan aktif
     *              (PENDING + VERIFIED), fasilitas bermasalah, 5 laporan terbaru dengan
     *              relasi place dan confirmations, serta distribusi laporan per kategori.
     *              Data dikirim ke view 'dashboard' melalui compact().
     */
    public function index(): View
    {
        $totalFacilities = Place::count();
        $totalReports = Report::count();
        $activeReports = Report::whereIn('status', ['PENDING', 'VERIFIED'])->count();
        $problemPlaces = Place::whereHas('reports', function ($q) {
            $q->whereIn('status', ['PENDING', 'VERIFIED']);
        })->count();

        $recentReports = Report::with(['place', 'confirmations'])
            ->whereIn('status', ['PENDING', 'VERIFIED'])
            ->latest()
            ->take(5)
            ->get();

        $reportsByCategory = Report::selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        return view('dashboard', compact(
            'totalFacilities',
            'totalReports',
            'activeReports',
            'problemPlaces',
            'recentReports',
            'reportsByCategory'
        ));
    }
}