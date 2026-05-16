<x-public-layout>
    @section('title', 'Verifikasi E-AIW')

    <section class="pt-32 pb-20 bg-surface-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[40px] shadow-card overflow-hidden border border-surface-100">
                <!-- Success Status -->
                <div class="bg-nu-green-600 p-12 text-center text-white relative overflow-hidden">
                    <div class="absolute inset-0 hero-pattern opacity-20"></div>
                    <div class="relative z-10">
                        <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6 backdrop-blur-sm">
                            <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 2.944a11.955 11.955 0 01-8.618 3.04m0 0a11.955 11.955 0 012.238 6.54c0 2.38.694 4.594 1.882 6.46m0 0A11.956 11.956 0 0112 21.056a11.956 11.956 0 018.5-3.072 11.956 11.956 0 011.882-6.46m-4.332-4.332a11.955 11.955 0 012.238-6.54" /></svg>
                        </div>
                        <h1 class="text-3xl font-black mb-2">E-AIW Terverifikasi</h1>
                        <p class="text-white/80 font-medium uppercase tracking-widest text-xs">Akta Ikrar Wakaf Elektronik Sah</p>
                    </div>
                </div>

                <div class="p-10 space-y-8">
                    <!-- AIW Info -->
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-4">Data Wakif</h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="text-xs text-surface-500 mb-1">Nama Wakif</div>
                                    <div class="text-lg font-black text-nu-green-800">{{ $donation->aiw_data['wakif_name'] ?? $donation->donor_display_name }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-surface-500 mb-1">Alamat</div>
                                    <div class="text-sm font-bold text-surface-900">{{ $donation->aiw_data['wakif_address'] ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-4">Detail Ikrar</h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="text-xs text-surface-500 mb-1">Tanggal Ikrar</div>
                                    <div class="text-sm font-bold text-surface-900">{{ $donation->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                                </div>
                                <div>
                                    <div class="text-xs text-surface-500 mb-1">Status Pembayaran</div>
                                    <div class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $donation->payment_status === 'PAID' ? 'bg-nu-green-100 text-nu-green-600' : 'bg-nu-gold-100 text-nu-gold-600' }}">
                                        {{ $donation->payment_status }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pledge Text -->
                    <div class="bg-surface-50 rounded-3xl p-8 border border-surface-100">
                        <h4 class="text-[10px] font-black text-nu-green-600 uppercase tracking-widest mb-4 italic text-center">Naskah Ikrar</h4>
                        <p class="text-sm text-surface-900 font-medium leading-relaxed text-center italic">
                            "Dengan ini saya menyatakan ikrar wakaf kepada Lembaga Wakaf dan Pertanahan Nahdlatul Ulama (LWPNU) untuk dikelola sesuai ketentuan syariah dan peraturan yang berlaku, demi kemaslahatan umat."
                        </p>
                    </div>

                    <!-- Program Info -->
                    <div class="bg-surface-50 rounded-3xl p-6 border border-surface-100 flex items-center gap-6">
                        <div class="w-20 h-20 rounded-2xl overflow-hidden flex-shrink-0 shadow-sm">
                            @if($donation->program->cover_image)
                                <img src="{{ asset('storage/' . $donation->program->cover_image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-nu flex items-center justify-center text-white/30">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="text-[10px] font-black text-nu-green-600 uppercase tracking-widest mb-1">Peruntukan Wakaf</h4>
                            <div class="text-lg font-black text-surface-900 leading-tight mb-1">{{ $donation->program->title }}</div>
                            <div class="text-sm font-bold text-nu-gold-600">Rp {{ number_format($donation->amount, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="pt-6 text-center">
                        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-nu-green-600 font-black uppercase tracking-widest text-[10px] hover:text-nu-green-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            Kembali ke WAKAF NUSA
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
