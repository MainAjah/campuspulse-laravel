# CampusPulse

Platform informasi kondisi fasilitas kampus berbasis laporan komunitas mahasiswa. CampusPulse memungkinkan mahasiswa melaporkan, mengkonfirmasi, dan memantau kondisi fasilitas kampus secara real-time melalui peta interaktif.

---

## 📋 Deskripsi Proyek

CampusPulse adalah aplikasi web yang dibangun untuk membantu mahasiswa dan pengelola kampus dalam memantau kondisi fasilitas kampus. Mahasiswa dapat:
- Melihat peta fasilitas kampus secara interaktif
- Melaporkan kondisi fasilitas (kerusakan, kebersihan, keramaian, Wi-Fi, dll)
- Mengkonfirmasi laporan mahasiswa lain
- Melihat feed laporan terbaru

Sementara admin dapat:
- Mengelola data fasilitas (tambah, edit, hapus)
- Memoderasi laporan (verifikasi, tolak, selesaikan)
- Memantau statistik penggunaan

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| **Peta Interaktif** | Leaflet.js dengan marker fasilitas, popup detail, dan filter kategori |
| **Eksplorasi Fasilitas** | Daftar tempat dengan pencarian, filter kategori, dan halaman detail |
| **Feed Laporan** | Timeline laporan terbaru dengan status (Pending/Verified/Resolved/Rejected) |
| **Buat Laporan** | Form laporan dengan kategori, deskripsi, foto (opsional), dan lokasi otomatis |
| **Konfirmasi** | Mahasiswa lain bisa konfirmasi "Masih Berlaku" atau "Sudah Diperbaiki" |
| **Dashboard** | Ringkasan statistik pribadi (laporan saya, konfirmasi, poin) |
| **Admin Panel** | Kelola tempat, moderasi laporan, statistik global |
| **Autentikasi** | Login/Register dengan role USER dan ADMIN |
| **Responsive UI** | Tailwind CSS, mobile-first, dark mode support |

---

## 🛠 Teknologi Stack

| Layer | Teknologi |
|-------|-----------|
| **Backend** | Laravel 11 (PHP 8.3) |
| **Frontend** | Blade Templates + Vanilla JavaScript (ES Modules) |
| **Styling** | Tailwind CSS v4 (via Vite) |
| **Database** | MySQL (MariaDB via XAMPP) |
| **Peta** | Leaflet.js + OpenStreetMap |
| **Build Tool** | Vite |
| **Testing** | PHPUnit (Feature & Unit) |
| **Code Style** | Laravel Pint |

---

## 📁 Struktur Folder Utama

```
campuspulse-laravel/
├── app/
│   ├── Http/Controllers/       # Controller (Home, Dashboard, Map, Explore, Feed, Report, Auth, Admin, Confirmation)
│   ├── Models/                 # Eloquent Models (User, Campus, Place, Report, ReportConfirmation, ModerationAction)
│   └── Middleware/             # AdminMiddleware, VerifyCsrfToken
├── bootstrap/                  # Laravel bootstrap
├── config/                     # Config files (app, database, auth, cors, etc.)
├── database/
│   ├── factories/              # Model factories
│   ├── migrations/             # Schema migrations (users, campuses, places, reports, confirmations, moderation_actions)
│   └── seeders/                # DatabaseSeeder, CampusPulseDemoSeeder
├── public/                     # Entry point, assets, leaflet.css/js
├── resources/
│   ├── css/app.css             # Tailwind imports
│   ├── js/                     # Vanilla JS modules (map, report, feed, explore, admin)
│   └── views/                  # Blade templates
│       ├── layouts/            # app.blade.php, guest.blade.php
│       ├── components/         # Reusable components
│       ├── admin/              # Admin panel views
│       ├── auth/               # Login, Register
│       └── *.blade.php         # Home, dashboard, map, explore, feed, report
├── routes/
│   ├── web.php                 # Web routes
│   └── api.php                 # API routes (jika ada)
├── storage/                    # Logs, framework cache, uploaded files
├── tests/                      # Feature & Unit tests
├── vendor/                     # Composer dependencies
├── node_modules/               # NPM dependencies
├── .env                        # Environment config (copy dari .env.example)
├── .env.example                # Template environment
├── composer.json               # PHP dependencies
├── package.json                # JS dependencies
├── vite.config.js              # Vite configuration
├── phpunit.xml                 # PHPUnit config
└── artisan                     # CLI entry point
```

---

## 💻 Cara Instalasi di Windows (VS Code + XAMPP)

### Prasyarat
- **XAMPP** (Apache + MySQL/MariaDB + PHP 8.3+) — [Download](https://www.apachefriends.org/download.html)
- **Composer** — [Download](https://getcomposer.org/download/)
- **Node.js 20+** — [Download](https://nodejs.org/)
- **Git** — [Download](https://git-scm.com/)
- **VS Code** — [Download](https://code.visualstudio.com/)

> ⚠️ Pastikan `php`, `composer`, `node`, `npm`, dan `mysql` sudah ada di **System PATH**.

---

### 1. Clone Repository
```bash
git clone <repository-url> campuspulse-laravel
cd campuspulse-laravel
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Install Dependensi Node.js
```bash
npm install
```

### 4. Konfigurasi Environment
```bash
copy .env.example .env
```
Edit file `.env` (lihat bagian [Konfigurasi .env](#-konfigurasi-env)).

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Setup Database MySQL (XAMPP)
1. Buka **XAMPP Control Panel** → Start **Apache** dan **MySQL**
2. Buka `http://localhost/phpmyadmin`
3. Buat database baru: `campuspulse_laravel` (Collation: `utf8mb4_unicode_ci`)
4. (Opsional) Buat user `campuspulse` dengan password dan berikan privileges ke database tersebut

### 7. Jalankan Migrasi & Seeder
```bash
php artisan migrate --seed
```
> Seeder `CampusPulseDemoSeeder` akan membuat:
> - 1 kampus demo (Jakarta)
> - 2 user: **demo@campuspulse.id** / **demo1234** (USER) dan **admin@campuspulse.id** / **admin1234** (ADMIN)
> - 7 tempat demo (perpustakaan, ruang belajar, lab komputer, kantin, Wi-Fi, ruang rapat)
> - 4 laporan demo dengan berbagai status
> - Konfirmasi dan aksi moderasi demo

### 8. Build Asset Frontend
```bash
npm run build
```
> Untuk development dengan hot reload: `npm run dev` (jalankan di terminal terpisah)

### 9. Jalankan Server Development
```bash
php artisan serve --port=3200
```
Akses: **http://localhost:3200**

---

## ⚙️ Konfigurasi .env

Salin `.env.example` ke `.env` dan sesuaikan nilai berikut:

```env
APP_NAME=CampusPulse
APP_ENV=local
APP_KEY=base64:xxx               # Dihasilkan oleh php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:3200

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campuspulse_laravel
DB_USERNAME=campuspulse         # Atau root jika pakai default XAMPP
DB_PASSWORD=your_password       # Kosongkan jika root tanpa password

SESSION_DRIVER=database
SESSION_LIFETIME=120

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@campuspulse.id"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

> **Catatan**: Untuk XAMPP default, `DB_USERNAME=root` dan `DB_PASSWORD=` (kosong).

---

## 🗄 Migrasi Database

Tabel utama yang dibuat oleh migrasi:

| Migrasi | Tabel | Deskripsi |
|---------|-------|-----------|
| `0001_01_01_000000_create_users_table` | `users` | User dengan kolom `role` (USER/ADMIN) |
| `0001_01_01_000001_create_cache_table` | `cache` | Cache database |
| `0001_01_01_000002_create_jobs_table` | `jobs` | Queue jobs |
| `2026_10_11_062034_create_campuses_table` | `campuses` | Data kampus (nama, kota, is_demo) |
| `2026_10_11_062038_create_places_table` | `places` | Fasilitas (nama, kategori, deskripsi, lat/long, lantai) |
| `2026_10_11_062039_create_reports_table` | `reports` | Laporan (kategori, deskripsi, status, foto, user_id, place_id) |
| `2026_10_11_062040_create_report_confirmations_table` | `report_confirmations` | Konfirmasi laporan (still_valid, note) |
| `2026_10_11_062041_create_moderation_actions_table` | `moderation_actions` | Aksi admin (VERIFY/REJECT/RESOLVE) |
| `2026_10_11_062335_add_role_to_users_table` | `users` | Tambah kolom `role` enum |

### Perintah Migrasi Umum
```bash
php artisan migrate              # Jalankan migrasi pending
php artisan migrate:fresh        # Drop semua tabel & jalankan ulang
php artisan migrate:fresh --seed # Reset + seed data demo
php artisan migrate:status       # Cek status migrasi
php artisan migrate:rollback     # Rollback batch terakhir
```

---

## 🌱 Seeder

### DatabaseSeeder (Default)
```bash
php artisan db:seed
```

### CampusPulseDemoSeeder (Data Demo Lengkap)
```bash
php artisan db:seed --class=CampusPulseDemoSeeder
```
Atau gabungan:
```bash
php artisan migrate:fresh --seed
```

**Akun Demo yang Dibuat:**
| Email | Password | Role |
|-------|----------|------|
| `demo@campuspulse.id` | `demo1234` | USER |
| `admin@campuspulse.id` | `admin1234` | ADMIN |

---

## ▶️ Cara Menjalankan

### Development (Hot Reload)
Terminal 1:
```bash
php artisan serve --port=3200
```
Terminal 2:
```bash
npm run dev
```
Akses: **http://localhost:3200**

### Production Build
```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan serve --port=3200
```

### Menjalankan Test
```bash
php artisan test
# Atau spesifik:
php artisan test --filter=ReportTest
```

### Linting & Formatting
```bash
./vendor/bin/pint        # PHP code style (Laravel Pint)
npm run lint             # Jika dikonfigurasi (ESLint)
```

---

## 👤 Akun Demo

Setelah menjalankan `php artisan migrate --seed`:

| Role | Email | Password | Akses |
|------|-------|----------|-------|
| **Mahasiswa (USER)** | `demo@campuspulse.id` | `demo1234` | Dashboard, Peta, Eksplor, Feed, Buat Laporan, Konfirmasi |
| **Admin** | `admin@campuspulse.id` | `admin1234` | Semua fitur USER + Admin Panel (Kelola Tempat, Moderasi Laporan) |

---

## 📸 Screenshot Placeholder

> Tambahkan screenshot aktual di folder `public/screenshots/` dan update path di bawah ini.

| Halaman | Preview |
|---------|---------|
| **Beranda (Home)** | `![Home](public/screenshots/home.png)` |
| **Peta Interaktif** | `![Map](public/screenshots/map.png)` |
| **Eksplorasi Tempat** | `![Explore](public/screenshots/explore.png)` |
| **Feed Laporan** | `![Feed](public/screenshots/feed.png)` |
| **Buat Laporan** | `![Report](public/screenshots/report.png)` |
| **Dashboard** | `![Dashboard](public/screenshots/dashboard.png)` |
| **Admin Panel** | `![Admin](public/screenshots/admin.png)` |
| **Login / Register** | `![Auth](public/screenshots/auth.png)` |

---

## 🤝 Kontribusi

Kami menyambut kontribusi dari komunitas! Ikuti langkah berikut:

### 1. Fork & Clone
```bash
git fork <repository-url>
git clone <your-fork-url>
cd campuspulse-laravel
```

### 2. Buat Branch Fitur
```bash
git checkout -b fitur/nama-fitur-baru
# Contoh: fitur/tambah-kategori-laporan
```

### 3. Coding Standards
- Ikuti **PSR-12** (Laravel Pint: `./vendor/bin/pint`)
- Tulis test untuk fitur baru (`php artisan make:test`)
- Komit dengan pesan yang jelas (Conventional Commits):
  ```
  feat: tambah filter kategori di halaman explore
  fix: perbaiki validasi koordinat di form laporan
  docs: update README bagian instalasi
  ```

### 4. Jalankan Test & Lint
```bash
php artisan test
./vendor/bin/pint
```

### 5. Push & Pull Request
```bash
git push origin fitur/nama-fitur-baru
```
Buat **Pull Request** ke branch `main` dengan deskripsi:
- Apa yang diubah/ditambah
- Screenshot (jika UI)
- Checklist: test pass, lint pass, dokumentasi updated

### 6. Code Review
Maintainer akan review PR. Setelah approved → merge.

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **MIT License** — lihat file [LICENSE](LICENSE) untuk detail.

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com/) — The PHP Framework for Web Artisans
- [Tailwind CSS](https://tailwindcss.com/) — Utility-first CSS framework
- [Leaflet](https://leafletjs.com/) — Open-source JavaScript library for interactive maps
- [OpenStreetMap](https://www.openstreetmap.org/) — Free editable map of the world
- [Vite](https://vitejs.dev/) — Next generation frontend tooling

---

## 📞 Kontak & Dukungan

- **Issue Tracker**: [GitHub Issues](<repository-url>/issues)
- **Email**: support@campuspulse.id
- **Dokumentasi**: [Wiki](<repository-url>/wiki)

---

**Dibangun dengan ❤️ untuk komunitas mahasiswa Indonesia**