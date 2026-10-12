<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User - Merepresentasikan pengguna platform CampusPulse.
 *
 * @description Model ini mengelola data pengguna (mahasiswa, admin) yang dapat membuat laporan,
 *              mengkonfirmasi laporan, serta melakukan moderasi. Mewarisi Authenticatable
 *              untuk otentikasi Laravel dan Notifiable untuk notifikasi.
 *
 * @property int $id Primary key
 * @property string $name Nama lengkap pengguna
 * @property string $email Email unik untuk login
 * @property string $password Password yang di-hash otomatis
 * @property string $role Peran pengguna: 'USER' (default) atau 'ADMIN'
 * @property \Carbon\Carbon|null $email_verified_at Waktu verifikasi email
 * @property \Carbon\Carbon|null $created_at Waktu pembuatan record
 * @property \Carbon\Carbon|null $updated_at Waktu update terakhir
 *
 * @method bool isAdmin() Cek apakah pengguna adalah admin
 * @method \Illuminate\Database\Eloquent\Relations\HasMany reports() Relasi ke laporan yang dibuat
 * @method \Illuminate\Database\Eloquent\Relations\HasMany confirmations() Relasi ke konfirmasi yang diberikan
 * @method \Illuminate\Database\Eloquent\Relations\HasMany moderationActions() Relasi ke aksi moderasi yang dilakukan
 */
#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Konfigurasi casting atribut untuk tipe data otomatis.
     *
     * @description
     * - email_verified_at: dikonversi ke instance Carbon (datetime)
     * - password: di-hash otomatis saat diset (hashed)
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Cek apakah pengguna memiliki peran admin.
     *
     * @description Business logic: hanya user dengan role 'ADMIN' yang bisa mengakses
     *              fitur moderasi (approve/reject laporan, hapus laporan, dll).
     *
     * @return bool True jika role adalah 'ADMIN', false jika 'USER' atau lainnya
     */
    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    /**
     * Relasi: User memiliki banyak Report (one-to-many).
     *
     * @description Foreign key: reports.user_id -> users.id
     *              User dapat membuat banyak laporan kondisi fasilitas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Relasi: User memiliki banyak ReportConfirmation (one-to-many).
     *
     * @description Foreign key: report_confirmations.user_id -> users.id
     *              User dapat mengkonfirmasi (still_valid / tidak valid) laporan orang lain.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function confirmations()
    {
        return $this->hasMany(ReportConfirmation::class);
    }

    /**
     * Relasi: User (sebagai moderator) memiliki banyak ModerationAction (one-to-many).
     *
     * @description Foreign key: moderation_actions.moderator_id -> users.id
     *              Hanya user dengan role ADMIN yang seharusnya memiliki record di sini.
     *              Mencatat aksi moderasi: approve, reject, delete, dll.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moderationActions()
    {
        return $this->hasMany(ModerationAction::class, 'moderator_id');
    }
}