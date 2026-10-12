<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model ReportConfirmation - Merepresentasikan konfirmasi komunitas pada laporan.
 *
 * @description Model untuk sistem verifikasi berbasis komunitas (crowd-verification).
 *              User lain dapat mengkonfirmasi apakah kondisi yang dilaporkan "masih valid"
 *              (masih terjadi) atau "tidak valid" (sudah tidak terjadi/sudah diperbaiki).
 *              Digunakan untuk menaikkan status laporan dari PENDING ke VERIFIED
 *              saat cukup banyak konfirmasi still_valid=true.
 *
 * @property int $id Primary key
 * @property int $report_id Foreign key ke Report yang dikonfirmasi
 * @property int $user_id Foreign key ke User yang memberikan konfirmasi
 * @property bool $still_valid Status konfirmasi: true = kondisi masih terjadi, false = sudah tidak terjadi
 * @property string|null $note Catatan opsional dari user konfirmasi
 * @property \Carbon\Carbon|null $created_at Waktu konfirmasi dibuat
 * @property \Carbon\Carbon|null $updated_at Waktu update terakhir
 *
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo report() Relasi ke laporan yang dikonfirmasi
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo user() Relasi ke user yang konfirmasi
 */
class ReportConfirmation extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi massal (mass assignable).
     *
     * @description Field yang diperbolehkan di-create/update via create(), fill(), update().
     *              report_id, user_id, still_valid wajib. note opsional.
     *
     * @var array<int, string>
     */
    protected $fillable = ['report_id', 'user_id', 'still_valid', 'note'];

    /**
     * Casting atribut untuk tipe data otomatis.
     *
     * @description
     * - still_valid: boolean (true/false) - otomatis dikonversi dari tinyint/int database
     *
     * @var array<string, string>
     */
    protected $casts = [
        'still_valid' => 'boolean',
    ];

    /**
     * Relasi: Konfirmasi milik satu Report (many-to-one / belongsTo).
     *
     * @description Foreign key: report_confirmations.report_id -> reports.id
     *              Setiap konfirmasi pasti terkait satu laporan.
     *
     * @return BelongsTo
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Relasi: Konfirmasi dibuat oleh satu User (many-to-one / belongsTo).
     *
     * @description Foreign key: report_confirmations.user_id -> users.id
     *              User yang memberikan konfirmasi (bukan pelapor asli).
     *              Business rule: user tidak bisa konfirmasi laporan sendiri (divalidasi di controller).
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}