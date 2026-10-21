<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50/50">
                <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider w-16">No</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider">Kode Lokasi</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider">Nama Lokasi</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider">Tingkat</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider">Wilayah Induk</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider text-center">Aset Wakaf</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right w-32">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($locations as $index => $loc)
            <tr class="hover:bg-slate-50/80 transition-colors group" id="row-lokasi-{{ str_replace('.', '_', $loc->kode_lokasi) }}">
                <!-- Nomor -->
                <td class="px-6 py-4 text-xs font-bold text-slate-400">
                    {{ ($locations->currentPage() - 1) * $locations->perPage() + $loop->iteration }}
                </td>

                <!-- Kode Lokasi -->
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-black font-mono tracking-tight bg-slate-100 text-slate-800 border border-slate-200/60">
                            {{ $loc->kode_lokasi }}
                        </span>
                        <button type="button" onclick="copyToClipboard('{{ $loc->kode_lokasi }}')" class="opacity-0 group-hover:opacity-100 text-slate-400 hover:text-nu-green-600 transition-opacity p-1" title="Salin kode">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                    </div>
                </td>

                <!-- Nama Lokasi -->
                <td class="px-6 py-4">
                    <span class="text-sm font-black text-slate-800 block">
                        {{ $loc->nama_lokasi }}
                    </span>
                </td>

                <!-- Tingkat Wilayah -->
                <td class="px-6 py-4">
                    @php
                        $level = $loc->level;
                        $badgeClasses = match($level) {
                            'provinsi' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'kabupaten' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'kecamatan' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'kelurahan' => 'bg-slate-100 text-slate-700 border-slate-200',
                            default => 'bg-purple-50 text-purple-700 border-purple-200',
                        };
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider border {{ $badgeClasses }}">
                        {{ $loc->level_label }}
                    </span>
                </td>

                <!-- Wilayah Induk / Hirarki -->
                <td class="px-6 py-4">
                    <span class="text-xs font-semibold text-slate-500 block max-w-xs truncate" title="{{ $loc->parent_hierarchy }}">
                        {{ $loc->parent_hierarchy }}
                    </span>
                </td>

                <!-- Aset Wakaf Terhubung -->
                <td class="px-6 py-4 text-center">
                    @if($loc->asset_count > 0)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-black bg-nu-green-50 text-nu-green-700 border border-nu-green-200">
                            <svg class="w-3.5 h-3.5 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            {{ number_format($loc->asset_count, 0, ',', '.') }}
                        </span>
                    @else
                        <span class="text-xs font-bold text-slate-300">-</span>
                    @endif
                </td>

                <!-- Aksi -->
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <!-- Edit Button -->
                        <button type="button" onclick="openEditLokasiModal('{{ $loc->kode_lokasi }}')" class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit Lokasi">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>

                        <!-- Delete Button -->
                        <button type="button" onclick="confirmDeleteLokasi('{{ $loc->kode_lokasi }}', '{{ addslashes($loc->nama_lokasi) }}', {{ $loc->asset_count }})" class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus Lokasi">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-16 text-center">
                    <div class="max-w-sm mx-auto flex flex-col items-center">
                        <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <h4 class="text-base font-black text-slate-800 mb-1">Tidak Ada Data Lokasi</h4>
                        <p class="text-xs font-semibold text-slate-400">Tidak ada data lokasi yang sesuai dengan kata kunci atau filter wilayah yang dipilih.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Footer -->
@if($locations->hasPages())
<div class="px-8 py-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="text-xs font-semibold text-slate-500">
        Menampilkan <span class="font-bold text-slate-800">{{ $locations->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800">{{ $locations->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ number_format($locations->total(), 0, ',', '.') }}</span> data lokasi
    </div>
    <div class="lokasi-pagination-wrapper">
        {{ $locations->links() }}
    </div>
</div>
@endif
