<x-dashboard-layout>
@section('title', 'Wakaf Berhasil')
@section('page-title', 'Pembayaran Berhasil')

@section('sidebar-nav')
    @include($sidebar ?? 'dashboard.wakif.partials.nav')
@endsection

<div>
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-card overflow-hidden">
            <!-- Success Header -->
            <div class="bg-gradient-nu p-10 text-center text-white relative overflow-hidden">
                <div class="absolute inset-0 hero-pattern"></div>
                <div class="relative">
                    <div class="w-20 h-20 mx-auto rounded-full bg-white/20 flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-2">Jazakallah Khairan!</h2>
                    <p class="text-white/70">Wakaf Anda telah berhasil ditunaikan</p>
                </div>
            </div>

            <div class="p-8">
                <!-- Transaction Details -->
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between py-3 border-b border-surface-100">
                        <span class="text-sm text-surface-500">Kode Wakaf</span>
                        <span class="text-sm font-semibold text-surface-900">{{ $donation->donation_code }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-surface-100">
                        <span class="text-sm text-surface-500">Program</span>
                        <span class="text-sm font-semibold text-surface-900 text-right max-w-[60%]">{{ $donation->program->title }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-surface-100">
                        <span class="text-sm text-surface-500">Nominal Wakaf</span>
                        <span class="text-sm font-semibold text-surface-900">{{ $donation->formatted_amount }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-surface-100">
                        <span class="text-sm text-surface-500">Infaq Layanan</span>
                        <span class="text-sm font-semibold text-surface-900">{{ $donation->formatted_service_fee }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-surface-100 bg-nu-green-50/50 px-2 rounded-lg">
                        <span class="text-sm font-bold text-nu-green-700 uppercase tracking-wider">Total Donasi</span>
                        <span class="text-lg font-black text-nu-green-600">{{ $donation->formatted_total }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-surface-100">
                        <span class="text-sm text-surface-500">Metode</span>
                        <span class="text-sm font-medium text-surface-900">{{ $donation->payment_method === 'VIRTUAL_ACCOUNT' ? 'Virtual Account' : 'QRIS' }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-surface-100">
                        <span class="text-sm text-surface-500">Tanggal</span>
                        <span class="text-sm text-surface-900">{{ $donation->paid_at?->format('d M Y, H:i') }} WIB</span>
                    </div>
                </div>

                <!-- Certificate & AIW Download -->
                <div class="space-y-3 mb-8">
                    <!-- AIW (Always available after success) -->
                    <div class="bg-surface-50 border border-surface-100 rounded-xl p-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-nu-green-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-bold text-surface-900">Akta Ikrar Wakaf (e-AIW)</div>
                                <div class="text-xs text-surface-500">Dokumen resmi ikrar wakaf Anda</div>
                            </div>
                            <button @click="window.dispatchEvent(new CustomEvent('open-doc-preview', { 
                                detail: { 
                                    url: '{{ route('wakaf.aiw.download', $donation->id) }}?stream=1', 
                                    title: 'Akta Ikrar Wakaf (e-AIW)' 
                                } 
                            }))" class="px-6 py-2.5 bg-nu-green-600 text-white text-sm font-bold rounded-xl hover:bg-nu-green-700 transition-all shadow-md shadow-nu-green-600/20">
                                Unduh AIW
                            </button>
                        </div>
                    </div>
 
                    @if ($donation->amount >= 1000000 && $donation->certificates->count())
                    <div class="bg-nu-gold-50 border border-nu-gold-200 rounded-xl p-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-nu-gold-500 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-bold text-surface-900">Sertifikat Wakaf Uang</div>
                                <div class="text-xs text-surface-500">No: {{ $donation->certificates->first()->certificate_number }}</div>
                            </div>
                            <button @click="window.dispatchEvent(new CustomEvent('open-doc-preview', { 
                                detail: { 
                                    url: '{{ route('wakaf.certificate.download', $donation->certificates->first()->id) }}?stream=1', 
                                    title: 'Sertifikat Wakaf Uang' 
                                } 
                            }))" class="px-6 py-2.5 bg-nu-gold-500 text-white text-sm font-bold rounded-xl hover:bg-nu-gold-600 transition-all shadow-md shadow-nu-gold-500/20">
                                Unduh Sertifikat
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex gap-4">
                    <a href="{{ route('catalog') }}" class="flex-1 btn-primary text-center py-3.5 text-sm font-bold">Wakaf Lagi</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="flex-1 text-center py-3.5 border border-surface-300 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="flex-1 text-center py-3.5 border border-surface-300 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50 transition-colors">Login / Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
</x-dashboard-layout>
