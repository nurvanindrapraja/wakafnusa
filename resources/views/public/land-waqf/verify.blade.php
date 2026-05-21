<x-public-layout>
    @section('title', 'Verifikasi Data Wakaf')

    <div class="bg-slate-50 min-h-screen py-16">
        <div class="container mx-auto px-4 max-w-3xl">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-nu-green-100 text-nu-green-600 rounded-full mb-4 shadow-xl shadow-nu-green-100/50">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h1 class="text-3xl font-black text-slate-900 mb-2">Data Aset Terverifikasi</h1>
                <p class="text-slate-500 font-medium">Dokumen Bukti Pendaftaran Wakaf ini dinyatakan valid dan terdaftar resmi di sistem Wakaf Nusa (SIWAKNU).</p>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-100 bg-gradient-to-r from-nu-green-50 to-white flex justify-between items-center">
                    <div>
                        <div class="text-xs font-black text-nu-green-600 uppercase tracking-widest mb-1">Nomor Identitas Wakaf</div>
                        <div class="text-2xl font-black text-slate-900">{{ $item->NIA }}</div>
                    </div>
                    <img src="{{ asset('images/logo-nu.svg') }}" alt="Logo NU" class="h-16 opacity-20">
                </div>
                
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Aset Wakaf</div>
                            <div class="text-sm font-bold text-slate-900">{{ strtoupper($item->NAMA ?? '-') }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">No. AIW / Tgl AIW</div>
                            <div class="text-sm font-bold text-slate-900">{{ strtoupper($item->NO_AIW ?? '-') }} / {{ !empty($item->TGL_AIW ?? null) ? date('d M Y', strtotime($item->TGL_AIW)) : '-' }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Wakif</div>
                            <div class="text-sm font-bold text-slate-900">{{ strtoupper($item->WAKIF ?? '-') }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nadzir</div>
                            <div class="text-sm font-bold text-slate-900">{{ strtoupper($item->NADZIR ?? '-') }}</div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lokasi Aset</div>
                        <div class="text-sm font-bold text-slate-900 leading-relaxed">
                            {{ strtoupper($item->ALAMAT ?? '-') }}<br>
                            @if(!empty($item->KEC ?? null)) KEL. {{ strtoupper($item->KEL ?? '') }} KEC. {{ strtoupper($item->KEC) }}<br> @endif
                            @if(!empty($item->KAB ?? null)) KAB. {{ strtoupper($item->KAB) }}<br> @endif
                            @if(!empty($item->PROV ?? null)) {{ strtoupper($item->PROV) }} @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 border-t border-slate-100">
                        <div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Luas</div>
                            <div class="text-sm font-bold text-slate-900">{{ $item->LUAS ?? 0 }} M²</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Tanah</div>
                            <div class="text-sm font-bold text-slate-900">{{ strtoupper($item->JENIS_SW ?? '-') }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Produktif</div>
                            <div class="text-sm font-bold text-slate-900">{{ strtoupper($item->JENIS_SP ?? '-') }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Kriteria</div>
                            <div class="text-sm font-bold text-slate-900">{{ strtoupper($item->KRITERIA ?? '-') }}</div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-slate-50 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-500 font-medium">Data ini disajikan secara *real-time* dari *database* Lembaga Wakaf dan Pertanahan Pengurus Besar Nahdlatul Ulama.</p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('land-waqf.catalog') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-slate-600 rounded-xl font-black text-sm uppercase tracking-widest hover:text-nu-green-600 shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Lihat Katalog Wakaf
                </a>
            </div>

        </div>
    </div>
</x-public-layout>
