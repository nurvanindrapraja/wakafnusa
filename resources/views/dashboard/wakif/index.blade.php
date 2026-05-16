<x-dashboard-layout>
@section('title', 'Dashboard Wakif')
@section('page-title', 'Dashboard Wakif')

@section('sidebar-nav')
    @include('dashboard.wakif.partials.nav')
@endsection

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-card card-hover">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-nu-green-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <div class="text-2xl font-bold text-surface-900">Rp {{ number_format($stats['total_donated'], 0, ',', '.') }}</div>
        <div class="text-sm text-surface-500 mt-1">Total Wakaf Anda</div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-card card-hover">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
            </div>
        </div>
        <div class="text-2xl font-bold text-surface-900">{{ $stats['total_donations'] }}</div>
        <div class="text-sm text-surface-500 mt-1">Transaksi Sukses</div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-card card-hover">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            </div>
        </div>
        <div class="text-2xl font-bold text-surface-900">{{ $stats['programs_supported'] }}</div>
        <div class="text-sm text-surface-500 mt-1">Program Didukung</div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-card card-hover">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <div class="text-2xl font-bold text-surface-900">{{ $stats['pending_payments'] }}</div>
        <div class="text-sm text-surface-500 mt-1">Menunggu Bayar</div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-8">
    <!-- Recent Donations -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-card">
            <div class="p-6 border-b border-surface-100">
                <h2 class="text-lg font-bold text-surface-900">Riwayat Wakaf</h2>
            </div>
            <div class="divide-y divide-surface-50">
                @forelse ($recentDonations as $donation)
                <div class="p-5 flex items-center gap-4 hover:bg-surface-50 transition-colors">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $donation->isPaid() ? 'bg-green-50' : 'bg-amber-50' }}">
                        @if ($donation->isPaid())
                            <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @else
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-surface-900 truncate">{{ $donation->program->title ?? '-' }}</div>
                        <div class="text-xs text-surface-400 mt-0.5">{{ $donation->donation_code }} · {{ $donation->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-surface-900">{{ $donation->formatted_amount }}</div>
                        <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full {{ $donation->isPaid() ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $donation->isPaid() ? 'Lunas' : 'Pending' }}
                        </span>
                    </div>
                    @if (!$donation->isPaid())
                        <a href="{{ route('wakaf.payment', $donation->id) }}" class="text-xs text-nu-green-600 hover:underline font-medium">Bayar</a>
                    @endif
                </div>
                @empty
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-surface-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    <p class="text-sm text-surface-500">Belum ada riwayat wakaf</p>
                    <a href="{{ route('catalog') }}" class="btn-primary inline-block text-sm mt-4 px-6 py-2">Mulai Berwakaf</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Certificates Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-card mb-6">
            <div class="p-6 border-b border-surface-100">
                <h2 class="text-lg font-bold text-surface-900">Sertifikat</h2>
            </div>
            <div class="p-4 space-y-3">
                @forelse ($certificates as $cert)
                <div class="flex items-center gap-3 p-3 bg-surface-50 rounded-xl">
                    <div class="w-10 h-10 rounded-lg bg-nu-gold-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-nu-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-medium text-surface-900 truncate">{{ $cert->certificate_number }}</div>
                        <div class="text-xs text-surface-400">{{ $cert->issued_at?->format('d M Y') }}</div>
                    </div>
                    <a href="{{ route('wakaf.certificate.download', $cert->id) }}" class="text-nu-green-600 hover:text-nu-green-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    </a>
                </div>
                @empty
                <p class="text-sm text-surface-400 text-center py-4">Belum ada sertifikat</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Action -->
        <div class="bg-gradient-nu rounded-2xl p-6 text-white">
            <h3 class="font-bold text-lg mb-2">Wakaf Sekarang</h3>
            <p class="text-sm text-white/70 mb-4">Pilih program wakaf dan tunaikan kebaikan abadi Anda.</p>
            <a href="{{ route('catalog') }}" class="btn-gold text-sm inline-block">Lihat Program</a>
        </div>
    </div>
</div>
</x-dashboard-layout>
