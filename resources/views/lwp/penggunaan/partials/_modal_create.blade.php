<!-- Modal Tambah Penggunaan Baru -->
<div id="createUsageModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" onclick="closeCreateModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100">
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-gradient-to-r from-nu-green-50/50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-nu-green-600 text-white flex items-center justify-center shadow-md shadow-nu-green-600/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900" id="modal-title">Tambah Master Penggunaan</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tabel Referensi: wk_penggunaan</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Form -->
            <form id="createUsageForm" onsubmit="submitCreateForm(event)">
                @csrf
                <div class="px-8 py-6 max-h-[70vh] overflow-y-auto space-y-5">
                    <!-- Error Alert -->
                    <div id="createErrorAlert" class="hidden px-5 py-3.5 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs font-semibold flex items-center gap-3">
                        <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span id="createErrorMessage"></span>
                    </div>

                    <!-- Pilihan Tipe -->
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Tipe Penggunaan <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2.5 p-3.5 bg-slate-50 hover:bg-slate-100 rounded-2xl border border-slate-100 cursor-pointer transition-all">
                                <input type="radio" name="tipe_penggunaan" value="sub" checked onchange="toggleCategoryType()" class="text-nu-green-600 focus:ring-nu-green-500">
                                <div>
                                    <span class="text-xs font-black text-slate-800 block">Sub-Kategori</span>
                                    <span class="text-[10px] font-bold text-slate-400">Contoh: 11.01 (Masjid)</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-2.5 p-3.5 bg-slate-50 hover:bg-slate-100 rounded-2xl border border-slate-100 cursor-pointer transition-all">
                                <input type="radio" name="tipe_penggunaan" value="parent" onchange="toggleCategoryType()" class="text-nu-green-600 focus:ring-nu-green-500">
                                <div>
                                    <span class="text-xs font-black text-slate-800 block">Kategori Utama</span>
                                    <span class="text-[10px] font-bold text-slate-400">Contoh: 19 (Induk Baru)</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Kategori Induk Dropdown (untuk sub kategori) -->
                    <div id="parentCategoryGroup">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Pilih Kategori Utama</label>
                        <select id="selectParentCategory" onchange="onParentCategoryChange()" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                            <option value="">-- Pilih Kategori Utama --</option>
                            @foreach($parentCategories as $p)
                                <option value="{{ $p->KODE_PENG }}">{{ $p->KODE_PENG }} - {{ $p->PENGGUNAAN }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] font-semibold text-slate-400 mt-1.5">Memilih kategori utama akan membantu menghasilkan awalan kode sub-kategori.</p>
                    </div>

                    <!-- Kode Penggunaan & Kode Lama -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Kode Penggunaan <span class="text-red-500">*</span></label>
                            <input type="text" name="kode_peng" id="createKodePeng" required maxlength="8" placeholder="Misal: 11.04" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold font-mono text-slate-800 uppercase focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all placeholder:normal-case">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Kode Lama (Opsional)</label>
                            <input type="text" name="kode_peng_old" id="createKodePengOld" maxlength="3" placeholder="Misal: 1" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold font-mono text-slate-800 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <!-- Nama Penggunaan -->
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Nama Penggunaan <span class="text-red-500">*</span></label>
                        <input type="text" name="penggunaan" id="createPenggunaan" required maxlength="40" placeholder="Misal: ASRAMA SANTRI" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-800 uppercase focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all placeholder:normal-case">
                    </div>

                    <!-- Pilihan Ikon -->
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Ikon Peta (Opsional)</label>
                        <select name="icon" id="createIconSelect" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                            <option value="">-- Tanpa Ikon / Default --</option>
                            @foreach($availableIcons as $iconFile => $iconDesc)
                                <option value="{{ $iconFile }}">{{ $iconFile }} — {{ $iconDesc }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-8 py-5 border-t border-slate-50 bg-slate-50/50 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()" class="px-5 py-2.5 text-slate-500 hover:text-slate-700 text-xs font-bold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="createSubmitBtn" class="flex items-center gap-2 px-6 py-3 bg-nu-green-600 hover:bg-nu-green-700 text-white rounded-2xl font-black text-xs uppercase tracking-wider transition-all shadow-md shadow-nu-green-600/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
