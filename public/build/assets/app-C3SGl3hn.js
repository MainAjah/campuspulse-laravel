(function(){let e=(e,t=document)=>t.querySelector(e),t=(e,t=document)=>Array.from(t.querySelectorAll(e));function n(e,t){let n;return(...r)=>{clearTimeout(n),n=setTimeout(()=>e(...r),t)}}function r(e,t=`info`,n=`#alert-container`){let r=document.querySelector(n);if(!r)return;let i=document.createElement(`div`);i.className=`alert alert-${t} mb-4 animate-fade-in`,i.innerHTML=`
            <div class="flex items-center justify-between">
                <span>${e}</span>
                <button type="button" class="ml-4 text-lg leading-none hover:opacity-70" onclick="this.parentElement.parentElement.remove();">&times;</button>
            </div>
        `,r.prepend(i),setTimeout(()=>i.remove(),5e3)}function i(){let n=e(`#mobile-menu-btn`),r=e(`#mobile-menu`),i=t(`#mobile-menu a`);n&&r&&(n.addEventListener(`click`,()=>{let e=r.classList.toggle(`hidden`);n.setAttribute(`aria-expanded`,!e);let t=n.querySelector(`svg`);t&&(t.innerHTML=e?`<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>`:`<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>`)}),i.forEach(e=>{e.addEventListener(`click`,()=>{r.classList.add(`hidden`),n.setAttribute(`aria-expanded`,`false`);let e=n.querySelector(`svg`);e&&(e.innerHTML=`<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>`)})}),document.addEventListener(`keydown`,e=>{e.key===`Escape`&&!r.classList.contains(`hidden`)&&(r.classList.add(`hidden`),n.setAttribute(`aria-expanded`,`false`))}))}function a(){let n=e(`#toast-container`);n&&t(`.toast`,n).forEach((e,t)=>{setTimeout(()=>e.classList.add(`show`),t*100),setTimeout(()=>{e.classList.remove(`show`),setTimeout(()=>e.remove(),300)},5e3+t*100)})}function o(){t(`form[data-validate]`).forEach(e=>{e.addEventListener(`submit`,t=>{s(e)||t.preventDefault()}),t(`input, select, textarea`,e).forEach(e=>{e.addEventListener(`blur`,()=>c(e)),e.addEventListener(`input`,()=>d(e))})})}function s(e){let n=!0;return t(`[required]`,e).forEach(e=>{c(e)||(n=!1)}),t(`input[type="email"]`,e).forEach(e=>{e.value&&!l(e.value)&&(u(e,`Format email tidak valid`),n=!1)}),t(`input[type="password"][data-min-length]`,e).forEach(e=>{let t=parseInt(e.dataset.minLength,10);e.value&&e.value.length<t&&(u(e,`Password minimal ${t} karakter`),n=!1)}),t(`input[data-confirm]`,e).forEach(e=>{let t=e.dataset.confirm,r=document.getElementById(t);r&&e.value!==r.value&&(u(e,`Password tidak cocok`),n=!1)}),n}function c(e){if(e.hasAttribute(`required`)&&!e.value.trim())return u(e,`Field ini wajib diisi`),!1;if(e.type===`email`&&e.value&&!l(e.value))return u(e,`Format email tidak valid`),!1;if(e.type===`number`){let t=e.hasAttribute(`min`)?parseFloat(e.min):null,n=e.hasAttribute(`max`)?parseFloat(e.max):null,r=parseFloat(e.value);if(e.value&&(t!==null&&r<t||n!==null&&r>n))return u(e,`Nilai harus antara ${t} dan ${n}`),!1}return d(e),!0}function l(e){return/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e)}function u(e,t){d(e),e.classList.add(`border-red-500`),e.setAttribute(`aria-invalid`,`true`);let n=document.createElement(`p`);n.className=`form-error`,n.textContent=t,n.id=`${e.id}-error`,e.setAttribute(`aria-describedby`,n.id),e.parentNode.appendChild(n)}function d(e){e.classList.remove(`border-red-500`),e.removeAttribute(`aria-invalid`);let t=e.getAttribute(`aria-describedby`);if(t){let n=document.getElementById(t);n&&n.remove(),e.removeAttribute(`aria-describedby`)}}let f=null,p=[];function m(e,t={}){if(!document.getElementById(e)||typeof L>`u`)return null;let n={center:t.center||[-6.2,106.8],zoom:t.zoom||15,scrollWheelZoom:t.scrollWheelZoom!==!1};f=L.map(e,n),L.tileLayer(`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`,{attribution:`&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors`,maxZoom:19}).addTo(f),L.icon({iconUrl:`https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png`,iconRetinaUrl:`https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png`,shadowUrl:`https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png`,iconSize:[25,41],iconAnchor:[12,41],popupAnchor:[1,-34],shadowSize:[41,41]});let r=L.icon({iconUrl:`data:image/svg+xml;base64,`+btoa(`
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                    <circle cx="12" cy="12" r="10" fill="#326B55"/>
                    <circle cx="12" cy="12" r="6" fill="#FFFFFF"/>
                </svg>
            `),iconSize:[32,32],iconAnchor:[16,32],popupAnchor:[0,-32]});if(t.places&&h(t.places,t.icon||r),p.length>0){let e=new L.featureGroup(p);f.fitBounds(e.getBounds().pad(.1))}return setTimeout(()=>f.invalidateSize(),100),f}function h(e,t){p.forEach(e=>f.removeLayer(e)),p=[],e.forEach(e=>{if(!e.latitude||!e.longitude)return;let n=L.marker([e.latitude,e.longitude],{icon:t}).addTo(f),r=e.latest_report,i=r?_(r):`Tidak ada laporan`,a=r?v(r.status):`-`,o=`
                <div class="p-2 min-w-[240px] font-sans">
                    <h3 class="font-semibold text-primary mb-1">${T(e.name)}</h3>
                    <p class="text-xs text-secondary mb-2">${T(e.category_label||e.category)}</p>
                    ${e.description?`<p class="text-sm text-secondary mb-2">${T(e.description)}</p>`:``}
                    ${r?`
                        <div class="border-t border-border pt-2">
                            <p class="text-xs text-secondary mb-1">Laporan Terbaru: <span class="font-medium text-primary">${T(r.description)}</span></p>
                            <div class="flex items-center gap-2">
                                <span class="badge" style="background: ${y(i)}; color: ${b(i)}; border-color: ${x(i)};">${i}</span>
                                <span class="badge" style="background: ${S(a)}; color: ${C(a)}; border-color: ${w(a)};">${a}</span>
                            </div>
                        </div>
                    `:`<p class="text-xs text-secondary">Belum ada laporan</p>`}
                    <a href="/explore/${e.id}" class="btn btn-primary btn-sm mt-2 w-full text-center">Lihat Detail</a>
                </div>
            `;n.bindPopup(o,{maxWidth:300,className:`leaflet-popup-custom`}),p.push(n)})}function g(e){if(!f)return;p.forEach(t=>{let n=t._placeData;n&&(e===`ALL`||n.category===e?f.addLayer(t):f.removeLayer(t))});let t=p.filter(e=>f.hasLayer(e));if(t.length>0){let e=new L.featureGroup(t);f.fitBounds(e.getBounds().pad(.1))}}function _(e){let t=(new Date-new Date(e.created_at))/6e4,n;if(e.category===`WIFI`)n=45;else if(e.category===`CROWD`)n=60;else return`Tetap`;return t<=n*.5?`Segar`:t<=n?`Mulai Usang`:`Kedaluwarsa`}function v(e){return{PENDING:`Menunggu`,VERIFIED:`Terverifikasi`,RESOLVED:`Terselesaikan`,REJECTED:`Ditolak`}[e]||e}function y(e){return{Segar:`#f0fdf4`,"Mulai Usang":`#fffbeb`,Kedaluwarsa:`#fef2f2`,Tetap:`#eff6ff`}[e]||`#f9fafb`}function b(e){return{Segar:`#166534`,"Mulai Usang":`#92400e`,Kedaluwarsa:`#991b1b`,Tetap:`#1e40af`}[e]||`#374151`}function x(e){return{Segar:`#bbf7d0`,"Mulai Usang":`#fde68a`,Kedaluwarsa:`#fecaca`,Tetap:`#bfdbfe`}[e]||`#d1d5db`}function S(e){return{Menunggu:`#fffbeb`,Terverifikasi:`#eff6ff`,Terselesaikan:`#f0fdf4`,Ditolak:`#fef2f2`}[e]||`#f9fafb`}function C(e){return{Menunggu:`#92400e`,Terverifikasi:`#1e40af`,Terselesaikan:`#166534`,Ditolak:`#991b1b`}[e]||`#374151`}function w(e){return{Menunggu:`#fde68a`,Terverifikasi:`#bfdbfe`,Terselesaikan:`#bbf7d0`,Ditolak:`#fecaca`}[e]||`#d1d5db`}function T(e){let t=document.createElement(`div`);return t.textContent=e,t.innerHTML}window.CampusPulse={initMap:m,addMarkers:h,filterMarkers:g,showAlert:r};function E(){t(`.confirm-btn`).forEach(e=>{e.addEventListener(`click`,async function(e){e.preventDefault();let t=this.dataset.reportId,n=this.dataset.isStillValid===`true`;if(!this.closest(`form`))try{let e=await fetch(`/confirm`,{method:`POST`,headers:{"Content-Type":`application/json`,"X-CSRF-TOKEN":document.querySelector(`meta[name="csrf-token"]`)?.content||``},body:JSON.stringify({report_id:t,is_still_valid:n})}),i=await e.json();e.ok?(r(i.message||`Konfirmasi berhasil dikirim`,`success`),this.disabled=!0,this.textContent=n?`Masih Valid ✓`:`Sudah Tidak Valid ✓`,this.classList.add(`btn-outline`,`opacity-50`),this.classList.remove(`btn-primary`)):r(i.message||`Gagal mengirim konfirmasi`,`error`)}catch(e){console.error(e),r(`Terjadi kesalahan jaringan`,`error`)}})})}function D(){let r=e(`#filter-form`);if(!r)return;let i=t(`input, select`,r),a=n(()=>r.submit(),300);i.forEach(e=>{e.addEventListener(`change`,a),e.tagName===`INPUT`&&e.addEventListener(`input`,a)})}function O(){t(`.modal-backdrop`).forEach(e=>{e.addEventListener(`click`,t=>{t.target===e&&A(e)})}),t(`[data-modal-close]`).forEach(e=>{e.addEventListener(`click`,()=>{let t=e.closest(`.modal-backdrop`);t&&A(t)})}),document.addEventListener(`keydown`,e=>{if(e.key===`Escape`){let e=document.querySelector(`.modal-backdrop:not(.hidden)`);e&&A(e)}})}function k(e){let t=document.getElementById(e);if(!t)return;t.classList.remove(`hidden`),t.classList.add(`flex`),document.body.style.overflow=`hidden`;let n=t.querySelector(`input, select, textarea, button`);n&&n.focus()}function A(e){e.classList.add(`hidden`),e.classList.remove(`flex`),document.body.style.overflow=``}window.openModal=k,window.closeModal=A;function j(){t(`[data-copy]`).forEach(e=>{e.addEventListener(`click`,async()=>{let t=e.dataset.copy;try{await navigator.clipboard.writeText(t);let n=e.textContent;e.textContent=`Disalin!`,setTimeout(()=>e.textContent=n,2e3)}catch(e){console.error(e),r(`Gagal menyalin`,`error`)}})})}function M(){t(`a[href^="#"]`).forEach(e=>{e.addEventListener(`click`,function(e){let t=this.getAttribute(`href`);if(t===`#`)return;let n=document.querySelector(t);n&&(e.preventDefault(),n.scrollIntoView({behavior:`smooth`,block:`start`}),n.focus({preventScroll:!0}))})})}function N(){if(`IntersectionObserver`in window){let e=new IntersectionObserver(t=>{t.forEach(t=>{if(t.isIntersecting){let n=t.target;n.dataset.src&&(n.src=n.dataset.src,n.removeAttribute(`data-src`)),n.dataset.srcset&&(n.srcset=n.dataset.srcset,n.removeAttribute(`data-srcset`)),e.unobserve(n)}})},{rootMargin:`100px`});t(`img[data-src]`).forEach(t=>e.observe(t))}}function P(){localStorage.getItem(`theme`)===`dark`&&document.documentElement.classList.add(`dark`)}function F(){i(),a(),o(),E(),D(),O(),j(),M(),N(),P(),document.dispatchEvent(new CustomEvent(`campuspulse:ready`))}document.readyState===`loading`?document.addEventListener(`DOMContentLoaded`,F):F()})();var e=document.createElement(`style`);e.textContent=`
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
`,document.head.appendChild(e);