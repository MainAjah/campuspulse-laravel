// CampusPulse - Vanilla JavaScript untuk Interaktivitas
// Tanpa dependensi framework - bekerja dengan Laravel Blade

// IIFE (Immediately Invoked Function Expression) untuk menghindari polusi global scope
(function() {
    'use strict';

    // ============================================
    // Fungsi-fungsi Utility
    // ============================================

    /**
     * Shortcut untuk querySelector - memilih satu elemen DOM
     * @param {string} selector - CSS selector
     * @param {Element} context - Konteks pencarian (default: document)
     * @returns {Element|null} Elemen DOM yang ditemukan
     */
    const $ = (selector, context = document) => context.querySelector(selector);

    /**
     * Shortcut untuk querySelectorAll - memilih banyak elemen DOM
     * @param {string} selector - CSS selector
     * @param {Element} context - Konteks pencarian (default: document)
     * @returns {Array} Array dari elemen DOM yang ditemukan
     */
    const $$ = (selector, context = document) => Array.from(context.querySelectorAll(selector));

    /**
     * Debounce function - menunda eksekusi fungsi sampai jeda waktu tertentu
     * Berguna untuk optimasi event yang sering dipanggil (scroll, input, resize)
     * @param {Function} fn - Fungsi yang akan di-debounce
     * @param {number} delay - Waktu delay dalam milidetik
     * @returns {Function} Fungsi yang sudah di-debounce
     */
    function debounce(fn, delay) {
        let timeoutId;
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => fn(...args), delay);
        };
    }

    /**
     * Menampilkan alert/notifikasi di halaman
     * @param {string} message - Pesan yang akan ditampilkan
     * @param {string} type - Tipe alert (info, success, error, warning)
     * @param {string} container - Selector untuk container alert
     */
    function showAlert(message, type = 'info', container = '#alert-container') {
        const el = document.querySelector(container);
        if (!el) return;

        // Buat elemen alert baru
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} mb-4 animate-fade-in`;
        alert.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button type="button" class="ml-4 text-lg leading-none hover:opacity-70" onclick="this.parentElement.parentElement.remove();">&times;</button>
            </div>
        `;

        // Tambahkan alert ke container
        el.prepend(alert);

        // Hapus alert secara otomatis setelah 5 detik
        setTimeout(() => alert.remove(), 5000);
    }

    /**
     * Mengubah form HTML menjadi object JavaScript
     * Mendukung multiple values untuk field yang sama
     * @param {HTMLFormElement} form - Elemen form yang akan di-serialize
     * @returns {Object} Object dengan key-value dari form
     */
    function serializeForm(form) {
        const formData = new FormData(form);
        const data = {};

        // Iterasi setiap field dalam form
        for (const [key, value] of formData.entries()) {
            // Jika key sudah ada, ubah menjadi array
            if (data[key]) {
                data[key] = Array.isArray(data[key]) ? [...data[key], value] : [data[key], value];
            } else {
                data[key] = value;
            }
        }

        return data;
    }

    // ============================================
    // Navigasi Mobile
    // ============================================

    /**
     * Inisialisasi menu navigasi mobile
     * Menangani toggle menu, animasi icon, dan menutup menu saat link diklik
     */
    function initMobileNav() {
        const menuBtn = $('#mobile-menu-btn');
        const menu = $('#mobile-menu');
        const menuLinks = $$('#mobile-menu a');

        if (!menuBtn || !menu) return;

        // Event handler untuk tombol menu (hamburger)
        menuBtn.addEventListener('click', () => {
            // Toggle class hidden untuk menampilkan/menyembunyikan menu
            const isOpen = menu.classList.toggle('hidden');

            // Update atribut aria untuk accessibility
            menuBtn.setAttribute('aria-expanded', !isOpen);

            // Ubah icon antara hamburger dan close (X)
            const icon = menuBtn.querySelector('svg');
            if (icon) {
                icon.innerHTML = isOpen
                    ? '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>';
            }
        });

        // Tutup menu saat link di dalam menu diklik
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.add('hidden');
                menuBtn.setAttribute('aria-expanded', 'false');

                // Kembalikan icon ke hamburger
                const icon = menuBtn.querySelector('svg');
                if (icon) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>';
                }
            });
        });

        // Tutup menu saat tombol Escape ditekan
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                menuBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // ============================================
    // Toast Notifications (Notifikasi Pop-up)
    // ============================================

    /**
     * Inisialisasi toast notifications
     * Menampilkan notifikasi yang muncul dari samping dengan animasi
     * Biasanya digunakan untuk flash messages dari session Laravel
     */
    function initToasts() {
        const toastContainer = $('#toast-container');
        if (!toastContainer) return;

        const toasts = $$('.toast', toastContainer);

        // Animasi tampil untuk setiap toast dengan delay bertahap
        toasts.forEach((toast, i) => {
            // Tampilkan toast dengan delay 100ms antar toast
            setTimeout(() => toast.classList.add('show'), i * 100);

            // Sembunyikan dan hapus toast setelah 5 detik
            setTimeout(() => {
                toast.classList.remove('show');
                // Hapus dari DOM setelah animasi selesai
                setTimeout(() => toast.remove(), 300);
            }, 5000 + i * 100);
        });
    }

    // ============================================
    // Validasi Form
    // ============================================

    /**
     * Inisialisasi validasi form untuk semua form dengan atribut data-validate
     * Mendukung validasi real-time saat user mengetik atau blur dari field
     */
    function initFormValidation() {
        const forms = $$('form[data-validate]');

        forms.forEach(form => {
            // Validasi saat form di-submit
            form.addEventListener('submit', (e) => {
                if (!validateForm(form)) {
                    e.preventDefault(); // Cegah submit jika tidak valid
                }
            });

            // Validasi real-time pada setiap input
            const inputs = $$('input, select, textarea', form);
            inputs.forEach(input => {
                // Validasi saat input kehilangan fokus
                input.addEventListener('blur', () => validateField(input));
                // Hapus error saat user mulai mengetik
                input.addEventListener('input', () => clearFieldError(input));
            });
        });
    }

    /**
     * Validasi seluruh form
     * @param {HTMLFormElement} form - Form yang akan divalidasi
     * @returns {boolean} True jika valid, false jika ada error
     */
    function validateForm(form) {
        let isValid = true;
        const requiredFields = $$('[required]', form);

        // Validasi semua field yang required
        requiredFields.forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });

        // Validasi format email
        const emailFields = $$('input[type="email"]', form);
        emailFields.forEach(field => {
            if (field.value && !isValidEmail(field.value)) {
                showFieldError(field, 'Format email tidak valid');
                isValid = false;
            }
        });

        // Validasi panjang password minimum
        const passwordFields = $$('input[type="password"][data-min-length]', form);
        passwordFields.forEach(field => {
            const minLength = parseInt(field.dataset.minLength, 10);
            if (field.value && field.value.length < minLength) {
                showFieldError(field, `Password minimal ${minLength} karakter`);
                isValid = false;
            }
        });

        // Validasi konfirmasi password (password harus sama)
        const confirmFields = $$('input[data-confirm]', form);
        confirmFields.forEach(field => {
            const targetId = field.dataset.confirm;
            const target = document.getElementById(targetId);
            if (target && field.value !== target.value) {
                showFieldError(field, 'Password tidak cocok');
                isValid = false;
            }
        });

        return isValid;
    }

    /**
     * Validasi satu field
     * @param {HTMLInputElement} field - Field yang akan divalidasi
     * @returns {boolean} True jika valid, false jika error
     */
    function validateField(field) {
        // Validasi field required
        if (field.hasAttribute('required') && !field.value.trim()) {
            showFieldError(field, 'Field ini wajib diisi');
            return false;
        }

        // Validasi format email
        if (field.type === 'email' && field.value && !isValidEmail(field.value)) {
            showFieldError(field, 'Format email tidak valid');
            return false;
        }

        // Validasi range angka (min dan max)
        if (field.type === 'number') {
            const min = field.hasAttribute('min') ? parseFloat(field.min) : null;
            const max = field.hasAttribute('max') ? parseFloat(field.max) : null;
            const val = parseFloat(field.value);

            if (field.value && (min !== null && val < min || max !== null && val > max)) {
                showFieldError(field, `Nilai harus antara ${min} dan ${max}`);
                return false;
            }
        }

        // Jika lolos semua validasi, hapus error
        clearFieldError(field);
        return true;
    }

    /**
     * Validasi format email dengan regex
     * @param {string} email - Email yang akan divalidasi
     * @returns {boolean} True jika format valid
     */
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    /**
     * Menampilkan pesan error di bawah field
     * @param {HTMLInputElement} field - Field yang error
     * @param {string} message - Pesan error yang ditampilkan
     */
    function showFieldError(field, message) {
        clearFieldError(field); // Hapus error lama jika ada

        // Tambahkan styling error pada field
        field.classList.add('border-red-500');
        field.setAttribute('aria-invalid', 'true');

        // Buat elemen pesan error
        const error = document.createElement('p');
        error.className = 'form-error';
        error.textContent = message;
        error.id = `${field.id}-error`;

        // Hubungkan field dengan pesan error untuk accessibility
        field.setAttribute('aria-describedby', error.id);

        // Tambahkan pesan error setelah field
        field.parentNode.appendChild(error);
    }

    /**
     * Menghapus pesan error dari field
     * @param {HTMLInputElement} field - Field yang akan dibersihkan
     */
    function clearFieldError(field) {
        // Hapus styling error
        field.classList.remove('border-red-500');
        field.removeAttribute('aria-invalid');

        // Hapus pesan error jika ada
        const errorId = field.getAttribute('aria-describedby');
        if (errorId) {
            const error = document.getElementById(errorId);
            if (error) error.remove();
            field.removeAttribute('aria-describedby');
        }
    }

    // ============================================
    // Inisialisasi Map (Leaflet)
    // ============================================

    // Variabel global untuk menyimpan instance map dan marker
    let mapInstance = null;
    let mapMarkers = [];

    /**
     * Inisialisasi peta dengan Leaflet.js
     * @param {string} containerId - ID elemen container untuk peta
     * @param {Object} options - Konfigurasi peta (center, zoom, places, dll)
     * @returns {Object|null} Instance Leaflet map atau null jika gagal
     */
    function initMap(containerId, options = {}) {
        const container = document.getElementById(containerId);
        if (!container || typeof L === 'undefined') return null;

        // Konfigurasi default untuk peta
        const defaultOptions = {
            center: options.center || [-6.2, 106.8], // Default: Jakarta
            zoom: options.zoom || 15,
            scrollWheelZoom: options.scrollWheelZoom !== false,
        };

        // Buat instance peta
        mapInstance = L.map(containerId, defaultOptions);

        // Tambahkan tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(mapInstance);

        // Icon marker default dari Leaflet
        const defaultIcon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41],
        });

        // Icon marker custom dengan warna hijau (sesuai tema CampusPulse)
        const greenIcon = L.icon({
            iconUrl: 'data:image/svg+xml;base64,' + btoa(`
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                    <circle cx="12" cy="12" r="10" fill="#326B55"/>
                    <circle cx="12" cy="12" r="6" fill="#FFFFFF"/>
                </svg>
            `),
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32],
        });

        // Tambahkan marker jika ada data places
        if (options.places) {
            addMarkers(options.places, options.icon || greenIcon);
        }

        // Sesuaikan zoom dan center agar semua marker terlihat
        if (mapMarkers.length > 0) {
            const group = new L.featureGroup(mapMarkers);
            mapInstance.fitBounds(group.getBounds().pad(0.1));
        }

        // Perbaiki rendering peta setelah container dimuat
        setTimeout(() => mapInstance.invalidateSize(), 100);

        return mapInstance;
    }

    /**
     * Menambahkan marker-marker ke peta
     * @param {Array} places - Array objek place dengan latitude, longitude, dll
     * @param {Object} icon - Icon Leaflet untuk marker
     */
    function addMarkers(places, icon) {
        // Hapus marker lama jika ada
        mapMarkers.forEach(m => mapInstance.removeLayer(m));
        mapMarkers = [];

        places.forEach(place => {
            // Skip jika tidak ada koordinat
            if (!place.latitude || !place.longitude) return;

            // Buat marker di posisi koordinat
            const marker = L.marker([place.latitude, place.longitude], { icon })
                .addTo(mapInstance);

            // Ambil data laporan terbaru untuk place ini
            const latestReport = place.latest_report;
            const freshness = latestReport ? getFreshnessLabel(latestReport) : 'Tidak ada laporan';
            const status = latestReport ? getStatusLabel(latestReport.status) : '-';

            // Konten popup yang menampilkan info place dan laporan terbaru
            const popupContent = `
                <div class="p-2 min-w-[240px] font-sans">
                    <h3 class="font-semibold text-primary mb-1">${escapeHtml(place.name)}</h3>
                    <p class="text-xs text-secondary mb-2">${escapeHtml(place.category_label || place.category)}</p>
                    ${place.description ? `<p class="text-sm text-secondary mb-2">${escapeHtml(place.description)}</p>` : ''}
                    ${latestReport ? `
                        <div class="border-t border-border pt-2">
                            <p class="text-xs text-secondary mb-1">Laporan Terbaru: <span class="font-medium text-primary">${escapeHtml(latestReport.description)}</span></p>
                            <div class="flex items-center gap-2">
                                <span class="badge" style="background: ${getFreshnessBg(freshness)}; color: ${getFreshnessText(freshness)}; border-color: ${getFreshnessBorder(freshness)};">${freshness}</span>
                                <span class="badge" style="background: ${getStatusBg(status)}; color: ${getStatusText(status)}; border-color: ${getStatusBorder(status)};">${status}</span>
                            </div>
                        </div>
                    ` : '<p class="text-xs text-secondary">Belum ada laporan</p>'}
                    <a href="/explore/${place.id}" class="btn btn-primary btn-sm mt-2 w-full text-center">Lihat Detail</a>
                </div>
            `;

            // Bind popup ke marker
            marker.bindPopup(popupContent, {
                maxWidth: 300,
                className: 'leaflet-popup-custom',
            });

            // Simpan marker ke array
            mapMarkers.push(marker);
        });
    }

    /**
     * Filter marker berdasarkan kategori
     * @param {string} category - Kategori yang akan ditampilkan (atau 'ALL' untuk semua)
     */
    function filterMarkers(category) {
        if (!mapInstance) return;

        mapMarkers.forEach(marker => {
            const place = marker._placeData;
            if (!place) return;

            // Tampilkan atau sembunyikan marker berdasarkan kategori
            if (category === 'ALL' || place.category === category) {
                mapInstance.addLayer(marker);
            } else {
                mapInstance.removeLayer(marker);
            }
        });

        // Sesuaikan zoom agar marker yang terlihat fit di layar
        const visibleMarkers = mapMarkers.filter(m => mapInstance.hasLayer(m));
        if (visibleMarkers.length > 0) {
            const group = new L.featureGroup(visibleMarkers);
            mapInstance.fitBounds(group.getBounds().pad(0.1));
        }
    }

    /**
     * Menentukan label kesegaran laporan berdasarkan waktu dan TTL
     * @param {Object} report - Objek laporan dengan created_at dan category
     * @returns {string} Label kesegaran (Segar, Mulai Usang, Kedaluwarsa, Tetap)
     */
    function getFreshnessLabel(report) {
        const now = new Date();
        const created = new Date(report.created_at);
        const diffMinutes = (now - created) / (1000 * 60);

        // Tentukan TTL berdasarkan kategori
        let ttl;
        if (report.category === 'WIFI') ttl = 45;       // WiFi: 45 menit
        else if (report.category === 'CROWD') ttl = 60; // Crowd: 60 menit
        else return 'Tetap'; // DAMAGE: tidak pernah kedaluwarsa

        // Tentukan label berdasarkan persentase TTL
        if (diffMinutes <= ttl * 0.5) return 'Segar';        // 0-50% TTL
        if (diffMinutes <= ttl) return 'Mulai Usang';        // 50-100% TTL
        return 'Kedaluwarsa';                                 // > 100% TTL
    }

    /**
     * Mengubah status laporan dari kode ke label Indonesia
     * @param {string} status - Status code (PENDING, VERIFIED, RESOLVED, REJECTED)
     * @returns {string} Label status dalam bahasa Indonesia
     */
    function getStatusLabel(status) {
        const labels = {
            PENDING: 'Menunggu',
            VERIFIED: 'Terverifikasi',
            RESOLVED: 'Terselesaikan',
            REJECTED: 'Ditolak',
        };
        return labels[status] || status;
    }

    /**
     * Mendapatkan warna background untuk badge kesegaran
     * @param {string} label - Label kesegaran
     * @returns {string} Kode warna hex untuk background
     */
    function getFreshnessBg(label) {
        const map = { 'Segar': '#f0fdf4', 'Mulai Usang': '#fffbeb', 'Kedaluwarsa': '#fef2f2', 'Tetap': '#eff6ff' };
        return map[label] || '#f9fafb';
    }

    /**
     * Mendapatkan warna teks untuk badge kesegaran
     * @param {string} label - Label kesegaran
     * @returns {string} Kode warna hex untuk teks
     */
    function getFreshnessText(label) {
        const map = { 'Segar': '#166534', 'Mulai Usang': '#92400e', 'Kedaluwarsa': '#991b1b', 'Tetap': '#1e40af' };
        return map[label] || '#374151';
    }

    /**
     * Mendapatkan warna border untuk badge kesegaran
     * @param {string} label - Label kesegaran
     * @returns {string} Kode warna hex untuk border
     */
    function getFreshnessBorder(label) {
        const map = { 'Segar': '#bbf7d0', 'Mulai Usang': '#fde68a', 'Kedaluwarsa': '#fecaca', 'Tetap': '#bfdbfe' };
        return map[label] || '#d1d5db';
    }

    /**
     * Mendapatkan warna background untuk badge status
     * @param {string} label - Label status
     * @returns {string} Kode warna hex untuk background
     */
    function getStatusBg(label) {
        const map = { 'Menunggu': '#fffbeb', 'Terverifikasi': '#eff6ff', 'Terselesaikan': '#f0fdf4', 'Ditolak': '#fef2f2' };
        return map[label] || '#f9fafb';
    }

    /**
     * Mendapatkan warna teks untuk badge status
     * @param {string} label - Label status
     * @returns {string} Kode warna hex untuk teks
     */
    function getStatusText(label) {
        const map = { 'Menunggu': '#92400e', 'Terverifikasi': '#1e40af', 'Terselesaikan': '#166534', 'Ditolak': '#991b1b' };
        return map[label] || '#374151';
    }

    /**
     * Mendapatkan warna border untuk badge status
     * @param {string} label - Label status
     * @returns {string} Kode warna hex untuk border
     */
    function getStatusBorder(label) {
        const map = { 'Menunggu': '#fde68a', 'Terverifikasi': '#bfdbfe', 'Terselesaikan': '#bbf7d0', 'Ditolak': '#fecaca' };
        return map[label] || '#d1d5db';
    }

    /**
     * Escape HTML untuk mencegah XSS
     * @param {string} text - Teks yang akan di-escape
     * @returns {string} Teks yang sudah aman dari HTML injection
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Expose fungsi-fungsi peta ke global scope agar bisa dipanggil dari Blade
    window.CampusPulse = {
        initMap,
        addMarkers,
        filterMarkers,
        showAlert,
    };

    // ============================================
    // Tombol Konfirmasi Laporan
    // ============================================

    /**
     * Inisialisasi tombol konfirmasi untuk laporan
     * User bisa konfirmasi apakah laporan masih valid atau sudah tidak valid
     */
    function initConfirmButtons() {
        $$('.confirm-btn').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();

                const reportId = this.dataset.reportId;
                const isStillValid = this.dataset.isStillValid === 'true';
                const form = this.closest('form');

                // Jika tombol ada dalam form, biarkan form yang handle submit
                if (form) {
                    return;
                }

                // Kirim konfirmasi via AJAX
                try {
                    const response = await fetch('/confirm', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        },
                        body: JSON.stringify({ report_id: reportId, is_still_valid: isStillValid }),
                    });

                    const data = await response.json();

                    if (response.ok) {
                        showAlert(data.message || 'Konfirmasi berhasil dikirim', 'success');

                        // Update UI tombol setelah berhasil konfirmasi
                        this.disabled = true;
                        this.textContent = isStillValid ? 'Masih Valid ✓' : 'Sudah Tidak Valid ✓';
                        this.classList.add('btn-outline', 'opacity-50');
                        this.classList.remove('btn-primary');
                    } else {
                        showAlert(data.message || 'Gagal mengirim konfirmasi', 'error');
                    }
                } catch (err) {
                    console.error(err);
                    showAlert('Terjadi kesalahan jaringan', 'error');
                }
            });
        });
    }

    // ============================================
    // Filter Laporan (Halaman Explore/Feed)
    // ============================================

    /**
     * Inisialisasi filter laporan yang auto-submit saat user mengubah filter
     * Menggunakan debounce untuk mengurangi jumlah request
     */
    function initReportFilters() {
        const filterForm = $('#filter-form');
        if (!filterForm) return;

        const inputs = $$('input, select', filterForm);

        // Debounce submit agar tidak langsung submit setiap keystroke
        const debouncedSubmit = debounce(() => filterForm.submit(), 300);

        inputs.forEach(input => {
            // Auto-submit saat value berubah
            input.addEventListener('change', debouncedSubmit);

            // Auto-submit saat mengetik (khusus input text)
            if (input.tagName === 'INPUT') {
                input.addEventListener('input', debouncedSubmit);
            }
        });
    }

    // ============================================
    // Modal Handling (Dialog Pop-up)
    // ============================================

    /**
     * Inisialisasi modal dialog
     * Menangani open, close, backdrop click, dan escape key
     */
    function initModals() {
        // Tutup modal saat backdrop (area gelap di belakang) diklik
        $$('.modal-backdrop').forEach(backdrop => {
            backdrop.addEventListener('click', (e) => {
                // Hanya tutup jika klik langsung di backdrop, bukan child element
                if (e.target === backdrop) {
                    closeModal(backdrop);
                }
            });
        });

        // Tutup modal via tombol close
        $$('[data-modal-close]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.closest('.modal-backdrop');
                if (modal) closeModal(modal);
            });
        });

        // Tutup modal saat tombol Escape ditekan
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal-backdrop:not(.hidden)');
                if (openModal) closeModal(openModal);
            }
        });
    }

    /**
     * Membuka modal dengan ID tertentu
     * @param {string} modalId - ID elemen modal yang akan dibuka
     */
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        // Tampilkan modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Disable scroll pada body saat modal terbuka
        document.body.style.overflow = 'hidden';

        // Fokus ke input pertama di modal untuk UX yang lebih baik
        const firstInput = modal.querySelector('input, select, textarea, button');
        if (firstInput) firstInput.focus();
    }

    /**
     * Menutup modal
     * @param {HTMLElement} modal - Elemen modal yang akan ditutup
     */
    function closeModal(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        // Kembalikan scroll pada body
        document.body.style.overflow = '';
    }

    // Expose fungsi modal ke global scope agar bisa dipanggil dari inline onclick
    window.openModal = openModal;
    window.closeModal = closeModal;

    // ============================================
    // Copy to Clipboard (Salin ke Clipboard)
    // ============================================

    /**
     * Inisialisasi tombol copy to clipboard
     * Menyalin teks ke clipboard dan menampilkan feedback
     */
    function initCopyButtons() {
        $$('[data-copy]').forEach(btn => {
            btn.addEventListener('click', async () => {
                const text = btn.dataset.copy;

                try {
                    // Gunakan Clipboard API modern
                    await navigator.clipboard.writeText(text);

                    // Ubah teks tombol sementara sebagai feedback
                    const originalText = btn.textContent;
                    btn.textContent = 'Disalin!';

                    // Kembalikan teks tombol setelah 2 detik
                    setTimeout(() => btn.textContent = originalText, 2000);
                } catch (err) {
                    console.error(err);
                    showAlert('Gagal menyalin', 'error');
                }
            });
        });
    }

    // ============================================
    // Smooth Scroll untuk Anchor Links
    // ============================================

    /**
     * Inisialisasi smooth scroll untuk link anchor (#)
     * Memberikan animasi smooth saat klik link yang menuju ke bagian lain di halaman
     */
    function initSmoothScroll() {
        $$('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return; // Abaikan link '#' tanpa target

                const target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();

                    // Scroll dengan animasi smooth
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });

                    // Fokus ke target untuk accessibility
                    target.focus({ preventScroll: true });
                }
            });
        });
    }

    // ============================================
    // Lazy Load Images (Muat Gambar Saat Terlihat)
    // ============================================

    /**
     * Inisialisasi lazy loading untuk gambar
     * Gambar hanya dimuat saat sudah terlihat di viewport untuk menghemat bandwidth
     */
    function initLazyLoad() {
        // Cek browser support untuk IntersectionObserver
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    // Jika gambar sudah terlihat di viewport
                    if (entry.isIntersecting) {
                        const img = entry.target;

                        // Load gambar dari data-src ke src
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }

                        // Load srcset jika ada (untuk responsive images)
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                            img.removeAttribute('data-srcset');
                        }

                        // Hentikan observasi setelah gambar dimuat
                        observer.unobserve(img);
                    }
                });
            }, { rootMargin: '100px' }); // Mulai load 100px sebelum gambar terlihat

            // Observasi semua gambar dengan atribut data-src
            $$('img[data-src]').forEach(img => observer.observe(img));
        }
    }

    // ============================================
    // Theme Toggle (Toggle Tema Gelap/Terang)
    // ============================================

    /**
     * Inisialisasi tema (light/dark mode)
     * Saat ini hanya light mode, tapi sudah dipersiapkan untuk dark mode di masa depan
     */
    function initTheme() {
        // Cek tema yang tersimpan di localStorage
        const savedTheme = localStorage.getItem('theme');

        // Terapkan tema dark jika tersimpan
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        }

        // TODO: Tambahkan tombol toggle tema
    }

    // ============================================
    // Inisialisasi Utama
    // ============================================

    /**
     * Fungsi utama yang menginisialisasi semua feature
     * Dipanggil saat DOM sudah ready
     */
    function init() {
        initMobileNav();
        initToasts();
        initFormValidation();
        initConfirmButtons();
        initReportFilters();
        initModals();
        initCopyButtons();
        initSmoothScroll();
        initLazyLoad();
        initTheme();

        // Dispatch custom event untuk script eksternal yang mungkin perlu tahu
        // bahwa CampusPulse sudah siap
        document.dispatchEvent(new CustomEvent('campuspulse:ready'));
    }

    // Jalankan init saat DOM sudah ready
    if (document.readyState === 'loading') {
        // DOM masih loading, tunggu event DOMContentLoaded
        document.addEventListener('DOMContentLoaded', init);
    } else {
        // DOM sudah ready, jalankan langsung
        init();
    }
})();

// ============================================
// CSS untuk Animasi (Diinjeksi via JavaScript)
// ============================================

// Buat elemen style untuk animasi CSS
const style = document.createElement('style');
style.textContent = `
    /* Animasi fade in dari atas */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.3s ease; }

    /* Animasi slide in dari kanan */
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(100%); }
        to { opacity: 1; transform: translateX(0); }
    }
    .animate-slide-in { animation: slideIn 0.3s ease; }

    /* Styling dan animasi untuk toast notifications */
    .toast {
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    }
    .toast.show {
        opacity: 1;
        transform: translateX(0);
    }

    /* Animasi untuk modal backdrop */
    .modal-backdrop {
        transition: opacity 0.2s ease;
    }
    .modal-backdrop.hidden {
        opacity: 0;
        pointer-events: none;
    }
    .modal-backdrop:not(.hidden) {
        opacity: 1;
        pointer-events: auto;
    }

    /* Animasi scale untuk konten modal */
    .modal-backdrop > div {
        transform: scale(0.95);
        transition: transform 0.2s ease;
    }
    .modal-backdrop:not(.hidden) > div {
        transform: scale(1);
    }
`;

// Tambahkan style ke head dokumen
document.head.appendChild(style);
