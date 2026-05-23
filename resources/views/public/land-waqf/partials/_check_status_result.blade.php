@if($searched)
    @if(!$item)
        <!-- Not Found Alert -->
        <div class="bg-red-50/50 border border-red-100 rounded-[2rem] p-8 text-center space-y-4 animate-fade-in-up">
            <div class="w-16 h-16 bg-red-100 rounded-3xl flex items-center justify-center text-red-600 mx-auto shadow-sm shadow-red-100/50 animate-[bounce_2s_infinite]">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <div class="max-w-md mx-auto space-y-2">
                <h3 class="text-lg font-black text-red-900 uppercase">Nomor Registrasi Tidak Ditemukan</h3>
                <p class="text-sm font-semibold text-red-700/80 leading-relaxed">
                    Maaf, nomor registrasi <span class="font-bold text-red-950">"{{ $nomorPengajuan }}"</span> tidak terdaftar di database kami. Silakan periksa kembali keselarasan karakter dan format nomor bukti pendaftaran Anda.
                </p>
            </div>
        </div>
    @else
        <!-- Registration Record Found -->
        <div class="space-y-8 animate-fade-in-up">
            
            <!-- Status Banner & Details Card -->
            <div class="bg-white rounded-[2rem] border border-surface-100 shadow-card overflow-hidden">
                <!-- Status Banner Header -->
                <div class="p-8 border-b border-surface-50 flex flex-col md:flex-row items-center justify-between gap-6 {{
                    $item->status === 'SELESAI' ? 'bg-nu-green-50/30' : (
                    $item->status === 'DIBATALKAN' ? 'bg-red-50/30' : (
                    $item->status === 'VERIFIKASI_LAPANGAN' ? 'bg-blue-50/30' : 
                    'bg-amber-50/30'
                )) }}">
                    <div class="space-y-1 text-center md:text-left">
                        <span class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Status Pendaftaran</span>
                        <h2 class="text-2xl font-black uppercase {{
                            $item->status === 'SELESAI' ? 'text-nu-green-700' : (
                            $item->status === 'DIBATALKAN' ? 'text-red-700' : (
                            $item->status === 'VERIFIKASI_LAPANGAN' ? 'text-blue-700' : 
                            'text-amber-700'
                        )) }}">
                            @if($item->status === 'PENDING')
                                Menunggu Verifikasi Berkas
                            @elseif($item->status === 'VERIFIKASI_LAPANGAN')
                                Verifikasi Lapangan
                            @elseif($item->status === 'SELESAI')
                                Selesai & Resmi Terdaftar
                            @elseif($item->status === 'DIBATALKAN')
                                Pengajuan Dibatalkan
                            @else
                                {{ $item->status }}
                            @endif
                        </h2>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest text-white shadow-lg {{
                            $item->status === 'SELESAI' ? 'bg-nu-green-600 shadow-nu-green-100' : (
                            $item->status === 'DIBATALKAN' ? 'bg-red-600 shadow-red-100' : (
                            $item->status === 'VERIFIKASI_LAPANGAN' ? 'bg-blue-600 shadow-blue-100' : 
                            'bg-amber-500 shadow-amber-100'
                        )) }}">
                            {{ $item->status }}
                        </span>
                    </div>
                </div>

                <!-- Status Explanation Alert -->
                <div class="px-8 pt-8">
                    <div class="p-6 rounded-2xl border text-sm font-semibold leading-relaxed {{
                        $item->status === 'SELESAI' ? 'bg-nu-green-50/50 border-nu-green-100 text-nu-green-800' : (
                        $item->status === 'DIBATALKAN' ? 'bg-red-50/50 border-red-100 text-red-800' : (
                        $item->status === 'VERIFIKASI_LAPANGAN' ? 'bg-blue-50/50 border-blue-100 text-blue-800' : 
                        'bg-amber-50/50 border-amber-100 text-amber-800'
                    )) }}">
                        @if($item->status === 'PENDING')
                            Pendaftaran Anda telah sukses diterima. Saat ini, tim pengurus LWP NU setempat sedang melakukan peninjauan administrasi dan kelayakan dokumen awal yang Anda lampirkan. Mohon bersedia menunggu.
                        @elseif($item->status === 'VERIFIKASI_LAPANGAN')
                            Berkas pendaftaran Anda dinyatakan memenuhi syarat kelayakan berkas awal. Tim Pengurus LWP NU sedang menjadwalkan kunjungan verifikasi lapangan langsung ke lokasi tanah wakaf Anda. 
                            @if($item->tanggal_verifikasi)
                                Jadwal verifikasi lapangan telah ditetapkan pada tanggal <span class="font-black text-blue-950">{{ $item->tanggal_verifikasi->format('d F Y') }}</span>.
                            @endif
                        @elseif($item->status === 'SELESAI')
                            Selamat! Pengajuan pendaftaran aset wakaf Anda telah dinyatakan selesai dan terverifikasi secara resmi. Data aset tanah ini telah terintegrasi langsung ke dalam data aset utama SIWAKNU dengan Nomor Akta Ikrar Wakaf (AIW) <span class="font-black text-nu-green-950">{{ $item->nomor_ikrar }}</span>.
                        @elseif($item->status === 'DIBATALKAN')
                            Pengajuan pendaftaran aset Anda terpaksa dibatalkan oleh pengurus LWP NU. 
                            @if($item->alasan_batal)
                                <div class="mt-2 pt-2 border-t border-red-100/50">
                                    <span class="block text-[10px] font-black uppercase text-red-500 tracking-wider">Alasan Pembatalan:</span>
                                    <span class="font-bold text-red-900">{{ $item->alasan_batal }}</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Progress Timeline Indicator -->
                <div class="p-8 md:p-10 border-b border-surface-50">
                    <h4 class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-6">Progres Pendaftaran</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                        <!-- Stage 1: Pendaftaran Diterima -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full shrink-0 flex items-center justify-center font-bold text-sm bg-nu-green-600 text-white shadow shadow-nu-green-100">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div>
                                <span class="block font-black text-surface-900 text-sm">Pendaftaran Diterima</span>
                                <span class="block text-xs font-semibold text-surface-400 mt-1">Selesai terkirim ke sistem</span>
                            </div>
                        </div>

                        <!-- Stage 2: Verifikasi Lapangan -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full shrink-0 flex items-center justify-center font-bold text-sm transition-all {{
                                $item->status === 'VERIFIKASI_LAPANGAN' || $item->status === 'SELESAI'
                                    ? 'bg-nu-green-600 text-white shadow shadow-nu-green-100'
                                    : ($item->status === 'DIBATALKAN' ? 'bg-red-100 text-red-600' : 'bg-surface-100 text-surface-400')
                            }}">
                                @if($item->status === 'VERIFIKASI_LAPANGAN' || $item->status === 'SELESAI')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                @elseif($item->status === 'DIBATALKAN')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                @else
                                    2
                                @endif
                            </div>
                            <div>
                                <span class="block font-black text-surface-900 text-sm">Verifikasi Lapangan</span>
                                <span class="block text-xs font-semibold text-surface-400 mt-1">
                                    @if($item->status === 'VERIFIKASI_LAPANGAN')
                                        Sedang dijadwalkan / berlangsung
                                    @elseif($item->status === 'SELESAI')
                                        Selesai diverifikasi
                                    @elseif($item->status === 'DIBATALKAN')
                                        Pengajuan terhenti
                                    @else
                                        Menunggu antrean
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Stage 3: Selesai & Terdaftar -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full shrink-0 flex items-center justify-center font-bold text-sm transition-all {{
                                $item->status === 'SELESAI'
                                    ? 'bg-nu-green-600 text-white shadow shadow-nu-green-100'
                                    : ($item->status === 'DIBATALKAN' ? 'bg-red-100 text-red-600' : 'bg-surface-100 text-surface-400')
                            }}">
                                @if($item->status === 'SELESAI')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                @elseif($item->status === 'DIBATALKAN')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                @else
                                    3
                                @endif
                            </div>
                            <div>
                                <span class="block font-black text-surface-900 text-sm">Selesai & Terdaftar</span>
                                <span class="block text-xs font-semibold text-surface-400 mt-1">
                                    @if($item->status === 'SELESAI')
                                        Aset resmi tercatat di SIWAKNU
                                    @elseif($item->status === 'DIBATALKAN')
                                        Dibatalkan oleh pengurus
                                    @else
                                        Belum diproses
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details Sections Grid -->
                <div class="p-8 md:p-10 space-y-8 bg-surface-50/20">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Lokasi Aset -->
                        <div class="bg-white p-6 rounded-3xl border border-surface-100 shadow-sm space-y-4">
                            <h4 class="text-[10px] font-black text-surface-400 uppercase tracking-widest border-b border-surface-50 pb-2">Lokasi Aset</h4>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <span class="text-[10px] text-surface-400 font-bold block">Alamat Lengkap</span>
                                    <span class="font-bold text-surface-800">{{ $item->alamat }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-[10px] text-surface-400 font-bold block">Kelurahan</span>
                                        <span class="font-bold text-surface-800">{{ $item->kelurahan }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-surface-400 font-bold block">Kecamatan</span>
                                        <span class="font-bold text-surface-800">{{ $item->kecamatan }}</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-[10px] text-surface-400 font-bold block">Kabupaten / Kota</span>
                                        <span class="font-bold text-surface-800">{{ $item->kabupaten }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-surface-400 font-bold block">Provinsi</span>
                                        <span class="font-bold text-surface-800">{{ $item->provinsi }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Identitas Aset -->
                        <div class="bg-white p-6 rounded-3xl border border-surface-100 shadow-sm space-y-4">
                            <h4 class="text-[10px] font-black text-surface-400 uppercase tracking-widest border-b border-surface-50 pb-2">Identitas Aset</h4>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <span class="text-[10px] text-surface-400 font-bold block">Nama Aset (jika ada)</span>
                                    <span class="font-bold text-surface-800">{{ $item->nama_aset ?: '-' }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-[10px] text-surface-400 font-bold block">Luas Tanah</span>
                                        <span class="font-bold text-surface-800">{{ number_format($item->luas, 0, ',', '.') }} m²</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-surface-400 font-bold block">Jenis Penggunaan</span>
                                        <span class="font-bold text-surface-800 uppercase">{{ $item->penggunaan }}</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-[10px] text-surface-400 font-bold block">Nama Wakif</span>
                                    <span class="font-bold text-surface-800">{{ $item->nama_wakif }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Legalitas Ikrar & Sertifikat -->
                        <div class="bg-white p-6 rounded-3xl border border-surface-100 shadow-sm space-y-4 md:col-span-2">
                            <h4 class="text-[10px] font-black text-surface-400 uppercase tracking-widest border-b border-surface-50 pb-2">Legalitas & Status Dokumen</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                                <!-- Ikrar -->
                                <div class="space-y-3">
                                    <h5 class="text-[10px] font-black text-nu-green-700 uppercase tracking-widest mb-1">Akta Ikrar Wakaf (AIW)</h5>
                                    <div>
                                        <span class="text-[10px] text-surface-400 font-bold block">Status Ikrar</span>
                                        <span class="font-bold text-surface-800">{{ $item->sudah_ikrar ? 'SUDAH IKRAR WAKAF' : 'BELUM IKRAR WAKAF' }}</span>
                                    </div>
                                    @if($item->sudah_ikrar)
                                        <div>
                                            <span class="text-[10px] text-surface-400 font-bold block">Nomor Ikrar</span>
                                            <span class="font-bold text-surface-800">{{ $item->nomor_ikrar }}</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <span class="text-[10px] text-surface-400 font-bold block">Tanggal Ikrar</span>
                                                <span class="font-bold text-surface-800">{{ $item->tanggal_ikrar ? $item->tanggal_ikrar->format('d-m-Y') : '-' }}</span>
                                            </div>
                                            <div>
                                                <span class="text-[10px] text-surface-400 font-bold block">Nama Nazhir</span>
                                                <span class="font-bold text-surface-800">{{ $item->nama_nazhir }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Sertifikat -->
                                <div class="space-y-3">
                                    <h5 class="text-[10px] font-black text-nu-green-700 uppercase tracking-widest mb-1">Sertifikat Tanah</h5>
                                    <div>
                                        <span class="text-[10px] text-surface-400 font-bold block">Status Sertifikat</span>
                                        <span class="font-bold text-surface-800">{{ $item->sudah_sertifikat ? 'SUDAH BERSERTIFIKAT' : 'BELUM BERSERTIFIKAT' }}</span>
                                    </div>
                                    @if($item->sudah_sertifikat)
                                        <div>
                                            <span class="text-[10px] text-surface-400 font-bold block">Nomor Sertifikat / NIB</span>
                                            <span class="font-bold text-surface-800">{{ $item->nomor_sertifikat }}</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <span class="text-[10px] text-surface-400 font-bold block">Tanggal Sertifikat</span>
                                                <span class="font-bold text-surface-800">{{ $item->tanggal_sertifikat ? $item->tanggal_sertifikat->format('d-m-Y') : '-' }}</span>
                                            </div>
                                            <div>
                                                <span class="text-[10px] text-surface-400 font-bold block">Jenis Sertifikat</span>
                                                <span class="font-bold text-surface-800 uppercase">{{ $item->jenis_sertifikat }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-[10px] text-surface-400 font-bold block">Atas Nama Sertifikat</span>
                                            <span class="font-bold text-surface-800">{{ $item->atas_nama_sertifikat }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Kontak & Tindakan -->
                        <div class="bg-white p-6 rounded-3xl border border-surface-100 shadow-sm space-y-4 md:col-span-2">
                            <h4 class="text-[10px] font-black text-surface-400 uppercase tracking-widest border-b border-surface-50 pb-2">Kontak & Tindakan Aset</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                                <div>
                                    <span class="text-[10px] text-surface-400 font-bold block">Tindakan Terhadap Aset</span>
                                    <span class="font-black text-surface-800 uppercase tracking-wide text-sm block mt-0.5">
                                        @if($item->tindakan_aset === 'WAKAF_NU') DIWAKAFKAN KE NU
                                        @elseif($item->tindakan_aset === 'ASET_NU') DIDAFTARKAN MENJADI ASET NU
                                        @elseif($item->tindakan_aset === 'GANTI_NAZHIR_NU') GANTI NAZHIR KE NU
                                        @else {{ $item->tindakan_aset }}
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-surface-400 font-bold block">Nomor HP / WhatsApp</span>
                                    <span class="font-bold text-surface-600 block mt-0.5">
                                        {{ preg_replace('/(\d{4})\d+(\d{4})/', '$1****$2', $item->hp_wa) }} <span class="text-xs font-semibold text-surface-400 ml-1">(Disamarkan demi privasi)</span>
                                    </span>
                                </div>
                                <div class="md:col-span-2">
                                    <span class="text-[10px] text-surface-400 font-bold block">Catatan / Keterangan</span>
                                    <span class="font-semibold text-surface-600 block mt-0.5">{{ $item->keterangan ?: '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endif
@endif
