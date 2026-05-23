<x-guest-layout title="Lupa Password - Wakaf Nusa">
    <div class="mb-6 text-sm text-surface-600 leading-relaxed font-medium">
        Lupa password Anda? Tidak masalah. Cukup masukkan alamat email Anda di bawah ini dan kami akan mengirimkan tautan pemulihan password ke email Anda agar Anda dapat membuat password yang baru.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" onsubmit="showResetProgress(this)">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm font-bold text-surface-400 hover:text-nu-green-600 transition-colors flex items-center gap-1.5 group" href="{{ route('login') }}">
                <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Login
            </a>

            <x-primary-button class="bg-nu-green-700 hover:bg-nu-green-800 focus:bg-nu-green-800 active:bg-nu-green-900 focus:ring-nu-green-500">
                Kirim Tautan Pemulihan
            </x-primary-button>
        </div>
    </form>

    <script>
        function showResetProgress(form) {
            const btn = form.querySelector('button[type="submit"]');
            const progressBar = document.getElementById('authProgressBar');
            
            btn.disabled = true;
            btn.innerHTML = `<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...`;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            
            if (progressBar) {
                progressBar.classList.remove('transition-none');
                progressBar.style.transform = 'scaleX(0)';
                setTimeout(() => { progressBar.style.transform = 'scaleX(0.3)'; }, 50);
                setTimeout(() => { progressBar.style.transform = 'scaleX(0.6)'; }, 200);
                setTimeout(() => { progressBar.style.transform = 'scaleX(0.85)'; }, 600);
            }
        }
    </script>
</x-guest-layout>
