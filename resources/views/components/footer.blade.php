<footer class="bg-white border-t border-border mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-accent text-sm font-bold text-white">
                        CP
                    </span>
                    <span class="text-lg font-semibold tracking-tight text-primary">CampusPulse</span>
                </a>
                <p class="mt-4 text-secondary text-sm max-w-md">
                    Platform info kondisi fasilitas kampus berbasis laporan komunitas mahasiswa.
                    Bersama membangun kampus yang lebih nyaman dan terinformasi.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-primary mb-4">Navigasi</h3>
                <ul class="space-y-2 text-sm text-secondary">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a></li>
                    <li><a href="{{ route('map') }}" class="hover:text-primary transition-colors">Peta Kampus</a></li>
                    <li><a href="{{ route('explore') }}" class="hover:text-primary transition-colors">Jelajahi Fasilitas</a></li>
                    <li><a href="{{ route('feed') }}" class="hover:text-primary transition-colors">Feed Komunitas</a></li>
                    <li><a href="{{ route('report') }}" class="hover:text-primary transition-colors">Buat Laporan</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold text-primary mb-4">Tentang</h3>
                <ul class="space-y-2 text-sm text-secondary">
                    <li>Data demo untuk tujuan pengembangan</li>
                    <li>Laporan kedaluwarsa: keramaian 60 menit, Wi-Fi 45 menit</li>
                    <li>Kerusakan tidak otomatis kedaluwarsa</li>
                    <li>Dibangun dengan Laravel & MySQL</li>
                </ul>
            </div>
        </div>

        <div class="mt-10 pt-8 border-t border-border flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-sm text-secondary">
                &copy; {{ date('Y') }} CampusPulse. Dibuat untuk komunitas kampus.
            </p>
            <div class="flex items-center gap-4 text-sm text-secondary">
                <span>v1.0.0 (DEMO)</span>
            </div>
        </div>
    </div>
</footer>