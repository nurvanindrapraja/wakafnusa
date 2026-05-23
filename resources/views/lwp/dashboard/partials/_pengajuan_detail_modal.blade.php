<div class="flex flex-col h-[85vh]">
    <!-- Modal Header -->
    <div class="px-8 pt-8 pb-4 border-b border-slate-50 shrink-0 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-black text-slate-900 uppercase leading-none">Detail Pengajuan Aset Wakaf</h3>
            <p class="text-[10px] font-bold text-nu-green-600 uppercase tracking-widest mt-2">Nomor: {{ $item->nomor_pengajuan }}</p>
        </div>
        <button onclick="closeLwpModal()" class="text-slate-400 hover:text-nu-green-600 transition-colors p-2">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <!-- Modal Body -->
    <div class="flex-1 overflow-y-auto p-8 bg-slate-50/30 space-y-6">
        <!-- Status Banner -->
        <div class="p-6 rounded-3xl border flex items-center justify-between {{ 
            $item->status === 'SELESAI' ? 'bg-nu-green-50/50 border-nu-green-100 text-nu-green-800' : (
            $item->status === 'DIBATALKAN' ? 'bg-red-50 border-red-100 text-red-800' : (
            $item->status === 'VERIFIKASI_LAPANGAN' ? 'bg-blue-50 border-blue-100 text-blue-800' : 
            'bg-amber-50 border-amber-100 text-amber-800'
        )) }}">
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest block">Status Saat Ini</span>
                <span class="text-lg font-black uppercase mt-1 block">
                    @if($item->status === 'VERIFIKASI_LAPANGAN') VERIFIKASI LAPANGAN
                    @else {{ $item->status }} @endif
                </span>
            </div>
            @if($item->status === 'VERIFIKASI_LAPANGAN' && $item->tanggal_verifikasi)
                <div class="text-right">
                    <span class="text-[10px] font-black uppercase tracking-widest block">Tanggal Verifikasi Lapangan</span>
                    <span class="text-sm font-black mt-1 block">{{ $item->tanggal_verifikasi->format('d M Y') }}</span>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Lokasi Aset -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-2">Lokasi Aset</h4>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold block">Alamat Lengkap</span>
                        <span class="font-bold text-slate-800">{{ $item->alamat }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block">Kelurahan</span>
                            <span class="font-bold text-slate-800">{{ $item->kelurahan }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block">Kecamatan</span>
                            <span class="font-bold text-slate-800">{{ $item->kecamatan }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block">Kabupaten / Kota</span>
                            <span class="font-bold text-slate-800">{{ $item->kabupaten }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block">Provinsi</span>
                            <span class="font-bold text-slate-800">{{ $item->provinsi }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identitas Aset -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-2">Identitas Aset</h4>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold block">Nama Aset (jika ada)</span>
                        <span class="font-bold text-slate-800">{{ $item->nama_aset ?: '-' }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block">Luas Tanah</span>
                            <span class="font-bold text-slate-800">{{ number_format($item->luas, 0, ',', '.') }} m²</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block">Jenis Penggunaan</span>
                            <span class="font-bold text-slate-800 uppercase">{{ $item->penggunaan }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold block">Nama Wakif</span>
                        <span class="font-bold text-slate-800">{{ $item->nama_wakif }}</span>
                    </div>
                </div>
            </div>

            <!-- Legalitas Ikrar & Sertifikat -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4 md:col-span-2">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-2">Legalitas & Status Dokumen</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <!-- Ikrar -->
                    <div class="space-y-3">
                        <h5 class="text-[10px] font-black text-nu-green-700 uppercase tracking-widest mb-1">Akta Ikrar Wakaf (AIW)</h5>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block">Status Ikrar</span>
                            <span class="font-bold text-slate-800">{{ $item->sudah_ikrar ? 'SUDAH IKRAR' : 'BELUM IKRAR' }}</span>
                        </div>
                        @if($item->sudah_ikrar)
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold block">Nomor Ikrar</span>
                                <span class="font-bold text-slate-800">{{ $item->nomor_ikrar }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-[10px] text-slate-400 font-bold block">Tanggal Ikrar</span>
                                    <span class="font-bold text-slate-800">{{ $item->tanggal_ikrar ? $item->tanggal_ikrar->format('d-m-Y') : '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 font-bold block">Nama Nazhir</span>
                                    <span class="font-bold text-slate-800">{{ $item->nama_nazhir }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sertifikat -->
                    <div class="space-y-3">
                        <h5 class="text-[10px] font-black text-nu-green-700 uppercase tracking-widest mb-1">Sertifikat Tanah</h5>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block">Status Sertifikat</span>
                            <span class="font-bold text-slate-800">{{ $item->sudah_sertifikat ? 'SUDAH BERSERTIFIKAT' : 'BELUM BERSERTIFIKAT' }}</span>
                        </div>
                        @if($item->sudah_sertifikat)
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold block">Nomor Sertifikat / NIB</span>
                                <span class="font-bold text-slate-800">{{ $item->nomor_sertifikat }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-[10px] text-slate-400 font-bold block">Tanggal Sertifikat</span>
                                    <span class="font-bold text-slate-800">{{ $item->tanggal_sertifikat ? $item->tanggal_sertifikat->format('d-m-Y') : '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 font-bold block">Jenis Sertifikat</span>
                                    <span class="font-bold text-slate-800 uppercase">{{ $item->jenis_sertifikat }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold block">Atas Nama Sertifikat</span>
                                <span class="font-bold text-slate-800">{{ $item->atas_nama_sertifikat }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kontak & Tindakan -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4 md:col-span-2">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-2">Tindakan & Kontak Pengaju</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold block">Tindakan Terhadap Aset</span>
                        <span class="font-black text-slate-800 uppercase tracking-wide text-sm block mt-0.5">
                            @if($item->tindakan_aset === 'WAKAF_NU') DIWAKAFKAN KE NU
                            @elseif($item->tindakan_aset === 'ASET_NU') DIDAFTARKAN MENJADI ASET NU
                            @elseif($item->tindakan_aset === 'GANTI_NAZHIR_NU') GANTI NAZHIR KE NU
                            @else {{ $item->tindakan_aset }}
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold block">Nomor HP / WhatsApp</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->hp_wa) }}" target="_blank" class="font-bold text-nu-green-700 hover:underline flex items-center gap-2 mt-0.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            {{ $item->hp_wa }} (Hubungi via WA)
                        </a>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-[10px] text-slate-400 font-bold block">Keterangan / Catatan</span>
                        <span class="font-semibold text-slate-600 block mt-0.5">{{ $item->keterangan ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Audit Trail (Audited Log) -->
            @if($item->processed_by)
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4 md:col-span-2 text-sm">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-2">Catatan Audit Pengurusan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block">Diproses Oleh</span>
                            <span class="font-bold text-slate-800">{{ $item->processor ? $item->processor->NAMA_USER : 'Pengurus LWP' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block">Diproses Pada</span>
                            <span class="font-bold text-slate-800">{{ $item->processed_at ? $item->processed_at->format('d-m-Y H:i') : '-' }} WIB</span>
                        </div>
                        @if($item->status === 'DIBATALKAN')
                            <div class="md:col-span-2 bg-red-50/50 p-4 rounded-2xl border border-red-100 text-red-800">
                                <span class="text-[10px] text-red-500 font-black uppercase tracking-widest block mb-1">Alasan Pembatalan</span>
                                <span class="font-bold">{{ $item->alasan_batal }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Footer (Action Panel) -->
    <div class="px-8 py-6 bg-white border-t border-slate-50 shrink-0 flex flex-wrap items-center justify-end gap-3">
        <button type="button" onclick="closeLwpModal()" class="px-6 py-3.5 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">Tutup</button>
        
        @if($item->status === 'PENDING')
            <button type="button" onclick="showVerifyLapangan({{ $item->id }})" class="px-6 py-3.5 bg-blue-600 text-white hover:bg-blue-700 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-100 transition-all">Verifikasi Lapangan</button>
            <button type="button" onclick="showCancelPengajuan({{ $item->id }})" class="px-6 py-3.5 bg-red-600 text-white hover:bg-red-700 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-red-100 transition-all">Batalkan Pengajuan</button>
        @elseif($item->status === 'VERIFIKASI_LAPANGAN')
            <button type="button" onclick="showCompletePengajuan({{ $item->id }}, '{{ $item->nomor_ikrar }}', '{{ $item->nomor_sertifikat }}', '{{ $item->nama_wakif }}', '{{ $item->atas_nama_sertifikat }}')" class="px-6 py-3.5 bg-nu-green-600 text-white hover:bg-nu-green-700 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-nu-green-100 transition-all">Proses Selesai</button>
            <button type="button" onclick="showCancelPengajuan({{ $item->id }})" class="px-6 py-3.5 bg-red-600 text-white hover:bg-red-700 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-red-100 transition-all">Batalkan Pengajuan</button>
        @endif
    </div>
</div>
