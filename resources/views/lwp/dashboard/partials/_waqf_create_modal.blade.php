<div class="flex flex-col h-[85vh]">
    <!-- Modal Header -->
    <div class="px-8 pt-8 pb-4 border-b border-slate-50 shrink-0">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-black text-slate-900 uppercase leading-none">Input Aset Wakaf Baru</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Silakan lengkapi seluruh tahapan pendataan</p>
            </div>
            <button onclick="closeLwpModal()" class="text-slate-400 hover:text-nu-green-600 transition-colors p-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Stepper Indicator -->
        <div class="flex items-center gap-4">
            <div class="step-indicator flex-1 h-1.5 rounded-full bg-nu-green-600 transition-all duration-500" data-step="1"></div>
            <div class="step-indicator flex-1 h-1.5 rounded-full bg-slate-100 transition-all duration-500" data-step="2"></div>
            <div class="step-indicator flex-1 h-1.5 rounded-full bg-slate-100 transition-all duration-500" data-step="3"></div>
            <div class="step-indicator flex-1 h-1.5 rounded-full bg-slate-100 transition-all duration-500" data-step="4"></div>
            <div class="step-indicator flex-1 h-1.5 rounded-full bg-slate-100 transition-all duration-500" data-step="5"></div>
        </div>
        <div class="flex justify-between mt-2 px-1">
            <span class="text-[9px] font-black uppercase tracking-widest text-nu-green-600">Lokasi</span>
            <span class="text-[9px] font-black uppercase tracking-widest text-slate-300">Legalitas</span>
            <span class="text-[9px] font-black uppercase tracking-widest text-slate-300">Identitas</span>
            <span class="text-[9px] font-black uppercase tracking-widest text-slate-300">Peta</span>
            <span class="text-[9px] font-black uppercase tracking-widest text-slate-300">Dokumen</span>
        </div>
    </div>

    <!-- Modal Body -->
    <div class="flex-1 overflow-y-auto p-8 bg-slate-50/30">
        <form id="createWaqfForm" class="space-y-8">
            <!-- Step 1: Lokasi -->
            <div id="step-1" class="waqf-step space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Provinsi</label>
                        <select name="provinsi" id="sel-prov" onchange="loadChildLokasi(this.value, 'kab')" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p->kode_lokasi }}">{{ $p->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Kabupaten / Kota</label>
                        <select name="kabupaten" id="sel-kab" onchange="loadChildLokasi(this.value, 'kec')" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            <option value="">Pilih Kabupaten</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Kecamatan</label>
                        <select name="kecamatan" id="sel-kec" onchange="loadChildLokasi(this.value, 'kel')" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Kelurahan / Desa</label>
                        <select name="kelurahan" id="sel-kel" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            <option value="">Pilih Kelurahan</option>
                        </select>
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none placeholder:font-normal" placeholder="Nama jalan, RT/RW..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Step 2: Legalitas -->
            <div id="step-2" class="waqf-step hidden space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor AIW</label>
                        <input type="text" name="no_aiw" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal AIW</label>
                        <input type="date" name="tgl_aiw" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor Sertifikat</label>
                        <input type="text" name="no_sert" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Sertifikat</label>
                        <input type="date" name="tgl_sert" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                </div>
            </div>

            <!-- Step 3: Identitas -->
            <div id="step-3" class="waqf-step hidden space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Objek</label>
                        <input type="text" name="nama" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none" placeholder="Contoh: Masjid Al-Ikhlas">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Wakif</label>
                        <input type="text" name="wakif" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Nazhir</label>
                        <input type="text" name="nazhir" value="NAHDLATUL ULAMA" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Luas (m<sup>2</sup>)</label>
                        <input type="number" step="0.01" name="luas" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Penggunaan</label>
                        <select name="penggunaan" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            @foreach($usages as $u)
                                <option value="{{ $u->KODE_PENG }}">{{ $u->PENGGUNAAN }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Sengketa</label>
                        <select name="status_sengketa" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            @foreach($st_tanah as $st)
                                <option value="{{ $st->ID_ST }}" {{ $st->JENIS_ST == 'TIDAK SENGKETA' ? 'selected' : '' }}>{{ $st->JENIS_ST }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Produktif</label>
                        <select name="status_produktif" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            @foreach($st_prod as $sp)
                                <option value="{{ $sp->ID_SP }}">{{ $sp->JENIS_SP }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Tanah</label>
                        <select name="status_tanah" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            @foreach($st_wakaf as $sw)
                                <option value="{{ $sw->ID_SW }}">{{ $sw->JENIS_SW }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Kriteria</label>
                        <select name="kriteria" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            @foreach($kriterias as $k)
                                <option value="{{ $k->ID_KRITERIA }}">{{ $k->KRITERIA }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">NJOP</label>
                            <input type="number" name="njop" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Tahun NJOP</label>
                            <input type="number" name="thn_njop" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                        </div>
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Keterangan Tambahan</label>
                        <textarea name="keterangan" rows="2" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Step 4: Peta -->
            <div id="step-4" class="waqf-step hidden space-y-6">
                <p class="text-xs font-medium text-slate-500">Silakan klik pada peta untuk menentukan titik koordinat aset.</p>
                <div id="createMap" class="w-full h-[400px] bg-slate-200 rounded-3xl border-4 border-white shadow-xl overflow-hidden relative"></div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Latitude</label>
                        <input type="text" name="lat" id="inp-lat" readonly class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-black text-nu-green-700 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Longitude</label>
                        <input type="text" name="lng" id="inp-lng" readonly class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-black text-nu-green-700 outline-none">
                    </div>
                </div>
            </div>

            <!-- Step 5: Dokumen -->
            <div id="step-5" class="waqf-step hidden space-y-6">
                <p class="text-xs font-medium text-slate-500 mb-4">Unggah dokumen terkait aset (format PDF/JPG/PNG maksimal 2MB per file).</p>
                <div class="space-y-6">
                    <div class="p-5 border border-slate-100 rounded-2xl bg-white shadow-sm">
                        <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-nu-green-100 text-nu-green-600 flex items-center justify-center">1</span>
                            Fisik Tanah / Bangunan
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">File Upload</label>
                                <input type="file" name="file_fisik[]" accept=".pdf,image/*" multiple class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Keterangan</label>
                                <input type="text" name="ket_fisik" placeholder="Cth: Foto Masjid tampak depan" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="p-5 border border-slate-100 rounded-2xl bg-white shadow-sm">
                        <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-nu-green-100 text-nu-green-600 flex items-center justify-center">2</span>
                            Dokumen Sertifikat <span class="text-slate-300 font-normal ml-2">(Opsional)</span>
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">File Upload</label>
                                <input type="file" name="file_sertifikat[]" accept=".pdf,image/*" multiple class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Keterangan</label>
                                <input type="text" name="ket_sertifikat" placeholder="Cth: Scan Sertifikat asli" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="p-5 border border-slate-100 rounded-2xl bg-white shadow-sm">
                        <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-nu-green-100 text-nu-green-600 flex items-center justify-center">3</span>
                            Dokumen AIW <span class="text-slate-300 font-normal ml-2">(Opsional)</span>
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">File Upload</label>
                                <input type="file" name="file_aiw[]" accept=".pdf,image/*" multiple class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Keterangan</label>
                                <input type="text" name="ket_aiw" placeholder="Cth: Akta Ikrar Wakaf KUA" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Footer -->
    <div class="px-8 py-6 bg-white border-t border-slate-50 shrink-0 flex justify-between items-center">
        <button id="btnPrev" onclick="moveStep(-1)" class="hidden px-8 py-3 bg-slate-100 text-slate-600 rounded-xl font-black text-[11px] uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali
        </button>
        <div class="flex-1"></div>
        <button id="btnNext" onclick="moveStep(1)" class="px-8 py-3 bg-nu-green-600 text-white rounded-xl font-black text-[11px] uppercase tracking-widest shadow-xl shadow-nu-green-100 hover:bg-nu-green-700 transition-all flex items-center gap-2">
            Selanjutnya
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>
        <button id="btnSubmit" onclick="submitCreateWaqf()" class="hidden px-8 py-3 bg-nu-green-600 text-white rounded-xl font-black text-[11px] uppercase tracking-widest shadow-xl shadow-nu-green-100 hover:bg-nu-green-700 transition-all flex items-center gap-2">
            Simpan Aset Baru
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        </button>
    </div>
</div>
