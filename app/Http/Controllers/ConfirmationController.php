<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Controller untuk mengelola konfirmasi laporan oleh pengguna.
 * Memungkinkan user mengonfirmasi apakah laporan masih valid atau tidak, serta update status otomatis.
 */
class ConfirmationController extends Controller
{
    /**
     * Menyimpan konfirmasi baru untuk laporan.
     *
     * @param Request $request Objek request HTTP berisi report_id, still_valid (boolean), note (opsional)
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse Redirect atau JSON response
     * @throws ValidationException Jika: laporan sudah RESOLVED/REJECTED, user sudah konfirmasi, atau validasi gagal
     * @description Validasi: report_id wajib ada di tabel reports, still_valid wajib boolean, note max 255 char.
     *              Cek apakah laporan sudah tidak bisa dikonfirmasi (status RESOLVED/REJECTED).
     *              Cek duplikat konfirmasi oleh user yang sama.
     *              Buat konfirmasi baru dengan user_id dari Auth.
     *              Panggil updateReportStatus untuk update status laporan berdasarkan mayoritas konfirmasi.
     *              Jika request JSON, kembalikan JSON dengan data konfirmasi dan report fresh.
     *              Jika form biasa, redirect back dengan flash message success.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_id' => 'required|exists:reports,id',
            'still_valid' => 'required|boolean',
            'note' => 'nullable|string|max:255',
        ]);

        $report = Report::findOrFail($validated['report_id']);

        if (in_array($report->status, ['RESOLVED', 'REJECTED'])) {
            throw ValidationException::withMessages([
                'report_id' => 'Laporan ini sudah tidak dapat dikonfirmasi.',
            ]);
        }

        // Check for duplicate confirmation
        $existing = ReportConfirmation::where('report_id', $report->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            throw ValidationException::withMessages([
                'report_id' => 'Anda sudah mengonfirmasi laporan ini.',
            ]);
        }

        $confirmation = ReportConfirmation::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'still_valid' => $validated['still_valid'],
            'note' => $validated['note'] ?? null,
        ]);

        // Update report status based on majority confirmation
        $this->updateReportStatus($report);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'confirmation' => $confirmation,
                'report' => $report->fresh()->load('confirmations'),
            ]);
        }

        return redirect()->back()
            ->with('success', 'Konfirmasi berhasil dikirim.');
    }

    /**
     * Update status laporan berdasarkan mayoritas konfirmasi (private).
     *
     * @param Report $report Model Report yang akan diupdate statusnya
     * @return void
     * @description Hitung jumlah konfirmasi still_valid=true (validCount) dan false (invalidCount).
     *              Jika total konfirmasi >= 3:
     *              - Jika validCount > invalidCount dan status bukan VERIFIED -> update ke VERIFIED
     *              - Jika invalidCount > validCount dan status PENDING -> update ke REJECTED
     *              Logika mayoritas untuk memastikan laporan diverifikasi/ditolak berdasarkan konsensus.
     */
    private function updateReportStatus(Report $report): void
    {
        $confirmations = $report->confirmations;
        $validCount = $confirmations->where('still_valid', true)->count();
        $invalidCount = $confirmations->where('still_valid', false)->count();
        $total = $confirmations->count();

        if ($total >= 3) {
            if ($validCount > $invalidCount && $report->status !== 'VERIFIED') {
                $report->update(['status' => 'VERIFIED']);
            } elseif ($invalidCount > $validCount && $report->status === 'PENDING') {
                $report->update(['status' => 'REJECTED']);
            }
        }
    }
}