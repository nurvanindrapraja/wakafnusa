<!-- Modal Edit Penggunaan -->
<div id="editUsageModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-edit-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" onclick="closeEditModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100">
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-gradient-to-r from-blue-50/50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-600/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900" id="modal-edit-title">Ubah Master Penggunaan</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kode Awal: <span id="editOriginCode" class="font-mono text-blue-600 font-extrabold"></span></p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Form -->
            <form id="editUsageForm" onsubmit="submitEditForm(event)">
                @csrf
                <input type="hidden" id="editOriginalKodePeng" value="">

                <div class="px-8 py-6 max-h-[70vh] overflow-y-auto space-y-5">
                    <!-- Error Alert -->
                    <div id="editErrorAlert" class="hidden px-5 py-3.5 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs font-semibold flex items-center gap-3">
                        <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span id="editErrorMessage"></span>
                    </div>

                    <!-- Info Wakaf Terkait -->
                    <div id="editWakafNotice" class="hidden px-4 py-3 bg-blue-50 border border-blue-100 rounded-2xl flex items-center justify-between text-xs font-bold text-blue-800">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Data Tanah Wakaf Terkait:</span>
                        </div>
                        <span id="editWakafCountBadge" class="px-2.5 py-0.5 rounded-full bg-blue-200 text-blue-900 text-xs font-mono font-black">0</span>
                    </div>

                    <!-- Kode Penggunaan & Kode Lama -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Kode Penggunaan <span class="text-red-500">*</span></label>
                            <input type="text" name="kode_peng" id="editKodePeng" required maxlength="8" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold font-mono text-slate-800 uppercase focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Kode Lama (Opsional)</label>
                            <input type="text" name="kode_peng_old" id="editKodePengOld" maxlength="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold font-mono text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <!-- Nama Penggunaan -->
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Nama Penggunaan <span class="text-red-500">*</span></label>
                        <input type="text" name="penggunaan" id="editPenggunaan" required maxlength="40" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-800 uppercase focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                    </div>

                    <!-- Pilihan Ikon -->
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Ikon Peta (Opsional)</label>
                        <select name="icon" id="editIconSelect" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                            <option value="">-- Tanpa Ikon / Default --</option>
                            @foreach($availableIcons as $iconFile => $iconDesc)
                                <option value="{{ $iconFile }}">{{ $iconFile }} — {{ $iconDesc }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-8 py-5 border-t border-slate-50 bg-slate-50/50 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 text-slate-500 hover:text-slate-700 text-xs font-bold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="editSubmitBtn" class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-xs uppercase tracking-wider transition-all shadow-md shadow-blue-600/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
