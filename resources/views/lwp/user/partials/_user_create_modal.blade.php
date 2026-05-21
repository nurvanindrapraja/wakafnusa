<div id="createModel" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50" onclick="closeCreateModal()"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-slate-900">Tambah Pengguna Baru</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5 tracking-wider">Lembaga Wakaf dan Pertanahan NU</p>
                </div>
                <button type="button" onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Form -->
            <form id="createForm" method="POST" onsubmit="submitCreateForm(event)">
                @csrf
                <div class="px-8 py-6 max-h-[65vh] overflow-y-auto space-y-6">
                    <!-- Error Alert -->
                    <div id="createErrorAlert" class="hidden px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl text-xs font-semibold">
                    </div>

                    <!-- Row 1: Nama & Username -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_user" required placeholder="Nama Lengkap Pengguna" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Username <span class="text-red-500">*</span></label>
                            <input type="text" name="username_user" required placeholder="Username untuk Login" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <!-- Row 2: Email & Telp -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Alamat Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email_user" required placeholder="name@example.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Nomor Telepon</label>
                            <input type="text" name="telp_user" placeholder="081xxx" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <!-- Row 3: Password (with Recommendation Generator) -->
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Password <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="text" id="createPasswordInput" name="password_user" required minlength="6" placeholder="Masukkan password baru" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                            </div>
                            <button type="button" onclick="generateCreatePasswordRecommendation()" class="px-4 py-3.5 bg-nu-green-50 hover:bg-nu-green-100 text-nu-green-700 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                                Rekomendasi
                            </button>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium mt-1.5">Password disimpan terenkripsi SHA1.</p>
                    </div>

                    <!-- Row 4: Level Pengguna -->
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Level Pengguna <span class="text-red-500">*</span></label>
                        <select name="level" required class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                            <option value="">Pilih Level</option>
                            @foreach($levels as $lvl)
                                <option value="{{ $lvl->LEVEL }}">{{ $lvl->NAMA_LEVEL }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Row 5: Kartanu & Alamat -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Nomor Kartanu</label>
                            <input type="text" name="no_kartanu" placeholder="Nomor KartaNU" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Alamat Rumah</label>
                            <textarea name="alamat_user" rows="2" placeholder="Alamat lengkap pengguna..." class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all"></textarea>
                        </div>
                    </div>

                    <!-- Row 6: Cascading Wilayah -->
                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 pb-1.5 border-b border-slate-100">Wilayah Penugasan</div>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Provinsi -->
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Provinsi</label>
                                <select id="createProv" name="provinsi" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinces as $prov)
                                        <option value="{{ $prov->kode_lokasi }}">{{ $prov->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Kabupaten -->
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Kabupaten / Kota</label>
                                <select id="createKab" name="kabupaten" disabled class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all disabled:opacity-50">
                                    <option value="">Pilih Kabupaten</option>
                                </select>
                            </div>
                            <!-- Kecamatan -->
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Kecamatan</label>
                                <select id="createKec" name="kecamatan" disabled class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all disabled:opacity-50">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <!-- Kelurahan -->
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Kelurahan / Desa</label>
                                <select id="createKel" name="kelurahan" disabled class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all disabled:opacity-50">
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Row 7: Cabang LWP PCNU -->
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Cabang (LWP PCNU)</label>
                        <select name="id_cabang" id="createCabang" disabled class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all disabled:opacity-50">
                            <option value="">Pilih Cabang (Pilih Provinsi Terlebih Dahulu)</option>
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-3xl">
                    <button type="button" onclick="closeCreateModal()" class="px-6 py-3 bg-white hover:bg-slate-100 text-slate-700 border border-slate-100 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                        Batal
                    </button>
                    <button type="submit" id="createSubmitBtn" class="px-6 py-3 bg-nu-green-600 hover:bg-nu-green-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
