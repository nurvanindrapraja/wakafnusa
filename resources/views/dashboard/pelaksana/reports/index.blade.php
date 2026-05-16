<x-dashboard-layout>
    @section('title', 'Laporan Realisasi')
    @section('page-title', 'Laporan Realisasi')

    @section('sidebar-nav')
        @include('dashboard.pelaksana.partials.nav')
    @endsection

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
            <form action="{{ route('pelaksana.reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <select name="status" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach(['SUBMITTED', 'VALIDATED', 'REJECTED'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative">
                    <input type="text" name="program" value="{{ request('program') }}" placeholder="Cari nama program..." 
                           class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                </div>

                <div class="relative">
                    <input type="text" name="title" value="{{ request('title') }}" placeholder="Cari judul laporan..." 
                           class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="flex-1 bg-surface-900 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-black transition-all">Filter</button>
                    <a href="{{ route('pelaksana.reports.index') }}" class="px-4 py-2 border border-surface-200 text-surface-600 rounded-xl text-sm font-bold hover:bg-surface-50 transition-all text-center">Reset</a>
                </div>
            </form>
        </div>

        <!-- Reports Table -->
        <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-50 text-surface-500 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Laporan & Program</th>
                            <th class="px-6 py-4 font-semibold">Periode</th>
                            <th class="px-6 py-4 font-semibold">Tgl Kirim</th>
                            <th class="px-6 py-4 font-semibold">Tgl Verifikasi</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold text-right">Realisasi</th>
                            <th class="px-6 py-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse($reports as $report)
                        <tr class="hover:bg-surface-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-surface-900 text-sm mb-0.5">{{ $report->title }}</div>
                                <div class="text-[10px] text-nu-green-600 font-bold tracking-wider truncate max-w-[200px]">{{ $report->program->title }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-surface-600 font-medium">
                                {{ $report->report_period }}
                            </td>
                            <td class="px-6 py-4 text-[10px] text-surface-500 font-bold whitespace-nowrap">
                                {{ $report->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-[10px] text-surface-500 font-bold whitespace-nowrap">
                                {{ $report->validated_at ? $report->validated_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                    {{ match($report->status) {
                                        'SUBMITTED' => 'bg-amber-100 text-amber-700',
                                        'VALIDATED' => 'bg-nu-green-100 text-nu-green-700',
                                        'REJECTED' => 'bg-red-100 text-red-700',
                                        default => 'bg-surface-100 text-surface-600'
                                    } }}">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="text-sm font-black text-surface-900">Rp {{ number_format($report->realized_amount, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('pelaksana.reports.show', $report) }}" class="p-2 text-surface-400 hover:text-nu-green-600 transition-colors" title="Detail Laporan">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>

                                    @if(!empty($report->evidence_photos))
                                    <button type="button" 
                                        @click="$dispatch('open-doc-preview', { url: '{{ Storage::url($report->evidence_photos[0]) }}', title: 'Foto Bukti: {{ $report->title }}', type: 'image' })"
                                        class="p-2 text-surface-400 hover:text-blue-600 transition-colors" title="Lihat Foto">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </button>
                                    @endif

                                    @if(!empty($report->evidence_docs))
                                    <button type="button" 
                                        @click="$dispatch('open-doc-preview', { url: '{{ Storage::url($report->evidence_docs[0]) }}', title: 'Dokumen Pendukung: {{ $report->title }}', type: 'pdf' })"
                                        class="p-2 text-surface-400 hover:text-amber-600 transition-colors" title="Lihat Dokumen">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-surface-400 italic text-sm">Belum ada laporan realisasi yang dikirim.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($reports->hasPages())
            <div class="px-6 py-4 border-t border-surface-100 bg-surface-50">
                {{ $reports->links() }}
            </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
