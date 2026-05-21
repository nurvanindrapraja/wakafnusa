<div id="detailModel" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50" onclick="closeDetailModal()"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-slate-900">Detail Profil Pengguna</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5 tracking-wider font-semibold">Informasi Akun Pengurus LWP</p>
                </div>
                <button type="button" onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="px-8 py-6 max-h-[60vh] overflow-y-auto space-y-6">
                <!-- Avatar & Basic info -->
                <div class="flex items-center gap-4 pb-4 border-b border-slate-50">
                    <div class="w-16 h-16 bg-nu-green-50 text-nu-green-700 font-black rounded-3xl flex items-center justify-center text-xl uppercase" id="detailInitial">
                        U
                    </div>
                    <div>
                        <h4 class="font-black text-slate-800 text-sm leading-snug" id="detailNama">Nama Lengkap</h4>
                        <span class="inline-flex px-2 py-0.5 bg-nu-green-50 text-nu-green-700 text-[10px] font-black rounded-full uppercase tracking-wider mt-1" id="detailLevel">Level</span>
                    </div>
                </div>

                <!-- Fields -->
                <div class="grid grid-cols-1 gap-4 text-xs font-semibold text-slate-600">
                    <div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Username</span>
                        <div class="px-4 py-3 bg-slate-50 rounded-2xl text-slate-800" id="detailUsername">-</div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Email</span>
                            <div class="px-4 py-3 bg-slate-50 rounded-2xl text-slate-800 break-all" id="detailEmail">-</div>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">No. Telepon</span>
                            <div class="px-4 py-3 bg-slate-50 rounded-2xl text-slate-800" id="detailTelp">-</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">LWP Cabang</span>
                            <div class="px-4 py-3 bg-slate-50 rounded-2xl text-slate-800" id="detailCabang">-</div>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">No. Kartanu</span>
                            <div class="px-4 py-3 bg-slate-50 rounded-2xl text-slate-800" id="detailKartanu">-</div>
                        </div>
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Kode & Wilayah Tugas</span>
                        <div class="px-4 py-3 bg-slate-50 rounded-2xl text-slate-800" id="detailWilayah">-</div>
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Alamat Rumah</span>
                        <div class="px-4 py-3 bg-slate-50 rounded-2xl text-slate-800 whitespace-pre-line leading-relaxed" id="detailAlamat">-</div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end rounded-b-3xl">
                <button type="button" onclick="closeDetailModal()" class="px-6 py-3 bg-white hover:bg-slate-100 text-slate-700 border border-slate-100 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
