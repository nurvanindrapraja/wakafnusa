<x-guest-layout title="Login Wakaf Nusa">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" onsubmit="showLoginProgress(this)">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-nu-green-600 shadow-sm focus:ring-nu-green-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ingatkan saya</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-nu-green-500" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif

            <x-primary-button class="ms-3 bg-nu-green-700 hover:bg-nu-green-800 focus:bg-nu-green-800 active:bg-nu-green-900 focus:ring-nu-green-500">
                Masuk
            </x-primary-button>
        </div>
    </form>

    <script>
        function showLoginProgress(form) {
            const btn = form.querySelector('button[type="submit"]');
            const progressBar = document.getElementById('authProgressBar');
            
            btn.disabled = true;
            btn.innerHTML = `<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Masuk...`;
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
