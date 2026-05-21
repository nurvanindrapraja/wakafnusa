@extends('layouts.lwp')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Statistik dan data aset wakaf tanah Nahdlatul Ulama.')

@section('content')
    @if((int)Auth::guard('lwp')->user()->LEVEL === 1)
        <!-- Alert Level 1 Pengurus Baru -->
        <div class="bg-amber-50 border border-amber-200 rounded-[2rem] p-8 mb-8 flex flex-col md:flex-row items-start gap-6 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h4 class="text-lg font-black text-amber-900 mb-2">Akun Anda Baru Terdaftar (Akses Terbatas)</h4>
                <p class="text-sm text-amber-800 leading-relaxed font-medium">
                    Silakan lengkapi data akun Anda di menu <a href="{{ route('lwp.profil') }}" class="underline font-bold hover:text-amber-950">Profil Saya</a> terlebih dahulu. Setelah itu, silakan hubungi pengurus **LWP PWNU / PCNU** sesuai dengan wilayah penugasan Anda agar diberikan akses penuh untuk pengelolaan data aset wakaf.
                </p>
            </div>
        </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <!-- Total Nasional -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-nu-green-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Nasional</div>
            <div class="flex items-end gap-4 mb-2">
                <div class="text-4xl font-black text-slate-900 leading-none">{{ number_format($totalNasional, 0, ',', '.') }} <span class="text-lg text-slate-400 font-medium">Aset</span></div>
            </div>
            <div class="text-sm font-bold text-nu-green-600 mt-2">Luas: {{ number_format($luasNasional, 2, ',', '.') }} m²</div>
        </div>

        <!-- Total Wilayah Spesifik / Top Provinsi -->
        @if($totalSpesifik !== null)
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-nu-gold-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ $namaSpesifik }}</div>
            <div class="flex items-end gap-4 mb-2">
                <div class="text-4xl font-black text-slate-900 leading-none">{{ number_format($totalSpesifik, 0, ',', '.') }} <span class="text-lg text-slate-400 font-medium">Aset</span></div>
            </div>
            <div class="text-sm font-bold text-nu-gold-600 mt-2">Luas: {{ number_format($luasSpesifik, 2, ',', '.') }} m²</div>
        </div>
        @endif
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Pie Chart -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
            <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-3">
                <span class="w-2 h-6 bg-nu-green-600 rounded-full"></span>
                Komposisi Penggunaan
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="penggunaanPieChart"></canvas>
            </div>
        </div>
        <!-- Bar Chart -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
            <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-3">
                <span class="w-2 h-6 bg-nu-gold-600 rounded-full"></span>
                Top 10 Provinsi
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="provinsiBarChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
        <!-- Top 10 Kab/Kota -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="text-lg font-black text-slate-900 flex items-center gap-3">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                    10 Kab/Kota Terbanyak
                </h3>
                <div class="relative w-full md:w-64">
                    <input type="text" id="searchKabupaten" placeholder="Cari Kab/Kota..." 
                        class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-nu-green-500 focus:border-transparent transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div id="kabupatenTableContainer" class="flex-1 relative min-h-[300px]">
                <div class="absolute inset-0 flex items-center justify-center bg-white/80 z-10 hidden" id="tableLoader">
                    <div class="w-8 h-8 border-4 border-nu-green-200 border-t-nu-green-600 rounded-full animate-spin"></div>
                </div>
                <div id="kabupatenTableContent">
                    <!-- Loaded via AJAX -->
                </div>
            </div>
        </div>

        <!-- Penggunaan per Provinsi -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-8 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-900 flex items-center gap-3">
                    <span class="w-2 h-6 bg-purple-600 rounded-full"></span>
                    Penggunaan per Provinsi
                </h3>
            </div>
            <div class="flex-1 p-6 overflow-y-auto max-h-[500px]">
                <div class="space-y-6">
                    @foreach($tablePenggunaanProv as $kodeProv => $prov)
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                            <h4 class="font-black text-slate-800 mb-3 text-sm tracking-wide">{{ $prov['nama_lokasi'] }}</h4>
                            <div class="space-y-2">
                                @foreach($prov['penggunaan'] as $peng)
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-600 font-medium">{{ $peng['nama'] }}</span>
                                        <span class="font-bold text-slate-900 bg-white px-2 py-1 rounded-lg border border-slate-100 shadow-sm">{{ number_format($peng['jumlah'], 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Pie Chart ---
        const pieCtx = document.getElementById('penggunaanPieChart').getContext('2d');
        const pieDataRaw = @json($pieData);
        
        // Calculate total for percentages
        const totalAset = pieDataRaw.reduce((sum, item) => sum + parseInt(item.JUMLAH), 0);

        const pieLabels = pieDataRaw.map(item => {
            const percentage = totalAset > 0 ? ((parseInt(item.JUMLAH) / totalAset) * 100).toFixed(1) : 0;
            return `${item.PENGGUNAAN} (${percentage}%)`;
        });
        const pieValues = pieDataRaw.map(item => item.JUMLAH);
        
        // Bervariatif colors
        const pieColors = [
            '#ef4444', '#f97316', '#f59e0b', '#84cc16', '#22c55e', 
            '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9', '#3b82f6', 
            '#6366f1', '#8b5cf6', '#a855f7', '#d946ef', '#f43f5e'
        ];
        
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieValues,
                    backgroundColor: pieColors.slice(0, pieValues.length),
                    borderWidth: 1,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: { family: "'Inter', sans-serif", size: 10 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                return ` ${label}: ${new Intl.NumberFormat('id-ID').format(value)} Aset`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });

        // --- Bar Chart ---
        const barCtx = document.getElementById('provinsiBarChart').getContext('2d');
        const barDataRaw = @json($barData);
        
        const barLabels = barDataRaw.map(item => item.NAMA_LOKASI);
        const barValues = barDataRaw.map(item => item.JUMLAH);
        
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [{
                    label: 'Jumlah Aset',
                    data: barValues,
                    backgroundColor: '#059669',
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 4], color: '#f1f5f9' } },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });

        // --- AJAX Table Pagination & Search ---
        const tableContainer = document.getElementById('kabupatenTableContent');
        const loader = document.getElementById('tableLoader');
        const searchInput = document.getElementById('searchKabupaten');
        let searchTimeout;

        function loadTable(url) {
            loader.classList.remove('hidden');
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                tableContainer.innerHTML = html;
                loader.classList.add('hidden');
                
                // Re-bind pagination links
                const links = tableContainer.querySelectorAll('.pagination-container a');
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        loadTable(this.href);
                    });
                });
            })
            .catch(error => {
                console.error('Error loading table:', error);
                loader.classList.add('hidden');
            });
        }

        searchInput.addEventListener('keyup', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchVal = e.target.value;
                const url = new URL('{{ route('lwp.dashboard.kabupaten-table') }}');
                if (searchVal) {
                    url.searchParams.append('search', searchVal);
                }
                loadTable(url.toString());
            }, 300);
        });

        // Load initial data
        loadTable('{{ route('lwp.dashboard.kabupaten-table') }}');
    });
</script>
@endsection
