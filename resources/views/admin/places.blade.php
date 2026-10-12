@php
    $categoryLabels = [
        'LIBRARY' => 'Perpustakaan',
        'STUDY_ROOM' => 'Ruang Belajar',
        'LABORATORY' => 'Laboratorium',
        'CANTEEN' => 'Kantin',
        'WIFI' => 'Wi-Fi',
        'OTHER' => 'Lainnya',
    ];
@endphp

<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-lg font-semibold text-primary">Daftar Fasilitas</h2>
        <button type="button" id="open-place-modal" class="btn btn-primary">Tambah Fasilitas</button>
    </div>

    @if ($places->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-secondary border-b border-border">
                        <th class="pb-3 font-medium">Nama</th>
                        <th class="pb-3 font-medium hidden md:table-cell">Kategori</th>
                        <th class="pb-3 font-medium hidden lg:table-cell">Kampus</th>
                        <th class="pb-3 font-medium hidden xl:table-cell">Lantai</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($places as $place)
                        <tr class="hover:bg-surface/50 transition-colors">
                            <td class="py-4 font-medium text-primary">{{ $place->name }}</td>
                            <td class="py-4 hidden md:table-cell">
                                <span class="px-2 py-1 rounded bg-surface border border-border text-xs">{{ $categoryLabels[$place->category] ?? $place->category }}</span>
                            </td>
                            <td class="py-4 hidden lg:table-cell text-sm text-secondary">{{ $place->campus->name ?? '-' }}</td>
                            <td class="py-4 hidden xl:table-cell text-sm text-secondary">{{ $place->floor ?? '-' }}</td>
                            <td class="py-4">
                                @if ($place->is_demo)
                                    <span class="px-2 py-1 rounded bg-soft-green text-accent text-xs font-medium">DEMO</span>
                                @else
                                    <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-medium">Aktif</span>
                                @endif
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" class="btn btn-outline btn-sm edit-place" data-place='@json($place)'>Edit</button>
                                    <form action="{{ route('admin.places.destroy', $place) }}" method="POST" class="inline" onsubmit="return confirm('Hapus fasilitas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline btn-sm text-red-600 border-red-200 hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        {{ $places->links() }}
    @else
        <div class="card p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-border mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <h3 class="text-lg font-medium text-primary mb-1">Belum ada fasilitas</h3>
            <p class="text-secondary mb-4">Tambahkan fasilitas pertama untuk memulai</p>
            <button type="button" id="open-place-modal-empty" class="btn btn-primary">Tambah Fasilitas</button>
        </div>
    @endif
</div>

<!-- Place Modal -->
<div id="place-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-border flex items-center justify-between sticky top-0 bg-white">
            <h3 id="modal-title" class="text-lg font-semibold text-primary">Tambah Fasilitas</h3>
            <button type="button" id="close-place-modal" class="p-1 rounded-lg hover:bg-surface text-secondary" aria-label="Tutup">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="place-form" action="{{ route('admin.places.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="place_id" id="place_id">

            <div>
                <label for="campus_id" class="block text-sm font-medium text-secondary mb-1">Kampus <span class="text-red-500">*</span></label>
                <select name="campus_id" id="campus_id" class="input w-full py-2.5" required>
                    @foreach (\App\Models\Campus::all() as $campus)
                        <option value="{{ $campus->id }}" {{ $campus->is_demo ? '(DEMO) ' : '' }}>{{ $campus->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-secondary mb-1">Nama Fasilitas <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" class="input w-full py-2.5" required maxlength="120">
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-secondary mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="category" id="category" class="input w-full py-2.5" required>
                    @foreach ($categoryLabels as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-secondary mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="input w-full" maxlength="500"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-sm font-medium text-secondary mb-1">Latitude <span class="text-red-500">*</span></label>
                    <input type="number" step="any" name="latitude" id="latitude" class="input w-full py-2.5" required min="-90" max="90">
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-medium text-secondary mb-1">Longitude <span class="text-red-500">*</span></label>
                    <input type="number" step="any" name="longitude" id="longitude" class="input w-full py-2.5" required min="-180" max="180">
                </div>
            </div>

            <div>
                <label for="floor" class="block text-sm font-medium text-secondary mb-1">Lantai</label>
                <input type="text" name="floor" id="floor" class="input w-full py-2.5" maxlength="20" placeholder="Contoh: 1, 2, B1, Lantai 3">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_demo" id="is_demo" class="w-4 h-4 text-accent border-border rounded focus:ring-accent">
                <label for="is_demo" class="text-sm text-secondary">Tandai sebagai data demo</label>
            </div>

            <div class="pt-4 border-t border-border flex justify-end gap-3">
                <button type="button" id="cancel-place-modal" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('place-modal');
        const form = document.getElementById('place-form');
        const openBtn = document.getElementById('open-place-modal');
        const openBtnEmpty = document.getElementById('open-place-modal-empty');
        const closeBtn = document.getElementById('close-place-modal');
        const cancelBtn = document.getElementById('cancel-place-modal');
        const modalTitle = document.getElementById('modal-title');

        function openModal(place = null) {
            form.reset();
            document.getElementById('place_id').value = '';

            if (place) {
                modalTitle.textContent = 'Edit Fasilitas';
                document.getElementById('place_id').value = place.id;
                document.getElementById('campus_id').value = place.campus_id;
                document.getElementById('name').value = place.name;
                document.getElementById('category').value = place.category;
                document.getElementById('description').value = place.description || '';
                document.getElementById('latitude').value = place.latitude;
                document.getElementById('longitude').value = place.longitude;
                document.getElementById('floor').value = place.floor || '';
                document.getElementById('is_demo').checked = place.is_demo;
                form.action = `{{ route('admin.places.update', ':id') }}`.replace(':id', place.id);
            } else {
                modalTitle.textContent = 'Tambah Fasilitas';
                form.action = '{{ route('admin.places.store') }}';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        if (openBtn) openBtn.addEventListener('click', () => openModal());
        if (openBtnEmpty) openBtnEmpty.addEventListener('click', () => openModal());
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        // Edit buttons
        document.querySelectorAll('.edit-place').forEach(btn => {
            btn.addEventListener('click', function() {
                const place = JSON.parse(this.dataset.place);
                openModal(place);
            });
        });

        // Close on backdrop click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
        });
    });
</script>
@endsection