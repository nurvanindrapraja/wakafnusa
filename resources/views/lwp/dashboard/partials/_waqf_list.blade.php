<!-- Table View -->
<div id="tableView" class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
    <div class="table-container overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">ID / Lokasi</th>
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Aset / Penggunaan</th>
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Wakif / Nazhir</th>
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Luas</th>
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($items as $item)
                <tr class="transition-colors group {{ $item->ISOK == 0 ? 'bg-amber-50/40 hover:bg-amber-50/60' : 'hover:bg-slate-50/50' }}">
                    <td class="px-8 py-6 text-left">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="text-xs font-black text-nu-green-600">#{{ $item->ID_WAKAF }}</span>
                            @if(!empty($item->LAT) && !empty($item->LNG))
                            <button type="button" onclick="showMapPopup({{ $item->LAT }}, {{ $item->LNG }}, '{{ addslashes($item->PENGGUNAAN) }}')" class="p-0.5 bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all rounded" title="Lihat Peta Lokasi">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            @endif
                            @if(!empty($item->JML_GAMBAR) && $item->JML_GAMBAR > 0)
                            <button type="button" onclick="showImagesPopup({{ $item->ID_WAKAF }}, '{{ addslashes($item->PENGGUNAAN) }}')" class="p-0.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-all rounded" title="Lihat Berkas ({{ $item->JML_GAMBAR }})">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </button>
                            @endif
                        </div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase leading-tight">
                            {{ $item->KEC }}, {{ $item->KAB }}<br>{{ $item->PROV }}
                        </div>
                    </td>
                    <td class="px-8 py-6 text-left">
                        <div class="text-sm font-black text-slate-900 group-hover:text-nu-green-700 transition-colors uppercase">{{ $item->PENGGUNAAN }}</div>
                        <div class="text-[10px] text-slate-400 font-medium truncate max-w-xs mt-1">{{ $item->ALAMAT }}</div>
                    </td>
                    <td class="px-8 py-6 text-left">
                        <div class="text-[11px] font-bold text-slate-900">{{ $item->WAKIF }}</div>
                        <div class="text-[10px] text-slate-400 font-medium mt-1 uppercase">N: {{ $item->NADZIR }}</div>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="text-sm font-black text-slate-900">{{ str_replace(',00', '', number_format($item->LUAS, 2, ',', '.')) }}</div>
                        <div class="text-[9px] font-black text-slate-300 uppercase tracking-widest">METER<sup>2</sup></div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center justify-center gap-1">
                            <button onclick="showDetail({{ $item->ID_WAKAF }})" class="p-2 text-slate-400 hover:text-nu-green-600 hover:bg-nu-green-50 rounded-xl transition-all" title="Detail">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                            <button onclick="editWaqf({{ $item->ID_WAKAF }})" class="p-2 text-slate-400 hover:text-nu-gold-600 hover:bg-nu-gold-50 rounded-xl transition-all" title="Edit">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            <button onclick="confirmDelete({{ $item->ID_WAKAF }})" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Hapus">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center">
                        <p class="text-slate-400">Tidak ada data yang ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Card View (Mobile Friendly) -->
<div id="cardView" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($items as $item)
    <div class="rounded-3xl p-6 shadow-sm border transition-all group {{ $item->ISOK == 0 ? 'bg-amber-50/70 border-amber-200/80 shadow-amber-100/30' : 'bg-white border-slate-100 hover:shadow-md' }}">
        <div class="flex justify-between items-start mb-4">
            <div class="flex items-center gap-1.5">
                <span class="px-3 py-1 bg-nu-green-50 text-nu-green-700 text-[10px] font-black uppercase rounded-full">#{{ $item->ID_WAKAF }}</span>
                @if(!empty($item->LAT) && !empty($item->LNG))
                <button type="button" onclick="showMapPopup({{ $item->LAT }}, {{ $item->LNG }}, '{{ addslashes($item->PENGGUNAAN) }}')" class="p-1 bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all rounded-xl shadow-sm" title="Lihat Peta Lokasi">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
                @endif
                @if(!empty($item->JML_GAMBAR) && $item->JML_GAMBAR > 0)
                <button type="button" onclick="showImagesPopup({{ $item->ID_WAKAF }}, '{{ addslashes($item->PENGGUNAAN) }}')" class="p-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-all rounded-xl shadow-sm" title="Lihat Berkas ({{ $item->JML_GAMBAR }})">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </button>
                @endif
            </div>
            <div class="flex gap-1">
                <button onclick="showDetail({{ $item->ID_WAKAF }})" class="p-2 text-slate-400 hover:text-nu-green-600 transition-colors" title="Lihat Detail"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></button>
                <button onclick="editWaqf({{ $item->ID_WAKAF }})" class="p-2 text-slate-400 hover:text-nu-gold-600 transition-colors" title="Edit"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                <button onclick="confirmDelete({{ $item->ID_WAKAF }})" class="p-2 text-slate-400 hover:text-red-600 transition-colors" title="Hapus"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
            </div>
        </div>
        
        <h4 class="text-base font-black text-slate-900 mb-1 text-left leading-tight group-hover:text-nu-green-700 transition-colors uppercase">{{ $item->PENGGUNAAN }}</h4>
        <div class="text-[9px] font-black text-nu-green-600 uppercase tracking-widest mb-4 flex items-center gap-1.5">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            {{ $item->KEL }}, {{ $item->KEC }}, {{ $item->KAB }}, {{ $item->PROV }}
        </div>
        <p class="text-[10px] text-slate-400 font-bold mb-4 uppercase text-left tracking-tight line-clamp-1 italic">{{ $item->ALAMAT }}</p>
        
        <div class="space-y-3 pt-4 border-t border-slate-50">
            <div class="flex justify-between items-center text-[11px]">
                <span class="text-slate-400 font-bold uppercase tracking-widest">Wakif</span>
                <span class="text-slate-900 font-black">{{ $item->WAKIF }}</span>
            </div>
            <div class="flex justify-between items-center text-[11px]">
                <span class="text-slate-400 font-bold uppercase tracking-widest">Luas Tanah</span>
                <span class="text-nu-green-700 font-black">{{ str_replace(',00', '', number_format($item->LUAS, 2, ',', '.')) }} m<sup>2</sup></span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-10 text-center bg-white rounded-3xl border border-slate-100">
        <p class="text-slate-400">Tidak ada data yang ditemukan.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-8 lwp-ajax-pagination">
    {{ $items->links() }}
</div>
