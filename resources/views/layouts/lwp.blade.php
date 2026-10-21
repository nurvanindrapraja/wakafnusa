<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Pengurus LWP</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-nu.svg') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-nu.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nu-green': { 50: '#f0fdf4', 100: '#dcfce7', 500: '#22c55e', 600: '#006400', 700: '#004d00' },
                        'nu-gold': { 50: '#fffbeb', 500: '#D4AF37', 600: '#B8860B' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Outfit', sans-serif; } 
        .table-container::-webkit-scrollbar { height: 8px; }
        .table-container::-webkit-scrollbar-track { background: #f1f5f9; }
        .table-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .sticky-header { backdrop-filter: blur(8px); }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col md:flex-row overflow-x-hidden">
    <!-- Global Logout Progress Bar -->
    <div id="logoutProgressBar" class="fixed top-0 left-0 right-0 h-1 bg-red-500 transition-all duration-300 transform scale-x-0 origin-left z-[100]"></div>
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-100 transform -translate-x-full md:translate-x-0 md:static md:inset-auto transition-transform duration-300 ease-in-out flex flex-col shrink-0">
        <div class="p-8 text-center border-b border-slate-50 flex items-center justify-center relative">
            <img src="{{ asset('images/logo-nu.svg') }}" alt="Logo NU" class="w-16 mx-auto">
            <button id="closeSidebar" class="md:hidden absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-nu-green-600 p-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="px-8 py-4 border-b border-slate-50">
            <div class="text-xs font-black text-nu-green-700 uppercase tracking-widest text-center">Pengurus LWP</div>
            <div class="text-[10px] text-slate-400 font-bold mt-0.5 uppercase text-center tracking-tighter">Wakaf Nusa</div>
        </div>

        <nav class="p-6 flex-1 space-y-2 overflow-y-auto">
            @php $currentRoute = Route::currentRouteName(); @endphp
            <a href="{{ route('lwp.dashboard') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-sm transition-all group {{ $currentRoute == 'lwp.dashboard' ? 'bg-nu-green-50 text-nu-green-700 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-nu-green-600' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                Dashboard
            </a>
            @if((int)Auth::guard('lwp')->user()->LEVEL === 9)
            <a href="{{ route('lwp.peta-sebaran') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-sm transition-all group {{ $currentRoute == 'lwp.peta-sebaran' ? 'bg-nu-green-50 text-nu-green-700 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-nu-green-600' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.485V5.132a2 2 0 011.096-1.78l5.447-2.724a2 2 0 011.816 0l5.447 2.724A2 2 0 0118 5.132v10.353a2 2 0 01-1.096 1.78l-5.447 2.724a2 2 0 01-1.816 0z" /></svg>
                Peta Sebaran
            </a>
            @endif
            @if((int)Auth::guard('lwp')->user()->LEVEL !== 1)
            <a href="{{ route('lwp.manajemen') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-sm transition-all group {{ $currentRoute == 'lwp.manajemen' ? 'bg-nu-green-50 text-nu-green-700 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-nu-green-600' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                Manajemen Wakaf
            </a>
            <a href="{{ route('lwp.pengajuan.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-sm transition-all group {{ str_contains($currentRoute ?? '', 'lwp.pengajuan') ? 'bg-nu-green-50 text-nu-green-700 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-nu-green-600' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Pengajuan Wakif
            </a>
            <a href="{{ route('lwp.laporan') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-sm transition-all group {{ $currentRoute == 'lwp.laporan' ? 'bg-nu-green-50 text-nu-green-700 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-nu-green-600' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Laporan
            </a>
            @endif
            @if(in_array((int)Auth::guard('lwp')->user()->LEVEL, [9, 91, 31, 3]))
            <a href="{{ route('lwp.users.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-sm transition-all group {{ str_contains($currentRoute ?? '', 'lwp.users') ? 'bg-nu-green-50 text-nu-green-700 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-nu-green-600' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Data Pengguna
            </a>
            @endif
            @if(in_array((int)Auth::guard('lwp')->user()->LEVEL, [9, 91]))
            <a href="{{ route('lwp.penggunaan.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-sm transition-all group {{ str_contains($currentRoute ?? '', 'lwp.penggunaan') ? 'bg-nu-green-50 text-nu-green-700 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-nu-green-600' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                Master Penggunaan
            </a>
            @endif
            @if(in_array((int)Auth::guard('lwp')->user()->LEVEL, [9, 91, 31, 3]))
            <a href="{{ route('lwp.trash') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-sm transition-all group {{ $currentRoute == 'lwp.trash' ? 'bg-nu-green-50 text-nu-green-700 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-nu-green-600' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                Data Sampah
            </a>
            @endif
            <a href="{{ route('lwp.profil') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-sm transition-all group {{ $currentRoute == 'lwp.profil' ? 'bg-nu-green-50 text-nu-green-700 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-nu-green-600' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                Profil Saya
            </a>
        </nav>

        <div class="p-6 border-t border-slate-50">
            <form action="{{ route('lwp.logout') }}" method="POST" onsubmit="showLogoutProgress(this)">
                @csrf
                <button type="submit" class="w-full flex items-center gap-4 px-6 py-4 text-red-400 hover:bg-red-50 rounded-2xl font-bold text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Backdrop Overlay (Mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 z-40 bg-slate-900/50 hidden md:hidden"></div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-screen overflow-y-auto">
        <!-- Sticky Header -->
        <header class="md:sticky top-0 z-30 bg-white/80 border-b border-slate-100 px-6 md:px-12 py-5 sticky-header">
            <div class="flex items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <button id="openSidebar" class="md:hidden text-slate-600 hover:text-nu-green-600 p-2 -ml-2">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <button id="toggleDesktopSidebar" class="hidden md:block text-slate-400 hover:text-nu-green-600 p-2 -ml-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <div>
                        <h2 class="text-xl font-black text-slate-900 leading-tight">@yield('page_title')</h2>
                        <div class="hidden md:block text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">@yield('page_subtitle')</div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block text-right">
                        <div class="text-xs font-black text-slate-900">{{ Auth::guard('lwp')->user()->NAMA_USER }}</div>
                        <div class="text-[9px] font-bold text-nu-green-600 uppercase tracking-tighter">Online</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-nu-green-100 flex items-center justify-center text-nu-green-600 font-black text-sm uppercase">
                        {{ substr(Auth::guard('lwp')->user()->NAMA_USER, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Body -->
        <main class="p-6 md:p-12">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const openBtn = document.getElementById('openSidebar');
            const closeBtn = document.getElementById('closeSidebar');
            const toggleDesktop = document.getElementById('toggleDesktopSidebar');
            const mainWrapper = document.getElementById('mainWrapper');

            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }

            if (openBtn) openBtn.addEventListener('click', toggleSidebar);
            if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
            if (overlay) overlay.addEventListener('click', toggleSidebar);

            if (toggleDesktop) {
                toggleDesktop.addEventListener('click', function() {
                    sidebar.classList.toggle('md:hidden');
                });
            }
        });

        function showLogoutProgress(form) {
            const btn = form.querySelector('button[type="submit"]');
            const progressBar = document.getElementById('logoutProgressBar');
            
            btn.disabled = true;
            btn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Keluar...`;
            btn.classList.add('opacity-70');
            
            progressBar.classList.remove('transition-none');
            progressBar.style.transform = 'scaleX(0)';
            setTimeout(() => { progressBar.style.transform = 'scaleX(0.4)'; }, 50);
            setTimeout(() => { progressBar.style.transform = 'scaleX(0.75)'; }, 350);
        }
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
