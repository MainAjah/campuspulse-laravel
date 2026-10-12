@props(['report', 'freshnessLabel', 'freshnessColor', 'statusLabel', 'statusColor', 'categoryLabel', 'confirmationsCount', 'stillValidCount', 'alreadyConfirmed'])

<div class="p-6">
    <button type="button" data-modal-close class="absolute top-4 right-4 text-border hover:text-primary transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <div class="flex items-start gap-4 mb-4">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-2 flex-wrap">
                <h3 class="font-semibold text-primary">{{ $report->place->name }}</h3>
                @if ($report->place->is_demo)
                    <span class="px-1.5 py-0.5 text-xs font-medium bg-soft-green text-accent rounded">DEMO</span>
                @endif
            </div>
            <p class="text-sm text-secondary">{{ $report->place->description }}</p>
        </div>
    </div>

    <div class="border-t border-border pt-4 space-y-4">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border"
                style="background: {{ $statusColor['bg'] }}; color: {{ $statusColor['text'] }}; border-color: {{ $statusColor['border'] }};">
                {{ $statusLabel }}
            </span>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium border"
                style="background: {{ $freshnessColor['bg'] }}; color: {{ $freshnessColor['text'] }}; border-color: {{ $freshnessColor['border'] }};">
                <span aria-hidden="true">{{ $freshnessLabel === 'Segar' ? '🟢' : ($freshnessLabel === 'Mulai Usang' ? '🟡' : '🔴') }}</span>
                {{ $freshnessLabel }}
            </span>
            <span class="px-2 py-1 rounded bg-surface border border-border text-xs text-secondary">{{ $categoryLabel }}</span>
        </div>

        <p class="text-sm text-secondary">{{ $report->description }}</p>

        <div class="flex items-center gap-4 text-xs text-secondary border-t border-border pt-4">
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                </svg>
                {{ $report->place->name }}
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $report->created_at->format('d M Y H:i') }}
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                {{ $confirmationsCount }} konfirmasi
            </span>
        </div>

        @if (Auth::check())
            @if (!$alreadyConfirmed)
                <form action="{{ route('confirm.store') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="hidden" name="report_id" value="{{ $report->id }}">
                    <button type="submit" name="is_still_valid" value="1" class="btn btn-primary flex-1" {{ $report->freshness === 'EXPIRED' ? 'disabled' : '' }}>
                        {{ $report->freshness === 'EXPIRED' ? 'Laporan Kedaluwarsa' : 'Masih Valid' }}
                    </button>
                    <button type="submit" name="is_still_valid" value="0" class="btn btn-outline flex-1" {{ $report->freshness === 'EXPIRED' ? 'disabled' : '' }}>
                        Tidak Valid
                    </button>
                </form>
            @else
                <div class="text-sm text-secondary text-center py-2 bg-soft-green rounded-lg">
                    Anda sudah mengonfirmasi laporan ini
                </div>
            @endif
        @else
            <div class="text-sm text-secondary text-center py-2">
                <a href="{{ route('login') }}" class="text-accent hover:underline">Login</a> untuk mengonfirmasi laporan ini
            </div>
        @endif
    </div>
</div>