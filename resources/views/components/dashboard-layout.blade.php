<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — WAKAF NUSA</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon_wakaf_nusa.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-surface-50" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-surface-900 text-white transform transition-transform duration-300 lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <!-- Logo -->
            <div class="h-16 flex items-center gap-3 px-6 border-b border-surface-800">
                <img src="{{ asset('images/logo_wakaf_nusa.png') }}" alt="Wakaf Nusa Logo" class="h-8 w-auto">
            </div>

            <!-- User Info -->
            <div class="px-6 py-4 border-b border-surface-800">
                @auth
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-nu-green-600 flex items-center justify-center text-sm font-bold uppercase">{{ substr(auth()->user()->name, 0, 2) }}</div>
                        <div class="min-w-0">
                            <div class="text-sm font-semibold truncate">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-surface-400">{{ auth()->user()->role }}</div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-700 flex items-center justify-center text-sm font-bold">
                            <svg class="w-5 h-5 text-surface-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-semibold">Guest</div>
                            <div class="text-xs text-surface-400">Mode Wakaf Langsung</div>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Navigation -->
            <nav class="px-4 py-4 space-y-1">
                @yield('sidebar-nav')
            </nav>

            <!-- Bottom -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-surface-800">
                <a href="/" class="flex items-center gap-2 px-3 py-2 text-sm text-surface-400 hover:text-white rounded-lg hover:bg-surface-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Beranda
                </a>
                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-surface-400 hover:text-red-400 rounded-lg hover:bg-surface-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Keluar
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-surface-400 hover:text-white rounded-lg hover:bg-surface-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Masuk / Daftar
                </a>
                @endauth
            </div>
        </aside>

        <!-- Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden" x-transition></div>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <!-- Top Bar -->
            <header class="h-16 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-20 shadow-sm" style="background-color: #f0fdf4; border-bottom: 1px solid #bbf7d0;">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-surface-600 hover:bg-surface-100">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <h1 class="text-lg font-semibold text-surface-800">@yield('page-title', 'Dashboard')</h1>
                <div class="flex items-center gap-3">
                    <a href="{{ route('catalog') }}" class="text-sm text-nu-green-600 hover:underline hidden sm:inline">Lihat Program</a>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 lg:p-8">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Global Document Preview Modal -->
    <div x-data="{ 
            show: false, 
            url: '', 
            title: '',
            type: 'pdf',
            init() {
                window.addEventListener('open-doc-preview', (e) => {
                    this.url = e.detail.url;
                    this.title = e.detail.title;
                    this.type = e.detail.type || 'pdf';
                    this.show = true;
                });
            }
        }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-surface-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-white rounded-[32px] shadow-2xl w-full max-w-5xl h-[90vh] flex flex-col overflow-hidden border border-surface-100">
            <!-- Modal Header -->
            <div class="px-8 py-4 border-b border-surface-100 flex items-center justify-between bg-surface-50">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-nu-green-100 flex items-center justify-center text-nu-green-600">
                        <svg x-show="type === 'pdf'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <svg x-show="type === 'image'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-surface-900" x-text="title"></h4>
                        <p class="text-[10px] text-surface-500 font-medium uppercase tracking-widest" x-text="type === 'pdf' ? 'Pratinjau Dokumen Resmi WAKAF NUSA' : 'Pratinjau Foto Bukti Realisasi'"></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a :href="url" download class="px-4 py-2 bg-nu-green-600 text-white text-xs font-bold rounded-xl hover:bg-nu-green-700 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Unduh File
                    </a>
                    <button @click="show = false" class="p-2 hover:bg-surface-100 rounded-xl transition-colors text-surface-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="flex-1 bg-surface-100 relative overflow-auto flex items-center justify-center p-8">
                <template x-if="type === 'pdf'">
                    <iframe :src="url" class="w-full h-full border-none relative z-10 rounded-xl shadow-inner bg-white"></iframe>
                </template>
                <template x-if="type === 'image'">
                    <img :src="url" class="max-w-full max-h-full object-contain rounded-xl shadow-2xl border-4 border-white">
                </template>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
