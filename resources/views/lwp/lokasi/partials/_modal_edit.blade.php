<!-- Modal Edit Lokasi -->
<div id="modalEditLokasi" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeEditLokasiModal()"></div>

    <div class="min-h-full flex items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-lg w-full p-8 shadow-2xl border border-slate-100 transform transition-all">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-6 border-b border-slate-100 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-800">Edit Data Lokasi</h3>
                        <p class="text-xs font-semibold text-slate-400">Perbarui nama atau kode lokasi wilayah</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditLokasiModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Loading Placeholder -->
            <div id="editLokasiLoading" class="py-12 flex flex-col items-center justify-center text-slate-400">
                <svg class="w-8 h-8 animate-spin text-nu-green-600 mb-3" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span class="text-xs font-bold">Memuat data lokasi...</span>
            </div>

            <!-- Form Content -->
            <form id="formEditLokasi" onsubmit="submitEditLokasi(event)" class="space-y-5 hidden">
                @csrf
                <input type="hidden" id="editOriginalKode" value="">

                <!-- Hierarchy & Association Info Box -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Tingkat Wilayah</span>
                        <span id="editLevelBadge" class="px-2.5 py-0.5 rounded-xl text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-700">
                            -
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Wilayah Induk</span>
                        <span id="editParentText" class="font-bold text-slate-700 truncate max-w-[240px]">
                            -
                        </span>
                    </div>
                    <div class="flex items-center justify-between pt-1 border-t border-slate-200/60">
                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Aset Wakaf Terkait</span>
                        <span id="editWakafCount" class="font-bold text-slate-700">0 aset</span>
                    </div>
                </div>

                <!-- Input Kode Lokasi -->
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1.5">Kode Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" id="editKodeLokasi" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-mono font-bold text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                    <span class="text-[10px] font-semibold text-amber-600 block mt-1">
                        ⚠️ Perubahan kode lokasi akan otomatis memperbarui kode pada sub-wilayah di bawahnya serta referensi aset wakaf.
                    </span>
                </div>

                <!-- Input Nama Lokasi -->
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1.5">Nama Lokasi / Wilayah <span class="text-red-500">*</span></label>
                    <input type="text" id="editNamaLokasi" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 uppercase focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                </div>

                <!-- Error Container -->
                <div id="editLokasiError" class="hidden p-3.5 bg-red-50 border border-red-200 rounded-2xl text-xs font-bold text-red-600"></div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeEditLokasiModal()" class="px-5 py-3 rounded-2xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitEditLokasi" class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-black transition-colors flex items-center gap-2 shadow-lg shadow-blue-600/20">
                        <span id="btnSubmitEditLokasiText">Simpan Perubahan</span>
                        <svg id="btnSubmitEditLokasiSpinner" class="w-4 h-4 animate-spin hidden" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
