@extends('layouts.lwp')

@section('title', 'Master Wilayah & Lokasi')
@section('page_title', 'Master Lokasi')
@section('page_subtitle', 'Kelola hierarki data wilayah nasional: Provinsi, Kabupaten/Kota, Kecamatan, dan Kelurahan/Desa (tabel wk_lokasi)')

@section('content')
<div class="space-y-8">
    <!-- Top Stats Cards (5 Grid) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
        <!-- Card 1: Total Lokasi -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
            <div>
                <span class="text-[9px] font-black uppercase text-slate-400 tracking-wider block">Total Lokasi</span>
                <span class="text-xl font-black text-slate-900 leading-tight">{{ number_format($stats['total'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Card 2: Provinsi -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <span class="text-[9px] font-black uppercase text-slate-400 tracking-wider block">Provinsi</span>
                <span class="text-xl font-black text-slate-900 leading-tight">{{ number_format($stats['provinsi'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Card 3: Kab / Kota -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            </div>
            <div>
                <span class="text-[9px] font-black uppercase text-slate-400 tracking-wider block">Kab / Kota</span>
                <span class="text-xl font-black text-slate-900 leading-tight">{{ number_format($stats['kabupaten'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Card 4: Kecamatan -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>
            </div>
            <div>
                <span class="text-[9px] font-black uppercase text-slate-400 tracking-wider block">Kecamatan</span>
                <span class="text-xl font-black text-slate-900 leading-tight">{{ number_format($stats['kecamatan'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Card 5: Desa / Kelurahan -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            </div>
            <div>
                <span class="text-[9px] font-black uppercase text-slate-400 tracking-wider block">Kelurahan / Desa</span>
                <span class="text-xl font-black text-slate-900 leading-tight">{{ number_format($stats['kelurahan'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden relative">
        <!-- Horizontal Loading Progress Bar -->
        <div id="lokasiProgressBar" class="absolute top-0 left-0 right-0 h-1 bg-nu-green-500 transition-all duration-300 transform scale-x-0 origin-left z-20"></div>

        <!-- Toolbar Filters -->
        <div class="p-4 sm:px-8 sm:py-6 border-b border-slate-50 space-y-4">
            <!-- Top Line: Search & Add Button -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <!-- Search Input -->
                <div class="relative flex-1 max-w-md">
                    <input type="text" id="searchLokasi" value="{{ $search }}" placeholder="Cari nama lokasi atau kode wilayah..." class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all placeholder:text-slate-400">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <button id="clearLokasiSearch" onclick="clearLokasiSearch()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-0.5 {{ $search ? '' : 'hidden' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Right Action Buttons -->
                <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto">
                    <button type="button" onclick="resetAllLokasiFilters()" class="flex-1 sm:flex-none justify-center px-4 py-3 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-2xl text-xs font-bold transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        Reset Filter
                    </button>
                    <button type="button" onclick="openCreateLokasiModal()" class="flex-1 sm:flex-none justify-center px-5 py-3 bg-nu-green-600 hover:bg-nu-green-700 text-white rounded-2xl text-xs font-black transition-all flex items-center gap-2 shadow-lg shadow-nu-green-600/20 shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Tambah Lokasi
                    </button>
                </div>
            </div>

            <!-- Second Line: Cascading Region & Level Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-2">
                <!-- Filter Tingkat -->
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1">Tingkat Wilayah</label>
                    <select id="filterLokasiLevel" onchange="applyLokasiFilters()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                        <option value="">Semua Tingkat</option>
                        <option value="provinsi" {{ $level == 'provinsi' ? 'selected' : '' }}>Provinsi (2 digit)</option>
                        <option value="kabupaten" {{ $level == 'kabupaten' ? 'selected' : '' }}>Kabupaten / Kota (5 digit)</option>
                        <option value="kecamatan" {{ $level == 'kecamatan' ? 'selected' : '' }}>Kecamatan (8 digit)</option>
                        <option value="kelurahan" {{ $level == 'kelurahan' ? 'selected' : '' }}>Desa / Kelurahan (13 digit)</option>
                    </select>
                </div>

                <!-- Filter Provinsi -->
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1">Provinsi</label>
                    <select id="filterLokasiProv" onchange="onFilterProvChange()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                        <option value="">Semua Provinsi</option>
                        @foreach($provinces as $p)
                            <option value="{{ $p->kode_lokasi }}" {{ $filterProv == $p->kode_lokasi ? 'selected' : '' }}>
                                {{ $p->nama_lokasi }} ({{ $p->kode_lokasi }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kabupaten / Kota -->
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1">Kabupaten / Kota</label>
                    <select id="filterLokasiKab" onchange="onFilterKabChange()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all {{ $filterProv ? '' : 'opacity-60' }}" {{ $filterProv ? '' : 'disabled' }}>
                        <option value="">Semua Kab / Kota</option>
                        @foreach($kabupatens as $k)
                            <option value="{{ $k->kode_lokasi }}" {{ $filterKab == $k->kode_lokasi ? 'selected' : '' }}>
                                {{ $k->nama_lokasi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kecamatan -->
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1">Kecamatan</label>
                    <select id="filterLokasiKec" onchange="applyLokasiFilters()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all {{ $filterKab ? '' : 'opacity-60' }}" {{ $filterKab ? '' : 'disabled' }}>
                        <option value="">Semua Kecamatan</option>
                        @foreach($kecamatans as $kc)
                            <option value="{{ $kc->kode_lokasi }}" {{ $filterKec == $kc->kode_lokasi ? 'selected' : '' }}>
                                {{ $kc->nama_lokasi }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div id="lokasiTableContainer">
            @include('lwp.lokasi.partials._table')
        </div>
    </div>
</div>

<!-- Modals -->
@include('lwp.lokasi.partials._modal_create')
@include('lwp.lokasi.partials._modal_edit')

<!-- Toast Notification -->
<div id="lokasiToast" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
    <div class="px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 text-xs font-bold bg-slate-900 text-white" id="lokasiToastInner">
        <span id="lokasiToastMessage">Pesan</span>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let searchTimeout = null;

    // Toast Helper
    function showToast(message, type = 'success') {
        const toast = document.getElementById('lokasiToast');
        const inner = document.getElementById('lokasiToastInner');
        const msg = document.getElementById('lokasiToastMessage');

        msg.textContent = message;
        inner.className = 'px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 text-xs font-black ' + 
            (type === 'success' ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white');

        toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
        }, 3500);
    }

    // Copy to clipboard helper
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast(`Kode ${text} disalin ke clipboard!`, 'success');
        });
    }

    // Loading Bar Controller
    function setProgressBar(progress) {
        const bar = document.getElementById('lokasiProgressBar');
        if (!bar) return;
        bar.style.transform = `scaleX(${progress})`;
        if (progress >= 1) {
            setTimeout(() => { bar.style.transform = 'scaleX(0)'; }, 350);
        }
    }

    // Search input listener (debounced)
    document.getElementById('searchLokasi')?.addEventListener('input', function(e) {
        const clearBtn = document.getElementById('clearLokasiSearch');
        if (e.target.value) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            applyLokasiFilters();
        }, 400);
    });

    function clearLokasiSearch() {
        const input = document.getElementById('searchLokasi');
        input.value = '';
        document.getElementById('clearLokasiSearch').classList.add('hidden');
        applyLokasiFilters();
    }

    function resetAllLokasiFilters() {
        document.getElementById('searchLokasi').value = '';
        document.getElementById('clearLokasiSearch').classList.add('hidden');
        document.getElementById('filterLokasiLevel').value = '';
        document.getElementById('filterLokasiProv').value = '';

        const kabSel = document.getElementById('filterLokasiKab');
        kabSel.innerHTML = '<option value="">Semua Kab / Kota</option>';
        kabSel.disabled = true;
        kabSel.classList.add('opacity-60');

        const kecSel = document.getElementById('filterLokasiKec');
        kecSel.innerHTML = '<option value="">Semua Kecamatan</option>';
        kecSel.disabled = true;
        kecSel.classList.add('opacity-60');

        applyLokasiFilters();
    }

    // Cascading Dropdown: Prov changed
    function onFilterProvChange() {
        const provCode = document.getElementById('filterLokasiProv').value;
        const kabSel = document.getElementById('filterLokasiKab');
        const kecSel = document.getElementById('filterLokasiKec');

        kabSel.innerHTML = '<option value="">Semua Kab / Kota</option>';
        kecSel.innerHTML = '<option value="">Semua Kecamatan</option>';
        kecSel.disabled = true;
        kecSel.classList.add('opacity-60');

        if (!provCode) {
            kabSel.disabled = true;
            kabSel.classList.add('opacity-60');
            applyLokasiFilters();
            return;
        }

        kabSel.disabled = false;
        kabSel.classList.remove('opacity-60');
        kabSel.innerHTML = '<option value="">Memuat...</option>';

        fetch(`/lwp/lokasi-helper?parent=${encodeURIComponent(provCode)}&level=kab`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">Semua Kab / Kota</option>';
                data.forEach(item => {
                    options += `<option value="${item.kode_lokasi}">${item.nama_lokasi}</option>`;
                });
                kabSel.innerHTML = options;
                applyLokasiFilters();
            })
            .catch(() => {
                kabSel.innerHTML = '<option value="">Gagal memuat data</option>';
                applyLokasiFilters();
            });
    }

    // Cascading Dropdown: Kab changed
    function onFilterKabChange() {
        const kabCode = document.getElementById('filterLokasiKab').value;
        const kecSel = document.getElementById('filterLokasiKec');

        kecSel.innerHTML = '<option value="">Semua Kecamatan</option>';

        if (!kabCode) {
            kecSel.disabled = true;
            kecSel.classList.add('opacity-60');
            applyLokasiFilters();
            return;
        }

        kecSel.disabled = false;
        kecSel.classList.remove('opacity-60');
        kecSel.innerHTML = '<option value="">Memuat...</option>';

        fetch(`/lwp/lokasi-helper?parent=${encodeURIComponent(kabCode)}&level=kec`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">Semua Kecamatan</option>';
                data.forEach(item => {
                    options += `<option value="${item.kode_lokasi}">${item.nama_lokasi}</option>`;
                });
                kecSel.innerHTML = options;
                applyLokasiFilters();
            })
            .catch(() => {
                kecSel.innerHTML = '<option value="">Gagal memuat data</option>';
                applyLokasiFilters();
            });
    }

    // Core AJAX Filter Fetcher
    function applyLokasiFilters(page = 1) {
        setProgressBar(0.3);

        const search = document.getElementById('searchLokasi')?.value || '';
        const level = document.getElementById('filterLokasiLevel')?.value || '';
        const prov = document.getElementById('filterLokasiProv')?.value || '';
        const kab = document.getElementById('filterLokasiKab')?.value || '';
        const kec = document.getElementById('filterLokasiKec')?.value || '';

        const params = new URLSearchParams({
            search: search,
            level: level,
            provinsi: prov,
            kabupaten: kab,
            kecamatan: kec,
            page: page
        });

        // Update browser URL without reload
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({ path: newUrl }, '', newUrl);

        fetch(newUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(res => {
            setProgressBar(0.8);
            if (res.status === 'success') {
                document.getElementById('lokasiTableContainer').innerHTML = res.html;
            }
            setProgressBar(1);
        })
        .catch(err => {
            console.error('Master Lokasi Filter Error:', err);
            setProgressBar(1);
            showToast('Gagal memuat data lokasi.', 'error');
        });
    }

    // Intercept pagination clicks
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.lokasi-pagination-wrapper a');
        if (link) {
            e.preventDefault();
            const url = new URL(link.href);
            const page = url.searchParams.get('page') || 1;
            applyLokasiFilters(page);
        }
    });

    // ─────────────────────────────────────────────────────────────
    // CREATE MODAL LOGIC
    // ─────────────────────────────────────────────────────────────
    function openCreateLokasiModal() {
        document.getElementById('modalCreateLokasi').classList.remove('hidden');
        document.getElementById('formCreateLokasi').reset();
        document.getElementById('createLokasiError').classList.add('hidden');
        onCreateLevelChange();
    }

    function closeCreateLokasiModal() {
        document.getElementById('modalCreateLokasi').classList.add('hidden');
    }

    function onCreateLevelChange() {
        const level = document.querySelector('input[name="create_level"]:checked')?.value || 'kelurahan';
        const wrapProv = document.getElementById('wrapperCreateProv');
        const wrapKab = document.getElementById('wrapperCreateKab');
        const wrapKec = document.getElementById('wrapperCreateKec');
        const suffixInput = document.getElementById('createKodeSuffix');

        // Reset fields
        document.getElementById('createSelectProv').value = '';
        document.getElementById('createSelectKab').innerHTML = '<option value="">-- Pilih Kabupaten / Kota --</option>';
        document.getElementById('createSelectKec').innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

        if (level === 'provinsi') {
            wrapProv.classList.add('hidden');
            wrapKab.classList.add('hidden');
            wrapKec.classList.add('hidden');
            suffixInput.placeholder = 'Contoh: 35';
        } else if (level === 'kabupaten') {
            wrapProv.classList.remove('hidden');
            wrapKab.classList.add('hidden');
            wrapKec.classList.add('hidden');
            suffixInput.placeholder = 'Contoh: 01 (2 digit)';
        } else if (level === 'kecamatan') {
            wrapProv.classList.remove('hidden');
            wrapKab.classList.remove('hidden');
            wrapKec.classList.add('hidden');
            suffixInput.placeholder = 'Contoh: 01 (2 digit)';
        } else if (level === 'kelurahan') {
            wrapProv.classList.remove('hidden');
            wrapKab.classList.remove('hidden');
            wrapKec.classList.remove('hidden');
            suffixInput.placeholder = 'Contoh: 2001 (4 digit)';
        }

        updateCreateFullCodePreview();
    }

    function onCreateProvChange() {
        const provCode = document.getElementById('createSelectProv').value;
        const level = document.querySelector('input[name="create_level"]:checked')?.value;
        const kabSel = document.getElementById('createSelectKab');
        const kecSel = document.getElementById('createSelectKec');

        kabSel.innerHTML = '<option value="">-- Pilih Kabupaten / Kota --</option>';
        kecSel.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

        if (!provCode || level === 'provinsi') {
            updateCreateFullCodePreview();
            return;
        }

        if (level === 'kecamatan' || level === 'kelurahan') {
            kabSel.innerHTML = '<option value="">Memuat...</option>';
            fetch(`/lwp/lokasi-helper?parent=${encodeURIComponent(provCode)}&level=kab`)
                .then(r => r.json())
                .then(data => {
                    let html = '<option value="">-- Pilih Kabupaten / Kota --</option>';
                    data.forEach(d => {
                        html += `<option value="${d.kode_lokasi}">${d.kode_lokasi} - ${d.nama_lokasi}</option>`;
                    });
                    kabSel.innerHTML = html;
                });
        }

        updateCreateFullCodePreview();
    }

    function onCreateKabChange() {
        const kabCode = document.getElementById('createSelectKab').value;
        const level = document.querySelector('input[name="create_level"]:checked')?.value;
        const kecSel = document.getElementById('createSelectKec');

        kecSel.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

        if (!kabCode || level !== 'kelurahan') {
            updateCreateFullCodePreview();
            return;
        }

        kecSel.innerHTML = '<option value="">Memuat...</option>';
        fetch(`/lwp/lokasi-helper?parent=${encodeURIComponent(kabCode)}&level=kec`)
            .then(r => r.json())
            .then(data => {
                let html = '<option value="">-- Pilih Kecamatan --</option>';
                data.forEach(d => {
                    html += `<option value="${d.kode_lokasi}">${d.kode_lokasi} - ${d.nama_lokasi}</option>`;
                });
                kecSel.innerHTML = html;
            });

        updateCreateFullCodePreview();
    }

    function onCreateKecChange() {
        updateCreateFullCodePreview();
    }

    function getCreateCurrentParent() {
        const level = document.querySelector('input[name="create_level"]:checked')?.value;
        if (level === 'provinsi') return '';
        if (level === 'kabupaten') return document.getElementById('createSelectProv').value;
        if (level === 'kecamatan') return document.getElementById('createSelectKab').value;
        if (level === 'kelurahan') return document.getElementById('createSelectKec').value;
        return '';
    }

    function updateCreateFullCodePreview() {
        const level = document.querySelector('input[name="create_level"]:checked')?.value;
        const parent = getCreateCurrentParent();
        const suffix = document.getElementById('createKodeSuffix').value.trim();
        const badge = document.getElementById('createParentPrefixBadge');
        const preview = document.getElementById('createFullCodePreview');

        if (level === 'provinsi') {
            badge.classList.add('hidden');
            preview.textContent = suffix || '-';
        } else {
            if (parent) {
                badge.classList.remove('hidden');
                badge.textContent = parent + '.';
                preview.textContent = suffix ? `${parent}.${suffix}` : `${parent}.-`;
            } else {
                badge.classList.add('hidden');
                preview.textContent = suffix ? `(Pilih Induk).${suffix}` : '-';
            }
        }
    }

    function autoSuggestCreateCode() {
        const level = document.querySelector('input[name="create_level"]:checked')?.value;
        const parent = getCreateCurrentParent();

        if (level !== 'provinsi' && !parent) {
            showToast('Silakan pilih wilayah induk terlebih dahulu.', 'error');
            return;
        }

        fetch(`/lwp/master-lokasi/suggest-code?level=${encodeURIComponent(level)}&parent=${encodeURIComponent(parent)}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(res => {
            if (res.status === 'success') {
                document.getElementById('createKodeSuffix').value = res.suffix;
                updateCreateFullCodePreview();
                showToast(`Kode otomatis '${res.full_code}' diterapkan.`, 'success');
            } else {
                showToast(res.message || 'Gagal menghasilkan saran kode.', 'error');
            }
        })
        .catch(() => {
            showToast('Gagal menghubungi server.', 'error');
        });
    }

    function submitCreateLokasi(e) {
        e.preventDefault();
        const errDiv = document.getElementById('createLokasiError');
        errDiv.classList.add('hidden');

        const btn = document.getElementById('btnSubmitCreateLokasi');
        const btnText = document.getElementById('btnSubmitCreateLokasiText');
        const spinner = document.getElementById('btnSubmitCreateLokasiSpinner');

        const level = document.querySelector('input[name="create_level"]:checked')?.value;
        const parent = getCreateCurrentParent();
        const suffix = document.getElementById('createKodeSuffix').value.trim();
        const nama = document.getElementById('createNamaLokasi').value.trim();

        if (level !== 'provinsi' && !parent) {
            errDiv.textContent = 'Wilayah induk wajib dipilih.';
            errDiv.classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        btnText.textContent = 'Menyimpan...';
        spinner.classList.remove('hidden');

        fetch(`{{ route('lwp.lokasi.store') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                level: level,
                parent_kode: parent,
                kode_suffix: suffix,
                nama_lokasi: nama
            })
        })
        .then(r => r.json().then(data => ({ status: r.status, body: data })))
        .then(res => {
            btn.disabled = false;
            btnText.textContent = 'Simpan Lokasi';
            spinner.classList.add('hidden');

            if (res.status === 200 && res.body.status === 'success') {
                closeCreateLokasiModal();
                showToast(res.body.message, 'success');
                applyLokasiFilters();
            } else {
                errDiv.textContent = res.body.message || 'Gagal menyimpan data lokasi.';
                errDiv.classList.remove('hidden');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btnText.textContent = 'Simpan Lokasi';
            spinner.classList.add('hidden');
            errDiv.textContent = 'Terjadi kesalahan sistem.';
            errDiv.classList.remove('hidden');
        });
    }

    // ─────────────────────────────────────────────────────────────
    // EDIT MODAL LOGIC
    // ─────────────────────────────────────────────────────────────
    function openEditLokasiModal(kodeLokasi) {
        const modal = document.getElementById('modalEditLokasi');
        modal.classList.remove('hidden');

        document.getElementById('editLokasiLoading').classList.remove('hidden');
        document.getElementById('formEditLokasi').classList.add('hidden');
        document.getElementById('editLokasiError').classList.add('hidden');

        fetch(`/lwp/master-lokasi/${encodeURIComponent(kodeLokasi)}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(res => {
            if (res.status === 'success') {
                const data = res.data;
                document.getElementById('editOriginalKode').value = data.kode_lokasi;
                document.getElementById('editKodeLokasi').value = data.kode_lokasi;
                document.getElementById('editNamaLokasi').value = data.nama_lokasi;
                document.getElementById('editLevelBadge').textContent = data.level_label;

                const parentParts = [];
                if (data.parents.provinsi) parentParts.push(data.parents.provinsi);
                if (data.parents.kabupaten) parentParts.push(data.parents.kabupaten);
                if (data.parents.kecamatan) parentParts.push(data.parents.kecamatan);
                document.getElementById('editParentText').textContent = parentParts.length > 0 ? parentParts.join(' › ') : '(Provinsi / Teratas)';

                document.getElementById('editWakafCount').textContent = `${data.wakaf_count} aset`;

                document.getElementById('editLokasiLoading').classList.add('hidden');
                document.getElementById('formEditLokasi').classList.remove('hidden');
            } else {
                closeEditLokasiModal();
                showToast(res.message || 'Gagal mengambil data lokasi.', 'error');
            }
        })
        .catch(() => {
            closeEditLokasiModal();
            showToast('Gagal terhubung dengan server.', 'error');
        });
    }

    function closeEditLokasiModal() {
        document.getElementById('modalEditLokasi').classList.add('hidden');
    }

    function submitEditLokasi(e) {
        e.preventDefault();
        const errDiv = document.getElementById('editLokasiError');
        errDiv.classList.add('hidden');

        const originalKode = document.getElementById('editOriginalKode').value;
        const newKode = document.getElementById('editKodeLokasi').value.trim();
        const nama = document.getElementById('editNamaLokasi').value.trim();

        const btn = document.getElementById('btnSubmitEditLokasi');
        const btnText = document.getElementById('btnSubmitEditLokasiText');
        const spinner = document.getElementById('btnSubmitEditLokasiSpinner');

        btn.disabled = true;
        btnText.textContent = 'Menyimpan...';
        spinner.classList.remove('hidden');

        fetch(`/lwp/master-lokasi/${encodeURIComponent(originalKode)}/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nama_lokasi: nama,
                new_kode_lokasi: newKode
            })
        })
        .then(r => r.json().then(data => ({ status: r.status, body: data })))
        .then(res => {
            btn.disabled = false;
            btnText.textContent = 'Simpan Perubahan';
            spinner.classList.add('hidden');

            if (res.status === 200 && res.body.status === 'success') {
                closeEditLokasiModal();
                showToast(res.body.message, 'success');
                applyLokasiFilters();
            } else {
                errDiv.textContent = res.body.message || 'Gagal memperbarui data lokasi.';
                errDiv.classList.remove('hidden');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btnText.textContent = 'Simpan Perubahan';
            spinner.classList.add('hidden');
            errDiv.textContent = 'Terjadi kesalahan sistem.';
            errDiv.classList.remove('hidden');
        });
    }

    // ─────────────────────────────────────────────────────────────
    // DELETE ACTION WITH VERIFICATION
    // ─────────────────────────────────────────────────────────────
    function confirmDeleteLokasi(kodeLokasi, namaLokasi, assetCount) {
        let warningText = `Apakah Anda yakin ingin menghapus data wilayah '${namaLokasi}' (${kodeLokasi})?`;
        if (assetCount > 0) {
            warningText += `\n\nPERINGATAN: Wilayah ini tercatat memiliki ${assetCount} aset wakaf terkait.`;
        }

        if (!confirm(warningText)) {
            return;
        }

        setProgressBar(0.4);

        fetch(`/lwp/master-lokasi/${encodeURIComponent(kodeLokasi)}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json().then(data => ({ status: r.status, body: data })))
        .then(res => {
            setProgressBar(1);
            if (res.status === 200 && res.body.status === 'success') {
                showToast(res.body.message, 'success');
                applyLokasiFilters();
            } else {
                alert(res.body.message || 'Gagal menghapus lokasi.');
            }
        })
        .catch(err => {
            setProgressBar(1);
            alert('Terjadi kesalahan saat menghapus data.');
        });
    }
</script>
@endsection
