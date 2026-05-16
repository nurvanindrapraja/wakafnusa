<x-dashboard-layout>
    @section('title', 'Laporan Keuangan')
    @section('page-title', 'Laporan Keuangan')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-card border border-surface-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <svg class="w-20 h-20 text-nu-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Total Dana Masuk</div>
                    <div class="text-2xl font-black text-nu-green-700">Rp {{ number_format($totalIn, 0, ',', '.') }}</div>
                    <div class="mt-4 flex flex-col gap-1">
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="text-surface-500 font-medium italic">Wakaf:</span>
                            <span class="font-bold text-surface-900">Rp {{ number_format($totalWaqf, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="text-surface-500 font-medium italic">Infaq Layanan:</span>
                            <span class="font-bold text-nu-gold-600">Rp {{ number_format($totalServiceFee, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-card border border-surface-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <svg class="w-20 h-20 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Total Dana Keluar</div>
                    <div class="text-2xl font-black text-red-600">Rp {{ number_format($totalOut, 0, ',', '.') }}</div>
                    <div class="mt-4 flex flex-col gap-1">
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="text-surface-500 font-medium italic">Penyaluran:</span>
                            <span class="font-bold text-surface-900">Rp {{ number_format($totalDisbursed, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="text-surface-500 font-medium italic">Pengambilan Infaq:</span>
                            <span class="font-bold text-surface-900">Rp {{ number_format($totalInfaqOut, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-surface-900 p-6 rounded-3xl shadow-xl shadow-surface-200 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Sisa Saldo Kumulatif</div>
                    <div class="text-3xl font-black text-white">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</div>
                    <p class="mt-4 text-[10px] text-surface-400 italic font-medium leading-relaxed">
                        Saldo ini merupakan akumulasi keseluruhan dana wakaf & infaq yang belum disalurkan/digunakan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Filter & Ledger -->
        <div class="bg-white rounded-[2rem] shadow-card border border-surface-100 overflow-hidden">
            <div class="p-8 border-b border-surface-50 bg-surface-50">
                <form action="{{ route('admin.financial-reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 items-end gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1.5 tracking-widest">Cari Deskripsi / Ref</label>
                        <input type="text" name="description" value="{{ request('description') }}" placeholder="Contoh: Nama Wakif, Judul..."
                            class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1.5 tracking-widest">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                            class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1.5 tracking-widest">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                            class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 py-2.5 bg-nu-green-600 text-white text-xs font-bold rounded-xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-100">Filter</button>
                        <a href="{{ route('admin.financial-reports.index') }}" class="px-4 py-2.5 bg-white border border-surface-200 text-surface-500 text-xs font-bold rounded-xl hover:bg-surface-50 transition-all">Reset</a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-50 text-surface-400 text-[10px] uppercase font-bold tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Tgl Transaksi</th>
                            <th class="px-8 py-4">Kategori</th>
                            <th class="px-8 py-4">Deskripsi</th>
                            <th class="px-8 py-4 text-right">Debit (Masuk)</th>
                            <th class="px-8 py-4 text-right">Kredit (Keluar)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse ($paginatedLedger as $item)
                            <tr class="hover:bg-surface-50 transition-colors">
                                <td class="px-8 py-5 text-sm font-medium text-surface-900">
                                    {{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                            {{ match($item['category']) {
                                                'WAKAF' => 'bg-nu-green-100 text-nu-green-700',
                                                'INFAQ' => 'bg-nu-gold-100 text-nu-gold-700',
                                                'PENYALURAN' => 'bg-blue-100 text-blue-700',
                                                'INFAQ_OUT' => 'bg-purple-100 text-purple-700',
                                                default => 'bg-surface-100 text-surface-500'
                                            } }}">
                                            {{ str_replace('_', ' ', $item['category']) }}
                                        </span>
                                        @if($item['is_offline'])
                                            <span class="px-2 py-0.5 rounded-lg bg-surface-900 text-white text-[8px] font-bold uppercase tracking-widest">Offline</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm text-surface-600 leading-relaxed">{{ $item['description'] }}</div>
                                    <div class="text-[10px] text-surface-400 font-bold mt-1 uppercase tracking-tighter">Ref: {{ $item['ref'] }}</div>
                                </td>
                                <td class="px-8 py-5 text-right font-bold text-sm {{ $item['type'] === 'MASUK' ? 'text-nu-green-600' : 'text-surface-300' }}">
                                    {{ $item['type'] === 'MASUK' ? 'Rp ' . number_format($item['amount'], 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-8 py-5 text-right font-bold text-sm {{ $item['type'] === 'KELUAR' ? 'text-red-600' : 'text-surface-300' }}">
                                    {{ $item['type'] === 'KELUAR' ? 'Rp ' . number_format($item['amount'], 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center text-surface-400 italic font-medium">Tidak ada transaksi dalam rentang tanggal ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($paginatedLedger->hasPages())
                <div class="px-8 py-4 bg-surface-50 border-t border-surface-100">
                    {{ $paginatedLedger->links() }}
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
