@extends('layouts.app')

@section('title', 'Komunitas - CampusPulse')

@section('content')
<div class="min-h-screen bg-background">
    <main class="flex-1">
        <div class="container mx-auto px-4 py-8">
            <!-- Page Header -->
            <header class="mb-8">
                <h1 class="text-3xl font-bold text-primary mb-2">Feed Komunitas</h1>
                <p class="text-secondary max-w-2xl">Laporan terbaru dari mahasiswa tentang kondisi fasilitas kampus</p>
            </header>

            <!-- Category Filter Tabs -->
            <div class="card p-4 mb-6">
                <div class="flex flex-wrap gap-2" role="tablist">
                    <button
                        data-category=""
                        class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-all {{ $selectedCategory === '' ? 'bg-accent text-white shadow-md' : 'bg-surface border border-border text-secondary hover:bg-soft-green' }}"
                        role="tab"
                        aria-selected="{{ $selectedCategory === '' ? 'true' : 'false' }}"
                    >
                        Semua
                    </button>
                    @foreach ($categories as $category)
                        <button
                            data-category="{{ $category }}"
                            class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-all {{ $selectedCategory === $category ? 'bg-accent text-white shadow-md' : 'bg-surface border border-border text-secondary hover:bg-soft-green' }}"
                            role="tab"
                            aria-selected="{{ $selectedCategory === $category ? 'true' : 'false' }}"
                        >
                            {{ $categoryLabels[$category] ?? $category }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Reports List -->
            <div id="reports-list" class="space-y-4">
                @if ($reports->isEmpty())
                    <div class="card p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-border mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-primary mb-2">Belum Ada Laporan</h3>
                        <p class="text-secondary mb-4">Jadilah yang pertama melaporkan kondisi fasilitas di kampus Anda.</p>
                        <a href="{{ route('report') }}" class="btn btn-primary">Buat Laporan</a>
                    </div>
                @else
                    @foreach ($reports as $report)
                        @php
                            $freshness = $report->freshness ?? 'FRESH';
                            $freshnessLabel = $freshnessLabels[$freshness] ?? $freshness;
                            $freshnessColor = $freshnessColors[$freshness] ?? ['bg' => '#f9fafb', 'text' => '#374151', 'border' => '#d1d5db'];
                            $statusLabel = $statusLabels[$report->status] ?? $report->status;
                            $statusColor = $statusColors[$report->status] ?? ['bg' => '#f9fafb', 'text' => '#374151', 'border' => '#d1d5db'];
                            $categoryLabel = $categoryLabels[$report->category] ?? $report->category;
                            $confirmationsCount = $report->confirmations_count ?? 0;
                            $stillValidCount = $report->still_valid_count ?? 0;
                            $alreadyConfirmed = Auth::check() ? $report->alreadyConfirmedByUser(Auth::id()) : false;
                        @endphp
                        
                        <article class="card p-4 hover:shadow-md transition-shadow" data-report-id="{{ $report->id }}">
                            <div class="flex items-start gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                                        <h3 class="font-medium text-primary">{{ $report->place->name }}</h3>
                                        @if ($report->place->is_demo)
                                            <span class="px-1.5 py-0.5 text-xs font-medium bg-soft-green text-accent rounded">DEMO</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-secondary line-clamp-2 mb-3">{{ $report->description }}</p>
                                    
                                    <div class="flex items-center gap-2 flex-wrap mb-3">
                                        <x-status-badge :status="$report->status" />
                                        <x-freshness-badge :report="$report" />
                                        <span class="category-badge px-2 py-1 rounded text-xs font-medium border bg-surface border-border text-secondary">{{ $categoryLabel }}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-4 text-xs text-secondary">
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
                                            {{ $confirmationsCount }} konfirmasi ({{ $stillValidCount }} valid)
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirmation Buttons -->
                            <div class="border-t border-border pt-3 mt-3">
                                @if (Auth::check())
                                    @if (!$alreadyConfirmed)
                                        <form action="{{ route('confirm.store') }}" method="POST" class="flex gap-2">
                                            @csrf
                                            <input type="hidden" name="report_id" value="{{ $report->id }}">
                                            <button type="submit" name="is_still_valid" value="1" class="btn btn-primary flex-1" {{ $freshness === 'EXPIRED' ? 'disabled' : '' }}>
                                                {{ $freshness === 'EXPIRED' ? 'Laporan Kedaluwarsa' : 'Masih Valid' }}
                                            </button>
                                            <button type="submit" name="is_still_valid" value="0" class="btn btn-outline flex-1" {{ $freshness === 'EXPIRED' ? 'disabled' : '' }}>
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
                        </article>
                    @endforeach
                @endif
            </div>

            <!-- Pagination -->
            @if ($reports->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $reports->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </main>

    <!-- Report Detail Modal -->
    <div id="report-detail-modal" class="modal-backdrop hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="modal-backdrop relative w-full max-w-xl bg-card rounded-xl shadow-xl">
                <!-- Modal content injected via JS -->
            </div>
        </div>
    </div>

    @include('components.footer')
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Category tab filtering
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const category = this.dataset.category;
                    const url = new URL(window.location.href);
                    if (category) {
                        url.searchParams.set('category', category);
                    } else {
                        url.searchParams.delete('category');
                    }
                    window.location.href = url.toString();
                });
            });

            // Report detail modal
            let currentReportId = null;
            
            // We'll use event delegation for dynamically loaded content
            document.addEventListener('click', function(e) {
                // For now, reports in feed don't have detail buttons - they have confirmation buttons
                // Detail view could be added later if needed
            });

            // Close modal on backdrop click
            document.getElementById('report-detail-modal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeReportDetail();
                }
            });

            // Close modal on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeReportDetail();
                }
            });

            function openReportDetail(reportId) {
                currentReportId = reportId;
                const modal = document.getElementById('report-detail-modal');
                const modalContent = modal.querySelector('.modal-backdrop > div');
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modalContent.innerHTML = `
                    <div class="p-6 text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-4 border-border border-t-accent mx-auto mb-4"></div>
                        <p class="text-secondary">Memuat detail...</p>
                    </div>
                `;
                document.body.style.overflow = 'hidden';

                fetch(`/feed/${reportId}/detail`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.html) {
                            modalContent.innerHTML = data.html;
                        } else {
                            modalContent.innerHTML = '<div class="p-6 text-center text-red-600">Gagal memuat detail</div>';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        modalContent.innerHTML = '<div class="p-6 text-center text-red-600">Terjadi kesalahan jaringan</div>';
                    });
            }

            function closeReportDetail() {
                const modal = document.getElementById('report-detail-modal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
                currentReportId = null;
            }

            // Make openReportDetail globally accessible if needed
            window.openReportDetail = openReportDetail;
        });
    </script>
@endsection