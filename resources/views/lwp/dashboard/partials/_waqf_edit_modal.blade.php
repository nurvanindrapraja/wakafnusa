<div class="flex flex-col h-[85vh]">
    <!-- Modal Header -->
    <div class="px-8 pt-8 pb-4 border-b border-slate-50 shrink-0">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-black text-slate-900 uppercase leading-none">Edit Aset Wakaf #{{ $item->ID_WAKAF }}</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Perbarui kelengkapan data aset wakaf</p>
            </div>
            <button onclick="closeLwpModal()" class="text-slate-400 hover:text-nu-green-600 transition-colors p-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Stepper Indicator -->
        <div class="flex items-center gap-4">
            <div class="edit-step-indicator flex-1 h-1.5 rounded-full bg-nu-green-600 transition-all duration-500" data-step="1"></div>
            <div class="edit-step-indicator flex-1 h-1.5 rounded-full bg-slate-100 transition-all duration-500" data-step="2"></div>
            <div class="edit-step-indicator flex-1 h-1.5 rounded-full bg-slate-100 transition-all duration-500" data-step="3"></div>
            <div class="edit-step-indicator flex-1 h-1.5 rounded-full bg-slate-100 transition-all duration-500" data-step="4"></div>
            <div class="edit-step-indicator flex-1 h-1.5 rounded-full bg-slate-100 transition-all duration-500" data-step="5"></div>
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
        <form id="editWaqfForm" onsubmit="submitEditWaqf(event, {{ $item->ID_WAKAF }})" class="space-y-8">
            <!-- Step 1: Lokasi -->
            <div id="edit-step-1" class="waqf-edit-step space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Provinsi</label>
                        <select name="provinsi" id="edit-sel-prov" onchange="loadEditChildLokasi(this.value, 'kab')" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p->kode_lokasi }}" {{ $prov === $p->kode_lokasi ? 'selected' : '' }}>{{ $p->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Kabupaten / Kota</label>
                        <select name="kabupaten" id="edit-sel-kab" onchange="loadEditChildLokasi(this.value, 'kec')" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            <option value="">Pilih Kabupaten</option>
                            @foreach($kabupatens as $k)
                                <option value="{{ $k->kode_lokasi }}" {{ $kab === $k->kode_lokasi ? 'selected' : '' }}>{{ $k->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Kecamatan</label>
                        <select name="kecamatan" id="edit-sel-kec" onchange="loadEditChildLokasi(this.value, 'kel')" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            <option value="">Pilih Kecamatan</option>
                            @foreach($kecamatans as $k)
                                <option value="{{ $k->kode_lokasi }}" {{ $kec === $k->kode_lokasi ? 'selected' : '' }}>{{ $k->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Kelurahan / Desa</label>
                        <select name="kelurahan" id="edit-sel-kel" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            <option value="">Pilih Kelurahan</option>
                            @foreach($kelurahans as $k)
                                <option value="{{ $k->kode_lokasi }}" {{ $kel === $k->kode_lokasi ? 'selected' : '' }}>{{ $k->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none placeholder:font-normal" placeholder="Nama jalan, RT/RW...">{{ $item->ALAMAT }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Step 2: Legalitas -->
            <div id="edit-step-2" class="waqf-edit-step hidden space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor AIW</label>
                        <input type="text" name="no_aiw" value="{{ $item->NO_AIW }}" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal AIW</label>
                        <input type="date" name="tgl_aiw" value="{{ $item->TGL_AIW }}" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor Sertifikat</label>
                        <input type="text" name="no_sert" value="{{ $item->NO_SERT }}" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Sertifikat</label>
                        <input type="date" name="tgl_sert" value="{{ $item->TGL_SERT }}" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                </div>
            </div>

            <!-- Step 3: Identitas -->
            <div id="edit-step-3" class="waqf-edit-step hidden space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Objek</label>
                        <input type="text" name="nama" value="{{ $item->NAMA }}" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none" placeholder="Contoh: Masjid Al-Ikhlas">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Wakif</label>
                        <input type="text" name="wakif" value="{{ $item->WAKIF }}" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Nazhir</label>
                        <input type="text" name="nazhir" value="{{ $item->NADZIR ?: 'NAHDLATUL ULAMA' }}" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Luas (m<sup>2</sup>)</label>
                        <input type="number" step="0.01" name="luas" value="{{ $item->LUAS }}" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Penggunaan</label>
                        <select name="penggunaan" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            @foreach($usages as $u)
                                <option value="{{ $u->KODE_PENG }}" {{ $item->KODE_PENG == $u->KODE_PENG ? 'selected' : '' }}>{{ $u->PENGGUNAAN }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Sengketa</label>
                        <select name="status_sengketa" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            @foreach($st_tanah as $st)
                                <option value="{{ $st->ID_ST }}" {{ $item->ID_ST == $st->ID_ST ? 'selected' : '' }}>{{ $st->JENIS_ST }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Produktif</label>
                        <select name="status_produktif" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            @foreach($st_prod as $sp)
                                <option value="{{ $sp->ID_SP }}" {{ $item->ID_SP == $sp->ID_SP ? 'selected' : '' }}>{{ $sp->JENIS_SP }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Tanah</label>
                        <select name="status_tanah" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            @foreach($st_wakaf as $sw)
                                <option value="{{ $sw->ID_SW }}" {{ $item->ID_SW == $sw->ID_SW ? 'selected' : '' }}>{{ $sw->JENIS_SW }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Kriteria</label>
                        <select name="kriteria" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                            @foreach($kriterias as $k)
                                <option value="{{ $k->ID_KRITERIA }}" {{ $item->ID_KRITERIA == $k->ID_KRITERIA ? 'selected' : '' }}>{{ $k->KRITERIA }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">NJOP</label>
                            <input type="number" name="njop" value="{{ $item->NJOP }}" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Tahun NJOP</label>
                            <input type="number" name="thn_njop" value="{{ $item->THN_NJOP }}" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">
                        </div>
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Keterangan Tambahan</label>
                        <textarea name="keterangan" rows="2" class="w-full px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none">{{ $item->KETERANGAN }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Step 4: Peta -->
            <div id="edit-step-4" class="waqf-edit-step hidden space-y-6">
                <p class="text-xs font-medium text-slate-500">Silakan klik pada peta untuk menentukan titik koordinat aset.</p>
                <div id="editMap" class="w-full h-[400px] bg-slate-200 rounded-3xl border-4 border-white shadow-xl overflow-hidden relative"></div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Latitude</label>
                        <input type="text" name="lat" id="edit-inp-lat" value="{{ $item->LAT }}" readonly class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-black text-nu-green-700 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Longitude</label>
                        <input type="text" name="lng" id="edit-inp-lng" value="{{ $item->LNG }}" readonly class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-black text-nu-green-700 outline-none">
                    </div>
                </div>
            </div>

            <!-- Step 5: Dokumen -->
            <div id="edit-step-5" class="waqf-edit-step hidden space-y-6">
                <p class="text-xs font-medium text-slate-500 mb-4">Kelola dokumen terkait aset (format PDF/JPG/PNG maksimal 2MB per file).</p>
                
                @if(count($images) > 0)
                <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50/50 shadow-inner mb-6">
                    <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest mb-4">Dokumen Tersimpan</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($images as $img)
                            @php
                                $ext = strtolower(pathinfo($img->PATH_GAMBAR, PATHINFO_EXTENSION));
                                $isPdf = $ext === 'pdf';
                            @endphp
                            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden group relative" id="image-card-{{ $img->ID_GAMBAR }}">
                                <div class="relative w-full aspect-square bg-slate-100 flex items-center justify-center">
                                    @if($isPdf)
                                        <div class="text-center p-2">
                                            <svg class="w-8 h-8 text-red-500 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                            <span class="text-[8px] font-black text-slate-400">PDF</span>
                                        </div>
                                    @else
                                        <img src="{{ $img->URL }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="p-2">
                                    <span class="inline-block px-1.5 py-0.5 bg-slate-100 text-slate-600 text-[8px] font-black uppercase rounded mb-1">{{ $img->JENIS_GAMBAR }}</span>
                                </div>
                                <button type="button" onclick="deleteSavedImage({{ $img->ID_GAMBAR }})" class="absolute top-2 right-2 w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="space-y-6">
                    <div class="p-5 border border-slate-100 rounded-2xl bg-white shadow-sm">
                        <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-nu-green-100 text-nu-green-600 flex items-center justify-center">1</span>
                            Tambah Fisik Tanah / Bangunan
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
                            Tambah Dokumen Sertifikat
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
                            Tambah Dokumen AIW
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
        <button id="editBtnPrev" onclick="moveEditStep(-1)" class="hidden px-8 py-3 bg-slate-100 text-slate-600 rounded-xl font-black text-[11px] uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali
        </button>
        <div class="flex-1"></div>
        <button id="editBtnNext" onclick="moveEditStep(1)" class="px-8 py-3 bg-nu-green-600 text-white rounded-xl font-black text-[11px] uppercase tracking-widest shadow-xl shadow-nu-green-100 hover:bg-nu-green-700 transition-all flex items-center gap-2">
            Selanjutnya
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>
        <button id="editBtnSubmit" onclick="submitEditWaqfForm(event)" class="hidden px-8 py-3 bg-nu-green-600 text-white rounded-xl font-black text-[11px] uppercase tracking-widest shadow-xl shadow-nu-green-100 hover:bg-nu-green-700 transition-all flex items-center gap-2">
            Simpan Perubahan
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        </button>
    </div>
</div>
