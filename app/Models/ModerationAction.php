<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model ModerationAction - Merepresentasikan aksi moderasi admin pada laporan.
 *
 * @description Model audit trail untuk mencatat semua aksi moderator (admin) terhadap laporan.
 *              Setiap kali admin approve, reject, delete, atau aksi lain, record dibuat di sini.
 *              Menyediakan jejak transparan: siapa, kapan, aksi apa, dan catatan alasannya.
 *              Hanya user dengan role ADMIN yang dapat membuat record ini.
 *
 * @property int $id Primary key
 * @property int $report_id Foreign key ke Report yang dimoderasi
 * @property int $moderator_id Foreign key ke User (admin) yang melakukan aksi
 * @property string $action Jenis aksi: 'APPROVE' (setujui/verified), 'REJECT' (tolak), 'DELETE' (hapus), 'RESOLVE' (tandai selesai), 'UNRESOLVE' (buka lagi)
 * @property string|null $note Catatan/alasan dari moderator (opsional tapi direkomendasikan)
 * @property \Carbon\Carbon|null $created_at Waktu aksi dilakukan
 * @property \Carbon\Carbon|null $updated_at Waktu update terakhir
 *
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo report() Relasi ke laporan yang dimoderasi
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo moderator() Relasi ke user admin yang bertindak
 */
class ModerationAction extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi massal (mass assignable).
     *
     * @description Field yang diperbolehkan di-create/update via create(), fill(), update().
     *              report_id, moderator_id, action wajib. note opsional.
     *
     * @var array<int, string>
     */
    protected $fillable = ['report_id', 'moderator_id', 'action', 'note'];

    /**
     * Casting atribut untuk tipe data otomatis.
     *
     * @description
     * - action: string (enum-like: APPROVE/REJECT/DELETE/RESOLVE/UNRESOLVE)
     *
     * @var array<string, string>
     */
    protected $casts = [
        'action' => 'string',
    ];

    /**
     * Relasi: Aksi moderasi milik satu Report (many-to-one / belongsTo).
     *
     * @description Foreign key: moderation_actions.report_id -> reports.id
     *              Setiap aksi pasti terkait satu laporan.
     *
     * @return BelongsTo
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Relasi: Aksi moderasi dilakukan oleh satu User/Moderator (many-to-one / belongsTo).
     *
     * @description Foreign key: moderation_actions.moderator_id -> users.id
     *              User dengan role ADMIN yang melakukan aksi moderasi.
     *              Menggunakan custom FK 'moderator_id' (bukan 'user_id').
     *
     * @return BelongsTo
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }
}