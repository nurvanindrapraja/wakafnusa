<nav class="fixed top-0 w-full z-50 transition-all duration-300 shadow-md" style="background-color: #004000;"
    x-data="{ scrolled: false, mobileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <a href="/" class="flex items-center gap-3">
                <img src="{{ asset('images/logo_wakaf_nusa.png') }}" alt="Wakaf Nusa Logo" class="h-12 w-auto">
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="/" class="text-sm font-medium transition-colors text-white/90 hover:text-white">Beranda</a>
                <a href="{{ route('catalog') }}"
                    class="text-sm font-medium transition-colors text-white/90 hover:text-white">Program Wakaf</a>
                <a href="{{ route('about') }}"
                    class="text-sm font-medium transition-colors text-white/90 hover:text-white">Tentang</a>
                <a href="{{ route('team') }}"
                    class="text-sm font-medium transition-colors text-white/90 hover:text-white">Anggota Tim</a>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="btn-primary text-sm px-5 py-2.5 border border-white/20">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium transition-colors text-white/90 hover:text-white">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-gold text-sm px-5 py-2.5">Daftar Sekarang</a>
                @endauth
            </div>
            <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg"
                :class="scrolled ? 'text-surface-700' : 'text-white'">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div x-show="mobileOpen" x-transition class="md:hidden pb-4">
            <div class="bg-white rounded-2xl shadow-lg p-4 space-y-2">
                <a href="/" class="block px-4 py-2 text-sm text-surface-700 hover:bg-nu-green-50 rounded-lg">Beranda</a>
                <a href="{{ route('catalog') }}"
                    class="block px-4 py-2 text-sm text-surface-700 hover:bg-nu-green-50 rounded-lg">Program Wakaf</a>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="block px-4 py-2 text-sm font-semibold text-nu-green-600">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-surface-700">Masuk</a>
                    <a href="{{ route('register') }}" class="block btn-gold text-sm text-center mt-2">Daftar Sekarang</a>
                @endauth
            </div>
        </div>
    </div>
</nav>