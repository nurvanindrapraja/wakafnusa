<div class="overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
            <tr>
                <th scope="col" class="px-6 py-4 font-bold">No</th>
                <th scope="col" class="px-6 py-4 font-bold">Kabupaten/Kota</th>
                <th scope="col" class="px-6 py-4 font-bold text-right">Jumlah Aset</th>
                <th scope="col" class="px-6 py-4 font-bold text-right">Luas (m²)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($topKabupaten as $index => $row)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-500">{{ $topKabupaten->firstItem() + $index }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $row->NAMA_LOKASI ?? 'Tidak Diketahui' }}</td>
                    <td class="px-6 py-4 font-bold text-nu-green-600 text-right">{{ number_format($row->total_aset, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-slate-600 text-right">{{ number_format($row->total_luas, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada data tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 px-6 pb-6 pagination-container">
    {{ $topKabupaten->links('lwp.dashboard.partials.pagination') }}
</div>
