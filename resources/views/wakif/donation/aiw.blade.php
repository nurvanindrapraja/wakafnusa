<x-dashboard-layout>
@section('title', 'Akta Ikrar Wakaf')
@section('page-title', 'Akta Ikrar Wakaf (e-AIW)')

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
    <span class="flex items-center gap-1 text-nu-green-600 font-semibold">
        <span class="w-6 h-6 rounded-full bg-nu-green-600 text-white flex items-center justify-center text-xs">2</span>
        e-AIW
    </span>
    <svg class="w-4 h-4 text-surface-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
    <span class="flex items-center gap-1 text-surface-400">
        <span class="w-6 h-6 rounded-full bg-surface-200 text-surface-500 flex items-center justify-center text-xs">3</span>
        Bayar
    </span>
</div>

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-card p-8">
        <!-- Header -->
        <div class="text-center mb-8 pb-6 border-b border-surface-100">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-nu flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </div>
            <h2 class="text-xl font-bold text-surface-900">Akta Ikrar Wakaf Elektronik</h2>
            <p class="text-sm text-surface-500 mt-1">Sesuai Peraturan BWI dan UU No. 41 Tahun 2004</p>
        </div>

        <!-- Program & Amount Info -->
        <div class="bg-surface-50 rounded-xl p-4 mb-6 flex justify-between items-center">
            <div>
                <div class="text-xs text-surface-400">Program Wakaf</div>
                <div class="text-sm font-semibold text-surface-900">{{ $program->title }}</div>
            </div>
            <div class="text-right">
                <div class="text-xs text-surface-400">Nominal</div>
                <div class="text-lg font-bold text-nu-green-600">Rp {{ number_format(session('donation_data.amount'), 0, ',', '.') }}</div>
            </div>
        </div>        <form action="{{ route('wakaf.store') }}" method="POST">
            @csrf
            <div class="space-y-5">
                <!-- Wakif Name -->
                <div>
                    <label class="block text-sm font-semibold text-surface-700 mb-1">Nama Lengkap Wakif <span class="text-red-500">*</span></label>
                    <input type="text" name="wakif_name" value="{{ old('wakif_name', auth()->user()?->name ?? session('donation_data.donor_name')) }}" required class="w-full rounded-xl border-surface-200 text-sm">
                    @error('wakif_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- NIK -->
                <div>
                    <label class="block text-sm font-semibold text-surface-700 mb-1">Nomor Induk Kependudukan (NIK)</label>
                    <input type="text" name="wakif_nik" value="{{ old('wakif_nik', auth()->user()?->nik ?? '') }}" maxlength="16" placeholder="16 digit NIK" class="w-full rounded-xl border-surface-200 text-sm">
                    @error('wakif_nik') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-semibold text-surface-700 mb-1">Alamat Lengkap <span class="text-xs text-surface-400 font-normal ml-1">(Opsional)</span></label>
                    <textarea name="wakif_address" rows="3" class="w-full rounded-xl border-surface-200 text-sm" placeholder="Masukkan alamat lengkap Anda (opsional)">{{ old('wakif_address', auth()->user()?->address ?? '') }}</textarea>
                    @error('wakif_address') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Niat Wakaf -->
                <div>
                    <label class="block text-sm font-semibold text-surface-700 mb-1">Niat/Ikrar Wakaf <span class="text-red-500">*</span></label>
                    <textarea name="niat_wakaf" rows="3" required class="w-full rounded-xl border-surface-200 text-sm" placeholder="Contoh: Saya berwakaf dengan ikhlas untuk kemaslahatan umat...">{{ old('niat_wakaf', 'Dengan ini saya menyatakan ikrar wakaf uang kepada Lembaga Wakaf dan Pertanahan Nahdlatul Ulama (LWPNU) untuk dikelola sesuai ketentuan syariah dan peraturan yang berlaku, demi kemaslahatan umat.') }}</textarea>
                    @error('niat_wakaf') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Agreement -->
                <div class="bg-nu-green-50 border border-nu-green-200 rounded-xl p-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="agreement" value="1" required class="mt-0.5 rounded text-nu-green-600 focus:ring-nu-green-500">
                        <span class="text-sm text-surface-700">
                            Saya menyatakan bahwa data yang diisi adalah benar dan saya bersedia mewakafkan uang sebesar
                            <strong class="text-nu-green-700">Rp {{ number_format(session('donation_data.amount'), 0, ',', '.') }}</strong>
                            untuk program <strong>{{ $program->title }}</strong> sesuai dengan ketentuan UU No. 41 Tahun 2004 tentang Wakaf
                            dan Peraturan Kementerian Agama RI.
                        </span>
                    </label>
                    @error('agreement') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <a href="{{ route('wakaf.create', $program->slug) }}" class="flex-1 text-center py-3.5 border border-surface-300 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50 transition-colors">← Kembali</a>
                <button type="submit" class="flex-1 btn-primary text-center py-3.5 font-bold">Kirim Ikrar & Bayar →</button>
            </div>
        </form>
    </div>
</div>
</x-dashboard-layout>
