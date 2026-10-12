@php
    $actionConfig = [
        'VERIFY' => ['label' => 'Verifikasi', 'class' => 'bg-blue-100 text-blue-800 border-blue-200', 'icon' => '✅'],
        'RESOLVE' => ['label' => 'Selesaikan', 'class' => 'bg-green-100 text-green-800 border-green-200', 'icon' => '✅'],
        'REJECT' => ['label' => 'Tolak', 'class' => 'bg-red-100 text-red-800 border-red-200', 'icon' => '❌'],
    ];
@endphp

<div>
    <h2 class="text-lg font-semibold text-primary mb-6">Histori Tindakan Moderasi</h2>

    @if ($actions->count() > 0)
        <div class="space-y-4">
            @foreach ($actions as $action)
                @php
                    $cfg = $actionConfig[$action->action] ?? ['label' => $action->action, 'class' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => '📝'];
                @endphp
                <div class="card p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center {{ $cfg['class'] }}/10">
                            <span class="text-xl">{{ $cfg['icon'] }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <h3 class="font-medium text-primary">{{ $action->report->place->name }}</h3>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border {{ $cfg['class'] }}">{{ $cfg['label'] }}</span>
                            </div>
                            <p class="text-sm text-secondary line-clamp-2 mb-2">{{ $action->report->description }}</p>
                            <div class="flex items-center gap-4 text-xs text-secondary flex-wrap">
                                <span>Kategori: {{ $action->report->category }}</span>
                                <span>Pelapor: {{ $action->report->user->name }}</span>
                                <span>Moderator: {{ $action->moderator->name }}</span>
                                <span>{{ $action->created_at->diffForHumans() }}</span>
                            </div>
                            @if ($action->note)
                                <p class="text-sm text-secondary mt-2 italic">Catatan: {{ $action->note }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        {{ $actions->links() }}
    @else
        <div class="card p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-border mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-medium text-primary mb-1">Belum ada histori moderasi</h3>
            <p class="text-secondary">Tindakan moderasi akan muncul di sini</p>
        </div>
    @endif
</div>