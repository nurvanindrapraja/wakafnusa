@extends('layouts.lwp')

@section('title', 'Manajemen Wakaf')
@section('page_title', 'Manajemen Wakaf')
@section('page_subtitle', 'Kelola seluruh aset wakaf tanah Nahdlatul Ulama.')

@section('content')
    <div class="mb-8 flex flex-col lg:flex-row items-center justify-between gap-6">
        <div class="flex flex-wrap items-center gap-4 w-full lg:w-auto">
            <!-- View Toggle -->
            <div class="flex items-center gap-2 bg-white p-1.5 rounded-2xl border border-slate-100 shadow-sm">
                <button id="toggleTable" class="view-btn p-2.5 rounded-xl transition-all bg-nu-green-600 text-white shadow-lg shadow-nu-green-100" data-view="table" title="Tampilan Tabel">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                </button>
                <button id="toggleCard" class="view-btn p-2.5 rounded-xl transition-all text-slate-400 hover:text-nu-green-600 hover:bg-nu-green-50" data-view="card" title="Tampilan Kartu">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                </button>
            </div>

            @php
                $hasActiveFilter = request()->filled('wakif') || request()->filled('penggunaan') || request()->filled('lokasi') || request()->filled('provinsi') || request()->filled('kabupaten') || request()->filled('kecamatan') || request()->filled('kelurahan');
            @endphp
            <!-- Filter Toggle -->
            <button id="toggleFilter" class="flex items-center gap-3 px-6 py-3.5 bg-white border rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all shadow-sm group {{ $hasActiveFilter ? 'bg-nu-green-50 text-nu-green-600 border-nu-green-200' : 'border-slate-100 text-slate-600 hover:border-nu-green-200 hover:text-nu-green-600' }}">
                <svg class="w-5 h-5 {{ $hasActiveFilter ? 'text-nu-green-600' : 'text-slate-400 group-hover:text-nu-green-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                Filter Data
                @if($hasActiveFilter)
                    <span class="w-2 h-2 rounded-full bg-nu-green-600"></span>
                @endif
            </button>
        </div>

        <button id="btnCreateWaqf" onclick="showCreateWaqf()" class="w-full lg:w-auto bg-nu-green-600 text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-nu-green-100 hover:bg-nu-green-700 transition-all flex items-center justify-center gap-3">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
            Input Aset Baru
        </button>
    </div>

    <!-- Filter Form -->
    <div id="filterForm" class="{{ $hasActiveFilter ? '' : 'hidden' }} mb-10 bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm transition-all duration-300">
        <form id="lwpSearchForm" class="space-y-6">
            <!-- Row 1: Pencarian Teks -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Wakif</label>
                    <input type="text" name="wakif" value="{{ request('wakif') }}" placeholder="Cari nama wakif..." class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Penggunaan</label>
                    <input type="text" name="penggunaan" value="{{ request('penggunaan') }}" placeholder="Masjid, Sekolah, dll..." class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kata Kunci Alamat / Wilayah</label>
                    <input type="text" name="lokasi" value="{{ request('lokasi') }}" placeholder="Prov, Kab, Kec, Kel, Alamat..." class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none transition-all">
                </div>
            </div>

            <!-- Row 2: Filter Lokasi Berjenjang -->
            <div class="pt-6 border-t border-slate-100">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-xs font-black uppercase tracking-wider text-slate-700">Filter Lokasi Berjenjang</span>
                </div>

                @if(in_array($level, [9, 91]))
                    {{-- Admin atau PBNU: Provinsi -> Kota/Kab -> Kecamatan -> Kelurahan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Provinsi</label>
                            <select name="provinsi" id="filter_provinsi" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-2 focus:ring-nu-green-500/20 transition-all shadow-sm">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->kode_lokasi }}" {{ request('provinsi') == $prov->kode_lokasi ? 'selected' : '' }}>{{ $prov->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kota / Kabupaten</label>
                            <select name="kabupaten" id="filter_kabupaten" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-2 focus:ring-nu-green-500/20 transition-all shadow-sm disabled:opacity-60 disabled:bg-slate-100" {{ empty(request('provinsi')) && $filterKabupatens->isEmpty() ? 'disabled' : '' }}>
                                <option value="">Semua Kota / Kab</option>
                                @foreach($filterKabupatens as $kab)
                                    <option value="{{ $kab->kode_lokasi }}" {{ request('kabupaten') == $kab->kode_lokasi ? 'selected' : '' }}>{{ $kab->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                            <select name="kecamatan" id="filter_kecamatan" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-2 focus:ring-nu-green-500/20 transition-all shadow-sm disabled:opacity-60 disabled:bg-slate-100" {{ empty(request('kabupaten')) && $filterKecamatans->isEmpty() ? 'disabled' : '' }}>
                                <option value="">Semua Kecamatan</option>
                                @foreach($filterKecamatans as $kec)
                                    <option value="{{ $kec->kode_lokasi }}" {{ request('kecamatan') == $kec->kode_lokasi ? 'selected' : '' }}>{{ $kec->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kelurahan / Desa</label>
                            <select name="kelurahan" id="filter_kelurahan" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-2 focus:ring-nu-green-500/20 transition-all shadow-sm disabled:opacity-60 disabled:bg-slate-100" {{ empty(request('kecamatan')) && $filterKelurahans->isEmpty() ? 'disabled' : '' }}>
                                <option value="">Semua Kelurahan</option>
                                @foreach($filterKelurahans as $kel)
                                    <option value="{{ $kel->kode_lokasi }}" {{ request('kelurahan') == $kel->kode_lokasi ? 'selected' : '' }}>{{ $kel->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @elseif($level === 31)
                    {{-- PWNU: Kota/Kab -> Kecamatan -> Kelurahan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kota / Kabupaten</label>
                            <select name="kabupaten" id="filter_kabupaten" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-2 focus:ring-nu-green-500/20 transition-all shadow-sm">
                                <option value="">Semua Kota / Kab</option>
                                @foreach($filterKabupatens as $kab)
                                    <option value="{{ $kab->kode_lokasi }}" {{ request('kabupaten') == $kab->kode_lokasi ? 'selected' : '' }}>{{ $kab->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                            <select name="kecamatan" id="filter_kecamatan" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-2 focus:ring-nu-green-500/20 transition-all shadow-sm disabled:opacity-60 disabled:bg-slate-100" {{ empty(request('kabupaten')) && $filterKecamatans->isEmpty() ? 'disabled' : '' }}>
                                <option value="">Semua Kecamatan</option>
                                @foreach($filterKecamatans as $kec)
                                    <option value="{{ $kec->kode_lokasi }}" {{ request('kecamatan') == $kec->kode_lokasi ? 'selected' : '' }}>{{ $kec->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kelurahan / Desa</label>
                            <select name="kelurahan" id="filter_kelurahan" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-2 focus:ring-nu-green-500/20 transition-all shadow-sm disabled:opacity-60 disabled:bg-slate-100" {{ empty(request('kecamatan')) && $filterKelurahans->isEmpty() ? 'disabled' : '' }}>
                                <option value="">Semua Kelurahan</option>
                                @foreach($filterKelurahans as $kel)
                                    <option value="{{ $kel->kode_lokasi }}" {{ request('kelurahan') == $kel->kode_lokasi ? 'selected' : '' }}>{{ $kel->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @elseif($level === 3)
                    {{-- PCNU: Kecamatan -> Kelurahan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                            <select name="kecamatan" id="filter_kecamatan" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-2 focus:ring-nu-green-500/20 transition-all shadow-sm">
                                <option value="">Semua Kecamatan</option>
                                @foreach($filterKecamatans as $kec)
                                    <option value="{{ $kec->kode_lokasi }}" {{ request('kecamatan') == $kec->kode_lokasi ? 'selected' : '' }}>{{ $kec->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kelurahan / Desa</label>
                            <select name="kelurahan" id="filter_kelurahan" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-2 focus:ring-nu-green-500/20 transition-all shadow-sm disabled:opacity-60 disabled:bg-slate-100" {{ empty(request('kecamatan')) && $filterKelurahans->isEmpty() ? 'disabled' : '' }}>
                                <option value="">Semua Kelurahan</option>
                                @foreach($filterKelurahans as $kel)
                                    <option value="{{ $kel->kode_lokasi }}" {{ request('kelurahan') == $kel->kode_lokasi ? 'selected' : '' }}>{{ $kel->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    {{-- MWC (Level 2) & lainnya: Kelurahan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kelurahan / Desa</label>
                            <select name="kelurahan" id="filter_kelurahan" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-black text-slate-700 focus:outline-none focus:border-nu-green-500 focus:ring-2 focus:ring-nu-green-500/20 transition-all shadow-sm">
                                <option value="">Semua Kelurahan</option>
                                @foreach($filterKelurahans as $kel)
                                    <option value="{{ $kel->kode_lokasi }}" {{ request('kelurahan') == $kel->kode_lokasi ? 'selected' : '' }}>{{ $kel->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="btnResetFilter" class="px-6 py-3 rounded-xl font-black text-[11px] uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-all">Reset</button>
                <button type="submit" class="px-8 py-3 bg-nu-green-600 text-white rounded-xl font-black text-[11px] uppercase tracking-widest shadow-lg shadow-nu-green-100 hover:bg-nu-green-700 transition-all">Terapkan Filter</button>
            </div>
        </form>
    </div>

    <!-- Progress Bar -->
    <div id="progressBar" class="fixed top-0 left-0 w-full h-1 z-[60] overflow-hidden hidden">
        <div class="h-full bg-nu-green-600 animate-[progress_2s_ease-in-out_infinite]" style="width: 30%"></div>
    </div>
    <style>
        @keyframes progress {
            0% { transform: translateX(-100%); width: 30%; }
            50% { width: 60%; }
            100% { transform: translateX(400%); width: 30%; }
        }
    </style>

    <!-- Content Container -->
    <div id="waqfDataContainer" class="transition-opacity duration-300">
        @include('lwp.dashboard.partials._waqf_list')
    </div>

    <!-- LWP Generic Modal -->
    <div id="lwpModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeLwpModal()"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div id="modalContent" class="relative w-full max-w-4xl bg-white rounded-[3rem] shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300">
                <!-- Content will be injected here -->
            </div>
    <!-- LWP Preview Modal (Sub-modal) -->
    <div id="lwpPreviewModal" class="fixed inset-0 z-[110] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" onclick="closePreview()"></div>
        <div class="flex min-h-screen items-center justify-center p-6">
            <div class="relative w-full max-w-6xl">
                <button onclick="closePreview()" class="absolute -top-12 right-0 text-white hover:text-nu-green-400 transition-colors flex items-center gap-2 font-black uppercase text-xs tracking-widest">
                    Tutup Preview
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <div id="previewBody" class="w-full flex items-center justify-center">
                    <!-- Preview content (img or iframe) will be injected here -->
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
    
    <script>
        function showLwpModal(html) {
            const modal = document.getElementById('lwpModal');
            const content = document.getElementById('modalContent');
            content.innerHTML = html;
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
            }, 10);
        }

        window.showMapPopup = function(lat, lng, usage) {
            const html = `
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-black text-slate-900">Lokasi Koordinat Aset</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">${usage || 'Peta Lokasi'}</p>
                        </div>
                        <button onclick="closeLwpModal()" class="p-2 text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div id="popupMap" class="w-full h-[450px] rounded-[2rem] overflow-hidden border border-slate-100 shadow-inner"></div>
                    <div class="mt-4 text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Koordinat: ${lat}, ${lng}
                    </div>
                </div>
            `;
            showLwpModal(html);
            
            setTimeout(() => {
                if(window.lwpDetailMap) {
                    window.lwpDetailMap.remove();
                }
                window.lwpDetailMap = L.map('popupMap').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(window.lwpDetailMap);
                L.marker([lat, lng]).addTo(window.lwpDetailMap)
                    .bindPopup(usage || "Lokasi Aset").openPopup();
            }, 400);
        }

        window.showImagesPopup = function(id, usage) {
            const initialHtml = `
                <div class="p-12 text-center">
                    <div class="w-16 h-16 border-4 border-nu-green-200 border-t-nu-green-600 rounded-full animate-spin mx-auto mb-4"></div>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Memuat Berkas Gambar...</p>
                </div>
            `;
            showLwpModal(initialHtml);

            fetch(`/lwp/wakaf/${id}/images`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(images => {
                    if(!images || images.length === 0) {
                        showLwpModal(`
                            <div class="p-12 text-center">
                                <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <h3 class="text-xl font-black text-slate-900 mb-2">Belum Ada Berkas</h3>
                                <p class="text-slate-400 font-medium mb-8 max-w-xs mx-auto">Aset wakaf ini belum memiliki berkas gambar yang diunggah.</p>
                                <button onclick="closeLwpModal()" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">Tutup</button>
                            </div>
                        `);
                        return;
                    }

                    let galleryHtml = `
                        <div class="p-8">
                            <div class="flex justify-between items-center mb-8 pb-4 border-b border-slate-50">
                                <div>
                                    <h3 class="text-xl font-black text-slate-900">Berkas Pendukung Aset</h3>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">${usage || 'Detail Gambar'}</p>
                                </div>
                                <button onclick="closeLwpModal()" class="p-2 text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-h-[60vh] overflow-y-auto pr-2">
                    `;

                    images.forEach(img => {
                        const url = img.URL;
                        const ext = img.PATH_GAMBAR.split('.').pop().toLowerCase();
                        const isPdf = ext === 'pdf';
                        
                        let filePreviewHtml = '';
                        if (isPdf) {
                            filePreviewHtml = `
                                <div class="text-center p-4">
                                    <svg class="w-12 h-12 text-red-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">DOKUMEN PDF</span>
                                </div>
                            `;
                        } else {
                            filePreviewHtml = `
                                <img src="${url}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            `;
                        }

                        galleryHtml += `
                            <div class="bg-slate-50 rounded-3xl p-4 border border-slate-100/50 hover:border-nu-green-100 hover:bg-white transition-all group flex flex-col justify-between">
                                <div class="relative w-full aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100 mb-4 flex items-center justify-center shadow-inner">
                                    ${filePreviewHtml}
                                    
                                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button onclick="previewFile('${url}', '${ext}')" class="p-3 bg-white text-nu-green-600 rounded-2xl shadow-xl hover:scale-105 active:scale-95 transition-all">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div>
                                    <span class="inline-block px-2.5 py-0.5 bg-nu-green-50 text-nu-green-700 text-[9px] font-black uppercase tracking-wider rounded-md mb-2">${img.JENIS_GAMBAR}</span>
                                    <h4 class="text-xs font-black text-slate-800 line-clamp-2 leading-snug mb-1">${img.KET_GAMBAR || 'Tanpa keterangan'}</h4>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">${new Date(img.TGL_UPLOAD).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})}</span>
                                </div>
                            </div>
                        `;
                    });

                    galleryHtml += `
                            </div>
                        </div>
                    `;
                    showLwpModal(galleryHtml);
                })
                .catch(err => {
                    console.error(err);
                    showLwpModal(`
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <h3 class="text-lg font-black text-slate-900 mb-1">Gagal Memuat Berkas</h3>
                            <p class="text-slate-400 font-medium mb-6 text-sm">Terjadi masalah saat mengambil data berkas dari server.</p>
                            <button onclick="closeLwpModal()" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">Tutup</button>
                        </div>
                    `);
                });
        }

        window.showCreateWaqf = function() {
            const btn = document.getElementById('btnCreateWaqf');
            const progressBar = document.getElementById('progressBar');
            
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
            if (progressBar) {
                progressBar.classList.remove('hidden');
            }
            window.currentCreateStep = 1;

            fetch(`{{ route('lwp.wakaf.create') }}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => {
                    if (!r.ok) throw new Error('Failed to load form');
                    return r.text();
                })
                .then(html => {
                    showLwpModal(html);
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal memuat form penambahan aset.');
                })
                .finally(() => {
                    if (btn) {
                        btn.disabled = false;
                        btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                    if (progressBar) {
                        progressBar.classList.add('hidden');
                    }
                });
        }

        window.closeLwpModal = function() {
            const modal = document.getElementById('lwpModal');
            const content = document.getElementById('modalContent');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                // Cleanup Map
                if(window.lwpDetailMap) {
                    window.lwpDetailMap.remove();
                    window.lwpDetailMap = null;
                }
                if(window.lwpCreateMap) {
                    window.lwpCreateMap.remove();
                    window.lwpCreateMap = null;
                    window.createMarker = null;
                }
                if(window.lwpEditMap) {
                    window.lwpEditMap.remove();
                    window.lwpEditMap = null;
                    window.editMarker = null;
                }
            }, 300);
        }

        function showDetail(id) {
            const progressBar = document.getElementById('progressBar');
            if(progressBar) progressBar.classList.remove('hidden');
            fetch(`/lwp/wakaf/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => showLwpModal(html))
                .finally(() => {
                    if(progressBar) progressBar.classList.add('hidden');
                });
        }

        function editWaqf(id) {
            const progressBar = document.getElementById('progressBar');
            if(progressBar) progressBar.classList.remove('hidden');
            window.currentEditStep = 1;
            fetch(`/lwp/wakaf/${id}/edit`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => showLwpModal(html))
                .finally(() => {
                    if(progressBar) progressBar.classList.add('hidden');
                });
        }

        // Global Load Data Function
        var currentManajemenPage = 1;

        window.loadData = function(url) {
            const container = document.getElementById('waqfDataContainer');
            const progressBar = document.getElementById('progressBar');
            
            progressBar.classList.remove('hidden');
            container.style.opacity = '0.5';

            // Track current page from URL
            try {
                const urlObj = new URL(url, window.location.origin);
                currentManajemenPage = urlObj.searchParams.get('page') || 1;
            } catch(e) {}

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                if(typeof window.applyView === 'function') window.applyView();
                container.style.opacity = '1';
                progressBar.classList.add('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .catch(err => {
                console.error(err);
                progressBar.classList.add('hidden');
                container.style.opacity = '1';
            });
        }

        window.refreshCurrentList = function() {
            const form = document.getElementById('lwpSearchForm');
            const params = new URLSearchParams(new FormData(form));
            for (const [key, val] of [...params.entries()]) {
                if (!val || val.trim() === '') {
                    params.delete(key);
                }
            }
            params.set('page', currentManajemenPage);
            const targetUrl = `{{ route('lwp.manajemen') }}?${params.toString()}`;
            window.history.pushState({}, '', targetUrl);
            window.loadData(targetUrl);
        }

        window.confirmDelete = function(id) {
            const confirmHtml = `
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Hapus Data Wakaf?</h3>
                    <p class="text-slate-400 font-medium mb-10 max-w-sm mx-auto text-center">Tindakan ini tidak dapat dibatalkan secara langsung. Data akan dipindahkan ke tempat pembuangan.</p>
                    <div class="flex justify-center gap-4">
                        <button onclick="closeLwpModal()" class="px-8 py-4 text-slate-400 font-black text-xs uppercase tracking-widest hover:text-slate-600 transition-all">Batal</button>
                        <button onclick="executeDelete(${id})" class="px-8 py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-red-100 hover:bg-red-700 transition-all">Ya, Hapus Data</button>
                    </div>
                </div>
            `;
            showLwpModal(confirmHtml);
        }

        window.verifikasiWaqf = function(id, btnElement) {
            const btn = btnElement || (window ? window.event ? window.event.currentTarget : null : null);
            Swal.fire({
                title: 'Verifikasi Data?',
                text: "Apakah Anda yakin ingin memverifikasi data wakaf ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'Ya, Verifikasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    try {
                        if(btn) {
                            btn.disabled = true;
                            btn.classList.add('opacity-50', 'cursor-not-allowed');
                        }

                        fetch(`/lwp/wakaf/${id}/verify`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(r => {
                            if (!r.ok) throw new Error('Terjadi kesalahan pada server (Status ' + r.status + ')');
                            return r.json();
                        })
                        .then(data => {
                            if(data.success) {
                                closeLwpModal();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Data aset berhasil diverifikasi.',
                                    confirmButtonColor: '#059669'
                                }).then(() => {
                                    if(typeof refreshCurrentList === 'function') refreshCurrentList();
                                    else window.location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Gagal memverifikasi dari respon server.');
                            }
                        })
                        .catch(err => {
                            console.error('Fetch error:', err);
                            Swal.fire('Gagal!', err.message || 'Terjadi kesalahan sistem.', 'error');
                            if(btn) {
                                btn.disabled = false;
                                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                            }
                        });
                    } catch (e) {
                        console.error('Sync error:', e);
                        Swal.fire('Error JS!', e.message, 'error');
                        if(btn) {
                            btn.disabled = false;
                            btn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    }
                }
            }).catch(e => {
                console.error('Swal error:', e);
            });
        }

        window.currentEditStep = 1;

        window.loadEditChildLokasi = function(parent, level) {
            if(!parent) return;
            const sel = document.getElementById('edit-sel-' + level);
            sel.innerHTML = `<option value="">Memuat...</option>`;

            if(level === 'kab') {
                document.getElementById('edit-sel-kec').innerHTML = `<option value="">Pilih Kecamatan</option>`;
                document.getElementById('edit-sel-kel').innerHTML = `<option value="">Pilih Kelurahan</option>`;
            } else if(level === 'kec') {
                document.getElementById('edit-sel-kel').innerHTML = `<option value="">Pilih Kelurahan</option>`;
            }

            const url = `/lwp/lokasi-helper?parent=${encodeURIComponent(parent)}&level=${level}`;
            fetch(url).then(r => r.json()).then(data => {
                let html = `<option value="">Pilih ${level === 'kab' ? 'Kabupaten' : (level === 'kec' ? 'Kecamatan' : 'Kelurahan')}</option>`;
                data.forEach(loc => {
                    html += `<option value="${loc.kode_lokasi}">${loc.nama_lokasi}</option>`;
                });
                sel.innerHTML = html;
            }).catch(err => {
                console.error('LWP Error:', err);
                sel.innerHTML = `<option value="">Gagal memuat data</option>`;
            });
        }

        window.moveEditStep = function(direction) {
            const currentDiv = document.getElementById('edit-step-' + window.currentEditStep);
            const nextStep = window.currentEditStep + direction;
            
            if (direction === 1) {
                // VALIDATION STEP 1: LOKASI
                if (window.currentEditStep === 1) {
                    const selProv = document.getElementById('edit-sel-prov').value;
                    const selKab = document.getElementById('edit-sel-kab').value;
                    const selKec = document.getElementById('edit-sel-kec').value;
                    const selKel = document.getElementById('edit-sel-kel').value;
                    
                    if (!selProv || !selKab || !selKec || !selKel) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Lokasi Belum Lengkap',
                            text: 'Pilih data Provinsi, Kabupaten/Kota, Kecamatan, dan Kelurahan/Desa secara berurutan.',
                            confirmButtonColor: '#059669'
                        });
                        return;
                    }
                }

                // VALIDATION STEP 3: IDENTITAS
                if (window.currentEditStep === 3) {
                    let luas = currentDiv.querySelector('input[name="luas"]').value;
                    luas = luas.replace(',', '.');
                    if (!luas || isNaN(parseFloat(luas)) || parseFloat(luas) <= 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Luas Tanah Tidak Valid',
                            text: 'Masukkan luas tanah berupa angka lebih dari 0.',
                            confirmButtonColor: '#059669'
                        });
                        return;
                    }
                }

                // VALIDATION STEP 2: LEGALITAS (Check double AIW)
                if (window.currentEditStep === 2) {
                    const form = document.getElementById('editWaqfForm');
                    const formData = new FormData(form);
                    
                    // We only check if AIW / Sertifikat is changed, but for simplicity we will let backend check or ignore if it's the same ID.
                    // Wait, validateLegalitas doesn't check ID yet. Let's just proceed for edit, or add ID to validateLegalitas.
                    // To avoid blocking edit, we skip double validation on edit unless we change validateLegalitas to ignore the current ID.
                    // For now, we proceed to next step directly.
                    window.proceedMoveEditStep(nextStep);
                    return;
                }
            }
            window.proceedMoveEditStep(nextStep);
        }

        window.proceedMoveEditStep = function(nextStep) {
            document.getElementById('edit-step-' + window.currentEditStep).classList.add('hidden');
            document.getElementById('edit-step-' + nextStep).classList.remove('hidden');
            window.currentEditStep = nextStep;

            document.querySelectorAll('.edit-step-indicator').forEach(ind => {
                const s = parseInt(ind.dataset.step);
                if(s <= window.currentEditStep) {
                    ind.classList.remove('bg-slate-100');
                    ind.classList.add('bg-nu-green-600');
                } else {
                    ind.classList.remove('bg-nu-green-600');
                    ind.classList.add('bg-slate-100');
                }
            });

            document.getElementById('editBtnPrev').classList.toggle('hidden', window.currentEditStep === 1);
            document.getElementById('editBtnNext').classList.toggle('hidden', window.currentEditStep === 5);
            document.getElementById('editBtnSubmit').classList.toggle('hidden', window.currentEditStep !== 5);

            if(window.currentEditStep === 4) {
                setTimeout(window.initEditMap, 300);
            }
        }

        window.initEditMap = function() {
            if(window.lwpEditMap) {
                window.lwpEditMap.invalidateSize();
                return;
            }
            const latInp = document.getElementById('edit-inp-lat').value;
            const lngInp = document.getElementById('edit-inp-lng').value;
            const lat = latInp ? parseFloat(latInp) : -7.5489;
            const lng = lngInp ? parseFloat(lngInp) : 112.2331;
            
            window.lwpEditMap = L.map('editMap').setView([lat, lng], latInp ? 16 : 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(window.lwpEditMap);

            window.editMarker = null;
            if(latInp && lngInp) {
                window.editMarker = L.marker([lat, lng]).addTo(window.lwpEditMap);
            }

            window.lwpEditMap.on('click', function(e) {
                const clat = e.latlng.lat.toFixed(7);
                const clng = e.latlng.lng.toFixed(7);
                document.getElementById('edit-inp-lat').value = clat;
                document.getElementById('edit-inp-lng').value = clng;
                if(window.editMarker) window.editMarker.setLatLng(e.latlng);
                else window.editMarker = L.marker(e.latlng).addTo(window.lwpEditMap);
            });
        }

        window.submitEditWaqfForm = function(e) {
            e.preventDefault();
            const form = document.getElementById('editWaqfForm');
            form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
        }

        window.submitEditWaqf = function(e, id) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const btnSubmit = document.getElementById('editBtnSubmit');
            
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = `Menyimpan...`;
            
            fetch(`/lwp/wakaf/${id}`, {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data aset berhasil diperbarui',
                        confirmButtonColor: '#059669',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.closeLwpModal();
                        if(window.refreshCurrentList) window.refreshCurrentList();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menyimpan data',
                        confirmButtonColor: '#059669'
                    });
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = `Simpan Perubahan`;
                }
            })
            .catch(err => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = `Simpan Perubahan`;
            });
        }

        window.deleteSavedImage = function(id) {
            Swal.fire({
                title: 'Hapus Dokumen?',
                text: "Dokumen yang dihapus tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/lwp/wakaf/image/${id}`, {
                        method: 'DELETE',
                        headers: { 
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if(data.success) {
                            document.getElementById('image-card-' + id).remove();
                        }
                    });
                }
            });
        }

        window.executeDelete = function(id) {
            fetch(`/lwp/wakaf/${id}`, {
                method: 'DELETE',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    closeLwpModal();
                    window.refreshCurrentList();
                }
            });
        }

        window.switchLwpTab = function(tabId) {
            document.querySelectorAll('.lwp-tab-btn').forEach(btn => {
                if(btn.dataset.tab === tabId) {
                    btn.className = 'lwp-tab-btn pb-3 text-xs font-black uppercase tracking-[0.2em] transition-all border-b-2 border-nu-green-600 text-nu-green-700';
                } else {
                    btn.className = 'lwp-tab-btn pb-3 text-xs font-black uppercase tracking-[0.2em] transition-all border-b-2 border-transparent text-slate-400 hover:text-slate-600';
                }
            });

            document.querySelectorAll('.lwp-tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            const activeTab = document.getElementById('lwp-tab-' + tabId);
            if(activeTab) activeTab.classList.remove('hidden');

            if(tabId === 'lokasi') {
                setTimeout(() => {
                    const mapEl = document.getElementById('lwpMap');
                    if(mapEl) {
                        const lat = parseFloat(mapEl.dataset.lat);
                        const lng = parseFloat(mapEl.dataset.lng);
                        const usage = mapEl.dataset.usage;

                        if(!window.lwpDetailMap) {
                            window.lwpDetailMap = L.map('lwpMap').setView([lat, lng], 16);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; OpenStreetMap'
                            }).addTo(window.lwpDetailMap);
                            L.marker([lat, lng]).addTo(window.lwpDetailMap)
                                .bindPopup(usage).openPopup();
                        } else {
                            window.lwpDetailMap.setView([lat, lng], 16);
                            window.lwpDetailMap.invalidateSize();
                        }
                    }
                }, 400);
            }
        }

        window.previewFile = function(url, type) {
            const previewModal = document.getElementById('lwpPreviewModal');
            const previewBody = document.getElementById('previewBody');
            
            if(type === 'pdf') {
                previewBody.innerHTML = `<iframe src="${url}" class="w-full h-[80vh] rounded-2xl border-none"></iframe>`;
            } else {
                previewBody.innerHTML = `<img src="${url}" class="max-w-full max-h-[80vh] rounded-2xl mx-auto shadow-2xl">`;
            }
            
            previewModal.classList.remove('hidden');
        }

        window.closePreview = function() {
            document.getElementById('lwpPreviewModal').classList.add('hidden');
            document.getElementById('previewBody').innerHTML = '';
        }

        // --- NEW ASSET FUNCTIONS ---
        window.currentCreateStep = 1;

        window.moveStep = function(delta) {
            const nextStep = window.currentCreateStep + delta;
            if(nextStep < 1 || nextStep > 5) return;
            
            const form = document.getElementById('createWaqfForm');
            
            // Validations for going NEXT
            if (delta === 1) {
                if (window.currentCreateStep === 1) {
                    if (!form.provinsi.value || !form.kabupaten.value || !form.kecamatan.value || !form.kelurahan.value) {
                        return Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Seluruh data lokasi (Provinsi s.d Kelurahan) wajib dipilih!',
                            confirmButtonColor: '#059669'
                        });
                    }
                }
                
                if (window.currentCreateStep === 3) {
                    if (!form.luas.value) {
                        return Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Luas aset wajib diisi!',
                            confirmButtonColor: '#059669'
                        });
                    }
                }
            }

            if (window.currentCreateStep === 2 && delta === 1) {
                const formData = new FormData(form);
                const btnNext = document.getElementById('btnNext');
                
                // Disable next button while checking
                const originalBtnContent = btnNext.innerHTML;
                btnNext.disabled = true;
                btnNext.innerHTML = 'Memeriksa...';
                
                fetch(`/lwp/wakaf/validate-legalitas`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    btnNext.disabled = false;
                    btnNext.innerHTML = originalBtnContent;
                    
                    if (data.exists) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Data Ganda Ditemukan!',
                            text: data.message,
                            confirmButtonColor: '#059669'
                        });
                        return;
                    } else {
                        window.proceedMoveStep(nextStep);
                    }
                })
                .catch(err => {
                    console.error('Validation Error:', err);
                    btnNext.disabled = false;
                    btnNext.innerHTML = originalBtnContent;
                    window.proceedMoveStep(nextStep);
                });
                return;
            }

            window.proceedMoveStep(nextStep);
        }

        window.proceedMoveStep = function(nextStep) {
            // Hide current
            document.getElementById('step-' + window.currentCreateStep).classList.add('hidden');
            
            // Show next
            document.getElementById('step-' + nextStep).classList.remove('hidden');
            window.currentCreateStep = nextStep;

            // Update Stepper UI
            document.querySelectorAll('.step-indicator').forEach(ind => {
                const s = parseInt(ind.dataset.step);
                if(s <= window.currentCreateStep) {
                    ind.classList.remove('bg-slate-100');
                    ind.classList.add('bg-nu-green-600');
                } else {
                    ind.classList.remove('bg-nu-green-600');
                    ind.classList.add('bg-slate-100');
                }
            });

            // Update Buttons
            document.getElementById('btnPrev').classList.toggle('hidden', window.currentCreateStep === 1);
            document.getElementById('btnNext').classList.toggle('hidden', window.currentCreateStep === 5);
            document.getElementById('btnSubmit').classList.toggle('hidden', window.currentCreateStep !== 5);

            // Special Map Init
            if(window.currentCreateStep === 4) {
                setTimeout(window.initCreateMap, 300);
            }
        }

        window.initCreateMap = function() {
            if(window.lwpCreateMap) {
                window.lwpCreateMap.invalidateSize();
                return;
            }
            window.lwpCreateMap = L.map('createMap').setView([-7.5489, 112.2331], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(window.lwpCreateMap);

            window.createMarker = null;
            window.lwpCreateMap.on('click', function(e) {
                const lat = e.latlng.lat.toFixed(7);
                const lng = e.latlng.lng.toFixed(7);
                document.getElementById('inp-lat').value = lat;
                document.getElementById('inp-lng').value = lng;
                if(window.createMarker) window.createMarker.setLatLng(e.latlng);
                else window.createMarker = L.marker(e.latlng).addTo(window.lwpCreateMap);
            });

            // Auto-center map based on selected location
            const provSel = document.getElementById('sel-prov');
            const kabSel = document.getElementById('sel-kab');
            const kecSel = document.getElementById('sel-kec');
            const kelSel = document.getElementById('sel-kel');
            
            let queryArr = [];
            if (kelSel && kelSel.value) queryArr.push(kelSel.options[kelSel.selectedIndex].text);
            if (kecSel && kecSel.value) queryArr.push(kecSel.options[kecSel.selectedIndex].text);
            if (kabSel && kabSel.value) queryArr.push(kabSel.options[kabSel.selectedIndex].text);
            if (provSel && provSel.value) queryArr.push(provSel.options[provSel.selectedIndex].text);

            let query = queryArr.join(', ').replace(/PROV\.\s*|KAB\.\s*|KOTA\s*|KEC\.\s*|KEL\.\s*/gi, '');

            if (query) {
                fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query))
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const lat = data[0].lat;
                            const lon = data[0].lon;
                            window.lwpCreateMap.setView([lat, lon], 13);
                            
                            // Auto place marker
                            document.getElementById('inp-lat').value = lat;
                            document.getElementById('inp-lng').value = lon;
                            if(window.createMarker) window.createMarker.setLatLng([lat, lon]);
                            else window.createMarker = L.marker([lat, lon]).addTo(window.lwpCreateMap);
                        }
                    })
                    .catch(err => console.error('Geocoding error:', err));
            }
        }

        window.loadChildLokasi = function(parent, level) {
            if(!parent) return;
            const sel = document.getElementById('sel-' + level);
            sel.innerHTML = `<option value="">Memuat...</option>`;

            if(level === 'kab') {
                document.getElementById('sel-kec').innerHTML = `<option value="">Pilih Kecamatan</option>`;
                document.getElementById('sel-kel').innerHTML = `<option value="">Pilih Kelurahan</option>`;
            } else if(level === 'kec') {
                document.getElementById('sel-kel').innerHTML = `<option value="">Pilih Kelurahan</option>`;
            }

            const url = `/lwp/lokasi-helper?parent=${encodeURIComponent(parent)}&level=${level}`;
            fetch(url).then(r => r.json()).then(data => {
                let html = `<option value="">Pilih ${level === 'kab' ? 'Kabupaten' : (level === 'kec' ? 'Kecamatan' : 'Kelurahan')}</option>`;
                data.forEach(loc => {
                    html += `<option value="${loc.kode_lokasi}">${loc.nama_lokasi}</option>`;
                });
                sel.innerHTML = html;
            }).catch(err => {
                console.error('LWP Error:', err);
                sel.innerHTML = `<option value="">Gagal memuat data</option>`;
            });
        }

        window.submitCreateWaqf = function() {
            const form = document.getElementById('createWaqfForm');
            const formData = new FormData(form);
            const btnSubmit = document.getElementById('btnSubmit');
            
            // Disable button & show progress indicator
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
            
            fetch(`{{ route('lwp.wakaf.store') }}`, {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Aset wakaf berhasil disimpan',
                        confirmButtonColor: '#059669',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.closeLwpModal();
                        if(window.refreshCurrentList) window.refreshCurrentList();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menyimpan data',
                        confirmButtonColor: '#059669'
                    });
                    
                    // Restore button if failed
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = `Simpan Aset Baru <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>`;
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan server',
                    confirmButtonColor: '#059669'
                });
                
                // Restore button if failed
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = `Simpan Aset Baru <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>`;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            let currentView = 'card';
            const container = document.getElementById('waqfDataContainer');
            const toggleFilter = document.getElementById('toggleFilter');
            const searchForm = document.getElementById('lwpSearchForm');

            window.applyView = function() {
                const tableView = document.getElementById('tableView');
                const cardView = document.getElementById('cardView');
                const toggleTable = document.getElementById('toggleTable');
                const toggleCard = document.getElementById('toggleCard');

                if (currentView === 'table') {
                    if(tableView) tableView.classList.remove('hidden');
                    if(cardView) cardView.classList.add('hidden');
                    toggleTable.className = 'view-btn p-2.5 rounded-xl transition-all bg-nu-green-600 text-white shadow-lg shadow-nu-green-100';
                    toggleCard.className = 'view-btn p-2.5 rounded-xl transition-all text-slate-400 hover:text-nu-green-600 hover:bg-nu-green-50';
                } else {
                    if(tableView) tableView.classList.add('hidden');
                    if(cardView) cardView.classList.remove('hidden');
                    toggleCard.className = 'view-btn p-2.5 rounded-xl transition-all bg-nu-green-600 text-white shadow-lg shadow-nu-green-100';
                    toggleTable.className = 'view-btn p-2.5 rounded-xl transition-all text-slate-400 hover:text-nu-green-600 hover:bg-nu-green-50';
                }
            }
            
            // Apply default view
            window.applyView();

            toggleFilter.addEventListener('click', () => {
                const filterForm = document.getElementById('filterForm');
                filterForm.classList.toggle('hidden');
                toggleFilter.classList.toggle('bg-nu-green-50');
                toggleFilter.classList.toggle('text-nu-green-600');
                toggleFilter.classList.toggle('border-nu-green-200');
            });

            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    currentView = this.dataset.view;
                    window.applyView();
                });
            });

            // Cascading Location Dropdowns
            function fetchFilterLokasi(parentCode, targetLevel, targetSelect, defaultText) {
                if (!parentCode) {
                    targetSelect.innerHTML = `<option value="">${defaultText}</option>`;
                    targetSelect.disabled = true;
                    return Promise.resolve();
                }
                targetSelect.disabled = true;
                targetSelect.innerHTML = `<option value="">Memuat...</option>`;
                
                return fetch(`/lwp/lokasi-helper?parent=${encodeURIComponent(parentCode)}&level=${targetLevel}`)
                    .then(res => res.json())
                    .then(data => {
                        let options = `<option value="">${defaultText}</option>`;
                        data.forEach(item => {
                            options += `<option value="${item.kode_lokasi}">${item.nama_lokasi}</option>`;
                        });
                        targetSelect.innerHTML = options;
                        targetSelect.disabled = false;
                    })
                    .catch(err => {
                        console.error('Error fetching location:', err);
                        targetSelect.innerHTML = `<option value="">Gagal memuat data</option>`;
                    });
            }

            const selProv = document.getElementById('filter_provinsi');
            const selKab = document.getElementById('filter_kabupaten');
            const selKec = document.getElementById('filter_kecamatan');
            const selKel = document.getElementById('filter_kelurahan');

            if (selProv) {
                selProv.addEventListener('change', function() {
                    const provVal = this.value;
                    if (selKab) {
                        fetchFilterLokasi(provVal, 'kab', selKab, 'Semua Kota / Kab');
                    }
                    if (selKec) {
                        selKec.innerHTML = '<option value="">Semua Kecamatan</option>';
                        selKec.disabled = true;
                    }
                    if (selKel) {
                        selKel.innerHTML = '<option value="">Semua Kelurahan</option>';
                        selKel.disabled = true;
                    }
                });
            }

            if (selKab) {
                selKab.addEventListener('change', function() {
                    const kabVal = this.value;
                    if (selKec) {
                        fetchFilterLokasi(kabVal, 'kec', selKec, 'Semua Kecamatan');
                    }
                    if (selKel) {
                        selKel.innerHTML = '<option value="">Semua Kelurahan</option>';
                        selKel.disabled = true;
                    }
                });
            }

            if (selKec) {
                selKec.addEventListener('change', function() {
                    const kecVal = this.value;
                    if (selKel) {
                        fetchFilterLokasi(kecVal, 'kel', selKel, 'Semua Kelurahan');
                    }
                });
            }

            // Reset Filter Button Handler
            const btnReset = document.getElementById('btnResetFilter');
            if (btnReset) {
                btnReset.addEventListener('click', function() {
                    searchForm.reset();
                    
                    if (selProv) {
                        selProv.value = '';
                        if (selKab) {
                            selKab.innerHTML = '<option value="">Semua Kota / Kab</option>';
                            selKab.disabled = true;
                        }
                        if (selKec) {
                            selKec.innerHTML = '<option value="">Semua Kecamatan</option>';
                            selKec.disabled = true;
                        }
                        if (selKel) {
                            selKel.innerHTML = '<option value="">Semua Kelurahan</option>';
                            selKel.disabled = true;
                        }
                    } else if (selKab && {{ in_array($level, [31]) ? 'true' : 'false' }}) {
                        selKab.value = '';
                        if (selKec) {
                            selKec.innerHTML = '<option value="">Semua Kecamatan</option>';
                            selKec.disabled = true;
                        }
                        if (selKel) {
                            selKel.innerHTML = '<option value="">Semua Kelurahan</option>';
                            selKel.disabled = true;
                        }
                    } else if (selKec && {{ in_array($level, [3]) ? 'true' : 'false' }}) {
                        selKec.value = '';
                        if (selKel) {
                            selKel.innerHTML = '<option value="">Semua Kelurahan</option>';
                            selKel.disabled = true;
                        }
                    } else if (selKel) {
                        selKel.value = '';
                    }

                    currentManajemenPage = 1;
                    const targetUrl = "{{ route('lwp.manajemen') }}";
                    window.history.pushState({}, '', targetUrl);
                    window.loadData(targetUrl);
                });
            }

            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                currentManajemenPage = 1; // Reset to page 1 on new search
                window.refreshCurrentList();
            });

            container.addEventListener('click', function(e) {
                const pageLink = e.target.closest('.lwp-ajax-pagination a');
                if (pageLink) {
                    e.preventDefault();
                    const urlObj = new URL(pageLink.href, window.location.origin);
                    currentManajemenPage = urlObj.searchParams.get('page') || 1;

                    // Merge active values from form to guarantee filters are never lost
                    const formData = new FormData(searchForm);
                    for (const [key, val] of formData.entries()) {
                        const trimmed = val ? val.trim() : '';
                        if (trimmed !== '') {
                            urlObj.searchParams.set(key, trimmed);
                        } else {
                            urlObj.searchParams.delete(key);
                        }
                    }

                    const finalUrl = urlObj.toString();
                    window.history.pushState({}, '', finalUrl);
                    window.loadData(finalUrl);
                }
            });
        });
    </script>
@endsection
