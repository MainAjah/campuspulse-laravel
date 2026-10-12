@props(['place', 'history'])

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

    $report = $place->latestReport ?? null;
    $freshness = $report->freshness ?? 'FRESH';
    $freshnessCfg = $freshnessConfig[$freshness] ?? $freshnessConfig['FRESH'];
    $statusCfg = $report ? ($statusConfig[$report->status] ?? $statusConfig['PENDING']) : null;
@endphp

<div class="card p-6" style="height: 400px; display: flex; flex-direction: column;">
    <div class="flex-1 overflow-y-auto">
        <div class="flex items-center gap-2 mb-2">
            <h3 class="font-semibold text-primary">{{ $place->name }}</h3>
            @if ($place->is_demo)
                <span class="px-1.5 py-0.5 text-xs font-medium bg-soft-green text-accent rounded">DEMO</span>
            @endif
        </div>

        <p class="text-secondary mb-4">{{ $place->description }}</p>

        <div class="flex items-center gap-2 flex-wrap mb-4">
            <span class="px-2 py-1 rounded bg-surface border border-border text-sm">{{ $categoryLabels[$place->category] ?? $place->category }}</span>
            @if ($place->floor)
                <span class="px-2 py-1 rounded bg-surface border border-border text-sm">Lantai {{ $place->floor }}</span>
            @endif
        </div>

        @if ($report)
            <div class="border-t border-border pt-4 space-y-3">
                <h4 class="font-medium text-primary">Laporan Terbaru</h4>
                <div class="flex items-center gap-2 flex-wrap">
                    <x-status-badge :status="$report->status" />
                    <x-freshness-badge :report="$report" />
                </div>
                <p class="text-sm text-secondary">{{ $report->description }}</p>
                <div class="flex items-center gap-3 text-xs text-secondary">
                    <span>Kategori: {{ $report->category }}</span>
                    <span>{{ $report->confirmations_count ?? 0 }} konfirmasi</span>
                </div>
            </div>
        @else
            <div class="border-t border-border pt-4">
                <p class="text-sm text-secondary">Belum ada laporan untuk fasilitas ini.</p>
            </div>
        @endif

        @if ($history && $history->count() > 0)
            <div class="border-t border-border pt-4">
                <h4 class="font-medium text-primary mb-3">Riwayat Laporan ({{ $history->count() }})</h4>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    @foreach ($history as $r)
                        @php
                            $rFreshness = $r->freshness ?? 'FRESH';
                            $rFreshnessCfg = $freshnessConfig[$rFreshness] ?? $freshnessConfig['FRESH'];
                            $rStatusCfg = $statusConfig[$r->status] ?? $statusConfig['PENDING'];
                        @endphp
                        <div class="p-3 rounded-lg bg-surface border border-border">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border {{ $rStatusCfg['class'] }}">{{ $rStatusCfg['label'] }}</span>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium border {{ $rFreshnessCfg['class'] }}">{{ $rFreshnessCfg['icon'] }} {{ $rFreshnessCfg['label'] }}</span>
                            </div>
                            <p class="text-sm text-secondary line-clamp-2">{{ $r->description }}</p>
                            <div class="text-xs text-secondary mt-1">{{ $r->created_at->format('d M Y H:i') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="mt-4 pt-4 border-t border-border flex gap-2">
        <a href="{{ route('report', ['placeId' => $place->id]) }}" class="btn btn-primary flex-1 text-center">Buat Laporan</a>
        <a href="{{ route('feed') }}" class="btn btn-outline flex-1 text-center">Lihat Feed</a>
    </div>
</div>