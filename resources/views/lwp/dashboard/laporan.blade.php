@extends('layouts.lwp')

@section('title', 'Laporan Rekapitulasi Wakaf')
@section('page_title', 'Laporan Rekapitulasi')
@section('page_subtitle', 'Analisis & Distribusi Wilayah Aset Wakaf')

@section('content')
<div class="space-y-8">
    <!-- Top Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Total Aset -->
        <div class="relative bg-white rounded-3xl p-6 border border-slate-100 shadow-sm overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-nu-green-50 rounded-full group-hover:scale-110 transition-transform duration-300"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2 font-black">Total Aset Wilayah</span>
                    <h3 id="statTotalAset" class="text-3xl font-black text-slate-900 leading-none transition-all duration-300">{{ number_format($totalAset, 0, ',', '.') }}</h3>
                    <p class="text-[10px] text-nu-green-600 font-bold uppercase mt-1">Aset Wakaf Terdata</p>
                </div>
                <div class="w-12 h-12 bg-nu-green-100 text-nu-green-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Luas -->
        <div class="relative bg-white rounded-3xl p-6 border border-slate-100 shadow-sm overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-110 transition-transform duration-300"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2 font-black">Total Luas Wilayah</span>
                    <h3 id="statTotalLuas" class="text-3xl font-black text-slate-900 leading-none transition-all duration-300">{{ number_format($totalLuas, 0, ',', '.') }}</h3>
                    <p class="text-[10px] text-amber-600 font-bold uppercase mt-1">Meter Persegi (M²)</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3: Wilayah Terjangkau -->
        <div class="relative bg-white rounded-3xl p-6 border border-slate-100 shadow-sm overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform duration-300"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2 font-black">Wilayah Terdaftar</span>
                    <h3 id="statTotalWilayah" class="text-3xl font-black text-slate-900 leading-none transition-all duration-300">{{ number_format($totalWilayah, 0, ',', '.') }}</h3>
                    <p id="statWilayahLabel" class="text-[10px] text-blue-600 font-bold uppercase mt-1">Masing-masing tingkat Provinsi</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Filter and Content Container -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden relative">
        <!-- Premium Horizontal Progress Bar -->
        <div id="laporanProgressBar" class="absolute top-0 left-0 right-0 h-1 bg-nu-green-500 transition-all duration-300 transform scale-x-0 origin-left z-20"></div>

        <!-- Combobox Berjenjang (Cascading Filters) -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 p-8 bg-slate-50/50 border-b border-slate-50">
            <!-- Provinsi -->
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Provinsi</label>
                <select id="filterProvinsi" class="w-full px-4 py-3 bg-white border border-slate-100 rounded-2xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-1 focus:ring-nu-green-500 transition-all shadow-sm" {{ in_array($currentLevel, [3, 2]) ? 'disabled' : '' }}>
                    @if(in_array($currentLevel, [9, 91]))
                        <option value="">Semua Provinsi</option>
                    @endif
                    @foreach($provinces as $prov)
                        <option value="{{ $prov->kode_lokasi }}" {{ strpos($kodeLokasi, $prov->kode_lokasi) === 0 ? 'selected' : '' }}>{{ $prov->nama_lokasi }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Kabupaten / Kota -->
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Kabupaten / Kota</label>
                <select id="filterKabupaten" class="w-full px-4 py-3 bg-white border border-slate-100 rounded-2xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-1 focus:ring-nu-green-500 transition-all shadow-sm disabled:opacity-60 disabled:bg-slate-100" {{ in_array($currentLevel, [3, 2]) ? 'disabled' : '' }} {{ empty($filterKabupatens) ? 'disabled' : '' }}>
                    @if(in_array($currentLevel, [9, 91, 31]))
                        <option value="">Semua Kabupaten / Kota</option>
                    @endif
                    @foreach($filterKabupatens as $kab)
                        <option value="{{ $kab->kode_lokasi }}" {{ strpos($kodeLokasi, $kab->kode_lokasi) === 0 ? 'selected' : '' }}>{{ $kab->nama_lokasi }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Kecamatan -->
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Kecamatan</label>
                <select id="filterKecamatan" class="w-full px-4 py-3 bg-white border border-slate-100 rounded-2xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-1 focus:ring-nu-green-500 transition-all shadow-sm disabled:opacity-60 disabled:bg-slate-100" {{ $currentLevel === 2 ? 'disabled' : '' }} {{ empty($filterKecamatans) ? 'disabled' : '' }}>
                    @if(in_array($currentLevel, [9, 91, 31, 3]))
                        <option value="">Semua Kecamatan</option>
                    @endif
                    @foreach($filterKecamatans as $kec)
                        <option value="{{ $kec->kode_lokasi }}" {{ strpos($kodeLokasi, $kec->kode_lokasi) === 0 ? 'selected' : '' }}>{{ $kec->nama_lokasi }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Kelurahan -->
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Kelurahan</label>
                <select id="filterKelurahan" class="w-full px-4 py-3 bg-white border border-slate-100 rounded-2xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-1 focus:ring-nu-green-500 transition-all shadow-sm disabled:opacity-60 disabled:bg-slate-100" {{ empty($filterKelurahans) ? 'disabled' : '' }}>
                    <option value="">Semua Kelurahan</option>
                    @foreach($filterKelurahans as $kel)
                        <option value="{{ $kel->kode_lokasi }}" {{ $kodeLokasi == $kel->kode_lokasi ? 'selected' : '' }}>{{ $kel->nama_lokasi }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Actions and Search Bar -->
        <div class="px-8 py-6 border-b border-slate-50 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            <!-- Search -->
            <div class="relative min-w-[280px]">
                <input type="text" id="searchWilayah" placeholder="Cari berdasarkan nama/kode..." class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all placeholder:text-slate-400">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </span>
                <button id="clearSearch" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-0.5 hidden">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Export Buttons -->
            <div class="flex gap-2">
                <a id="exportExcelBtn" href="{{ route('lwp.laporan.export', ['kode_lokasi' => $kodeLokasi, 'search' => $search, 'format' => 'excel']) }}" class="flex items-center justify-center gap-2 px-6 py-3.5 bg-nu-green-50 hover:bg-nu-green-100 text-nu-green-700 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Unduh Excel
                </a>
                <a id="exportPdfBtn" href="{{ route('lwp.laporan.export', ['kode_lokasi' => $kodeLokasi, 'search' => $search, 'format' => 'pdf']) }}" class="flex items-center justify-center gap-2 px-6 py-3.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Unduh PDF
                </a>
            </div>
        </div>

        <!-- Ajax Table Area -->
        <div id="laporanTableContainer">
            @include('lwp.dashboard.partials._laporan_table')
        </div>
    </div>
</div>

<!-- AJAX Location and Cascade JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterProv = document.getElementById('filterProvinsi');
    const filterKab = document.getElementById('filterKabupaten');
    const filterKec = document.getElementById('filterKecamatan');
    const filterKel = document.getElementById('filterKelurahan');
    const searchInput = document.getElementById('searchWilayah');
    const clearSearch = document.getElementById('clearSearch');
    const progressBar = document.getElementById('laporanProgressBar');

    const totalAsetEl = document.getElementById('statTotalAset');
    const totalLuasEl = document.getElementById('statTotalLuas');
    const totalWilEl = document.getElementById('statTotalWilayah');
    const wilLabelEl = document.getElementById('statWilayahLabel');

    const exportExcelBtn = document.getElementById('exportExcelBtn');
    const exportPdfBtn = document.getElementById('exportPdfBtn');

    let searchTimeout;

    // Helper: Progress Bar Loading Controls
    function startLoading() {
        progressBar.classList.remove('transition-none');
        progressBar.style.transform = 'scaleX(0)';
        setTimeout(() => {
            progressBar.style.transform = 'scaleX(0.4)';
        }, 50);
        setTimeout(() => {
            progressBar.style.transform = 'scaleX(0.75)';
        }, 350);
    }

    function stopLoading() {
        progressBar.style.transform = 'scaleX(1)';
        setTimeout(() => {
            progressBar.style.transform = 'scaleX(0)';
        }, 250);
    }

    // Helper: Fetch location children
    function fetchLokasiOptions(parentCode, targetLevel, targetSelectElement, defaultText) {
        if (!parentCode) {
            targetSelectElement.innerHTML = `<option value="">${defaultText}</option>`;
            targetSelectElement.disabled = true;
            return Promise.resolve();
        }

        startLoading();
        return fetch(`/lwp/lokasi-helper?parent=${parentCode}&level=${targetLevel}`)
            .then(res => res.json())
            .then(data => {
                let options = `<option value="">${defaultText}</option>`;
                data.forEach(item => {
                    options += `<option value="${item.kode_lokasi}">${item.nama_lokasi}</option>`;
                });
                targetSelectElement.innerHTML = options;
                targetSelectElement.disabled = false;
                stopLoading();
            })
            .catch(err => {
                console.error(err);
                stopLoading();
            });
    }

    // Get currently selected deepest location code
    function getSelectedKodeLokasi() {
        return filterKel.value || filterKec.value || filterKab.value || filterProv.value || '';
    }

    // Determine current target display level based on kode_lokasi length
    function getLabelForLevel(code) {
        if (!code) return 'Provinsi';
        if (code.length === 2) return 'Kabupaten / Kota';
        if (code.length === 5) return 'Kecamatan';
        return 'Kelurahan';
    }

    // Main: Load paginated reports data via AJAX
    function loadLaporanData(page = 1) {
        const kodeLokasi = getSelectedKodeLokasi();
        const search = searchInput.value;

        startLoading();

        fetch(`/lwp/laporan?kode_lokasi=${kodeLokasi}&search=${search}&page=${page}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                // Swap table
                document.getElementById('laporanTableContainer').innerHTML = res.html;

                // Animate stats
                totalAsetEl.innerText = res.aggregates.totalAset;
                totalLuasEl.innerText = res.aggregates.totalLuas;
                totalWilEl.innerText = res.aggregates.totalWilayah;
                wilLabelEl.innerText = 'Masing-masing tingkat ' + getLabelForLevel(kodeLokasi);

                // Update exports URLs
                exportExcelBtn.href = `/lwp/laporan/export?kode_lokasi=${kodeLokasi}&search=${search}&format=excel`;
                exportPdfBtn.href = `/lwp/laporan/export?kode_lokasi=${kodeLokasi}&search=${search}&format=pdf`;
                
                // Handle cleared search icon
                if (search) {
                    clearSearch.classList.remove('hidden');
                } else {
                    clearSearch.classList.add('hidden');
                }

                // Re-bind pagination clicks
                bindPagination();
            }
            stopLoading();
        })
        .catch(err => {
            console.error(err);
            stopLoading();
        });
    }

    // Bind pagination clicks for AJAX requests
    function bindPagination() {
        const paginationLinks = document.querySelectorAll('.laporan-pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page') || 1;
                loadLaporanData(page);
                
                // Scroll table to top smoothly
                document.getElementById('laporanTableContainer').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        });
    }

    // Dropdowns Change Listeners
    filterProv.addEventListener('change', function() {
        // Reset sub levels
        filterKab.innerHTML = '<option value="">Semua Kabupaten / Kota</option>';
        filterKab.disabled = true;
        filterKec.innerHTML = '<option value="">Semua Kecamatan</option>';
        filterKec.disabled = true;
        filterKel.innerHTML = '<option value="">Semua Kelurahan</option>';
        filterKel.disabled = true;

        fetchLokasiOptions(this.value, 'kab', filterKab, 'Semua Kabupaten / Kota')
            .then(() => {
                loadLaporanData(1);
            });
    });

    filterKab.addEventListener('change', function() {
        // Reset sub levels
        filterKec.innerHTML = '<option value="">Semua Kecamatan</option>';
        filterKec.disabled = true;
        filterKel.innerHTML = '<option value="">Semua Kelurahan</option>';
        filterKel.disabled = true;

        fetchLokasiOptions(this.value, 'kec', filterKec, 'Semua Kecamatan')
            .then(() => {
                loadLaporanData(1);
            });
    });

    filterKec.addEventListener('change', function() {
        // Reset sub levels
        filterKel.innerHTML = '<option value="">Semua Kelurahan</option>';
        filterKel.disabled = true;

        fetchLokasiOptions(this.value, 'kel', filterKel, 'Semua Kelurahan')
            .then(() => {
                loadLaporanData(1);
            });
    });

    filterKel.addEventListener('change', function() {
        loadLaporanData(1);
    });

    // Realtime Search Input debounce
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadLaporanData(1);
        }, 400);
    });

    // Clear Search click
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        loadLaporanData(1);
    });

    // Initial binding
    bindPagination();
});
</script>
@endsection
