<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative mt-1">
                <x-text-input id="email" name="email" type="email" class="block w-full bg-surface-50 text-surface-500 cursor-not-allowed" :value="old('email', $user->email)" readonly required autocomplete="username" />
                @if($user->hasVerifiedEmail())
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-nu-green-50 text-nu-green-600 border border-nu-green-100">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Email Terverifikasi</span>
                    </div>
                @endif
            </div>
            <p class="mt-1.5 text-[10px] text-surface-400 italic">* Email tidak dapat diubah untuk keamanan akun.</p>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone" :value="__('Nomor WhatsApp / HP')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="Contoh: 08123456789" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="address" :value="__('Alamat Lengkap')" />
            <textarea id="address" name="address" rows="3" class="mt-1 block w-full border-surface-200 focus:border-nu-green-500 focus:ring-nu-green-500 rounded-2xl shadow-sm text-sm" placeholder="Masukkan alamat lengkap lembaga">{{ old('address', $user->address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        @if($user->isPelaksana())
            <div>
                <x-input-label for="institution_short_profile" :value="__('Profil Singkat Lembaga')" />
                <textarea id="institution_short_profile" name="institution_short_profile" rows="4" class="mt-1 block w-full border-surface-200 focus:border-nu-green-500 focus:ring-nu-green-500 rounded-2xl shadow-sm text-sm" placeholder="Jelaskan secara singkat visi, misi, atau sejarah lembaga...">{{ old('institution_short_profile', $user->institution_short_profile) }}</textarea>
                <p class="mt-1.5 text-[10px] text-surface-400 italic">* Profil ini akan ditampilkan di halaman detail program wakaf Anda.</p>
                <x-input-error class="mt-2" :messages="$errors->get('institution_short_profile')" />
            </div>
        @endif

        @if($user->isPelaksana())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 rounded-2xl bg-nu-green-50/50 border border-nu-green-100">
                <div class="md:col-span-2">
                    <h4 class="text-xs font-bold text-nu-green-600 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        Informasi Rekening Lembaga (Penyaluran Dana)
                    </h4>
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="bank_name" :value="__('Nama Bank')" />
                    <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full" :value="old('bank_name', $user->bank_name)" placeholder="Contoh: Bank Syariah Indonesia (BSI)" />
                    <x-input-error class="mt-2" :messages="$errors->get('bank_name')" />
                </div>

                <div>
                    <x-input-label for="bank_account_number" :value="__('Nomor Rekening')" />
                    <x-text-input id="bank_account_number" name="bank_account_number" type="text" class="mt-1 block w-full" :value="old('bank_account_number', $user->bank_account_number)" placeholder="Masukkan nomor rekening" />
                    <x-input-error class="mt-2" :messages="$errors->get('bank_account_number')" />
                </div>

                <div>
                    <x-input-label for="bank_account_name" :value="__('Atas Nama Rekening')" />
                    <x-text-input id="bank_account_name" name="bank_account_name" type="text" class="mt-1 block w-full" :value="old('bank_account_name', $user->bank_account_name)" placeholder="Harus atas nama LEMBAGA" />
                    <p class="mt-1 text-[10px] text-surface-400 italic">* Penting: Rekening harus atas nama lembaga sesuai SK.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('bank_account_name')" />
                </div>

                <div class="md:col-span-2 mt-4 pt-4 border-t border-nu-green-100/50">
                    <h4 class="text-xs font-bold text-surface-400 uppercase tracking-widest mb-4">Dokumen Legalitas Nazhir</h4>
                </div>
                
                <div>
                    <x-input-label for="lsp_certificate" :value="__('Sertifikat LSP-BWI (PDF)')" />
                    <input type="file" id="lsp_certificate" name="lsp_certificate" accept="application/pdf" class="mt-1 block w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-nu-green-50 file:text-nu-green-700 hover:file:bg-nu-green-100 cursor-pointer">
                    @if($user->lsp_certificate && Storage::disk('public')->exists($user->lsp_certificate))
                        <p class="mt-1 text-[10px] text-nu-green-600 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            File sudah terunggah
                        </p>
                    @endif
                    <x-input-error class="mt-2" :messages="$errors->get('lsp_certificate')" />
                </div>

                <div>
                    <x-input-label for="institution_profile_doc" :value="__('Profil Lembaga (PDF)')" />
                    <input type="file" id="institution_profile_doc" name="institution_profile_doc" accept="application/pdf" class="mt-1 block w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-nu-green-50 file:text-nu-green-700 hover:file:bg-nu-green-100 cursor-pointer">
                    @if($user->institution_profile_doc && Storage::disk('public')->exists($user->institution_profile_doc))
                        <p class="mt-1 text-[10px] text-nu-green-600 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            File sudah terunggah
                        </p>
                    @endif
                    <x-input-error class="mt-2" :messages="$errors->get('institution_profile_doc')" />
                </div>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
