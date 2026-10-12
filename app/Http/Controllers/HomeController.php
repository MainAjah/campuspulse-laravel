<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Controller untuk halaman beranda (home/landing page).
 * Menampilkan halaman utama aplikasi CampusPulse.
 */
class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda.
     *
     * @return View View halaman beranda (home.blade.php)
     * @description Menampilkan landing page aplikasi tanpa data tambahan.
     */
    public function index(): View
    {
        return view('home');
    }
}