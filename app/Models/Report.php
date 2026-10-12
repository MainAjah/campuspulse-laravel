<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Report - Merepresentasikan laporan kondisi fasilitas kampus.
 *
 * @description Model inti sistem: laporan yang dibuat user mengenai kondisi tempat.
 *              Memiliki status lifecycle (PENDING -> VERIFIED -> RESOLVED/REJECTED),
 *              kategori (DAMAGE/CROWD/WIFI/OTHER), dan business logic untuk
 *              "freshness" (segar/expired) berdasarkan kategori dan waktu.
 *              Mendukung konfirmasi komunitas (ReportConfirmation) dan aksi moderasi.
 *
 * @property int $id Primary key
 * @property int $place_id Foreign key ke Place (tempat yang dilaporkan)
 * @property int $user_id Foreign key ke User (pelapor)
 * @property string $category Kategori laporan: 'DAMAGE' (rusak), 'CROWD' (penuh/keramaian), 'WIFI' (masalah wifi), 'OTHER'
 * @property string $description Deskripsi detail kondisi
 * @property string $status Status: 'PENDING' (menunggu verifikasi), 'VERIFIED' (dikonfirmasi komunitas), 'RESOLVED' (sudah diperbaiki), 'REJECTED' (ditolak moderator)
 * @property \Carbon\Carbon|null $created_at Waktu laporan dibuat
 * @property \Carbon\Carbon|null $updated_at Waktu update terakhir
 *
 * @property-read string $freshness Accessor: status kesegaran laporan (FRESH/AGED/EXPIRED)
 * @property-read int $confirmation_count Accessor: total konfirmasi dari user lain
 * @property-read int $still_valid_count Accessor: jumlah konfirmasi 'still_valid = true'
 *
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo place() Relasi ke tempat yang dilaporkan
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo user() Relasi ke user pelapor
 * @method \Illuminate\Database\Eloquent\Relations\HasMany confirmations() Relasi ke konfirmasi komunitas
 * @method \Illuminate\Database\Eloquent\Relations\HasMany moderationActions() Relasi ke aksi moderasi admin
 * @method static \Illuminate\Database\Eloquent\Builder scopeActive() Scope: laporan PENDING/VERIFIED
 * @method static \Illuminate\Database\Eloquent\Builder scopeFresh() Scope: laporan belum expired per kategori
 * @method bool alreadyConfirmedByUser(int $userId) Cek apakah user sudah konfirmasi laporan ini
 */
class Report extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi massal (mass assignable).
     *
     * @description Field yang diperbolehkan di-create/update via create(), fill(), update().
     *              place_id, user_id, category, description wajib. status default 'PENDING'.
     *
     * @var array<int, string>
     */
    protected $fillable = ['place_id', 'user_id', 'category', 'description', 'status'];

    /**
     * Casting atribut untuk tipe data otomatis.
     *
     * @description
     * - category: string (enum-like: DAMAGE/CROWD/WIFI/OTHER)
     * - status: string (enum-like: PENDING/VERIFIED/RESOLVED/REJECTED)
     *
     * @var array<string, string>
     */
    protected $casts = [
        'category' => 'string',
        'status' => 'string',
    ];

    /**
     * Relasi: Report milik satu Place (many-to-one / belongsTo).
     *
     * @description Foreign key: reports.place_id -> places.id
     *              Setiap laporan pasti terkait satu tempat/fasilitas.
     *
     * @return BelongsTo
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * Relasi: Report dibuat oleh satu User (many-to-one / belongsTo).
     *
     * @description Foreign key: reports.user_id -> users.id
     *              User yang membuat laporan ini.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Report memiliki banyak ReportConfirmation (one-to-many).
     *
     * @description Foreign key: report_confirmations.report_id -> reports.id
     *              Konfirmasi dari user lain: apakah kondisi masih valid (still_valid=true)
     *              atau sudah tidak (still_valid=false). Digunakan untuk verifikasi komunitas.
     *
     * @return HasMany
     */
    public function confirmations(): HasMany
    {
        return $this->hasMany(ReportConfirmation::class);
    }

    /**
     * Relasi: Report memiliki banyak ModerationAction (one-to-many).
     *
     * @description Foreign key: moderation_actions.report_id -> reports.id
     *              Riwayat aksi moderator: approve, reject, delete, dll.
     *              Hanya admin yang bisa membuat record di sini.
     *
     * @return HasMany
     */
    public function moderationActions(): HasMany
    {
        return $this->hasMany(ModerationAction::class);
    }

    /**
     * Query Scope: Filter laporan aktif (PENDING atau VERIFIED).
     *
     * @description Business logic: laporan "aktif" = masih relevan untuk ditampilkan
     *              di dashboard/peta. RESOLVED/REJECTED sudah tidak aktif.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['PENDING', 'VERIFIED']);
    }

    /**
     * Query Scope: Filter laporan "fresh" (belum expired) per kategori.
     *
     * @description Business logic: TTL (Time-To-Live) berbeda per kategori:
     *              - CROWD: default 60 menit (config campuspulse.crowd_ttl)
     *              - WIFI:  default 45 menit (config campuspulse.wifi_ttl)
     *              - DAMAGE: tidak expire (selalu fresh sampai RESOLVED/REJECTED)
     *              - OTHER:  tidak expire (default fresh)
     *              Digunakan untuk menampilkan hanya laporan yang masih relevan waktu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFresh($query)
    {
        return $query->where(function ($q) {
            $q->where('category', 'DAMAGE')
              ->orWhere(function ($q2) {
                  $q2->where('category', 'CROWD')
                     ->where('created_at', '>=', now()->subMinutes(config('campuspulse.crowd_ttl', 60)));
              })
              ->orWhere(function ($q2) {
                  $q2->where('category', 'WIFI')
                     ->where('created_at', '>=', now()->subMinutes(config('campuspulse.wifi_ttl', 45)));
              });
        });
    }

    /**
     * Accessor: Menghitung status kesegaran (freshness) laporan.
     *
     * @description Business logic utama untuk menentukan apakah laporan masih relevan:
     *              - RESOLVED/REJECTED -> EXPIRED (sudah selesai/ditolak)
     *              - CROWD: <50% TTL = FRESH, 50-100% TTL = AGED, >TTL = EXPIRED
     *              - WIFI:  <50% TTL = FRESH, 50-100% TTL = AGED, >TTL = EXPIRED
     *              - DAMAGE: selalu FRESH (rusak fisik tidak auto-expire)
     *              - OTHER/default: FRESH
     *              Digunakan UI untuk badge warna (hijau/kuning/merah) dan sorting.
     *
     * @return string 'FRESH' | 'AGED' | 'EXPIRED'
     */
    public function getFreshnessAttribute(): string
    {
        if ($this->status === 'RESOLVED' || $this->status === 'REJECTED') {
            return 'EXPIRED';
        }

        $minutesAgo = $this->created_at->diffInMinutes(now());

        switch ($this->category) {
            case 'CROWD':
                if ($minutesAgo <= config('campuspulse.crowd_ttl', 60) * 0.5) return 'FRESH';
                if ($minutesAgo <= config('campuspulse.crowd_ttl', 60)) return 'AGED';
                return 'EXPIRED';

            case 'WIFI':
                if ($minutesAgo <= config('campuspulse.wifi_ttl', 45) * 0.5) return 'FRESH';
                if ($minutesAgo <= config('campuspulse.wifi_ttl', 45)) return 'AGED';
                return 'EXPIRED';

            case 'DAMAGE':
                return 'FRESH';

            default:
                return 'FRESH';
        }
    }

    /**
     * Accessor: Total jumlah konfirmasi pada laporan ini.
     *
     * @description Menghitung semua ReportConfirmation terkait (still_valid true/false).
     *              Digunakan untuk menampilkan "X orang mengonfirmasi".
     *
     * @return int
     */
    public function getConfirmationCountAttribute(): int
    {
        return $this->confirmations->count();
    }

    /**
     * Accessor: Jumlah konfirmasi 'masih valid' (still_valid = true).
     *
     * @description Menghitung konfirmasi yang menyatakan kondisi masih terjadi.
     *              Digunakan untuk threshold verifikasi: jika still_valid_count >= 3
     *              maka status bisa naik ke VERIFIED (logika di service/controller).
     *
     * @return int
     */
    public function getStillValidCountAttribute(): int
    {
        return $this->confirmations->where('still_valid', true)->count();
    }

    /**
     * Cek apakah user tertentu sudah memberikan konfirmasi pada laporan ini.
     *
     * @description Business logic: mencegah double konfirmasi dari user yang sama.
     *              User hanya bisa konfirmasi sekali per laporan.
     *
     * @param int $userId ID user yang dicek
     * @return bool True jika user sudah konfirmasi, false jika belum
     */
    public function alreadyConfirmedByUser(int $userId): bool
    {
        return $this->confirmations->where('user_id', $userId)->isNotEmpty();
    }
}