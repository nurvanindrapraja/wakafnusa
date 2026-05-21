<div class="overflow-x-auto table-container">
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-slate-50/50 border-b border-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                <th class="px-8 py-5 text-center" width="8%">No</th>
                <th class="px-8 py-5 text-left" width="22%">Kode Lokasi</th>
                <th class="px-8 py-5 text-left">Nama Wilayah ({{ $level == 'prov' ? 'Provinsi' : ($level == 'kab' ? 'Kabupaten/Kota' : ($level == 'kec' ? 'Kecamatan' : 'Kelurahan')) }})</th>
                <th class="px-8 py-5 text-center" width="15%">Jumlah Aset</th>
                <th class="px-8 py-5 text-right" width="20%">Total Luas (M²)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
            <tr class="hover:bg-slate-50/30 transition-colors border-b border-slate-50 group">
                <td class="px-8 py-5 text-center font-bold text-slate-400 text-xs">
                    {{ $data->firstItem() + $index }}
                </td>
                <td class="px-8 py-5 text-left">
                    <span class="text-xs font-black text-slate-900 group-hover:text-nu-green-700 transition-colors">{{ $row->KODE_LOKASI }}</span>
                </td>
                <td class="px-8 py-5 text-left">
                    <span class="text-xs font-bold text-slate-700 uppercase group-hover:text-nu-green-700 transition-colors">{{ $row->NAMA_LOKASI }}</span>
                </td>
                <td class="px-8 py-5 text-center">
                    <span class="inline-flex px-3 py-1 bg-nu-green-50 text-nu-green-700 rounded-full text-xs font-black">
                        {{ number_format($row->JML_ASET, 0, ',', '.') }}
                    </span>
                </td>
                <td class="px-8 py-5 text-right">
                    <span class="text-xs font-black text-slate-900">{{ number_format($row->JML_LUAS, 0, ',', '.') }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-8 py-20 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <h4 class="text-sm font-black text-slate-800 mb-1">Tidak Ada Data</h4>
                    <p class="text-slate-400 text-xs font-medium max-w-xs mx-auto">Kami tidak dapat menemukan data rekapitulasi untuk tingkat atau pencarian wilayah ini.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Footer -->
@if($data->hasPages())
<div class="p-8 border-t border-slate-50 laporan-pagination">
    {{ $data->links() }}
</div>
@endif
