<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50/50">
                <th class="py-4 px-6 text-[10px] font-black uppercase text-slate-400 tracking-wider">No</th>
                <th class="py-4 px-6 text-[10px] font-black uppercase text-slate-400 tracking-wider">Kode</th>
                <th class="py-4 px-6 text-[10px] font-black uppercase text-slate-400 tracking-wider">Nama Penggunaan</th>
                <th class="py-4 px-6 text-[10px] font-black uppercase text-slate-400 tracking-wider">Kategori Induk</th>
                <th class="py-4 px-6 text-[10px] font-black uppercase text-slate-400 tracking-wider">Kode Lama</th>
                <th class="py-4 px-6 text-[10px] font-black uppercase text-slate-400 tracking-wider">Ikon</th>
                <th class="py-4 px-6 text-[10px] font-black uppercase text-slate-400 tracking-wider text-center">Tanah Terkait</th>
                <th class="py-4 px-6 text-[10px] font-black uppercase text-slate-400 tracking-wider text-center">Status</th>
                <th class="py-4 px-6 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50 text-xs font-semibold text-slate-700">
            @forelse($usages as $index => $item)
                @php
                    $isParent = !str_contains($item->KODE_PENG, '.');
                    $parentCode = $isParent ? null : explode('.', $item->KODE_PENG)[0];
                    $parentCategory = $parentCode ? $parentCategories->firstWhere('KODE_PENG', $parentCode) : null;
                    $relatedWakaf = $wakafCounts[$item->KODE_PENG] ?? 0;
                @endphp
                <tr class="hover:bg-slate-50/60 transition-colors {{ $isParent ? 'bg-nu-green-50/20' : '' }}">
                    <td class="py-4 px-6 text-slate-400 font-mono text-[11px]">
                        {{ $usages->firstItem() + $index }}
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-black font-mono {{ $isParent ? 'bg-nu-green-100 text-nu-green-800' : 'bg-slate-100 text-slate-700' }}">
                            @if($isParent)
                                <svg class="w-3 h-3 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            @else
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                            @endif
                            {{ $item->KODE_PENG }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <span class="{{ $isParent ? 'font-black text-slate-900 text-sm' : 'font-bold text-slate-800' }}">
                                {{ $item->PENGGUNAAN }}
                            </span>
                            @if($isParent)
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-nu-green-600 text-white">
                                    Induk
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="py-4 px-6 text-slate-500 whitespace-nowrap">
                        @if(!$isParent && $parentCategory)
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">
                                <span class="font-mono font-bold text-slate-500">{{ $parentCategory->KODE_PENG }}</span>
                                {{ $parentCategory->PENGGUNAAN }}
                            </span>
                        @elseif($isParent)
                            <span class="text-slate-400 italic text-[11px]">— Kategori Utama —</span>
                        @else
                            <span class="text-slate-400 text-[11px]">-</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 font-mono text-[11px] text-slate-500">
                        {{ $item->KODE_PENG_OLD ?: '-' }}
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap">
                        @if($item->ICON)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-100 text-[11px] font-mono text-slate-600">
                                <svg class="w-3.5 h-3.5 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ $item->ICON }}
                            </span>
                        @else
                            <span class="text-slate-400 text-[11px]">-</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center whitespace-nowrap">
                        @if($relatedWakaf > 0)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-black bg-blue-50 text-blue-700">
                                <svg class="w-3 h-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ number_format($relatedWakaf, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-slate-400 text-[11px]">0</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center whitespace-nowrap">
                        @if($item->ISDEL == 0)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-nu-green-50 text-nu-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-nu-green-500"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-red-50 text-red-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Sampah
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1.5">
                            @if($item->ISDEL == 0)
                                <button onclick="openEditModal('{{ $item->KODE_PENG }}')" title="Edit Penggunaan" class="p-2 text-slate-400 hover:text-nu-green-600 hover:bg-nu-green-50 rounded-xl transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <button onclick="confirmDeleteUsage('{{ $item->KODE_PENG }}', '{{ addslashes($item->PENGGUNAAN) }}', {{ $relatedWakaf }})" title="Hapus Penggunaan" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            @else
                                <button onclick="restoreUsage('{{ $item->KODE_PENG }}', '{{ addslashes($item->PENGGUNAAN) }}')" title="Pulihkan Penggunaan" class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-nu-green-700 bg-nu-green-50 hover:bg-nu-green-100 rounded-xl transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                    Pulihkan
                                </button>
                                <button onclick="confirmForceDeleteUsage('{{ $item->KODE_PENG }}', '{{ addslashes($item->PENGGUNAAN) }}', {{ $relatedWakaf }})" title="Hapus Permanen" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="py-12 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                            </div>
                            <span class="text-sm font-semibold">Tidak ada data penggunaan yang cocok dengan kriteria pencarian.</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($usages->hasPages())
    <div class="px-8 py-5 border-t border-slate-50 flex items-center justify-between">
        <div class="text-xs font-semibold text-slate-400">
            Menampilkan {{ $usages->firstItem() ?? 0 }} - {{ $usages->lastItem() ?? 0 }} dari {{ $usages->total() }} data
        </div>
        <div>
            {{ $usages->links() }}
        </div>
    </div>
@endif
