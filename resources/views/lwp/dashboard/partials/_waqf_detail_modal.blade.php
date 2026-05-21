<div class="flex flex-col h-[85vh]">
    <!-- Modal Header -->
    <div class="px-8 pt-8 pb-4 border-b border-slate-50 shrink-0">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-black text-slate-900 uppercase leading-none">Detail Aset #{{ $item->ID_WAKAF }}</h3>
                <div class="flex flex-wrap gap-2 mt-3">
                    @if($item->NIA)
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-900 text-white rounded-lg">
                            <span class="text-[9px] font-black uppercase tracking-widest opacity-50">NIA:</span>
                            <span class="text-xs font-black tracking-widest">{{ $item->NIA }}</span>
                        </div>
                    @endif
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-nu-green-50 text-nu-green-700 rounded-lg">
                        <span class="text-[9px] font-black uppercase tracking-widest opacity-50">Input By:</span>
                        <span class="text-[10px] font-black">{{ $item->INPUTTER ?: 'Sistem' }}</span>
                    </div>
                </div>
            </div>
            <button onclick="closeLwpModal()" class="text-slate-400 hover:text-nu-green-600 transition-colors p-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Tab Headers -->
        <div class="flex items-center gap-8">
            <button onclick="switchLwpTab('detail')" class="lwp-tab-btn pb-3 text-xs font-black uppercase tracking-[0.2em] transition-all border-b-2 border-nu-green-600 text-nu-green-700" data-tab="detail">Info Utama</button>
            @if($item->LAT && $item->LNG)
                <button onclick="switchLwpTab('lokasi')" class="lwp-tab-btn pb-3 text-xs font-black uppercase tracking-[0.2em] transition-all border-b-2 border-transparent text-slate-400 hover:text-slate-600" data-tab="lokasi">Lokasi Peta</button>
            @endif
            <button onclick="switchLwpTab('galeri')" class="lwp-tab-btn pb-3 text-xs font-black uppercase tracking-[0.2em] transition-all border-b-2 border-transparent text-slate-400 hover:text-slate-600" data-tab="galeri">Galeri & Dokumen</button>
            <button onclick="switchLwpTab('riwayat')" class="lwp-tab-btn pb-3 text-xs font-black uppercase tracking-[0.2em] transition-all border-b-2 border-transparent text-slate-400 hover:text-slate-600" data-tab="riwayat">Riwayat Data</button>
        </div>
    </div>

    <!-- Modal Body -->
    <div class="flex-1 overflow-y-auto p-8 bg-slate-50/30">
        <!-- Tab: Detail -->
        <div id="lwp-tab-detail" class="lwp-tab-content space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-[9px] font-black text-slate-300 uppercase tracking-widest mb-2">Nama Aset Wakaf</label>
                        <div class="text-base font-black text-slate-900 leading-tight">{{ $item->NAMA ?: '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-slate-300 uppercase tracking-widest mb-2">Penggunaan</label>
                        <div class="px-4 py-3 bg-nu-green-50 rounded-xl text-nu-green-700 font-black text-sm uppercase">{{ $item->PENGGUNAAN }}</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-black text-slate-300 uppercase tracking-widest mb-2">Wakif</label>
                            <div class="text-sm font-bold text-slate-900 leading-tight">{{ $item->WAKIF ?: '-' }}</div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-slate-300 uppercase tracking-widest mb-2">Nadzir</label>
                            <div class="text-sm font-bold text-slate-900 leading-tight">{{ $item->NADZIR ?: '-' }}</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-slate-300 uppercase tracking-widest mb-2">Luas</label>
                        <div class="text-sm font-black text-slate-900 leading-tight">{{ str_replace(',00', '', number_format($item->LUAS, 2, ',', '.')) }} m<sup>2</sup></div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-slate-300 uppercase tracking-widest mb-2">Keterangan</label>
                        <div class="text-xs font-medium text-slate-600 italic leading-relaxed">{{ $item->KETERANGAN ?: 'Tidak ada keterangan tambahan.' }}</div>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <label class="block text-[9px] font-black text-slate-300 uppercase tracking-widest mb-2">Alamat & Wilayah</label>
                        <div class="text-xs font-medium text-slate-500 leading-relaxed mb-2">{{ $item->ALAMAT }}</div>
                        <div class="flex items-center gap-2 text-nu-green-600 font-black text-[9px] uppercase tracking-widest">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            {{ $item->KEL }}, {{ $item->KEC }}, {{ $item->KAB }}, {{ $item->PROV }}
                        </div>
                    </div>
                </div>

                <!-- Right Column (Status & Docs) -->
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                        <div class="grid grid-cols-1 gap-3">
                            <div class="flex justify-between items-center text-[11px] border-b border-slate-50 pb-2.5">
                                <span class="text-slate-400 font-bold uppercase tracking-widest">Status Tanah</span>
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded font-black">{{ $item->JENIS_SW ?: '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-[11px] border-b border-slate-50 pb-2.5">
                                <span class="text-slate-400 font-bold uppercase tracking-widest">Produktifitas</span>
                                <span class="px-2 py-0.5 bg-purple-50 text-purple-700 rounded font-black">{{ $item->JENIS_SP ?: '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-[11px] border-b border-slate-50 pb-2.5">
                                <span class="text-slate-400 font-bold uppercase tracking-widest">Status Masalah</span>
                                <span class="px-2 py-0.5 {{ $item->ID_ST == 2 ? 'bg-nu-green-50 text-nu-green-700' : 'bg-red-50 text-red-700' }} rounded font-black">{{ $item->JENIS_ST ?: '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-[11px] pt-1">
                                <span class="text-slate-400 font-bold uppercase tracking-widest">Tanggal Input</span>
                                <span class="text-slate-900 font-black">{{ $item->TGINS ? date('d/m/Y H:i', strtotime($item->TGINS)) : '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                        <div class="flex justify-between items-center text-[11px] border-b border-slate-50 pb-3">
                            <span class="text-slate-400 font-bold uppercase tracking-widest">No. AIW</span>
                            <span class="text-slate-900 font-black">{{ $item->NO_AIW ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px] border-b border-slate-50 pb-3">
                            <span class="text-slate-400 font-bold uppercase tracking-widest">Tgl. AIW</span>
                            <span class="text-slate-900 font-black">{{ $item->TGL_AIW ? date('d/m/Y', strtotime($item->TGL_AIW)) : '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px] border-b border-slate-50 pb-3">
                            <span class="text-slate-400 font-bold uppercase tracking-widest">No. Sertifikat</span>
                            <span class="text-slate-900 font-black">{{ $item->NO_SERT ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px] border-b border-slate-50 pb-3">
                            <span class="text-slate-400 font-bold uppercase tracking-widest">Tgl. Sertifikat</span>
                            <span class="text-slate-900 font-black">{{ $item->TGL_SERT ? date('d/m/Y', strtotime($item->TGL_SERT)) : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Lokasi -->
        @if($item->LAT && $item->LNG)
        <div id="lwp-tab-lokasi" class="lwp-tab-content hidden">
            <div id="lwpMap" 
                 data-lat="{{ $item->LAT }}" 
                 data-lng="{{ $item->LNG }}" 
                 data-usage="{{ $item->PENGGUNAAN }}"
                 class="w-full h-[450px] bg-slate-200 rounded-3xl border-4 border-white shadow-xl overflow-hidden relative">
            </div>
        </div>
        @endif

        <!-- Tab: Galeri -->
        <div id="lwp-tab-galeri" class="lwp-tab-content hidden">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @forelse($images as $img)
                @php $isPdf = str_ends_with(strtolower($img->PATH_GAMBAR), '.pdf'); @endphp
                <div class="group relative bg-white p-3 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all cursor-pointer" onclick="previewFile('{{ $img->URL }}', '{{ $isPdf ? 'pdf' : 'image' }}')">
                    <div class="aspect-square bg-slate-50 rounded-xl overflow-hidden mb-3 flex items-center justify-center">
                        @if($isPdf)
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2v20h10V2H7zm8 18H9V4h6v16zM11 7h2v2h-2V7zm0 4h2v6h-2v-6z"/></svg>
                                <span class="text-[10px] font-black text-slate-400 uppercase">PDF Dokumen</span>
                            </div>
                        @else
                            <img src="{{ $img->URL }}" alt="{{ $img->JENIS_GAMBAR }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @endif
                    </div>
                    <div class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">{{ $img->JENIS_GAMBAR }}</div>
                    <div class="text-[10px] font-bold text-slate-900 truncate">{{ $img->KET_GAMBAR ?: 'Tanpa keterangan' }}</div>
                    <div class="absolute inset-0 z-10 opacity-0 group-hover:opacity-100 bg-nu-green-600/10 rounded-2xl flex items-center justify-center transition-opacity">
                        <span class="bg-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-nu-green-700 shadow-xl">Lihat Preview</span>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-slate-400 text-sm font-medium">Belum ada foto atau dokumen terlampir.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Tab: Riwayat -->
        <div id="lwp-tab-riwayat" class="lwp-tab-content hidden">
            <div class="max-w-xl mx-auto py-4">
                @forelse($history as $h)
                    <div class="relative pl-8 pb-10 border-l-2 border-slate-100 last:pb-0">
                        <!-- Dot -->
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-4 border-white shadow-sm 
                            @if($h->fase == 1) bg-nu-green-600 @elseif($h->fase == 2) bg-nu-gold-500 @else bg-red-500 @endif">
                        </div>
                        
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                        @if($h->fase == 1) bg-nu-green-50 text-nu-green-700 @elseif($h->fase == 2) bg-nu-gold-50 text-nu-gold-700 @else bg-red-50 text-red-700 @endif">
                                        @if($h->fase == 1) Entri Baru @elseif($h->fase == 2) Perubahan Data @else Penghapusan @endif
                                    </span>
                                </div>
                                <div class="text-[10px] font-black text-slate-300">{{ date('d M Y, H:i', strtotime($h->tgl_entri)) }}</div>
                            </div>
                            <div class="text-xs font-bold text-slate-900 mb-1">{{ $h->NAMA_USER }}</div>
                            <div class="text-[10px] text-slate-400 font-medium">Melakukan tindakan pada sistem SIWAKNU.</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-slate-400 text-sm">Tidak ada riwayat aktivitas ditemukan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Footer -->
    <div class="px-8 py-6 bg-white border-t border-slate-50 shrink-0 flex justify-end gap-3">
        <button onclick="previewFile('{{ route('lwp.wakaf.pdf', $item->ID_WAKAF) }}', 'pdf')" class="px-6 py-3 bg-blue-50 text-blue-600 rounded-xl font-black text-[11px] uppercase tracking-widest hover:bg-blue-100 transition-all flex items-center gap-2 mr-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Cetak PDF
        </button>
        
        @if(!isset($readOnly) || !$readOnly)
            @if($item->ISOK == 0)
            <button onclick="verifikasiWaqf({{ $item->ID_WAKAF }}, this)" class="px-8 py-3 bg-nu-gold-600 text-white rounded-xl font-black text-[11px] uppercase tracking-widest shadow-xl shadow-nu-gold-100 hover:bg-nu-gold-700 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Verifikasi Data
            </button>
            @endif
        @endif
        <button onclick="closeLwpModal()" class="px-8 py-3 bg-slate-100 text-slate-600 rounded-xl font-black text-[11px] uppercase tracking-widest hover:bg-slate-200 transition-all">Tutup</button>
        @if(!isset($readOnly) || !$readOnly)
        <button onclick="editWaqf({{ $item->ID_WAKAF }})" class="px-8 py-3 bg-nu-green-600 text-white rounded-xl font-black text-[11px] uppercase tracking-widest shadow-xl shadow-nu-green-100 hover:bg-nu-green-700 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            Edit Data
        </button>
        @endif
    </div>
</div>
