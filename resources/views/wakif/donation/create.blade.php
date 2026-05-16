<x-dashboard-layout>
@section('title', 'Wakaf — ' . $program->title)
@section('page-title', 'Tunaikan Wakaf')

@section('sidebar-nav')
    @include($sidebar ?? 'dashboard.wakif.partials.nav')
@endsection

<!-- Breadcrumb Steps -->
<div class="flex items-center gap-2 text-sm mb-8">
    <span class="flex items-center gap-1 text-nu-green-600 font-semibold">
        <span class="w-6 h-6 rounded-full bg-nu-green-600 text-white flex items-center justify-center text-xs">1</span>
        Nominal
    </span>
    <svg class="w-4 h-4 text-surface-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
    <span class="flex items-center gap-1 text-surface-400">
        <span class="w-6 h-6 rounded-full bg-surface-200 text-surface-500 flex items-center justify-center text-xs">2</span>
        e-AIW
    </span>
    <svg class="w-4 h-4 text-surface-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
    <span class="flex items-center gap-1 text-surface-400">
        <span class="w-6 h-6 rounded-full bg-surface-200 text-surface-500 flex items-center justify-center text-xs">3</span>
        Bayar
    </span>
</div>

<div class="grid lg:grid-cols-3 gap-8">
    <!-- Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-card p-8">
            <h2 class="text-xl font-bold text-surface-900 mb-6">Pilih Nominal & Metode Pembayaran</h2>

            <form action="{{ route('wakaf.aiw', $program->slug) }}" method="POST" x-data="{ 
                amount: 100000, 
                serviceFee: 1000, 
                custom: false, 
                customService: false,
                formatCurrency(val) {
                    if (!val && val !== 0) return '';
                    let str = val.toString().replace(/\D/g, '');
                    return str.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                },
                parseCurrency(val) {
                    return parseInt(val.toString().replace(/\./g, '')) || 0;
                }
            }">
                @csrf
                <!-- Quick Amount Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-surface-700 mb-3">Nominal Wakaf</label>
                    <div class="grid grid-cols-3 gap-3 mb-3">
                        @foreach ([50000, 100000, 250000, 500000, 1000000, 5000000] as $nominal)
                        <button type="button" @click="amount = {{ $nominal }}; custom = false"
                            :class="amount === {{ $nominal }} && !custom ? 'border-nu-green-600 bg-nu-green-50 text-nu-green-700' : 'border-surface-200 hover:border-surface-300'"
                            class="border-2 rounded-xl py-3 text-sm font-semibold transition-all text-center">
                            Rp {{ number_format($nominal, 0, ',', '.') }}
                        </button>
                        @endforeach
                    </div>
                    <div>
                        <button type="button" @click="custom = true; $nextTick(() => $refs.customInput.focus())"
                            :class="custom ? 'border-nu-green-600 bg-nu-green-50' : 'border-surface-200'"
                            class="w-full border-2 rounded-xl py-3 text-sm font-semibold transition-all text-center mb-2">
                            Nominal Lainnya
                        </button>
                        <div x-show="custom" x-transition class="mt-2">
                            <div class="flex items-center border-2 border-surface-200 rounded-xl overflow-hidden focus-within:border-nu-green-600">
                                <span class="px-4 py-3 bg-surface-50 text-sm font-semibold text-surface-600 border-r border-surface-200">Rp</span>
                                <input type="text" x-ref="customInput" 
                                    :value="formatCurrency(amount)" 
                                    @input="amount = parseCurrency($event.target.value); $event.target.value = formatCurrency(amount)"
                                    class="flex-1 px-4 py-3 text-sm border-0 focus:ring-0" placeholder="Masukkan nominal">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="amount" :value="amount">
                    @error('amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Service Fee / Infaq Platform -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-sm font-semibold text-surface-700 italic">Infaq Layanan / WAKAF NUSA Care</label>
                        <div class="text-[10px] bg-nu-green-100 text-nu-green-700 px-2 py-0.5 rounded font-bold uppercase tracking-wider">Opsional</div>
                    </div>
                    
                    <div class="grid grid-cols-4 gap-3 mb-3">
                        @foreach ([1000, 2000, 5000, 10000] as $infaq)
                        <button type="button" @click="serviceFee = {{ $infaq }}; customService = false"
                            :class="serviceFee === {{ $infaq }} && !customService ? 'border-nu-green-600 bg-nu-green-50 text-nu-green-700' : 'border-surface-200 hover:border-surface-300'"
                            class="border-2 rounded-xl py-2 text-xs font-bold transition-all text-center">
                            Rp {{ number_format($infaq, 0, ',', '.') }}
                        </button>
                        @endforeach
                    </div>

                    <div class="relative">
                        <button type="button" @click="customService = true; $nextTick(() => $refs.customServiceInput.focus())"
                            :class="customService ? 'border-nu-green-600 bg-nu-green-50' : 'border-surface-200'"
                            class="w-full border-2 rounded-xl py-2.5 text-xs font-bold transition-all text-center mb-2">
                            Nominal Infaq Lainnya
                        </button>
                        <div x-show="customService" x-transition class="mt-2">
                            <div class="flex items-center border-2 border-surface-200 rounded-xl overflow-hidden focus-within:border-nu-green-600">
                                <span class="px-3 py-2 bg-surface-50 text-xs font-semibold text-surface-600 border-r border-surface-200">Rp</span>
                                <input type="text" x-ref="customServiceInput" 
                                    :value="formatCurrency(serviceFee)" 
                                    @input="serviceFee = parseCurrency($event.target.value); $event.target.value = formatCurrency(serviceFee)"
                                    class="flex-1 px-3 py-2 text-xs border-0 focus:ring-0" placeholder="Min. Rp 1.000">
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-surface-50 border border-surface-200 rounded-2xl flex gap-3 mt-3">
                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center flex-shrink-0 text-nu-green-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="text-[10px] text-surface-500 leading-relaxed italic">
                            Infaq / biaya layanan digunakan sepenuhnya untuk mendukung keberlangsungan platform **WAKAF NUSA** dalam memfasilitasi pengelolaan wakaf yang aman dan transparan.
                        </div>
                    </div>
                    <input type="hidden" name="service_fee" :value="serviceFee">
                </div>

                <!-- Total Summary -->
                <div class="mb-8 p-6 bg-nu-green-600 rounded-3xl text-white shadow-lg shadow-nu-green-600/20">
                    <div class="flex justify-between items-center mb-3 text-white/70 text-xs font-medium uppercase tracking-widest">
                        <span>Rangkuman Donasi</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                    </div>
                    <div class="space-y-2 mb-4 pt-4 border-t border-white/20">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-white/80">Nominal Wakaf</span>
                            <span class="font-bold" x-text="'Rp ' + Number(amount).toLocaleString('id-ID')"></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-white/80">Infaq Layanan</span>
                            <span class="font-bold" x-text="'Rp ' + Number(serviceFee).toLocaleString('id-ID')"></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-white/20">
                        <span class="text-sm font-bold uppercase tracking-wider">Total Bayar</span>
                        <span class="text-2xl font-black" x-text="'Rp ' + (Number(amount) + Number(serviceFee)).toLocaleString('id-ID')"></span>
                    </div>
                </div>

                <!-- Guest Info (If not logged in) -->
                @guest
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-surface-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="donor_name" required
                            class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600"
                            placeholder="Masukkan nama Anda">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-surface-700 mb-2">WhatsApp / Email</label>
                        <input type="text" name="donor_phone" required
                            class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600"
                            placeholder="0812xxx atau email@anda.com">
                    </div>
                </div>
                @endguest

                <!-- Anonymity Option -->
                <div class="mb-6 p-4 rounded-xl bg-surface-50 border border-surface-100">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_anonymous" value="1" class="rounded text-nu-green-600 focus:ring-nu-green-500">
                        <div class="text-sm">
                            <span class="font-semibold text-surface-800 italic">Sembunyikan nama saya (Hamba Allah)</span>
                            <p class="text-[10px] text-surface-500 mt-0.5">Nama Anda tidak akan ditampilkan di daftar donatur publik.</p>
                        </div>
                    </label>
                </div>

                <!-- Payment Method -->
                <div class="mb-8" x-data="{ method: 'VIRTUAL_ACCOUNT' }">
                    <label class="block text-sm font-semibold text-surface-700 mb-3">Metode Pembayaran</label>
                    <div class="space-y-3">
                        <label @click="method = 'VIRTUAL_ACCOUNT'" :class="method === 'VIRTUAL_ACCOUNT' ? 'border-nu-green-600 bg-nu-green-50' : 'border-surface-200'"
                            class="flex items-center gap-4 border-2 rounded-xl p-4 cursor-pointer transition-all">
                            <input type="radio" name="payment_method" value="VIRTUAL_ACCOUNT" x-model="method" class="text-nu-green-600 focus:ring-nu-green-500">
                            <div class="flex-1">
                                <div class="text-sm font-semibold">Virtual Account</div>
                                <div class="text-xs text-surface-400">Transfer via Bank Syariah Indonesia / Muamalat</div>
                            </div>
                            <div class="flex gap-2">
                                <span class="text-xs bg-surface-100 px-2 py-1 rounded font-medium">BSI</span>
                                <span class="text-xs bg-surface-100 px-2 py-1 rounded font-medium">Muamalat</span>
                            </div>
                        </label>
                        <label @click="method = 'QRIS'" :class="method === 'QRIS' ? 'border-nu-green-600 bg-nu-green-50' : 'border-surface-200'"
                            class="flex items-center gap-4 border-2 rounded-xl p-4 cursor-pointer transition-all">
                            <input type="radio" name="payment_method" value="QRIS" x-model="method" class="text-nu-green-600 focus:ring-nu-green-500">
                            <div class="flex-1">
                                <div class="text-sm font-semibold">QRIS</div>
                                <div class="text-xs text-surface-400">Scan QR dari semua e-wallet & mobile banking</div>
                            </div>
                            <span class="text-xs bg-surface-100 px-2 py-1 rounded font-medium">Semua Bank</span>
                        </label>
                    </div>
                    @error('payment_method') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn-primary w-full text-center py-3.5 text-base font-bold">
                    Lanjutkan ke e-AIW →
                </button>
            </form>
        </div>
    </div>

    <!-- Program Summary Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-card p-6 sticky top-24">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-nu flex items-center justify-center">
                    <svg class="w-6 h-6 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <div class="min-w-0">
                    <span class="badge badge-{{ strtolower($program->category) }} text-xs">{{ $program->category }}</span>
                </div>
            </div>
            <h3 class="font-bold text-surface-900 mb-3">{{ $program->title }}</h3>
            <div class="mb-4">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-nu-green-600 font-semibold">{{ $program->formatted_collected }}</span>
                    <span class="text-surface-400">{{ $program->progress_percentage }}%</span>
                </div>
                <div class="progress-bar"><div class="progress-bar-fill" style="width: {{ $program->progress_percentage }}%"></div></div>
                <div class="text-xs text-surface-400 mt-1">Target: {{ $program->formatted_target }}</div>
            </div>
            <div class="space-y-2 text-sm text-surface-600">
                <div class="flex justify-between"><span class="text-surface-400">Lokasi</span><span>{{ $program->location_city }}</span></div>
                <div class="flex justify-between"><span class="text-surface-400">Sisa Waktu</span><span>{{ $program->days_remaining }} hari</span></div>
                <div class="flex justify-between"><span class="text-surface-400">Penerima</span><span>{{ number_format($program->beneficiary_count) }} orang</span></div>
            </div>
        </div>
    </div>
</div>
</x-dashboard-layout>
