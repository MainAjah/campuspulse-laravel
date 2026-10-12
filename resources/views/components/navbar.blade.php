<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-accent text-sm font-bold text-white">
                    CP
                </span>
                <span class="text-lg font-semibold tracking-tight text-primary">CampusPulse</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-secondary hover:text-primary transition-colors">Dashboard</a>
                <a href="{{ route('map') }}" class="text-sm font-medium text-secondary hover:text-primary transition-colors">Peta Kampus</a>
                <a href="{{ route('explore') }}" class="text-sm font-medium text-secondary hover:text-primary transition-colors">Jelajahi Fasilitas</a>
                <a href="{{ route('feed') }}" class="text-sm font-medium text-secondary hover:text-primary transition-colors">Feed Komunitas</a>

                @auth
                    <a href="{{ route('report') }}" class="text-sm font-medium text-secondary hover:text-primary transition-colors">Buat Laporan</a>

                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin') }}" class="text-sm font-medium text-secondary hover:text-primary transition-colors">Admin Panel</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-secondary hover:text-primary transition-colors">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-secondary hover:text-primary transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary text-sm">Daftar</a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button type="button" id="mobile-menu-btn" class="md:hidden p-2 rounded-lg text-secondary hover:bg-surface hover:text-primary transition-colors" aria-label="Toggle menu" aria-expanded="false">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden py-4 border-t border-border">
            <div class="flex flex-col gap-2">
                <a href="{{ route('dashboard') }}" class="px-3 py-2 text-sm font-medium text-secondary hover:text-primary hover:bg-surface rounded-lg transition-colors">Dashboard</a>
                <a href="{{ route('map') }}" class="px-3 py-2 text-sm font-medium text-secondary hover:text-primary hover:bg-surface rounded-lg transition-colors">Peta Kampus</a>
                <a href="{{ route('explore') }}" class="px-3 py-2 text-sm font-medium text-secondary hover:text-primary hover:bg-surface rounded-lg transition-colors">Jelajahi Fasilitas</a>
                <a href="{{ route('feed') }}" class="px-3 py-2 text-sm font-medium text-secondary hover:text-primary hover:bg-surface rounded-lg transition-colors">Feed Komunitas</a>

                @auth
                    <a href="{{ route('report') }}" class="px-3 py-2 text-sm font-medium text-secondary hover:text-primary hover:bg-surface rounded-lg transition-colors">Buat Laporan</a>

                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin') }}" class="px-3 py-2 text-sm font-medium text-secondary hover:text-primary hover:bg-surface rounded-lg transition-colors">Admin Panel</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-sm font-medium text-secondary hover:text-primary hover:bg-surface rounded-lg transition-colors">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium text-secondary hover:text-primary hover:bg-surface rounded-lg transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary text-sm text-center mx-3 mt-2">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        if (btn && menu) {
            btn.addEventListener('click', function() {
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', !expanded);
                menu.classList.toggle('hidden');
            });
        }
    });
</script>