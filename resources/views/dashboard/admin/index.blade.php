<x-dashboard-layout>
    @section('title', 'Admin Dashboard')
    @section('page-title', 'Pusat Kontrol Nazhir')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div>
                    <div class="text-xs text-surface-500 font-medium">Total Wakif</div>
                    <div class="text-2xl font-bold text-surface-900">{{ $stats['total_wakif'] }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-nu-green-50 flex items-center justify-center text-nu-green-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <div class="text-xs text-surface-500 font-medium">Dana Terkumpul</div>
                    <div class="text-xl font-bold text-surface-900">Rp {{ number_format($stats['total_funds'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <div class="text-xs text-surface-500 font-medium">Butuh PKS/Publish</div>
                    <div class="text-2xl font-bold text-surface-900">{{ $stats['pending_approval'] }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </div>
                <div>
                    <div class="text-xs text-surface-500 font-medium">Program Berjalan</div>
                    <div class="text-2xl font-bold text-surface-900">{{ $stats['active_programs'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-surface-100 flex items-center justify-between">
                    <h3 class="font-bold text-surface-800">Donasi Terakhir</h3>
                    <div class="flex gap-2">
                        <span class="badge badge-nu-green text-[10px]">Total Terfilter: {{ $recentDonations->total() }}</span>
                    </div>
                </div>
                
                <!-- Donation Filters -->
                <div class="p-4 bg-surface-50 border-b border-surface-100">
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="md:col-span-1">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="w-full rounded-lg border-surface-200 text-[11px] focus:ring-nu-green-600 focus:border-nu-green-600" 
                                placeholder="Cari Wakif atau Program...">
                        </div>
                        <div>
                            <select name="amount_range" class="w-full rounded-lg border-surface-200 text-[11px] focus:ring-nu-green-600 focus:border-nu-green-600">
                                <option value="">Semua Jumlah</option>
                                <option value="lt100k" {{ request('amount_range') == 'lt100k' ? 'selected' : '' }}> < 100 Ribu</option>
                                <option value="1k_1m" {{ request('amount_range') == '1k_1m' ? 'selected' : '' }}>1 Ribu sd < 1 Juta</option>
                                <option value="1m_5m" {{ request('amount_range') == '1m_5m' ? 'selected' : '' }}>1 Juta sd < 5 Juta</option>
                                <option value="5m_10m" {{ request('amount_range') == '5m_10m' ? 'selected' : '' }}>5 Juta sd < 10 Juta</option>
                                <option value="10m_50m" {{ request('amount_range') == '10m_50m' ? 'selected' : '' }}>10 Juta sd < 50 Juta</option>
                                <option value="50m_100m" {{ request('amount_range') == '50m_100m' ? 'selected' : '' }}>50 Juta sd 100 Juta</option>
                                <option value="gt100m" {{ request('amount_range') == 'gt100m' ? 'selected' : '' }}>> 100 Juta</option>
                            </select>
                        </div>
                        <div class="flex gap-1">
                            <button type="submit" class="flex-1 bg-nu-green-600 text-white text-[10px] font-bold py-2 rounded-lg hover:bg-nu-green-700 transition-all">
                                Filter
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="px-3 bg-white border border-surface-200 text-surface-500 text-[10px] font-bold py-2 rounded-lg hover:bg-surface-50 transition-all flex items-center justify-center">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            </a>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-surface-50 text-surface-500 text-[10px] uppercase font-bold">
                            <tr>
                                <th class="px-6 py-3">Wakif</th>
                                <th class="px-6 py-3">Program</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-100">
                            @forelse ($recentDonations as $donation)
                                <tr class="hover:bg-surface-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-surface-900">{{ $donation->donor_display_name }}</div>
                                        <div class="text-[9px] text-surface-400 font-medium">{{ ($donation->paid_at ?? $donation->created_at)->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-[11px] text-surface-600 line-clamp-1 max-w-[150px]">{{ $donation->program->title }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-nu-green-600">Rp {{ number_format($donation->amount, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4" x-data="{ 
                                         preview(url, title) {
                                             $dispatch('open-doc-preview', { url: url, title: title });
                                         }
                                     }">
                                         <div class="flex items-center justify-center gap-2">
                                             @if($donation->payment_status === 'PAID')
                                                 <button type="button" @click="preview('{{ route('wakaf.aiw.download', $donation->id) }}?stream=1', 'Preview Akta Ikrar Wakaf')"
                                                     class="p-1.5 rounded-lg bg-surface-50 text-surface-500 hover:text-nu-green-600 hover:bg-nu-green-50 transition-all" title="Pratinjau AIW">
                                                     <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                 </button>
                                                 @if($donation->amount >= 1000000 && $donation->certificates->count())
                                                     <button type="button" @click="preview('{{ route('wakaf.certificate.download', $donation->certificates->first()->id) }}?stream=1', 'Preview Sertifikat Wakaf')"
                                                         class="p-1.5 rounded-lg bg-nu-gold-50 text-nu-gold-600 hover:bg-nu-gold-100 transition-all" title="Pratinjau Sertifikat">
                                                         <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                     </button>
                                                 @endif
                                             @else
                                                 <span class="text-[9px] font-bold text-surface-300 uppercase tracking-wider">Unpaid</span>
                                             @endif
                                         </div>
                                     </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-surface-400 italic">Data donasi tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($recentDonations->hasPages())
                    <div class="px-6 py-3 border-t border-surface-100 bg-surface-50">
                        {{ $recentDonations->links() }}
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-surface-900 p-6 rounded-2xl text-white">
                <h3 class="font-bold mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('admin.programs.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-colors border border-white/10">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-500 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <span class="text-sm font-medium">Review Program Baru</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-colors border border-white/10">
                        <div class="w-8 h-8 rounded-lg bg-nu-green-500/20 text-nu-green-500 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <span class="text-sm font-medium">Validasi Pencairan</span>
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
                <h3 class="font-bold text-surface-800 mb-6 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                    Komposisi Program
                </h3>
                <div class="relative h-64">
                    <canvas id="programChart"></canvas>
                </div>
                <div class="mt-6 space-y-2">
                    @php
                        $totalPrograms = $programComposition->sum();
                    @endphp
                    @foreach($programComposition as $category => $total)
                        @php
                            $percentage = $totalPrograms > 0 ? round(($total / $totalPrograms) * 100, 1) : 0;
                        @endphp
                        <div class="flex items-center justify-between text-[10px]">
                            <div class="flex items-center gap-2">
                                <span class="text-surface-500 font-medium">{{ $category }}</span>
                                <span class="text-nu-green-600 font-black">({{ $percentage }}%)</span>
                            </div>
                            <span class="font-black text-surface-900">{{ $total }} Program</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100">
                <h3 class="font-bold text-surface-800 mb-4 text-sm">Status Sistem</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-surface-500">Koneksi Bank (Mock)</span>
                        <span class="px-2 py-0.5 rounded-full bg-green-50 text-green-600 font-bold uppercase tracking-wider">Online</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-surface-500">E-Sign Service</span>
                        <span class="px-2 py-0.5 rounded-full bg-green-50 text-green-600 font-bold uppercase tracking-wider">Ready</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('programChart').getContext('2d');
        const data = {!! json_encode($programComposition->values()) !!};
        const total = data.reduce((a, b) => a + b, 0);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($programComposition->keys()) !!},
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#004000', '#b48c00', '#006400', '#d4af37', '#2e7d32', '#f9a825',
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 12,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                const val = context.raw;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return `${context.label}: ${val} (${pct}%)`;
                            }
                        }
                    }
                },
                cutout: '75%'
            }
        });
    </script>
    @endpush
</x-dashboard-layout>
