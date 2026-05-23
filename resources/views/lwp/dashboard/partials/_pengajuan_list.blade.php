<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="table-container overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">No. Pengajuan</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Wakif & Aset</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Lokasi</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Luas & Penggunaan</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tindakan</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($items as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <span class="text-xs font-black text-nu-green-700 tracking-wider block">{{ $item->nomor_pengajuan }}</span>
                            <span class="text-[9px] text-slate-400 font-bold block mt-1 uppercase">{{ $item->created_at->format('d M Y H:i') }} WIB</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-xs font-black text-slate-900 group-hover:text-nu-green-700 transition-colors uppercase">{{ $item->nama_aset ?: 'ASET TANAH' }}</div>
                            <div class="text-[10px] text-slate-500 font-bold mt-0.5">Wakif: {{ strtoupper($item->nama_wakif) }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-xs font-bold text-slate-800 line-clamp-1 max-w-[200px]" title="{{ $item->alamat }}">{{ $item->alamat }}</div>
                            <div class="text-[9px] text-slate-400 font-black tracking-wider uppercase mt-1">
                                {{ $item->kelurahan }}, {{ $item->kecamatan }}
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-block px-2.5 py-0.5 bg-slate-100 text-slate-700 text-[10px] font-black uppercase tracking-wider rounded-md">{{ number_format($item->luas, 0, ',', '.') }} M²</span>
                            <div class="text-[9px] text-slate-400 font-bold tracking-wider mt-1 uppercase">{{ $item->penggunaan }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-xs font-black uppercase tracking-wide">
                                @if($item->tindakan_aset === 'WAKAF_NU')
                                    Diwakafkan ke NU
                                @elseif($item->tindakan_aset === 'ASET_NU')
                                    Aset NU
                                @elseif($item->tindakan_aset === 'GANTI_NAZHIR_NU')
                                    Ganti Nazhir ke NU
                                @else
                                    {{ $item->tindakan_aset }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            @if($item->status === 'PENDING')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 text-[9px] font-black uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    PENDING
                                </span>
                            @elseif($item->status === 'VERIFIKASI_LAPANGAN')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-[9px] font-black uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    VERIFIKASI
                                </span>
                            @elseif($item->status === 'SELESAI')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-nu-green-50 text-nu-green-700 text-[9px] font-black uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-nu-green-500"></span>
                                    SELESAI
                                </span>
                            @elseif($item->status === 'DIBATALKAN')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-50 text-red-700 text-[9px] font-black uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    BATAL
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="showDetail({{ $item->id }})" class="p-2 bg-slate-50 text-slate-600 hover:bg-nu-green-50 hover:text-nu-green-600 transition-all rounded-xl shadow-sm" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                <a href="{{ route('land-waqf.register.download', ['id' => $item->id]) }}" class="p-2 bg-slate-50 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all rounded-xl shadow-sm" title="Unduh Tanda Bukti (PDF)">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-8 py-16 text-center text-slate-400 font-bold uppercase tracking-widest text-[10px]">
                            Belum Ada Data Pengajuan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="px-8 py-5 border-t border-slate-50 flex items-center justify-between">
            {{ $items->links('lwp.dashboard.partials.pagination') }}
        </div>
    @endif
</div>
