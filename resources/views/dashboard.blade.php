@extends('layouts.app')

@section('title', ' - Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary">Dashboard</h1>
        <p class="text-secondary mt-1">Ringkasan kondisi fasilitas dan laporan terkini</p>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">Total Fasilitas</p>
                    <p class="text-3xl font-bold text-primary mt-1">{{ $totalFacilities }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-soft-green flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">Total Laporan</p>
                    <p class="text-3xl font-bold text-primary mt-1">{{ $totalReports }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">Laporan Aktif</p>
                    <p class="text-3xl font-bold text-primary mt-1">{{ $activeReports }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">Fasilitas Bermasalah</p>
                    <p class="text-3xl font-bold text-primary mt-1">{{ $problemPlaces }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Recent Reports & Problematic Places -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Recent Reports -->
            <div class="card">
                <div class="p-6 border-b border-border flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-primary">Laporan Terbaru</h2>
                    <a href="{{ route('feed') }}" class="text-sm text-accent hover:underline">Lihat semua</a>
                </div>
                <div class="divide-y divide-border">
                    @foreach ($recentReports as $report)
                        <div class="p-4 hover:bg-surface/50 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-medium text-primary">{{ $report->place->name }}</h3>
                                        <x-status-badge :status="$report->status" />
                                        <x-freshness-badge :report="$report" />
                                    </div>
                                    <p class="text-sm text-secondary line-clamp-2">{{ $report->description }}</p>
                                    <div class="flex items-center gap-4 mt-2 text-xs text-secondary">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $report->place->category }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $report->confirmations->count() }} konfirmasi
                                        </span>
                                        <span>{{ $report->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($recentReports->isEmpty())
                        <div class="p-8 text-center text-secondary">
                            <svg class="w-12 h-12 mx-auto text-border mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p>Belum ada laporan terbaru</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Problematic Facilities -->
            <div class="card">
                <div class="p-6 border-b border-border flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-primary">Fasilitas dengan Masalah</h2>
                </div>
                <div class="divide-y divide-border">
                    @php
                        $problemPlaces = \App\Models\Place::whereHas('reports', function ($q) {
                            $q->whereIn('status', ['PENDING', 'VERIFIED']);
                        })->with(['latestReport', 'reports'])->take(5)->get();
                    @endphp
                    @foreach ($problemPlaces as $place)
                        <div class="p-4 hover:bg-surface/50 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-medium text-primary">{{ $place->name }}</h3>
                                    <p class="text-sm text-secondary mt-1">{{ $place->description }}</p>
                                    <div class="flex items-center gap-3 mt-2 text-xs text-secondary">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $place->category }}
                                        </span>
                                        @if ($place->latestReport)
                                            <x-status-badge :status="$place->latestReport->status" />
                                            <x-freshness-badge :report="$place->latestReport" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($problemPlaces->isEmpty())
                        <div class="p-8 text-center text-secondary">
                            <svg class="w-12 h-12 mx-auto text-border mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>Tidak ada fasilitas bermasalah saat ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Reports by Category & Map Preview -->
        <div class="space-y-8">
            <!-- Reports by Category -->
            <div class="card">
                <div class="p-6 border-b border-border">
                    <h2 class="text-lg font-semibold text-primary">Laporan per Kategori</h2>
                </div>
                <div class="p-6 space-y-4">
                    @php
                        $allCategories = ['CROWD', 'WIFI', 'DAMAGE', 'CLEANLINESS', 'AVAILABILITY', 'OTHER'];
                        $categoryLabels = [
                            'CROWD' => 'Keramaian',
                            'WIFI' => 'Gangguan Wi-Fi',
                            'DAMAGE' => 'Kerusakan',
                            'CLEANLINESS' => 'Kebersihan',
                            'AVAILABILITY' => 'Ketersediaan',
                            'OTHER' => 'Lainnya',
                        ];
                    @endphp
                    @foreach ($allCategories as $cat)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-secondary">{{ $categoryLabels[$cat] }}</span>
                            <span class="font-semibold text-primary">{{ $reportsByCategory[$cat] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Map Preview -->
            <div class="card">
                <div class="p-6 border-b border-border flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-primary">Preview Peta</h2>
                    <a href="{{ route('map') }}" class="text-sm text-accent hover:underline">Buka peta penuh</a>
                </div>
                <div class="p-6">
                    <div id="dashboard-map" class="h-64 rounded-lg overflow-hidden" style="background: #E5E9E5;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Initialize mini map on dashboard
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof L !== 'undefined' && document.getElementById('dashboard-map')) {
            const map = L.map('dashboard-map', {
                zoomControl: false,
                attributionControl: false,
                dragging: false,
                scrollWheelZoom: false,
                doubleClickZoom: false,
                boxZoom: false,
                keyboard: false,
                tap: false,
            }).setView([-6.2088, 106.8456], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            // Add markers from inline data
            @php
                $mapPlaces = \App\Models\Place::select('id', 'name', 'category', 'latitude', 'longitude')->get()->toArray();
            @endphp
            const places = @json($mapPlaces);

            const categoryColors = {
                'LIBRARY': '#326B55',
                'STUDY_ROOM': '#285742',
                'LABORATORY': '#1E4D3A',
                'CANTEEN': '#E67E22',
                'WIFI': '#3498DB',
                'OTHER': '#95A5A6',
            };

            places.forEach(place => {
                const color = categoryColors[place.category] || '#326B55';
                const marker = L.circleMarker([place.latitude, place.longitude], {
                    radius: 8,
                    fillColor: color,
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.9,
                }).addTo(map);

                marker.bindPopup(`<strong>${place.name}</strong><br>${place.category}`);
            });
        }
    });
</script>
@endsection