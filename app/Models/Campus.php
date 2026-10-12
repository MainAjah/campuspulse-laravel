<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Campus - Merepresentasikan kampus/universitas dalam sistem.
 *
 * @description Model ini adalah entitas root untuk organisasi kampus. Setiap kampus
 *              memiliki banyak Place (tempat/fasilitas). Mendukung mode demo untuk
 *              keperluan presentasi/testing tanpa data nyata.
 *
 * @property int $id Primary key
 * @property string $name Nama kampus (contoh: "Universitas Indonesia")
 * @property string $city Kota lokasi kampus
 * @property bool $is_demo Flag mode demo (true = data sampel, tidak untuk produksi)
 * @property \Carbon\Carbon|null $created_at Waktu pembuatan record
 * @property \Carbon\Carbon|null $updated_at Waktu update terakhir
 *
 * @method \Illuminate\Database\Eloquent\Relations\HasMany places() Relasi ke semua tempat di kampus ini
 */
class Campus extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi massal (mass assignable).
     *
     * @description Field yang diperbolehkan di-create/update via create(), fill(), update().
     *              name dan city wajib, is_demo default false.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'city', 'is_demo'];

    /**
     * Casting atribut untuk tipe data otomatis.
     *
     * @description
     * - is_demo: boolean (true/false) - otomatis dikonversi dari tinyint/int database
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_demo' => 'boolean',
    ];

    /**
     * Relasi: Campus memiliki banyak Place (one-to-many).
     *
     * @description Foreign key: places.campus_id -> campuses.id
     *              Semua fasilitas/ruangan/area di kampus ini.
     *              Cascade delete: saat campus dihapus, places ikut terhapus (via migration FK).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function places()
    {
        return $this->hasMany(Place::class);
    }
}