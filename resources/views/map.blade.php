@extends('layouts.app')

@section('title', 'Peta Kampus - CampusPulse')

@section('head')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
@endsection

@section('content')
<div class="min-h-screen bg-background">
    <main class="flex-1">
        <div class="container mx-auto px-4 py-8">
            <!-- Page Header -->
            <header class="mb-8">
                <h1 class="text-3xl font-bold text-primary mb-2">Peta Kampus</h1>
                <p class="text-secondary max-w-2xl">Temukan fasilitas dan lihat kondisi real-time dari laporan komunitas</p>
            </header>

            <!-- Category Filter -->
            <div class="card p-4 mb-6">
                <div class="flex flex-wrap gap-2">
                    <button
                        data-category="ALL"
                        class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-all {{ $selectedCategory === 'ALL' ? 'bg-accent text-white shadow-md' : 'bg-surface border border-border text-secondary hover:bg-soft-green' }}"
                    >
                        Semua
                    </button>
                    @foreach ($categories as $category)
                        <button
                            data-category="{{ $category }}"
                            class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-all {{ $selectedCategory === $category ? 'bg-accent text-white shadow-md' : 'bg-surface border border-border text-secondary hover:bg-soft-green' }}"
                        >
                            {{ $categoryLabels[$category] ?? $category }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Map Container -->
            <div class="card overflow-hidden" style="height: 600px;">
                <div id="campus-map" style="width: 100%; height: 100%;"></div>
                
                <!-- Loading State -->
                <div id="map-loading" class="absolute inset-0 flex items-center justify-center bg-card/90 z-10">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-4 border-border border-t-accent mx-auto mb-4"></div>
                        <p class="text-secondary">Memuat peta...</p>
                    </div>
                </div>
                
                <!-- Error State -->
                <div id="map-error" class="hidden absolute inset-0 flex items-center justify-center bg-card/90 z-10 p-8">
                    <div class="text-center max-w-md">
                        <svg class="mx-auto h-16 w-16 text-border" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-primary mt-4">Gagal Memuat Peta</h3>
                        <p class="text-secondary mt-2">Tidak dapat memuat peta. Silakan coba muat ulang halaman.</p>
                        <button onclick="window.CampusPulse.initMap('campus-map', {{ json_encode(['places' => $placesData, 'center' => $center]) }})" class="btn btn-primary mt-4">Coba Lagi</button>
                    </div>
                </div>
            </div>

            <!-- Mobile Place List Fallback -->
            <div id="mobile-place-list" class="hidden lg:hidden mt-6 space-y-3">
                <h2 class="text-lg font-semibold text-primary">Daftar Fasilitas</h2>
                @if ($places->isEmpty())
                    <div class="card p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-border mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <p class="text-secondary">Tidak ada fasilitas ditemukan untuk kategori ini.</p>
                    </div>
                @else
                    @foreach ($places as $place)
                        <x-place-card :place="$place" :showDetailBtn="true" />
                    @endforeach
                @endif
            </div>

            <!-- Stats Summary -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div class="card p-4 text-center">
                    <div class="text-2xl font-bold text-accent">{{ $places->count() }}</div>
                    <div class="text-sm text-secondary">Total Fasilitas</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $places->where('category', 'LIBRARY')->count() }}</div>
                    <div class="text-sm text-secondary">Perpustakaan</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $places->where('category', 'STUDY_ROOM')->count() }}</div>
                    <div class="text-sm text-secondary">Ruang Belajar</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-2xl font-bold text-orange-600">{{ $places->where('category', 'LABORATORY')->count() }}</div>
                    <div class="text-sm text-secondary">Laboratorium</div>
                </div>
            </div>
        </div>
    </main>

    @include('components.footer')
</div>
@endsection

@section('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    
    <script>
        // Initialize map with data from controller
        document.addEventListener('DOMContentLoaded', function() {
            // Check if places data exists
            const placesData = @json($placesData);
            const center = @json($center);
            
            if (typeof L !== 'undefined' && placesData.length > 0) {
                window.CampusPulse.initMap('campus-map', {
                    places: placesData,
                    center: center,
                    zoom: 15
                });
                
                // Hide loading
                document.getElementById('map-loading')?.classList.add('hidden');
            } else if (typeof L === 'undefined') {
                document.getElementById('map-loading')?.classList.add('hidden');
                document.getElementById('map-error')?.classList.remove('hidden');
            }
            
            // Category filter buttons
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const category = this.dataset.category;
                    
                    // Update URL
                    const url = new URL(window.location.href);
                    if (category === 'ALL') {
                        url.searchParams.delete('category');
                    } else {
                        url.searchParams.set('category', category);
                    }
                    window.location.href = url.toString();
                });
            });
            
            // Show mobile list on small screens
            if (window.innerWidth < 1024) {
                document.getElementById('mobile-place-list')?.classList.remove('hidden');
            }
            
            window.addEventListener('resize', function() {
                if (window.innerWidth < 1024) {
                    document.getElementById('mobile-place-list')?.classList.remove('hidden');
                } else {
                    document.getElementById('mobile-place-list')?.classList.add('hidden');
                }
            });
        });
    </script>
@endsection