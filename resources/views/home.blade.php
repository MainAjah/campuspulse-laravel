@extends('layouts.app')

@section('title', ' - Platform Info Kondisi Fasilitas Kampus')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-accent/5 via-surface to-surface py-20 sm:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-soft-green text-accent text-sm font-medium mb-6">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-accent"></span>
            </span>
            Demo Data - Untuk Pengembangan
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-primary tracking-tight mb-6">
            Info Kondisi Fasilitas Kampus
            <br class="hidden sm:block">
            <span class="text-accent">Real-time</span> dari Komunitas Mahasiswa
        </h1>

        <p class="text-lg sm:text-xl text-secondary max-w-3xl mx-auto mb-10 leading-relaxed">
            Tahu kondisi perpustakaan, ruang belajar, laboratorium, kantin, dan Wi-Fi
            sebelum kamu pergi. Dibangun oleh mahasiswa, untuk mahasiswa.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
            <a href="{{ route('explore') }}" class="btn btn-primary px-8 py-3 text-lg">
                Jelajahi Fasilitas
            </a>
            <a href="{{ route('report') }}" class="btn btn-outline px-8 py-3 text-lg">
                Buat Laporan
            </a>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-8 text-sm text-secondary">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4 text-accent" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Data Demo
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4 text-accent" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Update Real-time
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4 text-accent" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Gratis & Terbuka
            </span>
        </div>
    </div>

    <!-- Floating stats -->
    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 hidden lg:block">
        <div class="grid grid-cols-4 gap-4 max-w-5xl px-4">
            <div class="card p-6 text-center">
                <div class="text-3xl font-bold text-accent">{{ $totalFacilities ?? 5 }}</div>
                <div class="text-sm text-secondary mt-1">Fasilitas</div>
            </div>
            <div class="card p-6 text-center">
                <div class="text-3xl font-bold text-accent">{{ $activeReports ?? 3 }}</div>
                <div class="text-sm text-secondary mt-1">Laporan Aktif</div>
            </div>
            <div class="card p-6 text-center">
                <div class="text-3xl font-bold text-accent">{{ \App\Models\User::count() }}</div>
                <div class="text-sm text-secondary mt-1">Pengguna</div>
            </div>
            <div class="card p-6 text-center">
                <div class="text-3xl font-bold text-accent">24/7</div>
                <div class="text-sm text-secondary mt-1">Akses</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 sm:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-primary mb-4">Fitur Utama</h2>
            <p class="text-secondary max-w-2xl mx-auto">Semua yang kamu butuhkan untuk tetap terinformasi soal kondisi kampus</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Feature 1 -->
            <div class="card p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-soft-green flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-primary mb-2">Peta Kampus Interaktif</h3>
                <p class="text-secondary">Lihat semua fasilitas di peta dengan marker berwarna. Filter berdasarkan kategori dan lihat detail kondisi terkini.</p>
            </div>

            <!-- Feature 2 -->
            <div class="card p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-soft-green flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-primary mb-2">Laporan Real-time</h3>
                <p class="text-secondary">Kirim laporan keramaian, gangguan Wi-Fi, kerusakan, kebersihan, dan ketersediaan fasilitas dalam hitungan detik.</p>
            </div>

            <!-- Feature 3 -->
            <div class="card p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-soft-green flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-primary mb-2">Konfirmasi Komunitas</h3>
                <p class="text-secondary">Konfirmasi apakah laporan masih valid. Mayoritas suara mengubah status laporan otomatis.</p>
            </div>

            <!-- Feature 4 -->
            <div class="card p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-soft-green flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-primary mb-2">Indikator Kesegaran</h3>
                <p class="text-secondary">Tahu seberapa baru laporan: Segar, Mulai Usang, atau Kedaluwarsa. Keramaian 60 menit, Wi-Fi 45 menit.</p>
            </div>

            <!-- Feature 5 -->
            <div class="card p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-soft-green flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-primary mb-2">Dashboard Statistik</h3>
                <p class="text-secondary">Ringkasan laporan, fasilitas bermasalah, dan tren kondisi kampus di satu halaman.</p>
            </div>

            <!-- Feature 6 -->
            <div class="card p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-soft-green flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-primary mb-2">Moderasi Admin</h3>
                <p class="text-secondary">Admin memverifikasi, menyelesaikan, atau menolak laporan. Riwayat tindakan transparan.</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-20 sm:py-28 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-primary mb-4">Cara Kerja</h2>
            <p class="text-secondary max-w-2xl mx-auto">Mudah, cepat, dan bermanfaat untuk semua mahasiswa</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="relative">
                <div class="absolute left-1/2 top-0 -translate-x-1/2 w-16 h-16 rounded-full bg-accent flex items-center justify-center text-white text-2xl font-bold">1</div>
                <div class="card p-6 pt-12 text-center">
                    <h3 class="text-xl font-semibold text-primary mb-2">Buka Peta atau Feed</h3>
                    <p class="text-secondary">Lihat kondisi fasilitas di peta interaktif atau baca laporan terbaru di feed komunitas.</p>
                </div>
            </div>

            <div class="relative">
                <div class="absolute left-1/2 top-0 -translate-x-1/2 w-16 h-16 rounded-full bg-accent flex items-center justify-center text-white text-2xl font-bold">2</div>
                <div class="card p-6 pt-12 text-center">
                    <h3 class="text-xl font-semibold text-primary mb-2">Buat Laporan</h3>
                    <p class="text-secondary">Temukan masalah? Pilih fasilitas, kategori, dan tulis deskripsi singkat. Kirim dalam hitungan detik.</p>
                </div>
            </div>

            <div class="relative">
                <div class="absolute left-1/2 top-0 -translate-x-1/2 w-16 h-16 rounded-full bg-accent flex items-center justify-center text-white text-2xl font-bold">3</div>
                <div class="card p-6 pt-12 text-center">
                    <h3 class="text-xl font-semibold text-primary mb-2">Bantu Komunitas</h3>
                    <p class="text-secondary">Konfirmasi laporan orang lain. Suara kamu menentukan apakah info tetap segar atau kedaluwarsa.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 sm:py-28 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-primary mb-4">Pertanyaan Umum</h2>
        </div>

        <div class="space-y-4" id="faq">
            <details class="group card p-6">
                <summary class="flex items-center justify-between cursor-pointer list-none font-medium text-primary">
                    <span>Berapa lama laporan dianggap kedaluwarsa?</span>
                    <svg class="w-5 h-5 text-secondary group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="mt-4 text-secondary text-sm leading-relaxed">
                    <p>Laporan keramaian (CROWD) kedaluwarsa setelah <strong>60 menit</strong>.</p>
                    <p class="mt-1">Laporan gangguan Wi-Fi kedaluwarsa setelah <strong>45 menit</strong> (bisa dikonfigurasi).</p>
                    <p class="mt-1">Laporan kerusakan (DAMAGE) <strong>tidak otomatis kedaluwarsa</strong> — tetap aktif sampai diselesaikan atau ditolak admin.</p>
                </div>
            </details>

            <details class="group card p-6">
                <summary class="flex items-center justify-between cursor-pointer list-none font-medium text-primary">
                    <span>Bisakah saya mengonfirmasi laporan yang sama berkali-kali?</span>
                    <svg class="w-5 h-5 text-secondary group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="mt-4 text-secondary text-sm leading-relaxed">
                    Tidak. Setiap pengguna hanya dapat mengonfirmasi satu laporan satu kali. Sistem mencegah duplikasi konfirmasi secara otomatis.
                </div>
            </details>

            <details class="group card p-6">
                <summary class="flex items-center justify-between cursor-pointer list-none font-medium text-primary">
                    <span>Siapa yang bisa mengakses Admin Panel?</span>
                    <svg class="w-5 h-5 text-secondary group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="mt-4 text-secondary text-sm leading-relaxed">
                    Hanya pengguna dengan peran <strong>ADMIN</strong> yang bisa mengakses panel administrasi. Peran ditetapkan di database dan tidak bisa diubah melalui frontend.
                </div>
            </details>

            <details class="group card p-6">
                <summary class="flex items-center justify-between cursor-pointer list-none font-medium text-primary">
                    <span>Apakah data ini data kampus yang nyata?</span>
                    <svg class="w-5 h-5 text-secondary group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="mt-4 text-secondary text-sm leading-relaxed">
                    <strong>Tidak.</strong> Semua data pada versi demo ini adalah data palsu (dummy) yang diberi label <strong>DEMO</strong>. CampusPulse tidak mengarang informasi kondisi kampus yang nyata.
                </div>
            </details>

            <details class="group card p-6">
                <summary class="flex items-center justify-between cursor-pointer list-none font-medium text-primary">
                    <span>Bagaimana cara kerjasama dengan kampus saya?</span>
                    <svg class="w-5 h-5 text-secondary group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="mt-4 text-secondary text-sm leading-relaxed">
                    CampusPulse adalah platform komunitas mahasiswa. Untuk integrasi resmi dengan sistem kampus, hubungi tim pengembang melalui saluran resmi.
                </div>
            </details>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 sm:py-28 bg-accent text-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold mb-6">Siap Membantu Komunitas Kampus?</h2>
        <p class="text-accent/80 text-lg mb-8 max-w-2xl mx-auto">
            Bergabung dengan ribuan mahasiswa yang sudah berbagi info kondisi fasilitas.
            Mulai dari hari ini, kamu tidak akan pernah ke perpustakaan penuh atau kantin yang kehabisan makanan tanpa tahu dulu.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            @auth
                <a href="{{ route('explore') }}" class="btn btn-primary px-8 py-3 text-lg">
                    Mulai Jelajahi
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary px-8 py-3 text-lg">
                    Daftar Gratis
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline px-8 py-3 text-lg">
                    Masuk
                </a>
            @endauth
        </div>
    </div>
</section>
@endsection