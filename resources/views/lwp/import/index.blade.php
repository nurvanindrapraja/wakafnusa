@extends('layouts.lwp')

@section('title', 'Import Data Wakaf Massal')
@section('page_title', 'Import Data Wakaf')
@section('page_subtitle', 'Unggah berkas spreadsheet (Excel/CSV), pratinjau data, generate NIA & kode wilayah/penggunaan, lalu import ke tabel wk_wakaf')

@section('content')
<div class="space-y-8">
    <!-- Top Progress / Stepper Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Steps -->
            <div class="flex items-center gap-3 sm:gap-6 w-full sm:w-auto">
                <!-- Step 1 -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-2xl flex items-center justify-center text-xs font-black {{ !$hasData ? 'bg-nu-green-600 text-white shadow-lg shadow-nu-green-600/25 ring-4 ring-nu-green-50' : 'bg-emerald-50 text-emerald-700' }}">
                        1
                    </div>
                    <div>
                        <span class="text-xs font-black text-slate-800 block">Unggah Berkas</span>
                        <span class="text-[10px] font-semibold text-slate-400">Excel / CSV</span>
                    </div>
                </div>

                <div class="w-8 h-0.5 bg-slate-200"></div>

                <!-- Step 2 -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-2xl flex items-center justify-center text-xs font-black {{ $hasData ? 'bg-nu-green-600 text-white shadow-lg shadow-nu-green-600/25 ring-4 ring-nu-green-50' : 'bg-slate-100 text-slate-400' }}">
                        2
                    </div>
                    <div>
                        <span class="text-xs font-black text-slate-800 block">Generate Kode</span>
                        <span class="text-[10px] font-semibold text-slate-400">NIA, Lokasi, Penggunaan</span>
                    </div>
                </div>

                <div class="w-8 h-0.5 bg-slate-200"></div>

                <!-- Step 3 -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-2xl flex items-center justify-center text-xs font-black bg-slate-100 text-slate-400" id="step3Badge">
                        3
                    </div>
                    <div>
                        <span class="text-xs font-black text-slate-800 block">Import ke wk_wakaf</span>
                        <span class="text-[10px] font-semibold text-slate-400">Simpan ke Database</span>
                    </div>
                </div>
            </div>

            <!-- Download Template Button -->
            <a href="{{ route('lwp.import-wakaf.download-template') }}" class="px-5 py-3 rounded-2xl border border-nu-green-200 bg-nu-green-50/50 hover:bg-nu-green-100/60 text-nu-green-700 text-xs font-black transition-all flex items-center gap-2.5 shrink-0">
                <svg class="w-4 h-4 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Unduh Template Excel
            </a>
        </div>
    </div>

    @if(!$hasData)
    <!-- ========================================== -->
    <!-- STATE 1: UPLOAD DROPZONE & INSTRUCTIONS -->
    <!-- ========================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upload Dropzone (2 Cols) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                <div id="uploadProgressBar" class="absolute top-0 left-0 right-0 h-1 bg-nu-green-500 transition-all duration-300 transform scale-x-0 origin-left"></div>

                <h3 class="text-base font-black text-slate-800 mb-2">Unggah Berkas Spreadsheet</h3>
                <p class="text-xs font-semibold text-slate-400 mb-6">Pilih atau seret berkas data wakaf yang telah disusun sesuai kolom template standar.</p>

                <form id="formUploadExcel" onsubmit="submitUploadExcel(event)" enctype="multipart/form-data">
                    @csrf
                    <!-- Dropzone -->
                    <div id="dropzoneArea" class="border-2 border-dashed border-slate-200 hover:border-nu-green-500 rounded-3xl p-10 text-center cursor-pointer transition-all bg-slate-50/50 hover:bg-nu-green-50/20 group">
                        <input type="file" id="excelFileInput" name="file" accept=".xlsx,.xls,.csv" class="hidden" onchange="handleFileSelect(event)">
                        
                        <div class="w-16 h-16 rounded-3xl bg-nu-green-50 text-nu-green-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                        </div>

                        <div id="dropzoneText">
                            <h4 class="text-sm font-black text-slate-800 mb-1">Tarik & Lepaskan Berkas di Sini</h4>
                            <p class="text-xs font-semibold text-slate-400 mb-3">Atau klik untuk memilih berkas dari komputer Anda</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-xl bg-slate-100 text-slate-600 text-[10px] font-bold">
                                Mendukung: .xlsx, .xls, .csv (Maks. 20 MB)
                            </span>
                        </div>

                        <div id="dropzoneSelectedFile" class="hidden pt-2">
                            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-2xl bg-white border border-slate-200 text-xs font-black text-slate-800 shadow-sm">
                                <svg class="w-5 h-5 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <span id="selectedFileName">nama_berkas.xlsx</span>
                                <span id="selectedFileSize" class="text-slate-400 font-semibold">(0 KB)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Error Alert -->
                    <div id="uploadErrorAlert" class="hidden mt-4 p-4 rounded-2xl bg-red-50 border border-red-200 text-xs font-bold text-red-600"></div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="submit" id="btnSubmitUpload" disabled class="px-6 py-3.5 rounded-2xl bg-nu-green-600 hover:bg-nu-green-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs font-black transition-all flex items-center gap-2 shadow-lg shadow-nu-green-600/20">
                            <span id="btnSubmitUploadText">Analisis & Pratinjau Berkas</span>
                            <svg id="btnSubmitUploadSpinner" class="w-4 h-4 animate-spin hidden" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Template Information Card (1 Col) -->
        <div class="space-y-4">
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-slate-800">Format Kolom Template</h4>
                        <p class="text-[10px] font-semibold text-slate-400">16 Kolom Standar SIWAKNU</p>
                    </div>
                </div>

                <div class="text-xs text-slate-600 space-y-2">
                    <p class="text-[11px] leading-relaxed text-slate-500">Pastikan baris pertama Excel Anda memiliki nama kolom berikut:</p>
                    <div class="grid grid-cols-2 gap-1.5 font-mono text-[10px]">
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">1. kel/desa (kelurahan)</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">2. kec (kecamatan)</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">3. kota/kab</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">4. provinsi</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">5. penggunaan</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">6. nama</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">7. alamat</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">8. wakif</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">9. nazhir</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">10. luas</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">11. no aiw</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">12. tgl aiw</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">13. no sert/nib</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">14. tgl sertipikat</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">15. keterangan</span>
                        <span class="p-1.5 bg-slate-50 rounded-lg border border-slate-100 font-bold text-slate-700">16. njop</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100">
                    <p class="text-[10px] font-semibold text-slate-400">
                        💡 Gunakan tombol <strong>Unduh Template Excel</strong> di atas untuk memperoleh format tabel siap isi lengkap dengan contoh data.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- ========================================== -->
    <!-- STATE 2: PREVIEW, GENERATE & IMPORT -->
    <!-- ========================================== -->
    @php
        $isGenerated = $sessionData['is_generated'] ?? false;
        $stats = $sessionData['stats'] ?? ['total' => count($sessionData['rows']), 'ready' => 0, 'incomplete' => 0];
        $suggestedNia = $sessionData['suggested_nia'] ?? '1614000001';
    @endphp

    <!-- Top Action Toolbar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Left Info -->
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-black text-slate-800">{{ $sessionData['filename'] }}</span>
                        <span class="px-2.5 py-0.5 rounded-xl bg-slate-100 text-slate-600 text-[10px] font-bold">
                            {{ count($sessionData['rows']) }} Data Baris
                        </span>
                    </div>
                    <p class="text-xs font-semibold text-slate-400">
                        @if($isGenerated)
                            Status: <span class="text-nu-green-600 font-bold">Kode telah di-generate</span>. Periksa data sebelum menekan tombol import.
                        @else
                            Status: <span class="text-amber-600 font-bold">Pratinjau Awal</span>. Tekan tombol <strong>Generate</strong> untuk memetakan NIA, Lokasi, dan Penggunaan.
                        @endif
                    </p>
                </div>
            </div>

            <!-- Right Controls: Starting NIA & Generate / Cancel Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Starting NIA Input -->
                <div class="flex items-center gap-2 px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-2xl">
                    <label for="inputStartingNia" class="text-[10px] font-black uppercase text-slate-400 tracking-wider whitespace-nowrap">NIA Awal:</label>
                    <input type="text" id="inputStartingNia" value="{{ $sessionData['starting_nia'] ?? $suggestedNia }}" class="w-28 px-2 py-1 bg-white border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none focus:border-nu-green-500">
                </div>

                <!-- Generate Button -->
                <button type="button" onclick="executeGenerate()" id="btnGenerate" class="px-5 py-3 rounded-2xl bg-nu-green-600 hover:bg-nu-green-700 text-white text-xs font-black transition-all flex items-center gap-2 shadow-lg shadow-nu-green-600/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    <span id="btnGenerateText">{{ $isGenerated ? 'Generate Ulang' : '⚡ Generate NIA & Kode' }}</span>
                    <svg id="btnGenerateSpinner" class="w-4 h-4 animate-spin hidden" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>

                <!-- Cancel Button -->
                <button type="button" onclick="confirmCancelImport()" class="px-4 py-3 rounded-2xl border border-red-200 hover:bg-red-50 text-red-600 text-xs font-bold transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    Batal
                </button>
            </div>
        </div>

        <!-- Generated Summary Cards (Shown once generated) -->
        <div id="statsContainer" class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-slate-100 {{ $isGenerated ? '' : 'hidden' }}">
            <!-- Total Data -->
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <span class="text-[9px] font-black uppercase text-slate-400 tracking-wider block">Total Data</span>
                <span id="statTotal" class="text-xl font-black text-slate-800">{{ $stats['total'] }}</span>
            </div>

            <!-- Siap Diimport -->
            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                <span class="text-[9px] font-black uppercase text-emerald-600 tracking-wider block">Siap Diimport (Lengkap)</span>
                <span id="statReady" class="text-xl font-black text-emerald-700">{{ $stats['ready'] }}</span>
            </div>

            <!-- Belum Lengkap -->
            <div class="p-4 bg-red-50 rounded-2xl border border-red-100">
                <span class="text-[9px] font-black uppercase text-red-600 tracking-wider block">Tidak Lengkap (Dilewati)</span>
                <span id="statIncomplete" class="text-xl font-black text-red-700">{{ $stats['incomplete'] }}</span>
            </div>

            <!-- Rentang NIA -->
            <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100">
                <span class="text-[9px] font-black uppercase text-blue-600 tracking-wider block">Rentang NIA</span>
                <span id="statNiaRange" class="text-xs font-mono font-black text-blue-800 truncate block mt-1">
                    {{ $stats['start_nia'] ?? '-' }} s/d {{ $stats['end_nia'] ?? '-' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Final Import Action Bar (Only when generated and ready > 0) -->
    <div id="importActionBar" class="p-6 bg-gradient-to-r from-emerald-800 via-emerald-900 to-emerald-950 rounded-3xl text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4 {{ ($isGenerated && $stats['ready'] > 0) ? '' : 'hidden' }}">
        <div>
            <h4 class="text-base font-black text-white mb-0.5">Data Siap Dimasukkan ke Database</h4>
            <p class="text-xs text-nu-green-100">
                Terdapat <strong id="importReadyCount" class="text-white">{{ $stats['ready'] }}</strong> data yang telah lengkap dengan NIA, Kode Lokasi, dan Kode Penggunaan.
            </p>
        </div>

        <button type="button" onclick="executeProcessImport()" id="btnProcessImport" class="px-8 py-4 rounded-2xl bg-white hover:bg-emerald-50 text-nu-green-700 text-xs font-black transition-all flex items-center gap-2.5 shadow-lg shrink-0">
            <svg class="w-4 h-4 text-nu-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
            <span id="btnProcessImportText">Masukkan ke Tabel wk_wakaf</span>
            <svg id="btnProcessImportSpinner" class="w-4 h-4 animate-spin text-nu-green-700 hidden" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </button>
    </div>

    <!-- Preview Table Container -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h4 class="text-sm font-black text-slate-800">Pratinjau Data Excel</h4>
                <p class="text-[10px] font-semibold text-slate-400">Periksa hasil pembacaan berkas dan hasil generate kode otomatis</p>
            </div>
            <span class="text-xs font-bold text-slate-500">
                Menampilkan seluruh <span class="font-black text-slate-800">{{ count($sessionData['rows']) }}</span> data baris
            </span>
        </div>

        <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="sticky top-0 z-10 bg-slate-50 shadow-sm">
                    <tr class="border-b border-slate-200">
                        <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider text-center w-12">No</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider text-center">Status</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">NIA</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Kode Lokasi</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Kode Penggunaan</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Wilayah (Excel)</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Penggunaan (Excel)</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Nama & Alamat</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Wakif / Nazhir</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Luas (m²)</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">No AIW & Sertipikat</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Keterangan & NJOP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="previewTableBody">
                    @foreach($sessionData['rows'] as $r)
                    <tr class="hover:bg-slate-50/70 transition-colors {{ $r['status'] == 'ready' ? 'bg-emerald-50/20' : ($r['status'] == 'incomplete' ? 'bg-red-50/20' : '') }}">
                        <!-- No -->
                        <td class="px-4 py-3 text-center font-bold text-slate-400">
                            {{ $r['row_id'] }}
                        </td>

                        <!-- Status Badge -->
                        <td class="px-4 py-3 text-center">
                            @if($r['status'] == 'ready')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-xl text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Siap Import
                                </span>
                            @elseif($r['status'] == 'incomplete')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-xl text-[9px] font-black uppercase tracking-wider bg-red-100 text-red-700 border border-red-200" title="{{ implode(', ', $r['errors'] ?? []) }}">
                                    Tidak Lengkap
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-xl text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">
                                    Belum Di-generate
                                </span>
                            @endif
                        </td>

                        <!-- NIA -->
                        <td class="px-4 py-3">
                            @if($r['nia'])
                                <span class="px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-mono font-black text-xs border border-blue-200/60">
                                    {{ $r['nia'] }}
                                </span>
                            @else
                                <span class="text-slate-300 font-mono">-</span>
                            @endif
                        </td>

                        <!-- Kode Lokasi -->
                        <td class="px-4 py-3">
                            @if($r['kode_lokasi'])
                                <div>
                                    <span class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-800 font-mono font-black text-xs border border-emerald-200/60 block w-max">
                                        {{ $r['kode_lokasi'] }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-600 block mt-0.5 truncate max-w-[140px]" title="{{ $r['nama_lokasi'] }}">
                                        {{ $r['nama_lokasi'] }}
                                    </span>
                                </div>
                            @else
                                <span class="text-red-500 text-[10px] font-bold block">
                                    {{ $isGenerated ? '⚠️ Tidak Cocok' : '-' }}
                                </span>
                            @endif
                        </td>

                        <!-- Kode Penggunaan -->
                        <td class="px-4 py-3">
                            @if($r['kode_peng'])
                                <div>
                                    <span class="px-2 py-0.5 rounded-lg bg-amber-50 text-amber-800 font-mono font-black text-xs border border-amber-200/60 block w-max">
                                        {{ $r['kode_peng'] }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-600 block mt-0.5 truncate max-w-[140px]" title="{{ $r['nama_peng'] }}">
                                        {{ $r['nama_peng'] }}
                                    </span>
                                </div>
                            @else
                                <span class="text-red-500 text-[10px] font-bold block">
                                    {{ $isGenerated ? '⚠️ Tidak Cocok' : '-' }}
                                </span>
                            @endif
                        </td>

                        <!-- Wilayah Excel -->
                        <td class="px-4 py-3">
                            <span class="font-black text-slate-800 block truncate max-w-[150px]">
                                {{ $r['desa'] ?: '-' }}
                            </span>
                            <span class="text-[10px] font-semibold text-slate-500 block truncate max-w-[150px]">
                                Kec. {{ $r['kecamatan'] ?: '-' }}, {{ $r['kabupaten'] ?: '-' }}
                            </span>
                        </td>

                        <!-- Penggunaan Excel -->
                        <td class="px-4 py-3 font-semibold text-slate-700">
                            {{ $r['penggunaan'] ?: '-' }}
                        </td>

                        <!-- Nama & Alamat -->
                        <td class="px-4 py-3">
                            <span class="font-bold text-slate-800 block truncate max-w-[150px]">
                                {{ $r['nama'] ?: '-' }}
                            </span>
                            <span class="text-[10px] font-medium text-slate-500 block truncate max-w-[150px]">
                                {{ $r['alamat'] ?: '-' }}
                            </span>
                        </td>

                        <!-- Wakif & Nazhir -->
                        <td class="px-4 py-3">
                            <span class="text-[11px] font-bold text-slate-700 block truncate max-w-[140px]" title="Wakif: {{ $r['wakif'] }}">
                                W: {{ $r['wakif'] ?: '-' }}
                            </span>
                            <span class="text-[10px] font-medium text-slate-500 block truncate max-w-[140px]" title="Nazhir: {{ $r['nazhir'] }}">
                                N: {{ $r['nazhir'] ?: '-' }}
                            </span>
                        </td>

                        <!-- Luas -->
                        <td class="px-4 py-3 text-right font-black font-mono text-slate-800">
                            {{ number_format($r['luas'], 0, ',', '.') }}
                        </td>

                        <!-- Dokumen Legalitas -->
                        <td class="px-4 py-3">
                            <span class="text-[10px] font-mono block truncate max-w-[130px]" title="AIW: {{ $r['no_aiw'] }}">
                                AIW: {{ $r['no_aiw'] ?: '-' }}
                            </span>
                            <span class="text-[10px] font-mono text-slate-500 block truncate max-w-[130px]" title="Sert: {{ $r['no_sert'] }}">
                                Sert: {{ $r['no_sert'] ?: '-' }}
                            </span>
                        </td>

                        <!-- Keterangan & NJOP -->
                        <td class="px-4 py-3">
                            <span class="text-[10px] font-medium text-slate-600 block truncate max-w-[130px]" title="{{ $r['keterangan'] }}">
                                {{ $r['keterangan'] ?: '-' }}
                            </span>
                            @if($r['njop'] > 0)
                                <span class="text-[10px] font-bold text-emerald-700 block">
                                    Rp {{ number_format($r['njop'], 0, ',', '.') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Drag and drop handling
    const dropzone = document.getElementById('dropzoneArea');
    const fileInput = document.getElementById('excelFileInput');

    if (dropzone) {
        dropzone.addEventListener('click', () => fileInput.click());

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-nu-green-500', 'bg-nu-green-50/30');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-nu-green-500', 'bg-nu-green-50/30');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length) {
                fileInput.files = files;
                updateSelectedFileDisplay(files[0]);
            }
        });
    }

    function handleFileSelect(e) {
        if (e.target.files.length) {
            updateSelectedFileDisplay(e.target.files[0]);
        }
    }

    function updateSelectedFileDisplay(file) {
        document.getElementById('dropzoneText').classList.add('hidden');
        document.getElementById('dropzoneSelectedFile').classList.remove('hidden');
        document.getElementById('selectedFileName').textContent = file.name;
        document.getElementById('selectedFileSize').textContent = `(${Math.round(file.size / 1024)} KB)`;
        document.getElementById('btnSubmitUpload').disabled = false;
    }

    function submitUploadExcel(e) {
        e.preventDefault();
        const errDiv = document.getElementById('uploadErrorAlert');
        errDiv.classList.add('hidden');

        const file = fileInput.files[0];
        if (!file) {
            errDiv.textContent = 'Silakan pilih berkas Excel terlebih dahulu.';
            errDiv.classList.remove('hidden');
            return;
        }

        const btn = document.getElementById('btnSubmitUpload');
        const btnText = document.getElementById('btnSubmitUploadText');
        const spinner = document.getElementById('btnSubmitUploadSpinner');
        const bar = document.getElementById('uploadProgressBar');

        btn.disabled = true;
        btnText.textContent = 'Membaca & Memproses Berkas...';
        spinner.classList.remove('hidden');
        bar.style.transform = 'scaleX(0.4)';

        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');

        fetch(`{{ route('lwp.import-wakaf.upload') }}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(r => r.json().then(data => ({ status: r.status, body: data })))
        .then(res => {
            bar.style.transform = 'scaleX(1)';
            if (res.status === 200 && res.body.status === 'success') {
                window.location.reload();
            } else {
                btn.disabled = false;
                btnText.textContent = 'Analisis & Pratinjau Berkas';
                spinner.classList.add('hidden');
                bar.style.transform = 'scaleX(0)';
                errDiv.textContent = res.body.message || 'Gagal membaca berkas Excel.';
                errDiv.classList.remove('hidden');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btnText.textContent = 'Analisis & Pratinjau Berkas';
            spinner.classList.add('hidden');
            bar.style.transform = 'scaleX(0)';
            errDiv.textContent = 'Terjadi kesalahan jaringan atau server saat mengunggah berkas.';
            errDiv.classList.remove('hidden');
        });
    }

    // ─────────────────────────────────────────────────────────────
    // GENERATE NIA & KODE
    // ─────────────────────────────────────────────────────────────
    function executeGenerate() {
        const btn = document.getElementById('btnGenerate');
        const btnText = document.getElementById('btnGenerateText');
        const spinner = document.getElementById('btnGenerateSpinner');
        const startingNia = document.getElementById('inputStartingNia')?.value || '';

        btn.disabled = true;
        btnText.textContent = 'Sedang Meng-generate...';
        spinner.classList.remove('hidden');

        fetch(`{{ route('lwp.import-wakaf.generate') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                starting_nia: startingNia
            })
        })
        .then(r => r.json().then(data => ({ status: r.status, body: data })))
        .then(res => {
            btn.disabled = false;
            btnText.textContent = 'Generate Ulang';
            spinner.classList.add('hidden');

            if (res.status === 200 && res.body.status === 'success') {
                // Update UI without full page reload
                renderPreviewTable(res.body.rows);
                updateStatsUI(res.body.stats);
            } else {
                alert(res.body.message || 'Gagal meng-generate kode.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btnText.textContent = '⚡ Generate NIA & Kode';
            spinner.classList.add('hidden');
            alert('Terjadi kesalahan saat memproses generate.');
        });
    }

    function updateStatsUI(stats) {
        document.getElementById('statsContainer')?.classList.remove('hidden');
        document.getElementById('statTotal').textContent = stats.total;
        document.getElementById('statReady').textContent = stats.ready;
        document.getElementById('statIncomplete').textContent = stats.incomplete;
        document.getElementById('statNiaRange').textContent = `${stats.start_nia || '-'} s/d ${stats.end_nia || '-'}`;

        const importBar = document.getElementById('importActionBar');
        const importReadyCount = document.getElementById('importReadyCount');
        if (stats.ready > 0) {
            importBar?.classList.remove('hidden');
            if (importReadyCount) importReadyCount.textContent = stats.ready;
        } else {
            importBar?.classList.add('hidden');
        }
    }

    function renderPreviewTable(rows) {
        const tbody = document.getElementById('previewTableBody');
        if (!tbody) return;

        let html = '';
        rows.forEach(r => {
            const isReady = r.status === 'ready';
            const isIncomplete = r.status === 'incomplete';
            const trClass = isReady ? 'bg-emerald-50/20' : (isIncomplete ? 'bg-red-50/20' : '');

            let statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-xl text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">Belum Di-generate</span>`;
            if (isReady) {
                statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-xl text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">Siap Import</span>`;
            } else if (isIncomplete) {
                const errText = (r.errors || []).join(', ');
                statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-xl text-[9px] font-black uppercase tracking-wider bg-red-100 text-red-700 border border-red-200" title="${errText}">Tidak Lengkap</span>`;
            }

            const niaBadge = r.nia 
                ? `<span class="px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-mono font-black text-xs border border-blue-200/60">${r.nia}</span>`
                : `<span class="text-slate-300 font-mono">-</span>`;

            let lokasiContent = `<span class="text-red-500 text-[10px] font-bold block">⚠️ Tidak Cocok</span>`;
            if (r.kode_lokasi) {
                lokasiContent = `<div><span class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-800 font-mono font-black text-xs border border-emerald-200/60 block w-max">${r.kode_lokasi}</span><span class="text-[10px] font-bold text-slate-600 block mt-0.5 truncate max-w-[140px]" title="${r.nama_lokasi || ''}">${r.nama_lokasi || ''}</span></div>`;
            }

            let pengContent = `<span class="text-red-500 text-[10px] font-bold block">⚠️ Tidak Cocok</span>`;
            if (r.kode_peng) {
                pengContent = `<div><span class="px-2 py-0.5 rounded-lg bg-amber-50 text-amber-800 font-mono font-black text-xs border border-amber-200/60 block w-max">${r.kode_peng}</span><span class="text-[10px] font-bold text-slate-600 block mt-0.5 truncate max-w-[140px]" title="${r.nama_peng || ''}">${r.nama_peng || ''}</span></div>`;
            }

            html += `
            <tr class="hover:bg-slate-50/70 transition-colors ${trClass}">
                <td class="px-4 py-3 text-center font-bold text-slate-400">${r.row_id}</td>
                <td class="px-4 py-3 text-center">${statusBadge}</td>
                <td class="px-4 py-3">${niaBadge}</td>
                <td class="px-4 py-3">${lokasiContent}</td>
                <td class="px-4 py-3">${pengContent}</td>
                <td class="px-4 py-3">
                    <span class="font-black text-slate-800 block truncate max-w-[150px]">${r.desa || '-'}</span>
                    <span class="text-[10px] font-semibold text-slate-500 block truncate max-w-[150px]">Kec. ${r.kecamatan || '-'}, ${r.kabupaten || '-'}</span>
                </td>
                <td class="px-4 py-3 font-semibold text-slate-700">${r.penggunaan || '-'}</td>
                <td class="px-4 py-3">
                    <span class="font-bold text-slate-800 block truncate max-w-[150px]">${r.nama || '-'}</span>
                    <span class="text-[10px] font-medium text-slate-500 block truncate max-w-[150px]">${r.alamat || '-'}</span>
                </td>
                <td class="px-4 py-3">
                    <span class="text-[11px] font-bold text-slate-700 block truncate max-w-[140px]" title="Wakif: ${r.wakif || '-'}">W: ${r.wakif || '-'}</span>
                    <span class="text-[10px] font-medium text-slate-500 block truncate max-w-[140px]" title="Nazhir: ${r.nazhir || '-'}">N: ${r.nazhir || '-'}</span>
                </td>
                <td class="px-4 py-3 text-right font-black font-mono text-slate-800">${Number(r.luas || 0).toLocaleString('id-ID')}</td>
                <td class="px-4 py-3">
                    <span class="text-[10px] font-mono block truncate max-w-[130px]">AIW: ${r.no_aiw || '-'}</span>
                    <span class="text-[10px] font-mono text-slate-500 block truncate max-w-[130px]">Sert: ${r.no_sert || '-'}</span>
                </td>
                <td class="px-4 py-3">
                    <span class="text-[10px] font-medium text-slate-600 block truncate max-w-[130px]">${r.keterangan || '-'}</span>
                    ${r.njop > 0 ? `<span class="text-[10px] font-bold text-emerald-700 block">Rp ${Number(r.njop).toLocaleString('id-ID')}</span>` : ''}
                </td>
            </tr>`;
        });

        tbody.innerHTML = html;
    }

    // ─────────────────────────────────────────────────────────────
    // PROCESS IMPORT
    // ─────────────────────────────────────────────────────────────
    function executeProcessImport() {
        if (!confirm('Apakah Anda yakin ingin memproses dan memasukkan data yang siap ke tabel wk_wakaf?')) {
            return;
        }

        const btn = document.getElementById('btnProcessImport');
        const btnText = document.getElementById('btnProcessImportText');
        const spinner = document.getElementById('btnProcessImportSpinner');

        btn.disabled = true;
        btnText.textContent = 'Sedang Menyimpan ke wk_wakaf...';
        spinner.classList.remove('hidden');

        fetch(`{{ route('lwp.import-wakaf.process') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json().then(data => ({ status: r.status, body: data })))
        .then(res => {
            btn.disabled = false;
            btnText.textContent = 'Masukkan ke Tabel wk_wakaf';
            spinner.classList.add('hidden');

            if (res.status === 200 && res.body.status === 'success') {
                alert(`Selamat! ${res.body.imported_count} data aset wakaf berhasil diimport.\nRentang NIA: ${res.body.first_nia} s/d ${res.body.last_nia}`);
                window.location.href = res.body.redirect;
            } else {
                alert(res.body.message || 'Gagal memproses import data.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btnText.textContent = 'Masukkan ke Tabel wk_wakaf';
            spinner.classList.add('hidden');
            alert('Terjadi kesalahan saat memproses import.');
        });
    }

    // ─────────────────────────────────────────────────────────────
    // CANCEL IMPORT
    // ─────────────────────────────────────────────────────────────
    function confirmCancelImport() {
        if (!confirm('Apakah Anda yakin ingin membatalkan proses import ini? Seluruh data yang sedang dipratinjau akan dibatalkan.')) {
            return;
        }

        fetch(`{{ route('lwp.import-wakaf.cancel') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json().then(data => ({ status: r.status, body: data })))
        .then(res => {
            if (res.status === 200) {
                window.location.reload();
            } else {
                alert('Gagal membatalkan proses.');
            }
        })
        .catch(() => {
            window.location.reload();
        });
    }
</script>
@endsection
