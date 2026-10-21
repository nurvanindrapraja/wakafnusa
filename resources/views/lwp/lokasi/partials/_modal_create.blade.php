<!-- Modal Tambah Lokasi -->
<div id="modalCreateLokasi" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeCreateLokasiModal()"></div>

    <div class="min-h-full flex items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-lg w-full p-8 shadow-2xl border border-slate-100 transform transition-all">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-6 border-b border-slate-100 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-nu-green-50 text-nu-green-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-800">Tambah Data Lokasi</h3>
                        <p class="text-xs font-semibold text-slate-400">Tambahkan wilayah baru ke dalam hierarki tabel wk_lokasi</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateLokasiModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Form -->
            <form id="formCreateLokasi" onsubmit="submitCreateLokasi(event)" class="space-y-5">
                @csrf

                <!-- Pilih Tingkat Wilayah -->
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Tingkat Wilayah <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="create_level" value="provinsi" class="peer sr-only" onchange="onCreateLevelChange()">
                            <div class="p-3 text-center rounded-2xl border border-slate-200 peer-checked:border-nu-green-500 peer-checked:bg-nu-green-50/50 peer-checked:text-nu-green-700 transition-all">
                                <span class="text-xs font-black block">Provinsi</span>
                                <span class="text-[9px] font-bold text-slate-400 block mt-0.5">2 digit</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="create_level" value="kabupaten" class="peer sr-only" onchange="onCreateLevelChange()">
                            <div class="p-3 text-center rounded-2xl border border-slate-200 peer-checked:border-nu-green-500 peer-checked:bg-nu-green-50/50 peer-checked:text-nu-green-700 transition-all">
                                <span class="text-xs font-black block">Kab / Kota</span>
                                <span class="text-[9px] font-bold text-slate-400 block mt-0.5">5 digit</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="create_level" value="kecamatan" class="peer sr-only" onchange="onCreateLevelChange()">
                            <div class="p-3 text-center rounded-2xl border border-slate-200 peer-checked:border-nu-green-500 peer-checked:bg-nu-green-50/50 peer-checked:text-nu-green-700 transition-all">
                                <span class="text-xs font-black block">Kecamatan</span>
                                <span class="text-[9px] font-bold text-slate-400 block mt-0.5">8 digit</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="create_level" value="kelurahan" checked class="peer sr-only" onchange="onCreateLevelChange()">
                            <div class="p-3 text-center rounded-2xl border border-slate-200 peer-checked:border-nu-green-500 peer-checked:bg-nu-green-50/50 peer-checked:text-nu-green-700 transition-all">
                                <span class="text-xs font-black block">Desa / Kel</span>
                                <span class="text-[9px] font-bold text-slate-400 block mt-0.5">13 digit</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Hierarki Parent Cascade (Muncul sesuai tingkat yang dipilih) -->
                <div id="createParentCascade" class="space-y-3">
                    <!-- Dropdown Provinsi Parent -->
                    <div id="wrapperCreateProv">
                        <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1.5">Pilih Provinsi Induk <span class="text-red-500">*</span></label>
                        <select id="createSelectProv" onchange="onCreateProvChange()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p->kode_lokasi }}">{{ $p->kode_lokasi }} - {{ $p->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dropdown Kabupaten Parent -->
                    <div id="wrapperCreateKab" class="hidden">
                        <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1.5">Pilih Kab / Kota Induk <span class="text-red-500">*</span></label>
                        <select id="createSelectKab" onchange="onCreateKabChange()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                            <option value="">-- Pilih Kabupaten / Kota --</option>
                        </select>
                    </div>

                    <!-- Dropdown Kecamatan Parent -->
                    <div id="wrapperCreateKec" class="hidden">
                        <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1.5">Pilih Kecamatan Induk <span class="text-red-500">*</span></label>
                        <select id="createSelectKec" onchange="onCreateKecChange()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>
                </div>

                <!-- Input Kode Suffix & Rekomendasi Kode Otomatis -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-black uppercase text-slate-500 tracking-wider">Kode Wilayah <span class="text-red-500">*</span></label>
                        <button type="button" onclick="autoSuggestCreateCode()" class="text-[11px] font-bold text-nu-green-600 hover:text-nu-green-700 flex items-center gap-1 hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            Saran Kode Otomatis
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <span id="createParentPrefixBadge" class="hidden px-3 py-3 bg-slate-100 text-slate-600 font-mono text-xs font-black rounded-2xl border border-slate-200 shrink-0">
                            35.22.
                        </span>
                        <input type="text" id="createKodeSuffix" oninput="updateCreateFullCodePreview()" required placeholder="Contoh: 2001" class="flex-1 px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-mono font-bold text-slate-800 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                    </div>

                    <!-- Live Full Code Preview Box -->
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Pratinjau Kode Lengkap:</span>
                        <span id="createFullCodePreview" class="text-xs font-black font-mono text-nu-green-700 bg-nu-green-50 px-2.5 py-1 rounded-xl border border-nu-green-200">
                            -
                        </span>
                    </div>
                </div>

                <!-- Input Nama Lokasi -->
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1.5">Nama Lokasi / Wilayah <span class="text-red-500">*</span></label>
                    <input type="text" id="createNamaLokasi" required placeholder="Contoh: KEUDE BAKONGAN" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 uppercase focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                    <span class="text-[10px] font-semibold text-slate-400 block mt-1">Nama wilayah akan otomatis disimpan dalam format huruf kapital.</span>
                </div>

                <!-- Error Container -->
                <div id="createLokasiError" class="hidden p-3.5 bg-red-50 border border-red-200 rounded-2xl text-xs font-bold text-red-600"></div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeCreateLokasiModal()" class="px-5 py-3 rounded-2xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitCreateLokasi" class="px-6 py-3 rounded-2xl bg-nu-green-600 hover:bg-nu-green-700 text-white text-xs font-black transition-colors flex items-center gap-2 shadow-lg shadow-nu-green-600/20">
                        <span id="btnSubmitCreateLokasiText">Simpan Lokasi</span>
                        <svg id="btnSubmitCreateLokasiSpinner" class="w-4 h-4 animate-spin hidden" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
