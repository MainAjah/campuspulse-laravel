@props(['place', 'showDetailBtn' => false])

@php
    $categoryLabels = [
        'LIBRARY' => 'Perpustakaan',
        'STUDY_ROOM' => 'Ruang Belajar',
        'LABORATORY' => 'Laboratorium',
        'CANTEEN' => 'Kantin',
        'WIFI' => 'Wi-Fi',
        'OTHER' => 'Lainnya',
    ];

    $freshnessConfig = [
        'FRESH' => ['label' => 'Segar', 'class' => 'bg-green-100 text-green-800 border-green-200', 'icon' => '🟢'],
        'AGED' => ['label' => 'Mulai Usang', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'icon' => '🟡'],
        'EXPIRED' => ['label' => 'Kedaluwarsa', 'class' => 'bg-red-100 text-red-800 border-red-200', 'icon' => '🔴'],
    ];

    $statusConfig = [
        'PENDING' => ['label' => 'Menunggu', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
        'VERIFIED' => ['label' => 'Terverifikasi', 'class' => 'bg-blue-100 text-blue-800 border-blue-200'],
        'RESOLVED' => ['label' => 'Terselesaikan', 'class' => 'bg-green-100 text-green-800 border-green-200'],
        'REJECTED' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-800 border-red-200'],
    ];
@endphp

<div class="card p-4 hover:shadow-md transition-shadow" data-place-id="{{ $place->id }}">
    <div class="flex items-start gap-4">
        <!-- Category Icon -->
        <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center {{ $place->category === 'LIBRARY' ? 'bg-blue-50' : ($place->category === 'STUDY_ROOM' ? 'bg-green-50' : ($place->category === 'LABORATORY' ? 'bg-purple-50' : ($place->category === 'CANTEEN' ? 'bg-orange-50' : ($place->category === 'WIFI' ? 'bg-cyan-50' : 'bg-gray-50')))) }}">
            @if ($place->category === 'LIBRARY')
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            @elseif ($place->category === 'STUDY_ROOM')
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            @elseif ($place->category === 'LABORATORY')
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                </svg>
            @elseif ($place->category === 'CANTEEN')
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
            @elseif ($place->category === 'WIFI')
                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                </svg>
            @else
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <h3 class="font-medium text-primary">{{ $place->name }}</h3>
                @if ($place->is_demo)
                    <span class="px-1.5 py-0.5 text-xs font-medium bg-soft-green text-accent rounded">DEMO</span>
                @endif
            </div>
            <p class="text-sm text-secondary line-clamp-2">{{ $place->description }}</p>
            <div class="flex items-center gap-3 mt-2 text-xs text-secondary flex-wrap">
                <span>{{ $categoryLabels[$place->category] ?? $place->category }}</span>
                @if ($place->floor)
                    <span>Lantai {{ $place->floor }}</span>
                @endif
                @if ($place->latestReport)
                    <x-status-badge :status="$place->latestReport->status" />
                    <x-freshness-badge :report="$place->latestReport" />
                    <span>{{ $place->latestReport->confirmations_count ?? 0 }} konfirmasi</span>
                @else
                    <span class="text-border">Belum ada laporan</span>
                @endif
            </div>
        </div>
        @if ($showDetailBtn)
            <button type="button" data-place-id="{{ $place->id }}" class="btn btn-outline btn-sm whitespace-nowrap flex-shrink-0">Detail</button>
        @endif
    </div>
</div>