<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Place - Merepresentasikan tempat/fasilitas di kampus.
 *
 * @description Model ini menyimpan data lokasi fisik (gedung, ruangan, area outdoor, dll)
 *              yang dapat dilaporkan kondisinya. Setiap place milik satu Campus.
 *              Mencakup koordinat GPS untuk pemetaan dan informasi lantai.
 *
 * @property int $id Primary key
 * @property int $campus_id Foreign key ke Campus
 * @property string $name Nama tempat (contoh: "Gedung A Lt.2 Ruang 201")
 * @property string $category Kategori tempat: 'CLASSROOM', 'LAB', 'LIBRARY', 'CANTEEN', 'TOILET', 'HALLWAY', 'OUTDOOR', 'OTHER'
 * @property string|null $description Deskripsi tambahan lokasi
 * @property float|null $latitude Latitude koordinat GPS (presisi 7 desimal)
 * @property float|null $longitude Longitude koordinat GPS (presisi 7 desimal)
 * @property int|null $floor Nomor lantai (null untuk outdoor)
 * @property \Carbon\Carbon|null $created_at Waktu pembuatan record
 * @property \Carbon\Carbon|null $updated_at Waktu update terakhir
 *
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo campus() Relasi ke kampus induk
 * @method \Illuminate\Database\Eloquent\Relations\HasMany reports() Relasi ke semua laporan di tempat ini
 * @method \Illuminate\Database\Eloquent\Relations\HasOne latestReport() Relasi ke laporan terbaru (latestOfMany)
 * @method \Illuminate\Database\Eloquent\Relations\HasMany activeReports() Relasi ke laporan aktif (PENDING/VERIFIED)
 */
class Place extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi massal (mass assignable).
     *
     * @description Field yang diperbolehkan di-create/update via create(), fill(), update().
     *              campus_id wajib, name wajib, category wajib, siswa opsional.
     *
     * @var array<int, string>
     */
    protected $fillable = ['campus_id', 'name', 'category', 'description', 'latitude', 'longitude', 'floor'];

    /**
     * Casting atribut untuk tipe data otomatis.
     *
     * @description
     * - latitude/longitude: decimal dengan 7 digit presisi (sekitar 1.1 meter akurasi)
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    /**
     * Relasi: Place milik satu Campus (many-to-one / belongsTo).
     *
     * @description Foreign key: places.campus_id -> campuses.id
     *              Setiap tempat pasti berada di satu kampus.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    /**
     * Relasi: Place memiliki banyak Report (one-to-many).
     *
     * @description Foreign key: reports.place_id -> places.id
     *              Semua laporan kondisi fasilitas di tempat ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Relasi: Place memiliki satu Report terbaru (hasOne + latestOfMany).
     *
     * @description Mengambil laporan dengan created_at paling baru untuk tempat ini.
     *              Digunakan untuk quick-view status terbaru di dashboard/peta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestReport()
    {
        return $this->hasOne(Report::class)->latestOfMany();
    }

    /**
     * Relasi: Place memiliki banyak Report aktif (PENDING atau VERIFIED).
     *
     * @description Filter laporan yang statusnya masih 'PENDING' atau 'VERIFIED'.
     *              Laporan RESOLVED/REJECTED tidak शामिल. Digunakan untuk menampilkan
     *              indikator masalah berjalan di peta/daftar.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeReports()
    {
        return $this->hasMany(Report::class)->whereIn('status', ['PENDING', 'VERIFIED']);
    }
}