<x-dashboard-layout>
@section('title', 'Pembayaran')
@section('page-title', 'Instruksi Pembayaran')

@section('sidebar-nav')
    @include($sidebar ?? 'dashboard.wakif.partials.nav')
@endsection

<!-- Breadcrumb Steps -->
<div class="flex items-center gap-2 text-sm mb-8">
    <span class="flex items-center gap-1 text-nu-green-600">
        <span class="w-6 h-6 rounded-full bg-nu-green-600 text-white flex items-center justify-center text-xs">✓</span>
        Nominal
    </span>
    <svg class="w-4 h-4 text-surface-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
    <span class="flex items-center gap-1 text-nu-green-600">
        <span class="w-6 h-6 rounded-full bg-nu-green-600 text-white flex items-center justify-center text-xs">✓</span>
        e-AIW
    </span>
    <svg class="w-4 h-4 text-surface-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
    <span class="flex items-center gap-1 text-nu-green-600 font-semibold">
        <span class="w-6 h-6 rounded-full bg-nu-green-600 text-white flex items-center justify-center text-xs">3</span>
        Bayar
    </span>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-card overflow-hidden">
        <!-- Amount Header -->
        <div class="bg-gradient-nu p-8 text-center text-white">
            <div class="text-sm text-white/70 mb-1">Total Pembayaran</div>
            <div class="text-4xl font-bold">{{ $donation->formatted_total }}</div>
            <div class="text-[10px] text-white/50 mt-1 uppercase tracking-widest font-bold">Waqf: {{ $donation->formatted_amount }} + Infaq: {{ $donation->formatted_service_fee }}</div>
            <div class="text-sm text-white/60 mt-3">{{ $donation->donation_code }}</div>
        </div>

        <div class="p-8">
            @if ($donation->payment_method === 'VIRTUAL_ACCOUNT')
            <!-- VA Payment -->
            <div class="mb-6">
                <div class="text-sm text-surface-500 mb-2">Nomor Virtual Account</div>
                <div class="flex items-center gap-3 bg-surface-50 rounded-xl p-4" x-data="{ copied: false }">
                    <div class="text-2xl font-mono font-bold text-surface-900 tracking-wider flex-1">{{ $donation->payment_code }}</div>
                    <button @click="navigator.clipboard.writeText('{{ $donation->payment_code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="px-4 py-2 rounded-lg bg-nu-green-600 text-white text-sm font-medium hover:bg-nu-green-700 transition-colors">
                        <span x-show="!copied">Salin</span>
                        <span x-show="copied">✓ Tersalin</span>
                    </button>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-surface-700 mb-3">Cara Pembayaran:</h3>
                <ol class="space-y-2 text-sm text-surface-600">
                    <li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-surface-100 flex items-center justify-center text-xs font-bold flex-shrink-0">1</span>Buka aplikasi mobile banking atau ATM</li>
                    <li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-surface-100 flex items-center justify-center text-xs font-bold flex-shrink-0">2</span>Pilih menu Transfer ke Virtual Account</li>
                    <li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-surface-100 flex items-center justify-center text-xs font-bold flex-shrink-0">3</span>Masukkan nomor VA: <strong>{{ $donation->payment_code }}</strong></li>
                    <li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-surface-100 flex items-center justify-center text-xs font-bold flex-shrink-0">4</span>Pastikan nominal sesuai dan konfirmasi pembayaran</li>
                </ol>
            </div>
            @else
            <!-- QRIS Payment -->
            <div class="mb-6 text-center">
                <div class="text-sm text-surface-500 mb-3">Scan kode QRIS di bawah ini</div>
                <div class="inline-block bg-white border-2 border-surface-200 rounded-2xl p-6 mb-3">
                    <div class="w-48 h-48 bg-surface-100 rounded-xl flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto text-surface-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                            <div class="text-xs text-surface-400 mt-2">QR Code Mock</div>
                        </div>
                    </div>
                </div>
                <div class="text-xs text-surface-400">Kode: {{ $donation->payment_code }}</div>
            </div>
            @endif

            <!-- Expiry Warning -->
            @if ($donation->expired_at)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-2 text-amber-700 text-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Bayar sebelum <strong>{{ $donation->expired_at->format('d M Y, H:i') }} WIB</strong></span>
                </div>
            </div>
            @endif

            <!-- Mock Confirm Button -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-2 text-blue-700 text-sm mb-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="font-semibold">Mode Demo</span>
                </div>
                <p class="text-xs text-blue-600 mb-3">Ini adalah mode demo. Klik tombol di bawah untuk simulasi pembayaran berhasil.</p>
                <form action="{{ route('wakaf.confirm', $donation->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full btn-primary text-sm py-2.5 text-center font-bold">
                        ✓ Simulasi Pembayaran Berhasil
                    </button>
                </form>
            </div>

            @auth
                <a href="{{ route('dashboard') }}" class="block text-center text-sm text-surface-500 hover:text-surface-700 transition-colors">
                    Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('catalog') }}" class="block text-center text-sm text-surface-500 hover:text-surface-700 transition-colors">
                    Kembali ke Katalog
                </a>
            @endauth
        </div>
    </div>
</div>
</x-dashboard-layout>
