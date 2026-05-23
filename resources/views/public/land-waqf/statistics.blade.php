<x-public-layout>
    @section('title', 'Statistik Wakaf Tanah')

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-20 bg-surface-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 pt-10">
                <h2 class="text-4xl font-black text-surface-900 sm:text-5xl">Statistik Wakaf Tanah</h2>
                <p class="mt-4 text-lg text-surface-600">Ringkasan data aset wakaf tanah yang dikelola oleh LWPNU di semua tingkatan kepengurusan NU.</p>
                
                <div class="mt-6 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('land-waqf.catalog') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-nu-green-600 text-white rounded-xl font-bold text-sm hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-100 group">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                        Buka Katalog Wakaf Tanah
                    </a>
                    <a href="{{ route('land-waqf.register.form') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-nu-gold-500 text-nu-green-900 rounded-xl font-bold text-sm hover:bg-nu-gold-600 transition-all shadow-lg shadow-nu-gold-100 group">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                        Daftarkan Aset Anda
                    </a>
                    <a href="{{ route('land-waqf.check-status.form') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-nu-green-50 text-nu-green-700 rounded-xl font-bold text-sm hover:bg-nu-green-100 transition-all group">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Cek Status Pendaftaran
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="bg-white p-10 rounded-[2.5rem] shadow-card border border-surface-100 flex items-center gap-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-nu-green-500/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform"></div>
                    <div class="w-20 h-20 rounded-3xl bg-nu-green-50 flex items-center justify-center text-nu-green-600 shrink-0">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-surface-400 uppercase tracking-[0.2em] mb-1">Total Aset Wakaf</div>
                        <div class="text-5xl font-black text-surface-900 leading-none">{{ number_format($totalCount, 0, ',', '.') }}</div>
                        <div class="text-sm text-surface-500 mt-2 font-medium">Titik aset terdata</div>
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[2.5rem] shadow-card border border-surface-100 flex items-center gap-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-nu-gold-500/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform"></div>
                    <div class="w-20 h-20 rounded-3xl bg-nu-gold-50 flex items-center justify-center text-nu-gold-600 shrink-0">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-surface-400 uppercase tracking-[0.2em] mb-1">Total Luas Tanah</div>
                        <div class="text-5xl font-black text-surface-900 leading-none">{{ number_format($totalArea, 0, ',', '.') }} <span class="text-2xl font-bold text-surface-400 ml-1">m<sup>2</sup></span></div>
                        <div class="text-sm text-surface-500 mt-2 font-medium">lahan aset terdata</div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <!-- Usage Composition Pie Chart -->
                <div class="bg-white p-8 rounded-[2rem] shadow-card border border-surface-100">
                    <h3 class="text-lg font-black text-surface-900 mb-8 flex items-center gap-3">
                        <span class="w-2 h-8 bg-nu-green-600 rounded-full"></span>
                        Komposisi Penggunaan
                    </h3>
                    <div class="relative h-80">
                        <canvas id="usagePieChart"></canvas>
                    </div>
                </div>

                <!-- Provincial Bar Chart -->
                <div class="bg-white p-8 rounded-[2rem] shadow-card border border-surface-100">
                    <h3 class="text-lg font-black text-surface-900 mb-8 flex items-center gap-3">
                        <span class="w-2 h-8 bg-nu-gold-500 rounded-full"></span>
                        Top 10 Provinsi Terbanyak
                    </h3>
                    <div class="relative h-80">
                        <canvas id="provinceBarChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Detailed Tables Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Usage Composition List -->
                <div class="bg-white p-8 rounded-[2rem] shadow-card border border-surface-100 lg:col-span-1">
                    <h3 class="text-lg font-black text-surface-900 mb-8 flex items-center gap-3">
                        <span class="w-2 h-8 bg-nu-green-600 rounded-full"></span>
                        Data Penggunaan
                    </h3>
                    <div class="space-y-6">
                        @foreach($usageComposition as $usage)
                            @php $pct = ($usage->JUMLAH / $totalCount) * 100; @endphp
                            <div class="group">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-bold text-surface-700 uppercase tracking-tight">{{ $usage->PENGGUNAAN }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-surface-400">({{ number_format($pct, 1) }}%)</span>
                                        <span class="text-sm font-black text-nu-green-600">{{ number_format($usage->JUMLAH, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="w-full h-3 bg-surface-50 rounded-full overflow-hidden">
                                    <div class="h-full bg-nu-green-600 rounded-full transition-all group-hover:bg-nu-green-500" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Regional Breakdown -->
                <div class="bg-white p-8 rounded-[2rem] shadow-card border border-surface-100 lg:col-span-2">
                    <h3 class="text-lg font-black text-surface-900 mb-8 flex items-center gap-3">
                        <span class="w-2 h-8 bg-nu-gold-500 rounded-full"></span>
                        Sebaran Wilayah (Detail)
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-[10px] font-black text-surface-400 uppercase tracking-widest">
                                    <th class="px-6 py-4">Wilayah</th>
                                    <th class="px-6 py-4 text-center">Jumlah Aset</th>
                                    <th class="px-6 py-4 text-right">Total Luas (m<sup>2</sup>)</th>
                                    <th class="px-6 py-4 w-10"></th>
                                </tr>
                            </thead>
                            <tbody id="breakdown-body">
                                @foreach($provinceBreakdown as $prov)
                                    <tr class="group cursor-pointer hover:bg-surface-50 transition-colors" data-code="{{ $prov->kode }}" data-level="2">
                                        <td class="px-6 py-5 rounded-l-2xl border-y border-l border-surface-100 bg-white group-hover:bg-surface-50">
                                            <div class="flex items-center gap-3">
                                                <div class="w-2 h-2 rounded-full bg-nu-green-600"></div>
                                                <span class="font-bold text-surface-900">{{ $prov->label }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 border-y border-surface-100 bg-white group-hover:bg-surface-50 text-center font-black text-surface-700">
                                            {{ number_format($prov->count, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-5 border-y border-surface-100 bg-white group-hover:bg-surface-50 text-right font-black text-nu-green-600">
                                            {{ number_format($prov->area, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-5 rounded-r-2xl border-y border-r border-surface-100 bg-white group-hover:bg-surface-50 text-surface-400">
                                            <svg class="w-5 h-5 transition-transform group-aria-expanded:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner Template -->
    <template id="loading-row">
        <tr class="loading-placeholder">
            <td colspan="4" class="px-6 py-4">
                <div class="flex items-center justify-center py-4">
                    <div class="w-8 h-1 bg-surface-100 rounded-full overflow-hidden relative">
                        <div class="absolute inset-0 bg-nu-green-600 animate-loading-bar"></div>
                    </div>
                </div>
            </td>
        </tr>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ── Pie Chart Logic ──────────────────────────────────────────
            const ctxPie = document.getElementById('usagePieChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($usageComposition->pluck('PENGGUNAAN')) !!},
                    datasets: [{
                        data: {!! json_encode($usageComposition->pluck('JUMLAH')) !!},
                        backgroundColor: [
                            '#006400', '#D4AF37', '#228B22', '#B8860B', '#32CD32', '#DAA520', '#6B8E23', '#8B4513'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { weight: 'bold', size: 10 } } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.raw;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // ── Bar Chart Logic ──────────────────────────────────────────
            const ctxBar = document.getElementById('provinceBarChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topProvinces->pluck('label')) !!},
                    datasets: [{
                        label: 'Jumlah Aset',
                        data: {!! json_encode($topProvinces->pluck('count')) !!},
                        backgroundColor: '#D4AF37',
                        borderRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { display: false } },
                        x: { grid: { display: false }, ticks: { font: { size: 9, weight: 'bold' } } }
                    }
                }
            });

            // ── Drill-down Table Logic ──────────────────────────────────
            const body = document.getElementById('breakdown-body');
            body.addEventListener('click', function(e) {
                const row = e.target.closest('tr');
                if (!row || !row.dataset.code || row.classList.contains('loading-placeholder')) return;
                
                const code = row.dataset.code;
                const level = parseInt(row.dataset.level);
                if (level >= 13) return;
                
                const isExpanded = row.getAttribute('aria-expanded') === 'true';
                if (isExpanded) {
                    row.setAttribute('aria-expanded', 'false');
                    row.querySelector('svg').style.transform = 'rotate(0deg)';
                    
                    // Remove ALL descendants (rows starting with the same prefix)
                    const descendants = body.querySelectorAll('tr');
                    descendants.forEach(d => {
                        if (d.dataset.code && d.dataset.code.startsWith(code) && d !== row) {
                            d.remove();
                        }
                    });
                    return;
                }

                // Show Loading
                row.setAttribute('aria-expanded', 'true');
                row.querySelector('svg').style.transform = 'rotate(90deg)';
                const loader = document.getElementById('loading-row').content.cloneNode(true).querySelector('tr');
                loader.dataset.parent = code;
                row.after(loader);

                fetch(`{{ route('land-waqf.breakdown') }}?parent_code=${code}&level=${level}`)
                    .then(response => response.json())
                    .then(data => {
                        loader.remove(); // Remove loading row
                        
                        data.forEach(item => {
                            const newRow = document.createElement('tr');
                            newRow.className = 'group cursor-pointer hover:bg-surface-50 transition-colors animate-fade-in-down';
                            newRow.dataset.code = item.kode;
                            newRow.dataset.level = item.kode.length;
                            newRow.dataset.parent = code;
                            newRow.innerHTML = `
                                <td class="px-6 py-4 rounded-l-2xl border-y border-l border-surface-100 bg-white group-hover:bg-surface-50">
                                    <div class="flex items-center gap-3" style="padding-left: ${level === 2 ? '2rem' : (level === 5 ? '4rem' : '6rem')}">
                                        <div class="w-1.5 h-1.5 rounded-full bg-surface-300"></div>
                                        <span class="text-sm font-bold text-surface-600">${item.label}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 border-y border-surface-100 bg-white group-hover:bg-surface-50 text-center font-bold text-surface-500 text-sm">${parseInt(item.count).toLocaleString('id-ID')}</td>
                                <td class="px-6 py-4 border-y border-surface-100 bg-white group-hover:bg-surface-50 text-right font-bold text-surface-500 text-sm">${parseFloat(item.area).toLocaleString('id-ID')}</td>
                                <td class="px-6 py-4 rounded-r-2xl border-y border-r border-surface-100 bg-white group-hover:bg-surface-50 text-surface-300">
                                    ${item.kode.length < 13 ? '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>' : ''}
                                </td>
                            `;
                            row.after(newRow);
                        });
                    });
            });
        });
    </script>

    <style>
        @keyframes fade-in-down {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fade-in-down 0.3s ease-out; }

        @keyframes loading-bar {
            0% { left: -100%; width: 50%; }
            100% { left: 100%; width: 50%; }
        }
        .animate-loading-bar { animation: loading-bar 1.5s infinite linear; }
    </style>
</x-public-layout>
