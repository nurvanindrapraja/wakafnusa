<x-public-layout>
    @section('title', 'Katalog Wakaf Tanah')

    <div class="py-20 bg-surface-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 pt-10">
                <h2 class="text-4xl font-black text-surface-900 sm:text-5xl">Katalog Wakaf Tanah</h2>
                <div class="mt-8 mb-12 flex justify-center gap-4">
                    <a href="{{ route('land-waqf.stats') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-nu-gold-500 text-nu-green-900 rounded-xl font-bold text-sm hover:bg-nu-gold-600 transition-all shadow-lg shadow-nu-gold-100 group">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        Buka Statistik Wakaf Tanah
                    </a>
                </div>

            <!-- Filter Toggle & Login Link -->
            <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
                <button type="button" id="toggleFilter" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white ring-1 ring-surface-200 text-surface-600 rounded-xl font-bold text-sm hover:bg-surface-50 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    Tampilkan Filter
                </button>
                
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('land-waqf.register.form') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-nu-green-50 text-nu-green-700 rounded-xl font-bold text-sm hover:bg-nu-green-100 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Daftarkan Aset Anda
                    </a>
                    
                    <a href="{{ route('land-waqf.check-status.form') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-nu-green-50 text-nu-green-700 rounded-xl font-bold text-sm hover:bg-nu-green-100 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Cek Status Pendaftaran
                    </a>
                    
                    <a href="{{ route('lwp.login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-nu-green-50 text-nu-green-700 rounded-xl font-bold text-sm hover:bg-nu-green-100 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        Login Pengurus LWP
                    </a>
                </div>
            </div>

            <!-- Filters Section (Hidden by default) -->
            <div id="filterContainer" class="hidden mb-12 bg-white p-8 rounded-3xl shadow-card border border-surface-100 relative overflow-hidden animate-fade-in-down">
                <!-- Loading Progress Bar -->
                <div id="loadingProgress" class="absolute top-0 left-0 w-full h-1 bg-nu-green-600 transform -translate-x-full transition-transform duration-500 z-10"></div>
                
                <form id="filterForm">
                    <!-- ... existing form fields ... -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <!-- Text Search -->
                        <div class="lg:col-span-2">
                            <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Cari Kata Kunci</label>
                            <div class="relative group">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all"
                                    placeholder="Wakif, No AIW, Sertifikat...">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-surface-400">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Penggunaan -->
                        <div>
                            <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Penggunaan</label>
                            <select name="penggunaan" class="w-full py-3 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all">
                                <option value="">Semua Penggunaan</option>
                                @foreach($usages as $usage)
                                    <option value="{{ $usage->KODE_PENG }}" {{ request('penggunaan') == $usage->KODE_PENG ? 'selected' : '' }}>
                                        {{ $usage->PENGGUNAAN }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Province -->
                        <div>
                            <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Provinsi</label>
                            <select name="provinsi" id="provinsi" class="w-full py-3 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->kode_lokasi }}" {{ request('provinsi') == $prov->kode_lokasi ? 'selected' : '' }}>
                                        {{ $prov->nama_lokasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kabupaten/Kota -->
                        <div>
                            <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Kab/Kota</label>
                            <select name="kabupaten" id="kabupaten" class="w-full py-3 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all {{ !request('provinsi') ? 'bg-surface-50 cursor-not-allowed' : '' }}" {{ !request('provinsi') ? 'disabled' : '' }}>
                                <option value="">Semua Kab/Kota</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->kode_lokasi }}" {{ request('kabupaten') == $city->kode_lokasi ? 'selected' : '' }}>
                                        {{ $city->nama_lokasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Kecamatan</label>
                            <select name="kecamatan" id="kecamatan" class="w-full py-3 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all {{ !request('kabupaten') ? 'bg-surface-50 cursor-not-allowed' : '' }}" {{ !request('kabupaten') ? 'disabled' : '' }}>
                                <option value="">Semua Kecamatan</option>
                                @foreach($districts as $dist)
                                    <option value="{{ $dist->kode_lokasi }}" {{ request('kecamatan') == $dist->kode_lokasi ? 'selected' : '' }}>
                                        {{ $dist->nama_lokasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kelurahan -->
                        <div class="lg:col-start-3">
                            <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Kelurahan</label>
                            <select name="kelurahan" id="kelurahan" class="w-full py-3 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all {{ !request('kecamatan') ? 'bg-surface-50 cursor-not-allowed' : '' }}" {{ !request('kecamatan') ? 'disabled' : '' }}>
                                <option value="">Semua Kelurahan</option>
                                @foreach($villages as $vill)
                                    <option value="{{ $vill->kode_lokasi }}" {{ request('kelurahan') == $vill->kode_lokasi ? 'selected' : '' }}>
                                        {{ $vill->nama_lokasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="lg:col-span-2 flex items-end gap-2">
                            <button type="submit" id="btnFilter" class="flex-1 py-3 bg-nu-green-600 text-white rounded-xl font-bold text-sm hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-100">
                                Terapkan Filter
                            </button>
                            <a href="{{ route('land-waqf.catalog') }}" class="px-6 py-3 bg-surface-100 text-surface-600 rounded-xl font-bold text-sm hover:bg-surface-200 transition-all">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Card List Container -->
            <div id="cardListContainer" class="relative">
                @include('public.land-waqf.partials._card_list')
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filterForm');
            const container = document.getElementById('cardListContainer');
            const progressBar = document.getElementById('loadingProgress');
            const btnFilter = document.getElementById('btnFilter');
            const toggleFilter = document.getElementById('toggleFilter');
            const filterContainer = document.getElementById('filterContainer');

            toggleFilter.addEventListener('click', function() {
                filterContainer.classList.toggle('hidden');
                this.innerHTML = filterContainer.classList.contains('hidden') 
                    ? '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg> Tampilkan Filter'
                    : '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg> Sembunyikan Filter';
            });

            function fetchData(url) {
                // Show loading
                progressBar.style.transform = 'translateX(0)';
                container.style.opacity = '0.5';
                btnFilter.disabled = true;

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    container.innerHTML = html;
                    // Reset UI
                    progressBar.style.transform = 'translateX(-100%)';
                    container.style.opacity = '1';
                    btnFilter.disabled = false;
                    
                    // Re-bind pagination links
                    bindPagination();
                    
                    // Scroll to top of cards
                    window.scrollTo({ top: container.offsetTop - 100, behavior: 'smooth' });
                })
                .catch(error => {
                    console.error('Error:', error);
                    progressBar.style.transform = 'translateX(-100%)';
                    container.style.opacity = '1';
                    btnFilter.disabled = false;
                });
            }

            function bindPagination() {
                const links = container.querySelectorAll('.ajax-pagination a');
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        fetchData(this.href);
                    });
                });
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                const params = new URLSearchParams(formData).toString();
                const url = `{{ route('land-waqf.catalog') }}?${params}`;
                fetchData(url);
            });

            // Initial bind
            bindPagination();

            // ── Cascading Dropdowns ──────────────────────────────────────
            const selects = ['provinsi', 'kabupaten', 'kecamatan', 'kelurahan'];
            selects.forEach((id, index) => {
                const element = document.getElementById(id);
                if (!element) return;
                element.addEventListener('change', function() {
                    const value = this.value;
                    for (let i = index + 1; i < selects.length; i++) {
                        const child = document.getElementById(selects[i]);
                        child.innerHTML = `<option value="">Semua ${child.previousElementSibling.innerText.toLowerCase()}</option>`;
                        child.disabled = true;
                        child.classList.add('bg-surface-50', 'cursor-not-allowed');
                    }
                    if (value && index < selects.length - 1) {
                        const nextId = selects[index + 1];
                        const nextElement = document.getElementById(nextId);
                        const nextLength = [2, 5, 8, 13][index + 1];
                        fetch(`{{ route('land-waqf.locations') }}?parent=${value}&length=${nextLength}`)
                            .then(response => response.json())
                            .then(data => {
                                nextElement.disabled = false;
                                nextElement.classList.remove('bg-surface-50', 'cursor-not-allowed');
                                data.forEach(loc => {
                                    const option = document.createElement('option');
                                    option.value = loc.kode_lokasi;
                                    option.textContent = loc.nama_lokasi;
                                    nextElement.appendChild(option);
                                });
                            });
                    }
                });
            });
        });
    </script>

    <style>
        #loadingProgress {
            transition: transform 0.5s ease-in-out;
        }
        #cardListContainer {
            transition: opacity 0.3s ease-in-out;
        }
    </style>
</x-public-layout>
