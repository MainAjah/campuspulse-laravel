@php
    $statusConfig = [
        'PENDING' => ['label' => 'Menunggu', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
        'VERIFIED' => ['label' => 'Terverifikasi', 'class' => 'bg-blue-100 text-blue-800 border-blue-200'],
        'RESOLVED' => ['label' => 'Terselesaikan', 'class' => 'bg-green-100 text-green-800 border-green-200'],
        'REJECTED' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-800 border-red-200'],
    ];
@endphp

<div>
    <h2 class="text-lg font-semibold text-primary mb-6">Statistik Umum</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card p-6">
            <p class="text-sm font-medium text-secondary">Total Laporan</p>
            <p class="text-3xl font-bold text-primary mt-1">{{ $stats['totalReports'] }}</p>
        </div>
        <div class="card p-6">
            <p class="text-sm font-medium text-secondary">Menunggu</p>
            <p class="text-3xl font-bold text-primary mt-1"><x-status-badge :status="'PENDING'" /> {{ $stats['pending'] }}</p>
        </div>
        <div class="card p-6">
            <p class="text-sm font-medium text-secondary">Terverifikasi</p>
            <p class="text-3xl font-bold text-primary mt-1"><x-status-badge :status="'VERIFIED'" /> {{ $stats['verified'] }}</p>
        </div>
        <div class="card p-6">
            <p class="text-sm font-medium text-secondary">Terselesaikan</p>
            <p class="text-3xl font-bold text-primary mt-1"><x-status-badge :status="'RESOLVED'" /> {{ $stats['resolved'] }}</p>
        </div>
        <div class="card p-6">
            <p class="text-sm font-medium text-secondary">Ditolak</p>
            <p class="text-3xl font-bold text-primary mt-1"><x-status-badge :status="'REJECTED'" /> {{ $stats['rejected'] }}</p>
        </div>
        <div class="card p-6">
            <p class="text-sm font-medium text-secondary">Total Fasilitas</p>
            <p class="text-3xl font-bold text-primary mt-1">{{ $stats['totalPlaces'] }}</p>
        </div>
        <div class="card p-6">
            <p class="text-sm font-medium text-secondary">Total Pengguna</p>
            <p class="text-3xl font-bold text-primary mt-1">{{ $stats['totalUsers'] }}</p>
        </div>
    </div>

    <!-- Reports by Category -->
    <div class="card p-6 mb-8">
        <h3 class="font-semibold text-primary mb-4">Laporan per Kategori</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
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
                <div class="p-4 rounded-lg bg-surface border border-border text-center">
                    <p class="text-sm text-secondary">{{ $categoryLabels[$cat] }}</p>
                    <p class="text-2xl font-bold text-primary mt-1">{{ $reportsByCategory[$cat] ?? 0 }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Reports Table -->
    <div class="card">
        <h3 class="font-semibold text-primary mb-4 pb-4 border-b border-border">Laporan Terbaru</h3>
        @php
            $recent = \App\Models\Report::with(['place', 'user'])->latest()->take(10)->get();
        @endphp
        @if ($recent->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm text-secondary border-b border-border">
                            <th class="pb-3 font-medium">Fasilitas</th>
                            <th class="pb-3 font-medium">Kategori</th>
                            <th class="pb-3 font-medium">Pelapor</th>
                            <th class="pb-3 font-medium">Status</th>
                            <th class="pb-3 font-medium">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach ($recent as $report)
                            <tr class="hover:bg-surface/50 transition-colors">
                                <td class="py-3 font-medium text-primary">{{ $report->place->name }}</td>
                                <td class="py-3 text-sm">{{ $report->category }}</td>
                                <td class="py-3 text-sm text-secondary">{{ $report->user->name }}</td>
                                <td class="py-3"><x-status-badge :status="$report->status" /></td>
                                <td class="py-3 text-sm text-secondary">{{ $report->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-secondary text-center py-8">Belum ada laporan</p>
        @endif
    </div>
</div>