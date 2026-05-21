@if($deletedWakaf->count() > 0)
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto table-container">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-50">
                    <th class="px-6 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">ID</th>
                    <th class="px-6 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Penggunaan / Lokasi</th>
                    <th class="px-6 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Wakif / Nadzir</th>
                    <th class="px-6 py-4 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest">Luas</th>
                    <th class="px-6 py-4 text-center text-[9px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($deletedWakaf as $item)
                <tr class="hover:bg-red-50/30 transition-colors">
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 bg-red-50 text-red-600 text-[10px] font-black uppercase rounded-full">#{{ $item->ID_WAKAF }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-black text-slate-900 uppercase">{{ $item->PENGGUNAAN }}</div>
                        <div class="text-[10px] text-slate-400 font-medium mt-1">{{ $item->KEL }}, {{ $item->KEC }}, {{ $item->KAB }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-slate-700">{{ $item->WAKIF }}</div>
                        <div class="text-[10px] text-slate-400 font-medium mt-1">N: {{ $item->NADZIR }}</div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="text-sm font-black text-slate-900">{{ str_replace(',00', '', number_format($item->LUAS, 2, ',', '.')) }}</div>
                        <div class="text-[9px] font-black text-slate-300 uppercase tracking-widest">METER<sup>2</sup></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button onclick="showTrashDetail({{ $item->ID_WAKAF }})" class="p-2 text-slate-400 hover:text-nu-green-600 hover:bg-nu-green-50 rounded-xl transition-all" title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                            <button onclick="restoreWakaf({{ $item->ID_WAKAF }})" class="p-2 text-nu-green-600 hover:bg-nu-green-50 rounded-xl transition-all" title="Pulihkan">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            </button>
                            <button onclick="permanentDeleteWakaf({{ $item->ID_WAKAF }})" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Hapus Permanen">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6 trash-ajax-pagination" data-container="trashWakafContainer">
    {{ $deletedWakaf->appends(['tab' => 'wakaf', 'search_wakaf' => request('search_wakaf')])->links() }}
</div>
@else
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-16 text-center">
    <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
    </div>
    <h3 class="text-lg font-black text-slate-900 mb-2">Tempat Sampah Kosong</h3>
    <p class="text-sm text-slate-400 font-medium">Tidak ada data aset wakaf yang dihapus.</p>
</div>
@endif
