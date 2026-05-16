<x-dashboard-layout>
    @section('title', 'Dashboard Pelaksana')
    @section('page-title', 'Dashboard Pelaksana')

    @section('sidebar-nav')
        @include('dashboard.pelaksana.partials.nav')
    @endsection

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <div>
                    <div class="text-sm text-surface-500 font-medium">Total Program</div>
                    <div class="text-2xl font-bold text-surface-900">{{ $stats['total_programs'] }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <div class="text-sm text-surface-500 font-medium">Program Aktif</div>
                    <div class="text-2xl font-bold text-surface-900">{{ $stats['active_programs'] }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <div class="text-sm text-surface-500 font-medium">Menunggu Review</div>
                    <div class="text-2xl font-bold text-surface-900">{{ $stats['pending_review'] }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-nu-green-50 flex items-center justify-center text-nu-green-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <div class="text-sm text-surface-500 font-medium">Total Terkumpul</div>
                    <div class="text-xl font-bold text-surface-900">Rp {{ number_format($stats['total_collected'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Programs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-surface-100 flex items-center justify-between">
                    <h3 class="font-bold text-surface-800">Program Terbaru</h3>
                    <a href="{{ route('pelaksana.programs.index') }}" class="text-sm text-nu-green-600 hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-surface-50 text-surface-500 text-xs uppercase">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Program</th>
                                <th class="px-6 py-3 font-semibold">Status</th>
                                <th class="px-6 py-3 font-semibold">Progress</th>
                                <th class="px-6 py-3 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-100">
                            @forelse ($programs as $program)
                                <tr class="hover:bg-surface-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-surface-900 text-sm">{{ $program->title }}</div>
                                        <div class="text-xs text-surface-400">{{ $program->category }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
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
                                        <div class="flex items-center gap-3">
                                            <div class="flex-1 h-1.5 bg-surface-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-nu-green-600 rounded-full" style="width: {{ $program->progress_percentage }}%"></div>
                                            </div>
                                            <span class="text-xs font-semibold text-surface-600">{{ $program->progress_percentage }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('pelaksana.programs.show', $program) }}" class="text-surface-600 hover:text-nu-green-600 font-semibold text-xs">Detail</a>
                                            @if(in_array($program->status, ['PUBLISHED', 'FUNDED', 'IMPLEMENTING']))
                                                <a href="{{ route('pelaksana.reports.create', $program) }}" class="text-nu-green-600 hover:text-nu-green-700 font-bold text-xs">Laporan</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-surface-500 italic">Belum ada program yang diusulkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-gradient-nu p-6 rounded-2xl shadow-card text-white">
                <h3 class="font-bold text-lg mb-2">Usulkan Program</h3>
                <p class="text-white/80 text-sm mb-6">Ajukan program wakaf baru untuk dikelola oleh LWPNU dan dapatkan pendanaan dari umat.</p>
                <a href="{{ route('pelaksana.programs.create') }}" class="inline-flex items-center justify-center w-full px-6 py-3 bg-white text-nu-green-700 font-bold rounded-xl hover:bg-surface-50 transition-colors">
                    Mulai Pengajuan
                </a>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
                <h3 class="font-bold text-surface-800 mb-4">Informasi Penting</h3>
                <ul class="space-y-3 text-sm text-surface-600">
                    <li class="flex gap-3">
                        <svg class="w-5 h-5 text-nu-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Laporan realisasi wajib diunggah setiap bulan.
                    </li>
                    <li class="flex gap-3">
                        <svg class="w-5 h-5 text-nu-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Pastikan dokumen pendukung lengkap dan jelas.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-dashboard-layout>
