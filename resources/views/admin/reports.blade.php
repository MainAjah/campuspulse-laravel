@php
    $statusConfig = [
        'PENDING' => ['label' => 'Menunggu', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
        'VERIFIED' => ['label' => 'Terverifikasi', 'class' => 'bg-blue-100 text-blue-800 border-blue-200'],
        'RESOLVED' => ['label' => 'Terselesaikan', 'class' => 'bg-green-100 text-green-800 border-green-200'],
        'REJECTED' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-800 border-red-200'],
    ];

    $freshnessConfig = [
        'FRESH' => ['label' => 'Segar', 'class' => 'bg-green-100 text-green-800 border-green-200', 'icon' => '🟢'],
        'AGED' => ['label' => 'Mulai Usang', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'icon' => '🟡'],
        'EXPIRED' => ['label' => 'Kedaluwarsa', 'class' => 'bg-red-100 text-red-800 border-red-200', 'icon' => '🔴'],
    ];
@endphp

<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-lg font-semibold text-primary">Laporan Perlu Moderasi</h2>

        <form method="GET" action="{{ route('admin') }}" class="flex items-center gap-2">
            <input type="hidden" name="tab" value="reports">
            <select name="status" class="input py-2 text-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="PENDING" {{ $status === 'PENDING' ? 'selected' : '' }}>Menunggu</option>
                <option value="VERIFIED" {{ $status === 'VERIFIED' ? 'selected' : '' }}>Terverifikasi</option>
            </select>
        </form>
    </div>

    @if ($reports->count() > 0)
        <div class="space-y-4">
            @foreach ($reports as $report)
                <div class="card p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <h3 class="font-medium text-primary">{{ $report->place->name }}</h3>
                                <x-status-badge :status="$report->status" />
                                <x-freshness-badge :report="$report" />
                            </div>
                            <p class="text-sm text-secondary line-clamp-2 mb-2">{{ $report->description }}</p>
                            <div class="flex items-center gap-4 text-xs text-secondary flex-wrap">
                                <span>Kategori: {{ $report->category }}</span>
                                <span>Oleh: {{ $report->user->name }}</span>
                                <span>{{ $report->confirmations->count() }} konfirmasi</span>
                                <span>{{ $report->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 flex-shrink-0">
                            <form action="{{ route('admin.reports.moderate', $report) }}" method="POST" class="flex flex-col gap-1">
                                @csrf
                                <input type="hidden" name="action" value="VERIFY">
                                <input type="hidden" name="note" value="Diverifikasi admin">
                                <button type="submit" class="btn btn-outline btn-sm btn-verify"
                                    {{ $report->status !== 'PENDING' ? 'disabled' : '' }}>
                                    Verifikasi
                                </button>
                            </form>

                            <form action="{{ route('admin.reports.moderate', $report) }}" method="POST" class="flex flex-col gap-1">
                                @csrf
                                <input type="hidden" name="action" value="RESOLVE">
                                <input type="hidden" name="note" value="Disesuaikan/diperbaiki">
                                <button type="submit" class="btn btn-outline btn-sm btn-resolve"
                                    {{ !in_array($report->status, ['PENDING', 'VERIFIED']) ? 'disabled' : '' }}>
                                    Selesaikan
                                </button>
                            </form>

                            <form action="{{ route('admin.reports.moderate', $report) }}" method="POST" class="flex flex-col gap-1">
                                @csrf
                                <input type="hidden" name="action" value="REJECT">
                                <input type="hidden" name="note" value="Laporan tidak valid/duplikat">
                                <button type="submit" class="btn btn-outline btn-sm btn-reject text-red-600 border-red-200 hover:bg-red-50"
                                    {{ $report->status === 'REJECTED' ? 'disabled' : '' }}>
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        {{ $reports->links() }}
    @else
        <div class="card p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-border mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-medium text-primary mb-1">Tidak ada laporan perlu moderasi</h3>
            <p class="text-secondary">Semua laporan sudah ditangani atau tidak ada laporan baru</p>
        </div>
    @endif
</div>