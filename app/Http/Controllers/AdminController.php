<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Report;
use App\Models\ModerationAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controller untuk panel admin (manajemen fasilitas, moderasi laporan, riwayat moderasi).
 * Menyediakan tab: places (CRUD fasilitas), reports (moderasi laporan), history (riwayat aksi moderasi), dan dashboard statistik.
 */
class AdminController extends Controller
{
    /**
     * Menampilkan halaman admin dengan tab yang dipilih.
     *
     * @param Request $request Objek request HTTP dengan query parameter 'tab' (default: 'places'), 'status' (untuk tab reports)
     * @return View View admin sesuai tab: places, reports, history, atau dashboard statistik
     * @description Tab 'places': daftar fasilitas dengan relasi campus dan reports (paginasi 15).
     *              Tab 'reports': daftar laporan PENDING/VERIFIED dengan filter status (paginasi 15).
     *              Tab 'history': riwayat aksi moderasi dengan relasi report.place dan moderator (paginasi 20).
     *              Tab lain (default): dashboard statistik total laporan per status, total fasilitas, total user,
     *              dan distribusi laporan per kategori.
     */
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'places');

        if ($tab === 'places') {
            $places = Place::with(['campus', 'reports'])->paginate(15);
            return view('admin.places', compact('places', 'tab'));
        }

        if ($tab === 'reports') {
            $status = $request->query('status', '');
            $query = Report::with(['place', 'user', 'confirmations'])
                ->whereIn('status', ['PENDING', 'VERIFIED'])
                ->latest();

            if ($status) {
                $query->where('status', $status);
            }

            $reports = $query->paginate(15);
            return view('admin.reports', compact('reports', 'tab', 'status'));
        }

        if ($tab === 'history') {
            $actions = ModerationAction::with(['report.place', 'moderator'])
                ->latest()
                ->paginate(20);
            return view('admin.history', compact('actions', 'tab'));
        }

        $stats = [
            'totalReports' => Report::count(),
            'pending' => Report::where('status', 'PENDING')->count(),
            'verified' => Report::where('status', 'VERIFIED')->count(),
            'resolved' => Report::where('status', 'RESOLVED')->count(),
            'rejected' => Report::where('status', 'REJECTED')->count(),
            'totalPlaces' => Place::count(),
            'totalUsers' => \App\Models\User::count(),
        ];

        $reportsByCategory = Report::selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        return view('admin.index', compact('stats', 'reportsByCategory', 'tab'));
    }

    /**
     * Menyimpan fasilitas baru (admin).
     *
     * @param Request $request Objek request HTTP berisi data fasilitas
     * @return \Illuminate\Http\RedirectResponse Redirect ke admin places dengan flash success
     * @throws ValidationException Jika validasi gagal
     * @description Validasi: campus_id wajib ada di campuses, name max 120 char, category harus valid,
     *              description max 500 char, latitude -90 s.d 90, longitude -180 s.d 180,
     *              floor max 20 char, is_demo boolean.
     *              Buat fasilitas baru dan redirect dengan pesan sukses.
     */
    public function placesStore(Request $request)
    {
        $validated = $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'name' => 'required|string|max:120',
            'category' => 'required|in:LIBRARY,STUDY_ROOM,LABORATORY,CANTEEN,WIFI,OTHER',
            'description' => 'nullable|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'floor' => 'nullable|string|max:20',
            'is_demo' => 'boolean',
        ]);

        Place::create($validated);

        return redirect()->route('admin', ['tab' => 'places'])
            ->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    /**
     * Memperbarui fasilitas yang ada (admin).
     *
     * @param Request $request Objek request HTTP berisi data fasilitas yang diupdate
     * @param Place $place Model Place yang di-update (route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirect ke admin places dengan flash success
     * @throws ValidationException Jika validasi gagal
     * @description Validasi sama seperti placesStore tapi tanpa campus_id (tidak bisa pindah campus).
     *              Update fasilitas dan redirect dengan pesan sukses.
     */
    public function placesUpdate(Request $request, Place $place)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'category' => 'required|in:LIBRARY,STUDY_ROOM,LABORATORY,CANTEEN,WIFI,OTHER',
            'description' => 'nullable|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'floor' => 'nullable|string|max:20',
            'is_demo' => 'boolean',
        ]);

        $place->update($validated);

        return redirect()->route('admin', ['tab' => 'places'])
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    /**
     * Menghapus fasilitas (admin).
     *
     * @param Place $place Model Place yang akan dihapus (route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirect ke admin places dengan flash success
     * @description Hapus fasilitas dari database (soft delete jika model menggunakan SoftDeletes).
     *              Redirect dengan pesan sukses.
     */
    public function placesDestroy(Place $place)
    {
        $place->delete();
        return redirect()->route('admin', ['tab' => 'places'])
            ->with('success', 'Fasilitas berhasil dihapus.');
    }

    /**
     * Memoderasi laporan (admin) - verify, resolve, atau reject.
     *
     * @param Request $request Objek request HTTP berisi action (VERIFY/RESOLVE/REJECT) dan note (opsional)
     * @param Report $report Model Report yang akan dimoderasi (route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirect back dengan flash success
     * @throws ValidationException Jika action tidak valid
     * @description Validasi: action wajib VERIFY/RESOLVE/REJECT, note max 255 char.
     *              Mapping action ke status: VERIFY->VERIFIED, RESOLVE->RESOLVED, REJECT->REJECTED.
     *              Update status laporan dan catat ModerationAction dengan moderator_id dari Auth.
     *              Redirect back dengan pesan sukses.
     */
    public function moderate(Request $request, Report $report)
    {
        $validated = $request->validate([
            'action' => 'required|in:VERIFY,RESOLVE,REJECT',
            'note' => 'nullable|string|max:255',
        ]);

        $action = $validated['action'];
        $note = $validated['note'] ?? '';

        $statusMap = [
            'VERIFY' => 'VERIFIED',
            'RESOLVE' => 'RESOLVED',
            'REJECT' => 'REJECTED',
        ];

        $report->update(['status' => $statusMap[$action]]);

        ModerationAction::create([
            'report_id' => $report->id,
            'moderator_id' => Auth::id(),
            'action' => $action,
            'note' => $note,
        ]);

        return redirect()->back()->with('success', 'Moderasi berhasil dilakukan.');
    }
}