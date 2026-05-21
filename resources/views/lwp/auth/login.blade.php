<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengurus LWP - WAKAF NUSA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nu-green': {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#006400', // Deep NU Green
                            700: '#004d00',
                        },
                        'nu-gold': {
                            50: '#fffbeb',
                            500: '#D4AF37', // Gold
                            600: '#B8860B',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .bg-nu-pattern {
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23006400' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-nu-pattern min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-[2.5rem] shadow-2xl border border-surface-100 overflow-hidden relative">
            <!-- Progress Bar -->
            <div id="loginProgressBar" class="absolute top-0 left-0 right-0 h-1 bg-nu-green-500 transition-all duration-300 transform scale-x-0 origin-left z-20"></div>
            <div class="p-10 text-center bg-gradient-to-b from-nu-green-50/50 to-white">
                <img src="{{ asset('images/logo-nu.svg') }}" 
                    alt="Logo NU" class="w-32 mx-auto mb-6 drop-shadow-lg">
                <h1 class="text-2xl font-black text-slate-900 leading-tight">Pengurus Lembaga Wakaf dan Pertanahan</h1>
                <p class="text-slate-400 text-sm mt-2 font-medium tracking-wide">Portal Pendataan Tanah Wakaf</p>
            </div>

            <div class="p-10 pt-0">
                <form action="{{ route('lwp.login.post') }}" method="POST" class="space-y-6" onsubmit="showLoginProgress(this)">
                    @csrf
                    
                    @if($errors->any())
                        <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-sm font-bold flex items-center gap-3 border border-red-100 animate-shake">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl text-sm font-bold flex items-center gap-3 border border-emerald-100">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>{!! session('success') !!}</div>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="bg-blue-50 text-blue-700 p-4 rounded-2xl text-sm font-bold flex items-center gap-3 border border-blue-100">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>{!! session('info') !!}</div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Username</label>
                        <div class="relative group">
                            <input type="text" name="username" required 
                                class="w-full px-5 py-4 bg-slate-50 border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-nu-green-600 rounded-2xl text-slate-900 font-bold transition-all"
                                placeholder="Masukkan username">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                        <div class="relative group">
                            <input type="password" name="password" required 
                                class="w-full px-5 py-4 bg-slate-50 border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-nu-green-600 rounded-2xl text-slate-900 font-bold transition-all"
                                placeholder="••••••••">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full py-4 bg-nu-green-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-nu-green-700 transition-all shadow-xl shadow-nu-green-100 group">
                        <span id="loginBtnText">Masuk Sekarang</span>
                        <svg id="loginBtnIcon" class="w-4 h-4 inline-block ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </form>

                <div class="mt-6 text-center text-xs font-bold">
                    <span class="text-slate-400">Belum punya akun pengurus?</span>
                    <a href="{{ route('lwp.register') }}" class="text-nu-green-600 hover:text-nu-green-700 transition-colors ml-1">
                        Daftar di sini
                    </a>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('land-waqf.catalog') }}" class="text-xs font-bold text-slate-400 hover:text-nu-green-600 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        Kembali ke Katalog
                    </a>
                </div>
            </div>
        </div>
        
        <p class="mt-8 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
            &copy; 2026 WAKAF NUSA • LWPNU
        </p>
    </div>

    <script>
        function showLoginProgress(form) {
            const btn = form.querySelector('button[type="submit"]');
            const btnText = document.getElementById('loginBtnText');
            const btnIcon = document.getElementById('loginBtnIcon');
            const progressBar = document.getElementById('loginProgressBar');

            btn.disabled = true;
            btnText.innerText = 'Memproses...';
            btnIcon.classList.add('hidden');
            
            progressBar.classList.remove('transition-none');
            progressBar.style.transform = 'scaleX(0)';
            setTimeout(() => {
                progressBar.style.transform = 'scaleX(0.4)';
            }, 50);
            setTimeout(() => {
                progressBar.style.transform = 'scaleX(0.75)';
            }, 350);
            setTimeout(() => {
                progressBar.style.transform = 'scaleX(0.9)';
            }, 1000);
        }
    </script>
</body>
</html>
