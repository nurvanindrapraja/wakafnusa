<x-dashboard-layout>
    @section('title', 'Dashboard Penilai')
    @section('page-title', 'Dashboard Penilai')

    @section('sidebar-nav')
        @include('dashboard.penilai.partials.nav')
    @endsection

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <div class="text-sm text-surface-500 font-medium mb-0.5">Perlu Review Segera</div>
                <div class="text-2xl font-bold text-surface-900">{{ $stats['pending'] }}</div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-nu-green-50 flex items-center justify-center text-nu-green-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <div class="text-sm text-surface-500 font-medium mb-0.5">Total Telah Direview</div>
                <div class="text-2xl font-bold text-surface-900">{{ $stats['reviewed'] }}</div>
            </div>
        </div>
    </div>

    <!-- Review Queue -->
    <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-surface-100 flex items-center justify-between">
            <h3 class="font-bold text-surface-800">Antrean Review (10 Terakhir)</h3>
            <a href="{{ route('penilai.queue') }}" class="text-sm text-nu-green-600 font-semibold hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-50 text-surface-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Program</th>
                        <th class="px-6 py-3 font-semibold">Pelaksana</th>
                        <th class="px-6 py-3 font-semibold">Target Dana</th>
                        <th class="px-6 py-3 font-semibold">Tanggal Masuk</th>
                        <th class="px-6 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($pendingPrograms as $program)
                        <tr class="hover:bg-surface-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-surface-900 text-sm">{{ $program->title }}</div>
                                <div class="text-xs text-surface-400">{{ $program->category }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-surface-600">{{ $program->pelaksana->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-surface-900">Rp {{ number_format($program->target_amount, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-surface-500">{{ $program->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('penilai.reviews.show', $program) }}" class="inline-flex items-center gap-2 px-4 py-1.5 bg-nu-green-600 text-white text-xs font-bold rounded-lg hover:bg-nu-green-700 transition-all">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-surface-500 italic">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-surface-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Semua usulan telah direview. Kerja bagus!
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- History -->
    <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-surface-100 flex items-center justify-between">
            <h3 class="font-bold text-surface-800">Riwayat Review (10 Terakhir)</h3>
            <a href="{{ route('penilai.history') }}" class="text-sm text-nu-green-600 font-semibold hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-50 text-surface-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Program</th>
                        <th class="px-6 py-3 font-semibold">Keputusan</th>
                        <th class="px-6 py-3 font-semibold">Tanggal Review</th>
                        <th class="px-6 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($reviewedPrograms as $program)
                        <tr class="hover:bg-surface-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-surface-900 text-sm">{{ $program->title }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ match($program->status) {
                                        'PROPOSED' => 'bg-surface-100 text-surface-600',
                                        'REVISION_NEEDED' => 'bg-amber-50 text-amber-600',
                                        'RECOMMENDED' => 'bg-blue-50 text-blue-600',
                                        'PKS_PENDING' => 'bg-indigo-50 text-indigo-600',
                                        'PUBLISHED' => 'bg-nu-green-50 text-nu-green-600',
                                        'FUNDED' => 'bg-nu-gold-50 text-nu-gold-600',
                                        'IMPLEMENTING' => 'bg-purple-50 text-purple-600',
                                        'COMPLETED' => 'bg-surface-900 text-white',
                                        'REJECTED' => 'bg-red-50 text-red-600',
                                        default => 'bg-surface-100 text-surface-600'
                                    } }}">
                                    {{ str_replace('_', ' ', $program->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-surface-500">{{ $program->reviews->where('reviewer_id', auth()->id())->first()->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('penilai.reviews.history', $program) }}" class="text-surface-400 hover:text-nu-green-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-surface-500 italic">Belum ada riwayat review.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
