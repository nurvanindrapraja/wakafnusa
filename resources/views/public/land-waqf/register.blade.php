<x-public-layout>
    @section('title', 'Pendaftaran Aset Wakaf')

    <div class="py-12 pt-36 bg-surface-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-black text-surface-900 tracking-tight uppercase">Pendaftaran Aset Anda</h1>
                <p class="text-surface-500 mt-2 font-semibold uppercase tracking-wider text-xs">
                    Daftarkan aset Anda untuk menjadi wakaf atas nama <span class="text-nu-green-700">Nazhir Nahdlatul Ulama</span>
                </p>
            </div>

            <!-- Card Container -->
            <div class="bg-white rounded-[2.5rem] shadow-card overflow-hidden border border-surface-100 relative">
                <!-- Top Progress Bar -->
                <div id="wizardProgressBar" class="absolute top-0 left-0 right-0 h-1.5 bg-nu-green-600 transition-all duration-500 transform scale-x-20 origin-left z-50"></div>

                <!-- Progress Steps Indicator -->
                <div class="px-8 pt-8 border-b border-surface-50">
                    <div class="flex items-center justify-between text-center relative max-w-lg mx-auto mb-6">
                        <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-surface-100 -translate-y-1/2 z-0"></div>
                        
                        <!-- Step 1 -->
                        <div class="step-indicator z-10 flex flex-col items-center" data-step="1">
                            <span class="w-8 h-8 rounded-full bg-nu-green-600 text-white font-bold text-sm flex items-center justify-center border-4 border-white shadow transition-all duration-300">1</span>
                            <span class="text-[9px] font-black uppercase text-nu-green-700 tracking-wider mt-2">Lokasi</span>
                        </div>
                        <!-- Step 2 -->
                        <div class="step-indicator z-10 flex flex-col items-center" data-step="2">
                            <span class="w-8 h-8 rounded-full bg-surface-100 text-surface-400 font-bold text-sm flex items-center justify-center border-4 border-white shadow transition-all duration-300">2</span>
                            <span class="text-[9px] font-black uppercase text-surface-400 tracking-wider mt-2">Identitas</span>
                        </div>
                        <!-- Step 3 -->
                        <div class="step-indicator z-10 flex flex-col items-center" data-step="3">
                            <span class="w-8 h-8 rounded-full bg-surface-100 text-surface-400 font-bold text-sm flex items-center justify-center border-4 border-white shadow transition-all duration-300">3</span>
                            <span class="text-[9px] font-black uppercase text-surface-400 tracking-wider mt-2">Ikrar</span>
                        </div>
                        <!-- Step 4 -->
                        <div class="step-indicator z-10 flex flex-col items-center" data-step="4">
                            <span class="w-8 h-8 rounded-full bg-surface-100 text-surface-400 font-bold text-sm flex items-center justify-center border-4 border-white shadow transition-all duration-300">4</span>
                            <span class="text-[9px] font-black uppercase text-surface-400 tracking-wider mt-2">Sertifikat</span>
                        </div>
                        <!-- Step 5 -->
                        <div class="step-indicator z-10 flex flex-col items-center" data-step="5">
                            <span class="w-8 h-8 rounded-full bg-surface-100 text-surface-400 font-bold text-sm flex items-center justify-center border-4 border-white shadow transition-all duration-300">5</span>
                            <span class="text-[9px] font-black uppercase text-surface-400 tracking-wider mt-2">Tindakan</span>
                        </div>
                    </div>
                </div>

                <!-- Form Content Area -->
                <form id="regForm" method="POST" action="{{ route('land-waqf.register.store') }}" class="p-8 md:p-12">
                    @csrf

                    <!-- ── STEP 1: LOKASI ASET ── -->
                    <div class="tab transition-all duration-300" data-step="1">
                        <h3 class="text-lg font-black text-surface-900 mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-nu-green-50 text-nu-green-600 flex items-center justify-center text-xs font-black">1</span>
                            Lokasi Aset Wakaf
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Provinsi -->
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Provinsi</label>
                                <select name="provinsi_id" id="provinsi" class="w-full py-3 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinces as $prov)
                                        <option value="{{ $prov->kode_lokasi }}">{{ $prov->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="provinsi" id="provinsi_name">
                            </div>
                            <!-- Kota/Kabupaten -->
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Kota / Kabupaten</label>
                                <select name="kabupaten_id" id="kabupaten" class="w-full py-3 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm bg-surface-50 cursor-not-allowed transition-all" disabled required>
                                    <option value="">Pilih Kota / Kabupaten</option>
                                </select>
                                <input type="hidden" name="kabupaten" id="kabupaten_name">
                            </div>
                            <!-- Kecamatan -->
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Kecamatan</label>
                                <select name="kecamatan_id" id="kecamatan" class="w-full py-3 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm bg-surface-50 cursor-not-allowed transition-all" disabled required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <input type="hidden" name="kecamatan" id="kecamatan_name">
                            </div>
                            <!-- Kelurahan -->
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Kelurahan</label>
                                <select name="kelurahan_id" id="kelurahan" class="w-full py-3 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm bg-surface-50 cursor-not-allowed transition-all" disabled required>
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                                <input type="hidden" name="kelurahan" id="kelurahan_name">
                            </div>
                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Alamat Lengkap Aset</label>
                                <textarea name="alamat" rows="3" class="w-full py-3 px-4 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all" placeholder="Masukkan jalan, RT/RW, nomor, dan detail lokasi..." required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- ── STEP 2: IDENTITAS ASET ── -->
                    <div class="tab hidden transition-all duration-300" data-step="2">
                        <h3 class="text-lg font-black text-surface-900 mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-nu-green-50 text-nu-green-600 flex items-center justify-center text-xs font-black">2</span>
                            Identitas Aset Tanah
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Nama Aset -->
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Nama Aset (jika ada)</label>
                                <input type="text" name="nama_aset" class="w-full py-3 px-4 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all" placeholder="Contoh: Masjid Al-Falah, Madrasah NU">
                            </div>
                            <!-- Luas -->
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Luas Tanah (m²)</label>
                                <input type="number" name="luas" min="1" step="any" class="w-full py-3 px-4 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all" placeholder="Contoh: 150" required>
                            </div>
                            <!-- Nama Wakif -->
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Nama Wakif</label>
                                <input type="text" name="nama_wakif" class="w-full py-3 px-4 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all" placeholder="Contoh: H. Ahmad Fauzi" required>
                            </div>
                            <!-- Penggunaan -->
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Jenis Penggunaan</label>
                                <select name="penggunaan" class="w-full py-3 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all" required>
                                    <option value="">Pilih Penggunaan</option>
                                    @foreach($usages as $use)
                                        <option value="{{ $use->PENGGUNAAN }}">{{ $use->PENGGUNAAN }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ── STEP 3: IKRAR WAKAF ── -->
                    <div class="tab hidden transition-all duration-300" data-step="3">
                        <h3 class="text-lg font-black text-surface-900 mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-nu-green-50 text-nu-green-600 flex items-center justify-center text-xs font-black">3</span>
                            Status Ikrar Wakaf
                        </h3>
                        
                        <div class="mb-6">
                            <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-3">Apakah sudah ikrar wakaf?</label>
                            <div class="flex gap-4">
                                <label class="flex-1 flex items-center justify-between p-4 rounded-2xl border-2 border-surface-100 hover:border-nu-green-200 cursor-pointer transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-nu-green-50 text-nu-green-600 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <div class="text-left">
                                            <div class="text-sm font-bold text-surface-900">Sudah</div>
                                            <div class="text-[10px] text-surface-400 font-medium">Telah memiliki Akta Ikrar Wakaf (AIW)</div>
                                        </div>
                                    </div>
                                    <input type="radio" name="sudah_ikrar" value="1" class="text-nu-green-600 focus:ring-nu-green-500">
                                </label>
                                
                                <label class="flex-1 flex items-center justify-between p-4 rounded-2xl border-2 border-surface-100 hover:border-nu-green-200 cursor-pointer transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </div>
                                        <div class="text-left">
                                            <div class="text-sm font-bold text-surface-900">Belum</div>
                                            <div class="text-[10px] text-surface-400 font-medium">Belum melakukan ikrar wakaf resmi</div>
                                        </div>
                                    </div>
                                    <input type="radio" name="sudah_ikrar" value="0" checked class="text-nu-green-600 focus:ring-nu-green-500">
                                </label>
                            </div>
                        </div>

                        <!-- Ikrar Fields (Show dynamically) -->
                        <div id="ikrarFields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6 p-6 rounded-3xl bg-surface-50 border border-surface-100 mb-6 animate-fade-in">
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Nomor Akta Ikrar Wakaf (AIW)</label>
                                <input type="text" name="nomor_ikrar" id="nomor_ikrar" class="w-full py-3 px-4 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm bg-white transition-all" placeholder="Contoh: W.2/015/05/2018">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Tanggal Ikrar Wakaf</label>
                                <input type="date" name="tanggal_ikrar" id="tanggal_ikrar" class="w-full py-3 px-4 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm bg-white transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Nama Nazhir Tercantum</label>
                                <input type="text" name="nama_nazhir" id="nama_nazhir" class="w-full py-3 px-4 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm bg-white transition-all" placeholder="Contoh: Pengurus Cabang Nahdlatul Ulama">
                            </div>
                        </div>
                    </div>

                    <!-- ── STEP 4: SERTIFIKASI ── -->
                    <div class="tab hidden transition-all duration-300" data-step="4">
                        <h3 class="text-lg font-black text-surface-900 mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-nu-green-50 text-nu-green-600 flex items-center justify-center text-xs font-black">4</span>
                            Status Sertifikasi Aset
                        </h3>

                        <div class="mb-6">
                            <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-3">Apakah aset sudah bersertifikat?</label>
                            <div class="flex gap-4">
                                <label class="flex-1 flex items-center justify-between p-4 rounded-2xl border-2 border-surface-100 hover:border-nu-green-200 cursor-pointer transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-nu-green-50 text-nu-green-600 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                        </div>
                                        <div class="text-left">
                                            <div class="text-sm font-bold text-surface-900">Sudah</div>
                                            <div class="text-[10px] text-surface-400 font-medium">Memiliki SHM, SHGB, atau Wakaf</div>
                                        </div>
                                    </div>
                                    <input type="radio" name="sudah_sertifikat" value="1" class="text-nu-green-600 focus:ring-nu-green-500">
                                </label>
                                
                                <label class="flex-1 flex items-center justify-between p-4 rounded-2xl border-2 border-surface-100 hover:border-nu-green-200 cursor-pointer transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </div>
                                        <div class="text-left">
                                            <div class="text-sm font-bold text-surface-900">Belum</div>
                                            <div class="text-[10px] text-surface-400 font-medium">Masih girik, petok D, atau belum AJB</div>
                                        </div>
                                    </div>
                                    <input type="radio" name="sudah_sertifikat" value="0" checked class="text-nu-green-600 focus:ring-nu-green-500">
                                </label>
                            </div>
                        </div>

                        <!-- Sertifikat Fields (Show dynamically) -->
                        <div id="sertifikatFields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6 p-6 rounded-3xl bg-surface-50 border border-surface-100 mb-6 animate-fade-in">
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Nomor Sertifikat / NIB</label>
                                <input type="text" name="nomor_sertifikat" id="nomor_sertifikat" class="w-full py-3 px-4 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm bg-white transition-all" placeholder="Contoh: 10.05.12.01.1.00204">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Tanggal Sertifikat</label>
                                <input type="date" name="tanggal_sertifikat" id="tanggal_sertifikat" class="w-full py-3 px-4 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Atas Nama Sertifikat</label>
                                <input type="text" name="atas_nama_sertifikat" id="atas_nama_sertifikat" class="w-full py-3 px-4 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm bg-white transition-all" placeholder="Contoh: Yayasan NU / Perorangan">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Jenis Sertifikat</label>
                                <select name="jenis_sertifikat" id="jenis_sertifikat" class="w-full py-3 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm bg-white transition-all">
                                    <option value="">Pilih Jenis</option>
                                    <option value="SHM">Sertifikat Hak Milik (SHM)</option>
                                    <option value="SHGB">Sertifikat Hak Guna Bangunan (SHGB)</option>
                                    <option value="WAKAF">Sertifikat Wakaf</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ── STEP 5: TINDAKAN & KONTAK ── -->
                    <div class="tab hidden transition-all duration-300" data-step="5">
                        <h3 class="text-lg font-black text-surface-900 mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-nu-green-50 text-nu-green-600 flex items-center justify-center text-xs font-black">5</span>
                            Tindakan Terhadap Aset & Kontak
                        </h3>

                        <div class="mb-6">
                            <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-3">Tindakan Yang Diinginkan</label>
                            <div class="space-y-4">
                                <label class="flex items-center justify-between p-4 rounded-2xl border-2 border-surface-100 hover:border-nu-green-200 cursor-pointer transition-all">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="tindakan_aset" value="WAKAF_NU" checked class="text-nu-green-600 focus:ring-nu-green-500">
                                        <div class="text-left">
                                            <div class="text-sm font-bold text-surface-900">Diwakafkan ke NU</div>
                                            <div class="text-[10px] text-surface-400 font-medium">Untuk sertifikat tanah hak milik (SHM) atau hak guna bangunan (SHGB)</div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center justify-between p-4 rounded-2xl border-2 border-surface-100 hover:border-nu-green-200 cursor-pointer transition-all">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="tindakan_aset" value="ASET_NU" class="text-nu-green-600 focus:ring-nu-green-500">
                                        <div class="text-left">
                                            <div class="text-sm font-bold text-surface-900">Didaftarkan menjadi aset NU</div>
                                            <div class="text-[10px] text-surface-400 font-medium">Untuk sertifikat wakaf yang sudah ber-Nazhir NU</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="flex items-center justify-between p-4 rounded-2xl border-2 border-surface-100 hover:border-nu-green-200 cursor-pointer transition-all">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="tindakan_aset" value="GANTI_NAZHIR_NU" class="text-nu-green-600 focus:ring-nu-green-500">
                                        <div class="text-left">
                                            <div class="text-sm font-bold text-surface-900">Ganti Nazhir ke NU</div>
                                            <div class="text-[10px] text-surface-400 font-medium">Untuk sertifikat wakaf yang belum ber-Nazhir NU</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Keterangan -->
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Keterangan Tambahan</label>
                                <textarea name="keterangan" rows="3" class="w-full py-3 px-4 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all" placeholder="Masukkan catatan opsional atau detail tambahan aset..."></textarea>
                            </div>
                            <!-- Nomor HP / WA -->
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2">Nomor HP / WhatsApp (Bisa dihubungi)</label>
                                <div class="relative group">
                                    <input type="tel" name="hp_wa" class="w-full pl-10 pr-4 py-3 rounded-xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-sm transition-all" placeholder="Contoh: 08123456789" required>
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-surface-400">
                                        <svg class="w-5 h-5 text-surface-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Button Navigation -->
                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-surface-50">
                        <button type="button" id="prevBtn" onclick="nextPrev(-1)" class="px-6 py-3 rounded-xl bg-white border border-surface-200 text-surface-600 text-xs font-black uppercase tracking-wider hover:bg-surface-50 transition-all">Kembali</button>
                        <button type="button" id="nextBtn" onclick="nextPrev(1)" class="px-6 py-3 rounded-xl bg-nu-green-600 text-white text-xs font-black uppercase tracking-wider hover:bg-nu-green-700 shadow shadow-nu-green-100 transition-all">Lanjut</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ── CONFIRMATION MODAL ── -->
    <div id="confirmModal" class="fixed inset-0 bg-slate-900/60 z-[100] flex items-center justify-center p-4 hidden backdrop-blur-sm transition-all duration-300">
        <div class="bg-white rounded-[2.5rem] shadow-xl w-full max-w-xl overflow-hidden border border-surface-100 transform scale-95 transition-all">
            <div class="p-8 md:p-10">
                <div class="w-12 h-12 rounded-2xl bg-nu-green-50 text-nu-green-600 flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-2xl font-black text-surface-900 tracking-tight mb-2">Konfirmasi Pendaftaran</h3>
                <p class="text-sm text-surface-500 font-medium mb-6">Silakan periksa kembali data aset yang Anda daftarkan di bawah ini:</p>

                <!-- Data Summary List -->
                <div class="space-y-3 max-h-64 overflow-y-auto pr-2 mb-8 text-sm">
                    <div class="flex justify-between py-2 border-b border-surface-50">
                        <span class="text-surface-400 font-medium">Lokasi</span>
                        <span id="sum-lokasi" class="text-surface-900 font-bold text-right"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-surface-50">
                        <span class="text-surface-400 font-medium">Nama Aset</span>
                        <span id="sum-aset" class="text-surface-900 font-bold"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-surface-50">
                        <span class="text-surface-400 font-medium">Luas Tanah</span>
                        <span id="sum-luas" class="text-surface-900 font-bold"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-surface-50">
                        <span class="text-surface-400 font-medium">Nama Wakif</span>
                        <span id="sum-wakif" class="text-surface-900 font-bold"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-surface-50">
                        <span class="text-surface-400 font-medium">Penggunaan</span>
                        <span id="sum-penggunaan" class="text-surface-900 font-bold"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-surface-50">
                        <span class="text-surface-400 font-medium">Status Ikrar</span>
                        <span id="sum-ikrar" class="text-surface-900 font-bold"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-surface-50">
                        <span class="text-surface-400 font-medium">Status Sertifikat</span>
                        <span id="sum-sertifikat" class="text-surface-900 font-bold"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-surface-50">
                        <span class="text-surface-400 font-medium">Tindakan</span>
                        <span id="sum-tindakan" class="text-surface-900 font-bold"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-surface-50">
                        <span class="text-surface-400 font-medium">HP / WA</span>
                        <span id="sum-kontak" class="text-surface-900 font-bold"></span>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center gap-4">
                    <button type="button" onclick="closeConfirmModal()" class="flex-1 px-6 py-4 rounded-2xl bg-white border border-surface-200 text-surface-600 text-xs font-black uppercase tracking-wider hover:bg-surface-50 transition-all">Perbaiki</button>
                    <button type="button" onclick="submitForm()" class="flex-1 px-6 py-4 rounded-2xl bg-nu-green-600 text-white text-xs font-black uppercase tracking-wider hover:bg-nu-green-700 shadow shadow-nu-green-100 transition-all">Simpan Pendaftaran</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── WARNING MODAL ── -->
    <div id="warningModal" class="fixed inset-0 bg-slate-900/60 z-[100] flex items-center justify-center p-4 hidden backdrop-blur-sm transition-all duration-300">
        <div class="bg-white rounded-[2.5rem] shadow-xl w-full max-w-md overflow-hidden border border-surface-100 text-center transform scale-95 transition-all">
            <div class="p-8 md:p-10">
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h3 class="text-2xl font-black text-surface-900 tracking-tight mb-3">Peringatan!</h3>
                <p id="warningMessage" class="text-sm text-surface-500 font-medium leading-relaxed mb-8">
                    Mohon lengkapi seluruh kolom wajib yang bertanda merah.
                </p>
                <button type="button" onclick="closeWarningModal()" class="block w-full py-4 rounded-2xl bg-nu-green-600 text-white text-xs font-black uppercase tracking-wider hover:bg-nu-green-700 shadow shadow-nu-green-100 transition-all">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ── SUCCESS NOTIFICATION OVERLAY ── -->
    <div id="successModal" class="fixed inset-0 bg-slate-900/60 z-[100] flex items-center justify-center p-4 hidden backdrop-blur-sm transition-all duration-300">
        <div class="bg-white rounded-[2.5rem] shadow-xl w-full max-w-md overflow-hidden border border-surface-100 text-center transform scale-95 transition-all">
            <div class="p-8 md:p-10">
                <div class="w-16 h-16 rounded-full bg-nu-green-100 text-nu-green-600 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h3 class="text-2xl font-black text-surface-900 tracking-tight mb-2">Pendaftaran Berhasil!</h3>
                
                <!-- Registration Number Highlight Box -->
                <div class="my-5 p-4 rounded-2xl bg-nu-green-50/50 border border-nu-green-100">
                    <div class="text-[10px] font-black text-nu-green-700 uppercase tracking-widest mb-1">Nomor Pengajuan</div>
                    <div id="successRegNo" class="text-lg font-black text-nu-green-800 tracking-wider">-</div>
                </div>

                <p class="text-sm text-surface-500 font-medium leading-relaxed mb-6">
                    Data pendaftaran aset Anda telah berhasil disimpan dan akan segera ditindaklanjuti oleh pengurus <span class="text-nu-green-700 font-bold">LWP NU</span> setempat. Terima kasih atas kepercayaan Anda.
                </p>
                
                <div class="space-y-3">
                    <a id="downloadBtn" href="#" class="block w-full py-4 rounded-2xl bg-nu-green-600 text-white text-xs font-black uppercase tracking-wider hover:bg-nu-green-700 shadow shadow-nu-green-100 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Unduh Bukti Pendaftaran (PDF)
                    </a>
                    
                    <a href="{{ route('land-waqf.stats') }}" class="block w-full py-4 rounded-2xl bg-white border border-surface-200 text-surface-600 text-xs font-black uppercase tracking-wider hover:bg-surface-50 transition-all">Selesai</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ── SCRIPTS ── -->
    <script>
        let currentStep = 1;
        showStep(currentStep);

        // Display the specific step wizard tab
        function showStep(n) {
            const tabs = document.getElementsByClassName("tab");
            tabs[n - 1].style.display = "block";
            
            // Toggle Back Button
            if (n === 1) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            
            // Handle Next / Submit button text
            if (n === tabs.length) {
                document.getElementById("nextBtn").innerHTML = "Simpan";
            } else {
                document.getElementById("nextBtn").innerHTML = "Lanjut";
            }
            
            // Update Step Indicators
            updateStepsIndicator(n);
        }

        // Handle Next or Previous action
        function nextPrev(n) {
            const tabs = document.getElementsByClassName("tab");
            
            // Validate step before proceeding
            if (n === 1 && !validateStepForm(currentStep)) return false;
            
            // Hide current tab
            tabs[currentStep - 1].style.display = "none";
            
            // Change step count
            currentStep = currentStep + n;
            
            // Submit form if final step
            if (currentStep > tabs.length) {
                currentStep = tabs.length;
                tabs[currentStep - 1].style.display = "block";
                openConfirmModal();
                return false;
            }
            
            // Render new step
            showStep(currentStep);
        }

        // Warning Modal Helpers
        function showWarning(message) {
            document.getElementById("warningMessage").innerText = message;
            const modal = document.getElementById("warningModal");
            modal.classList.remove("hidden");
            setTimeout(() => modal.querySelector("div").classList.remove("scale-95"), 50);
        }

        function closeWarningModal() {
            const modal = document.getElementById("warningModal");
            modal.querySelector("div").classList.add("scale-95");
            setTimeout(() => modal.classList.add("hidden"), 150);
        }

        // Validate currently active step fields
        function validateStepForm(step) {
            const tab = document.querySelector(`.tab[data-step="${step}"]`);
            const inputs = tab.querySelectorAll("input[required], select[required], textarea[required]");
            let valid = true;
            
            inputs.forEach(input => {
                // If it is a hidden/conditional field that is not currently visible, skip
                if (input.closest(".hidden")) return;

                if (!input.value.trim()) {
                    input.classList.add("ring-red-500", "ring-2");
                    valid = false;
                } else {
                    input.classList.remove("ring-red-500", "ring-2");
                }
            });
            
            if (!valid) {
                showWarning("Mohon lengkapi seluruh kolom wajib yang bertanda merah.");
            }
            return valid;
        }

        // Update step visual state
        function updateStepsIndicator(n) {
            const indicators = document.getElementsByClassName("step-indicator");
            const progressBar = document.getElementById("wizardProgressBar");
            
            // Progress Bar Scale
            const scale = (n - 1) / (indicators.length - 1);
            progressBar.style.transform = `scaleX(${scale || 0.05})`;

            for (let i = 0; i < indicators.length; i++) {
                const indicatorStep = parseInt(indicators[i].getAttribute("data-step"));
                const circle = indicators[i].querySelector("span");
                const label = indicators[i].querySelectorAll("span")[1];
                
                if (indicatorStep < n) {
                    // Completed step
                    circle.className = "w-8 h-8 rounded-full bg-nu-green-600 text-white font-bold text-sm flex items-center justify-center border-4 border-white shadow transition-all duration-300";
                    label.className = "text-[9px] font-black uppercase text-nu-green-700 tracking-wider mt-2";
                } else if (indicatorStep === n) {
                    // Active step
                    circle.className = "w-8 h-8 rounded-full bg-nu-green-600 text-white font-bold text-sm flex items-center justify-center border-4 border-white shadow transition-all duration-300 ring-4 ring-nu-green-100";
                    label.className = "text-[9px] font-black uppercase text-nu-green-700 tracking-wider mt-2";
                } else {
                    // Pending step
                    circle.className = "w-8 h-8 rounded-full bg-surface-100 text-surface-400 font-bold text-sm flex items-center justify-center border-4 border-white shadow transition-all duration-300";
                    label.className = "text-[9px] font-black uppercase text-surface-400 tracking-wider mt-2";
                }
            }
        }

        // Dynamic toggling of Ikrar fields
        const ikrarRadios = document.getElementsByName("sudah_ikrar");
        ikrarRadios.forEach(radio => {
            radio.addEventListener("change", function() {
                const fields = document.getElementById("ikrarFields");
                const inputs = fields.querySelectorAll("input");
                
                if (this.value === "1") {
                    fields.classList.remove("hidden");
                    inputs.forEach(input => input.setAttribute("required", "required"));
                } else {
                    fields.classList.add("hidden");
                    inputs.forEach(input => {
                        input.removeAttribute("required");
                        input.value = "";
                    });
                }
            });
        });

        // Dynamic toggling of Sertifikat fields
        const sertifikatRadios = document.getElementsByName("sudah_sertifikat");
        sertifikatRadios.forEach(radio => {
            radio.addEventListener("change", function() {
                const fields = document.getElementById("sertifikatFields");
                const inputs = fields.querySelectorAll("input, select");
                
                if (this.value === "1") {
                    fields.classList.remove("hidden");
                    inputs.forEach(input => input.setAttribute("required", "required"));
                } else {
                    fields.classList.add("hidden");
                    inputs.forEach(input => {
                        input.removeAttribute("required");
                        input.value = "";
                    });
                }
            });
        });

        // Cascading Dropdowns Ajax Logic
        document.addEventListener('DOMContentLoaded', function() {
            const selects = ['provinsi', 'kabupaten', 'kecamatan', 'kelurahan'];
            
            selects.forEach((id, index) => {
                const element = document.getElementById(id);
                if (!element) return;
                
                element.addEventListener('change', function() {
                    const value = this.value;
                    const selectedName = this.options[this.selectedIndex].text;
                    
                    // Set visible text name in hidden input
                    const nameInput = document.getElementById(`${id}_name`);
                    if (nameInput) {
                        nameInput.value = value ? selectedName : "";
                    }

                    // Reset all child levels
                    for (let i = index + 1; i < selects.length; i++) {
                        const child = document.getElementById(selects[i]);
                        child.innerHTML = `<option value="">Pilih ${child.previousElementSibling.innerText}</option>`;
                        child.disabled = true;
                        child.classList.add('bg-surface-50', 'cursor-not-allowed');
                        
                        const childNameInput = document.getElementById(`${selects[i]}_name`);
                        if (childNameInput) childNameInput.value = "";
                    }

                    if (value && index < selects.length - 1) {
                        const nextId = selects[index + 1];
                        const nextElement = document.getElementById(nextId);
                        const nextLength = [2, 5, 8, 13][index + 1];
                        
                        nextElement.innerHTML = `<option value="">Memuat data...</option>`;
                        
                        fetch(`/wakaf-tanah/locations?parent=${value}&length=${nextLength}`)
                            .then(response => response.json())
                            .then(data => {
                                nextElement.disabled = false;
                                nextElement.classList.remove('bg-surface-50', 'cursor-not-allowed');
                                nextElement.innerHTML = `<option value="">Pilih ${nextElement.previousElementSibling.innerText}</option>`;
                                
                                data.forEach(loc => {
                                    const option = document.createElement('option');
                                    option.value = loc.kode_lokasi;
                                    option.textContent = loc.nama_lokasi;
                                    nextElement.appendChild(option);
                                });
                            });
                    }
                });
            });
        });

        // Open Confirmation Modal with Summary
        function openConfirmModal() {
            document.getElementById("sum-lokasi").innerText = `${document.getElementById("kelurahan_name").value}, ${document.getElementById("kecamatan_name").value}, ${document.getElementById("kabupaten_name").value}, ${document.getElementById("provinsi_name").value}`;
            document.getElementById("sum-aset").innerText = document.getElementsByName("nama_aset")[0].value || "-";
            document.getElementById("sum-luas").innerHTML = `${document.getElementsByName("luas")[0].value} m²`;
            document.getElementById("sum-wakif").innerText = document.getElementsByName("nama_wakif")[0].value;
            document.getElementById("sum-penggunaan").innerText = document.getElementsByName("penggunaan")[0].value;
            
            const sudahIkrar = document.querySelector('input[name="sudah_ikrar"]:checked').value;
            document.getElementById("sum-ikrar").innerText = sudahIkrar === "1" ? `Sudah (No AIW: ${document.getElementById("nomor_ikrar").value})` : "Belum";
            
            const sudahSertifikat = document.querySelector('input[name="sudah_sertifikat"]:checked').value;
            const jenisSert = document.getElementById("jenis_sertifikat");
            document.getElementById("sum-sertifikat").innerText = sudahSertifikat === "1" ? `Sudah (${jenisSert.options[jenisSert.selectedIndex].text})` : "Belum";
            
            const tindakan = document.querySelector('input[name="tindakan_aset"]:checked').value;
            const tindakanLabels = {
                'WAKAF_NU': 'Diwakafkan ke NU',
                'ASET_NU': 'Didaftarkan menjadi aset NU',
                'GANTI_NAZHIR_NU': 'Ganti Nazhir ke NU'
            };
            document.getElementById("sum-tindakan").innerText = tindakanLabels[tindakan];
            document.getElementById("sum-kontak").innerText = document.getElementsByName("hp_wa")[0].value;

            const modal = document.getElementById("confirmModal");
            modal.classList.remove("hidden");
            setTimeout(() => modal.querySelector("div").classList.remove("scale-95"), 50);
        }

        function closeConfirmModal() {
            const modal = document.getElementById("confirmModal");
            modal.querySelector("div").classList.add("scale-95");
            setTimeout(() => modal.classList.add("hidden"), 150);
        }

        // Submit Form via AJAX
        function submitForm() {
            const form = document.getElementById("regForm");
            const formData = new FormData(form);

            // Disable modal buttons to prevent double-clicks
            const modalButtons = document.getElementById("confirmModal").querySelectorAll("button");
            modalButtons.forEach(btn => btn.disabled = true);

            fetch(form.action, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeConfirmModal();
                    
                    // Show registration number and set download link in Success Modal
                    document.getElementById("successRegNo").innerText = data.nomor_pengajuan;
                    document.getElementById("downloadBtn").setAttribute("href", data.download_url);

                    // Open Success Modal
                    const successModal = document.getElementById("successModal");
                    successModal.classList.remove("hidden");
                    setTimeout(() => successModal.querySelector("div").classList.remove("scale-95"), 50);
                } else {
                    showWarning("Gagal menyimpan data: " + (data.message || "Kesalahan tidak dikenal"));
                    modalButtons.forEach(btn => btn.disabled = false);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showWarning("Terjadi kesalahan koneksi server.");
                modalButtons.forEach(btn => btn.disabled = false);
            });
        }
    </script>
</x-public-layout>
