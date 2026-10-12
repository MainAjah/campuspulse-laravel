@extends('layouts.app')

@section('title', 'Jelajahi Fasilitas - CampusPulse')

@section('content')
<div class="min-h-screen bg-background">
    <main class="flex-1">
        <div class="container mx-auto px-4 py-8">
            <!-- Page Header -->
            <header class="mb-8">
                <h1 class="text-3xl font-bold text-primary mb-2">Jelajahi Fasilitas</h1>
                <p class="text-secondary max-w-2xl">Cari dan filter fasilitas kampus berdasarkan kategori dan kondisi</p>
            </header>

            <!-- Search & Filter Bar -->
            <div class="card p-4 mb-6">
                <form id="filter-form" method="GET" action="{{ route('explore') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:gap-4">
                    <!-- Search -->
                    <div class="md:flex-1">
                        <label for="search" class="block text-sm font-medium text-secondary mb-1">Cari Fasilitas</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-border" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Cari nama atau deskripsi fasilitas..."
                                class="input w-full pl-10 pr-4 py-2.5"
                            >
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="md:w-48">
                        <label for="category" class="block text-sm font-medium text-secondary mb-1">Kategori</label>
                        <select
                            id="category"
                            name="category"
                            class="input w-full py-2.5"
                        >
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>
                                    {{ $categoryLabels[$cat] ?? $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Condition Filter -->
                    <div class="md:w-48">
                        <label for="condition" class="block text-sm font-medium text-secondary mb-1">Kondisi</label>
                        <select
                            id="condition"
                            name="condition"
                            class="input w-full py-2.5"
                        >
                            <option value="">Semua Kondisi</option>
                            @foreach ($conditions as $key => $label)
                                <option value="{{ $key }}" {{ $condition === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit/Clear Buttons -->
                    <div class="flex gap-2 md:ml-4">
                        <button type="submit" class="btn btn-primary whitespace-nowrap">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12a7 7 0 1114 0 7 7 0 01-14 0z"></path>
                            </svg>
                            Filter
                        </button>
                        @if ($search || $category || $condition)
                            <a href="{{ route('explore') }}" class="btn btn-outline whitespace-nowrap">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Results Count -->
            <div class="mb-4 flex items-center justify-between">
                <p class="text-secondary">
                    Menampilkan <span class="font-medium text-primary">{{ $places->firstItem() ?? 0 }}</span>–<span class="font-medium text-primary">{{ $places->lastItem() ?? 0 }}</span> dari <span class="font-medium text-primary">{{ $places->total() }}</span> fasilitas
                </p>
                <p class="text-sm text-secondary">{{ $places->total() }} hasil ditemukan</p>
            </div>

            <!-- Places Grid -->
            <div id="places-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @if ($places->isEmpty())
                    <div class="col-span-full card p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-border mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-primary mb-2">Tidak Ada Fasilitas Ditemukan</h3>
                        <p class="text-secondary mb-4">Coba ubah filter atau kata kunci pencarian Anda.</p>
                        <a href="{{ route('explore') }}" class="btn btn-primary">Reset Filter</a>
                    </div>
                @else
                    @foreach ($places as $place)
                        <x-place-card :place="$place" :showDetailBtn="true" />
                    @endforeach
                @endif
            </div>

            <!-- Pagination -->
            @if ($places->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $places->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </main>

    <!-- Place Detail Modal -->
    <div id="place-detail-modal" class="modal-backdrop hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="modal-backdrop relative w-full max-w-2xl bg-card rounded-xl shadow-xl">
                <!-- Modal content will be injected here -->
            </div>
        </div>
    </div>

    @include('components.footer')
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Debounced form submission for filters
            const filterForm = document.getElementById('filter-form');
            if (filterForm) {
                const inputs = filterForm.querySelectorAll('input, select');
                let debounceTimer;
                
                inputs.forEach(input => {
                    input.addEventListener('change', function() {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(() => filterForm.submit(), 300);
                    });
                    
                    if (input.tagName === 'INPUT') {
                        input.addEventListener('input', function() {
                            clearTimeout(debounceTimer);
                            debounceTimer = setTimeout(() => filterForm.submit(), 500);
                        });
                    }
                });
            }

            // Place detail modal
            document.addEventListener('click', function(e) {
                const detailBtn = e.target.closest('[data-place-id]');
                if (detailBtn && detailBtn.tagName === 'BUTTON') {
                    e.preventDefault();
                    const placeId = detailBtn.dataset.placeId;
                    openPlaceDetail(placeId);
                }
            });

            function openPlaceDetail(placeId) {
                const modal = document.getElementById('place-detail-modal');
                const modalContent = modal.querySelector('.modal-backdrop > div');
                
                // Show loading
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modalContent.innerHTML = `
                    <div class="p-6 text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-4 border-border border-t-accent mx-auto mb-4"></div>
                        <p class="text-secondary">Memuat detail...</p>
                    </div>
                `;
                document.body.style.overflow = 'hidden';

                // Fetch place detail
                fetch(`/explore/${placeId}/detail`)
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

            // Close modal on backdrop click
            document.getElementById('place-detail-modal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closePlaceDetail();
                }
            });

            // Close modal on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closePlaceDetail();
                }
            });

            function closePlaceDetail() {
                const modal = document.getElementById('place-detail-modal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        });
    </script>
@endsection