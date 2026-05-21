<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse ($items as $item)
        <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-card border border-surface-100 hover:shadow-xl transition-all duration-300 group flex flex-col h-full">
            <div class="p-8 flex-1 flex flex-col">
                <!-- Location Badge -->
                <div class="flex items-start justify-between mb-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-nu-green-50 text-nu-green-700 text-[10px] font-bold uppercase tracking-wider">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        {{ $item->KAB }}, {{ $item->KEC }}
                    </span>
                    <span class="text-[10px] text-surface-400 font-medium">ID: #{{ $item->ID_WAKAF }}</span>
                </div>

                <h3 class="text-xl font-black text-surface-900 mb-6 group-hover:text-nu-green-700 transition-all uppercase tracking-tight text-left leading-tight">
                    {{ $item->PENGGUNAAN }}
                </h3>
                
                <div class="space-y-4 mb-8 text-left flex-1">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 text-nu-green-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[9px] uppercase text-surface-400 font-black tracking-widest mb-0.5">Alamat</div>
                            <div class="text-[11px] text-surface-600 font-bold leading-relaxed line-clamp-2">{{ $item->ALAMAT }}</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-1 text-nu-green-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[9px] uppercase text-surface-400 font-black tracking-widest mb-0.5">Wakif</div>
                            <div class="text-[11px] text-surface-900 font-black truncate">{{ $item->WAKIF }}</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-1 text-nu-green-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[9px] uppercase text-surface-400 font-black tracking-widest mb-0.5">Nazhir</div>
                            <div class="text-[11px] text-surface-600 font-bold truncate">{{ $item->NADZIR }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-surface-50">
                        <div>
                            <div class="text-[8px] uppercase text-surface-400 font-black tracking-widest mb-0.5">No. AIW</div>
                            <div class="text-[10px] text-surface-900 font-black truncate" title="{{ $item->NO_AIW }}">{{ $item->NO_AIW ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-[8px] uppercase text-surface-400 font-black tracking-widest mb-0.5">Luas Tanah</div>
                            <div class="text-[10px] text-surface-900 font-black truncate">{{ number_format($item->LUAS, 0, ',', '.') }} m<sup>2</sup></div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('land-waqf.show', $item->ID_WAKAF) }}" 
                    class="mt-4 flex items-center justify-center gap-2 w-full py-4 bg-nu-green-600 text-white text-xs font-black rounded-2xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-100 uppercase tracking-widest">
                    Lihat Detail
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full py-20 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-surface-100 text-surface-300 mb-4">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <h3 class="text-xl font-bold text-surface-900">Data tidak ditemukan</h3>
            <p class="text-surface-500 mt-2">Coba gunakan kata kunci pencarian atau filter yang lain.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-12 ajax-pagination">
    {{ $items->links() }}
</div>
