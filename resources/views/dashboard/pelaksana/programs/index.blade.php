<x-dashboard-layout>
    @section('title', 'Program Saya')
    @section('page-title', 'Program Saya')

    @section('sidebar-nav')
        @include('dashboard.pelaksana.partials.nav')
    @endsection

    <div class="space-y-6">
        <!-- Financial Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
                <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Total Target Dana</div>
                <div class="text-xl font-black text-surface-900">Rp {{ number_format($stats['total_target'], 0, ',', '.') }}</div>
                <div class="text-[9px] text-surface-400 mt-2 font-medium italic">* Akumulasi target semua program</div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
                <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Total Dana Disalurkan</div>
                <div class="text-xl font-black text-nu-green-600">Rp {{ number_format($stats['total_disbursed'], 0, ',', '.') }}</div>
                <div class="text-[9px] text-surface-400 mt-2 font-medium italic">* Total dana yang sudah diterima</div>
            </div>
            <div class="bg-surface-900 p-6 rounded-2xl shadow-xl shadow-surface-200">
                <div class="text-[10px] font-bold text-surface-300 uppercase tracking-widest mb-1">Total Sisa Dana</div>
                <div class="text-xl font-black text-white">Rp {{ number_format($stats['total_remaining'], 0, ',', '.') }}</div>
                <div class="text-[9px] text-surface-400 mt-2 font-medium italic">* Saldo dana belum tersalurkan</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
            <form action="{{ route('pelaksana.programs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-surface-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama program..." 
                           class="w-full pl-10 rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                </div>

                <div>
                    <select name="status" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach(['PROPOSED', 'RECOMMENDED', 'REVISION_NEEDED', 'REJECTED', 'PUBLISHED', 'FUNDED', 'IMPLEMENTING', 'COMPLETED'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ str_replace('_', ' ', $status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="flex-1 bg-surface-900 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-black transition-all">Filter</button>
                    <a href="{{ route('pelaksana.programs.index') }}" class="px-4 py-2 border border-surface-200 text-surface-600 rounded-xl text-sm font-bold hover:bg-surface-50 transition-all text-center">Reset</a>
                </div>
            </form>
        </div>

        <!-- Program Table -->
        <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-50 text-surface-500 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Nama Program</th>
                            <th class="px-6 py-4 font-semibold">Periode</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Target Dana</th>
                            <th class="px-6 py-4 font-semibold">Dana Disalurkan</th>
                            <th class="px-6 py-4 font-semibold">Sisa Dana</th>
                            <th class="px-6 py-4 font-semibold">Progress</th>
                            <th class="px-6 py-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse($programs as $program)
                        @php
                            $totalDisbursed = $program->disbursements->sum('amount');
                            $remaining = $program->collected_amount - $totalDisbursed;
                        @endphp
                        <tr class="hover:bg-surface-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-surface-900 text-sm mb-0.5">{{ $program->title }}</div>
                                <div class="text-[10px] text-surface-400 uppercase font-bold tracking-wider mb-1">{{ $program->category }}</div>
                                @if($totalDisbursed > 0)
                                    <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-amber-50 text-amber-600 text-[8px] font-bold border border-amber-100 uppercase tracking-tighter">
                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        Wajib Kirim Laporan Realisasi
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-bold text-surface-600">{{ $program->start_date ? $program->start_date->format('d/m/y') : '-' }} - {{ $program->end_date ? $program->end_date->format('d/m/y') : '-' }}</div>
                                <div class="text-[10px] text-surface-400 mt-1 uppercase font-bold">{{ $program->days_remaining }} Hari Tersisa</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                    {{ match($program->status) {
                                        'PROPOSED' => 'bg-amber-100 text-amber-700',
                                        'PUBLISHED' => 'bg-nu-green-100 text-nu-green-700',
                                        'REJECTED' => 'bg-red-100 text-red-700',
                                        'REVISION_NEEDED' => 'bg-amber-50 text-amber-600',
                                        'FUNDED' => 'bg-nu-gold-50 text-nu-gold-600',
                                        'IMPLEMENTING' => 'bg-purple-50 text-purple-600',
                                        'COMPLETED' => 'bg-surface-900 text-white',
                                        default => 'bg-surface-100 text-surface-600'
                                    } }}">
                                    {{ str_replace('_', ' ', $program->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-black text-surface-800">Rp {{ number_format($program->target_amount, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-nu-green-600">Rp {{ number_format($totalDisbursed, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-surface-900">Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-1.5 bg-surface-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-nu-green-600 rounded-full" style="width: {{ $program->progress_percentage }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-surface-600">{{ $program->progress_percentage }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('pelaksana.programs.show', $program) }}" 
                                       class="px-4 py-1.5 bg-surface-100 text-surface-700 text-[11px] font-bold rounded-lg hover:bg-surface-200 transition-all">
                                       Detail
                                    </a>
                                    @if(in_array($program->status, ['PUBLISHED', 'FUNDED', 'IMPLEMENTING']))
                                    <a href="{{ route('pelaksana.reports.create', $program) }}" 
                                       class="px-4 py-1.5 bg-nu-green-600 text-white text-[11px] font-bold rounded-lg hover:bg-nu-green-700 transition-all shadow-sm">
                                       Laporan
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-surface-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <p class="text-surface-400 italic text-sm">Tidak ada program yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($programs->hasPages())
            <div class="px-6 py-4 border-t border-surface-100 bg-surface-50">
                {{ $programs->links() }}
            </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
