<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Controller untuk membuat laporan kondisi fasilitas baru.
 * Menyediakan form laporan dan memproses pengiriman laporan dengan validasi.
 */
class ReportController extends Controller
{
    /**
     * Menampilkan halaman form buat laporan baru.
     *
     * @param Request $request Objek request HTTP dengan query parameter 'placeId' (opsional)
     * @return View View form laporan dengan daftar fasilitas, kategori, dan preselectPlaceId
     * @description Menampilkan form pembuatan laporan. Ambil semua fasilitas diurutkan nama.
     *              Siapkan daftar kategori laporan dengan label, ikon, dan deskripsi.
     *              Parameter 'placeId' dari query string untuk pre-select fasilitas di form.
     *              Kirim ke view 'report'.
     */
    public function index(Request $request): View
    {
        $preselectPlaceId = $request->query('placeId', '');

        $places = Place::orderBy('name')->get();
        $categories = [
            ['value' => 'CROWD', 'label' => 'Keramaian', 'icon' => '👥', 'desc' => 'Area terlalu ramai atau padat'],
            ['value' => 'WIFI', 'label' => 'Gangguan Wi-Fi', 'icon' => '📶', 'desc' => 'Wi-Fi lambat, tidak stabil, atau putus'],
            ['value' => 'DAMAGE', 'label' => 'Kerusakan Fasilitas', 'icon' => '🔧', 'desc' => 'Fasilitas rusak atau tidak berfungsi'],
            ['value' => 'CLEANLINESS', 'label' => 'Kebersihan', 'icon' => '🧹', 'desc' => 'Area kotor atau perlu dibersihkan'],
            ['value' => 'AVAILABILITY', 'label' => 'Ketersediaan Fasilitas', 'icon' => '🪑', 'desc' => 'Fasilitas penuh, tutup, atau tidak tersedia'],
            ['value' => 'OTHER', 'label' => 'Masalah Lainnya', 'icon' => '📝', 'desc' => 'Masalah lain yang tidak tercantum'],
        ];

        return view('report', compact('places', 'categories', 'preselectPlaceId'));
    }

    /**
     * Menyimpan laporan baru ke database.
     *
     * @param Request $request Objek request HTTP berisi place_id, category, description
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse Redirect atau JSON response
     * @throws ValidationException Jika validasi gagal (place_id tidak ada, category tidak valid, description kosong)
     * @description Validasi: place_id wajib ada di tabel places, category harus salah satu dari
     *              CROWD/WIFI/DAMAGE/CLEANLINESS/AVAILABILITY/OTHER, description min 5 max 1000 char.
     *              Buat laporan dengan user_id dari Auth, status default 'PENDING'.
     *              Jika request JSON, kembalikan JSON dengan success dan data report.
     *              Jika form biasa, redirect ke route 'report' dengan flash message success.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'place_id' => 'required|exists:places,id',
            'category' => 'required|in:CROWD,WIFI,DAMAGE,CLEANLINESS,AVAILABILITY,OTHER',
            'description' => 'required|string|min:5|max:1000',
        ]);

        $report = Report::create([
            'place_id' => $validated['place_id'],
            'user_id' => Auth::id(),
            'category' => $validated['category'],
            'description' => $validated['description'],
            'status' => 'PENDING',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'report' => $report->load('place'),
            ]);
        }

        return redirect()->route('report')
            ->with('success', 'Laporan berhasil dikirim! Terima kasih atas kontribusi Anda.');
    }
}