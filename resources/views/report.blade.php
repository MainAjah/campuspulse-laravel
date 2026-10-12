@extends('layouts.app')

@section('title', ' - Buat Laporan')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary">Buat Laporan</h1>
        <p class="text-secondary mt-1">Bantu komunitas dengan melaporkan kondisi fasilitas kampus</p>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- Report Form -->
    <form action="{{ route('report') }}" method="POST" class="card p-6 space-y-6" id="report-form">
        @csrf

        <!-- Place Selection -->
        <div>
            <label for="place_id" class="block text-sm font-medium text-secondary mb-2">Fasilitas <span class="text-red-500">*</span></label>
            <select name="place_id" id="place_id" class="input w-full py-2.5" required>
                <option value="">Pilih fasilitas...</option>
                @foreach ($places as $place)
                    <option value="{{ $place->id }}" {{ $preselectPlaceId == $place->id ? 'selected' : '' }}>
                        {{ $place->name }} ({{ $place->category }})
                    </option>
                @endforeach
            </select>
            @error('place_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Category Selection -->
        <div>
            <label class="block text-sm font-medium text-secondary mb-3">Kategori Laporan <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="category-options">
                @foreach ($categories as $cat)
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="category" value="{{ $cat['value'] }}" class="sr-only peer" required>
                        <div class="p-4 rounded-xl border-2 border-border peer-checked:border-accent peer-checked:bg-soft-green transition-all hover:border-accent/50">
                            <div class="text-3xl mb-2 text-center">{{ $cat['icon'] }}</div>
                            <div class="font-medium text-primary text-center">{{ $cat['label'] }}</div>
                            <div class="text-xs text-secondary text-center mt-1">{{ $cat['desc'] }}</div>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('category')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-secondary mb-2">Deskripsi <span class="text-red-500">*</span></label>
            <textarea name="description" id="description" rows="5" class="input w-full" placeholder="Jelaskan kondisi fasilitas secara detail (min. 10 karakter)..." required minlength="10" maxlength="1000"></textarea>
            <div class="flex items-center justify-between mt-1">
                <span id="char-count" class="text-xs text-secondary">0 / 1000 karakter</span>
                <span class="text-xs text-secondary">Min. 10 karakter</span>
            </div>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-border">
            <button type="submit" id="submit-btn" class="btn btn-primary w-full py-3 text-lg" disabled>
                <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                Kirim Laporan
            </button>
            <p class="text-xs text-secondary text-center mt-3">Laporan akan masuk ke antrian moderasi. Anda akan mendapat notifikasi saat status berubah.</p>
        </div>
    </form>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('report-form');
        const categoryInputs = document.querySelectorAll('input[name="category"]');
        const description = document.getElementById('description');
        const charCount = document.getElementById('char-count');
        const submitBtn = document.getElementById('submit-btn');
        const placeSelect = document.getElementById('place_id');

        // Character counter
        description.addEventListener('input', function() {
            const len = this.value.length;
            charCount.textContent = `${len} / 1000 karakter`;
            charCount.classList.toggle('text-red-500', len > 1000);
            charCount.classList.toggle('text-secondary', len <= 1000);
            validateForm();
        });

        // Category selection visual feedback
        categoryInputs.forEach(input => {
            input.addEventListener('change', function() {
                validateForm();
            });
        });

        // Place selection
        placeSelect.addEventListener('change', validateForm);

        function validateForm() {
            const hasPlace = placeSelect.value !== '';
            const hasCategory = Array.from(categoryInputs).some(i => i.checked);
            const hasDescription = description.value.length >= 10 && description.value.length <= 1000;

            submitBtn.disabled = !(hasPlace && hasCategory && hasDescription);
        }

        // Initial validation
        validateForm();

        // Form submission loading state
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline-block" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengirim...
            `;
        });
    });
</script>
@endsection