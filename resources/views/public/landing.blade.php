<x-public-layout>
@section('title', 'Beranda')

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center hero-pattern overflow-hidden" style="background-color: #004000;">
    <!-- Background Motif & Gradients -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-transparent"></div>
    <div class="absolute inset-0 bg-gradient-to-tr from-[#003000]/40 via-transparent to-black/20"></div>
    
    <!-- Decorative Glows -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-nu-green-400/20 rounded-full blur-[120px] animate-pulse-slow"></div>
    <div class="absolute -bottom-24 -left-24 w-[500px] h-[500px] bg-nu-gold-500/10 rounded-full blur-[140px] animate-float"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-40 pb-24">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- Left Content: Text & CTA -->
            <div class="animate-slide-up">
                <div class="inline-flex items-center gap-3 bg-white/5 backdrop-blur-md rounded-full px-5 py-2.5 mb-8 border border-white/10">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-nu-gold-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-nu-gold-500"></span>
                    </span>
                    <span class="text-white font-bold text-xs uppercase tracking-[0.2em]">Lembaga Wakaf & Pertanahan PBNU</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-8">
                    Berwakaf untuk<br>
                    <span class="text-transparent bg-clip-text bg-gradient-gold drop-shadow-sm">Kebaikan Abadi</span>
                </h1>
                
                <p class="text-xl text-white/80 max-w-lg mb-10 leading-relaxed font-medium">
                    Salurkan wakaf uang / melalui uang Anda melalui platform digital resmi Nahdlatul Ulama. 
                    Setiap rupiah adalah investasi akhirat yang transparan dan akuntabel.
                </p>
                
                <div class="flex flex-wrap gap-8">
                    <a href="{{ route('catalog') }}" class="btn-gold text-lg px-10 py-4 inline-flex items-center gap-3 group shadow-gold">
                        <svg class="w-6 h-6 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Tunaikan Wakaf
                    </a>
                    <a href="#cara-kerja" class="group inline-flex items-center gap-3 text-white font-bold px-10 py-4 rounded-2xl border-2 border-white/20 hover:bg-white/5 hover:border-white/40 transition-all">
                        Cara Kerja
                        <svg class="w-5 h-5 group-hover:translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </a>
                </div>
            </div>

            <!-- Right Content: Stats Display -->
            <div class="hidden lg:block relative">
                <div class="absolute inset-0 bg-nu-gold-500/20 rounded-4xl blur-[100px] -z-10"></div>
                <div class="glass rounded-[40px] p-10 border border-white/10 shadow-2xl relative overflow-hidden group">
                    <!-- Glass Shine Effect -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-white/5 to-transparent pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <div class="mb-10 text-center lg:text-left">
                            <div class="inline-block px-5 py-2 rounded-xl bg-white/10 text-white text-[10px] font-black uppercase tracking-widest mb-4 border border-white/20 backdrop-blur-sm">
                                Live Statistics
                            </div>
                            <div class="text-sm text-white/70 font-bold uppercase tracking-widest mb-2">Total Dana Terkumpul</div>
                            <div class="text-5xl lg:text-6xl font-black text-nu-gold-400 tracking-tight" 
                                x-data="counterAnimation({{ $stats['total_collected'] }})" 
                                x-text="'Rp ' + formattedNumber()">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-white/5 backdrop-blur-sm rounded-3xl p-6 border border-white/5 hover:bg-white/10 transition-colors">
                                <div class="text-3xl font-black text-white" x-data="counterAnimation({{ $stats['total_wakif'] }})" x-text="formattedNumber()"></div>
                                <div class="text-[10px] text-white font-black uppercase tracking-widest mt-2">Wakif Terdaftar</div>
                            </div>
                            <div class="bg-white/5 backdrop-blur-sm rounded-3xl p-6 border border-white/5 hover:bg-white/10 transition-colors">
                                <div class="text-3xl font-black text-white" x-data="counterAnimation({{ $stats['total_programs'] }})" x-text="formattedNumber()"></div>
                                <div class="text-[10px] text-white font-black uppercase tracking-widest mt-2">Program Aktif</div>
                            </div>
                        </div>

                        <!-- Trusted Badge -->
                        <div class="mt-10 pt-8 border-t border-white/5 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                                <svg class="w-6 h-6 text-nu-gold-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <div class="text-xs text-white/60 font-medium italic">
                                Diawasi oleh Badan Wakaf Indonesia & PBNU untuk transparansi maksimal.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Bar -->
<section class="relative -mt-16 z-10">
    <div class="max-w-5xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-card-hover grid grid-cols-2 md:grid-cols-4 divide-x divide-surface-100">
            <div class="p-6 text-center">
                <div class="text-2xl md:text-3xl font-bold text-nu-green-600 stat-number" x-data="counterAnimation({{ $stats['total_wakif'] }})" x-text="formattedNumber()"></div>
                <div class="text-sm text-surface-500 mt-1">Wakif Terdaftar</div>
            </div>
            <div class="p-6 text-center">
                <div class="text-2xl md:text-3xl font-bold text-nu-green-600 stat-number" x-data="counterAnimation({{ $stats['total_programs'] }})" x-text="formattedNumber()"></div>
                <div class="text-sm text-surface-500 mt-1">Program Aktif</div>
            </div>
            <div class="p-6 text-center">
                <div class="text-lg md:text-xl font-bold text-nu-green-600 stat-number" x-data="counterAnimation({{ $stats['total_collected'] / 1000000 }})" x-text="'Rp ' + formattedNumber() + ' Jt'"></div>
                <div class="text-sm text-surface-500 mt-1">Dana Terkumpul</div>
            </div>
            <div class="p-6 text-center">
                <div class="text-2xl md:text-3xl font-bold text-nu-green-600 stat-number" x-data="counterAnimation({{ $stats['total_beneficiaries'] }})" x-text="formattedNumber()"></div>
                <div class="text-sm text-surface-500 mt-1">Penerima Manfaat</div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section id="cara-kerja" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-nu-gold-500 font-semibold text-sm uppercase tracking-wider">Mudah & Cepat</span>
            <h2 class="text-3xl md:text-4xl font-bold text-surface-900 mt-2">Cara Berwakaf di WAKAF NUSA</h2>
            <p class="text-surface-500 mt-4 max-w-2xl mx-auto">Hanya 3 langkah mudah untuk menunaikan wakaf uang Anda secara digital</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @php $steps = [
                ['icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'title' => 'Pilih Program', 'desc' => 'Jelajahi katalog program wakaf dan pilih yang sesuai dengan niat Anda.'],
                ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Isi Data & e-AIW', 'desc' => 'Lengkapi data diri dan tanda tangan elektronik Akta Ikrar Wakaf.'],
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Bayar & Selesai', 'desc' => 'Bayar via Virtual Account atau QRIS, terima bukti wakaf otomatis.'],
            ] @endphp
            @foreach ($steps as $i => $step)
            <div class="relative text-center p-8 rounded-2xl bg-surface-50 card-hover group">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-nu flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}" /></svg>
                </div>
                <div class="absolute top-4 right-4 w-8 h-8 rounded-full bg-nu-gold-500/10 text-nu-gold-600 flex items-center justify-center font-bold text-sm">{{ $i + 1 }}</div>
                <h3 class="text-lg font-bold text-surface-900 mb-2">{{ $step['title'] }}</h3>
                <p class="text-surface-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Programs -->
<section class="py-24 bg-surface-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-nu-gold-500 font-semibold text-sm uppercase tracking-wider">Program Wakaf</span>
                <h2 class="text-3xl md:text-4xl font-bold text-surface-900 mt-2">Program Unggulan</h2>
            </div>
            <a href="{{ route('catalog') }}" class="hidden md:inline-flex items-center gap-2 text-nu-green-600 font-semibold hover:gap-3 transition-all">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </a>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($featuredPrograms as $program)
            <a href="{{ route('program.show', $program->slug) }}" class="bg-white rounded-2xl overflow-hidden shadow-card card-hover group block">
                <div class="relative h-48 bg-gradient-nu overflow-hidden">
                    @if ($program->cover_image)
                        <img src="{{ asset('storage/' . $program->cover_image) }}" alt="{{ $program->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-lg bg-white/95 backdrop-blur-sm text-surface-900 text-[10px] font-black uppercase tracking-wider shadow-md">
                            {{ $program->category }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-surface-900 mb-1 line-clamp-2 group-hover:text-nu-green-600 transition-colors">{{ $program->title }}</h3>
                    <div class="flex items-center gap-1.5 text-[10px] font-bold text-nu-green-600 uppercase tracking-wider mb-3">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        {{ $program->pelaksana->name }}
                    </div>
                    <p class="text-sm text-surface-500 mb-4 line-clamp-2">{{ $program->description }}</p>
                    <div class="mb-3">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-semibold text-nu-green-600">{{ $program->formatted_collected }}</span>
                            <span class="text-surface-400">{{ $program->progress_percentage }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-bar-fill" style="width: {{ $program->progress_percentage }}%"></div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-xs text-surface-400">
                        <span>Target: {{ $program->formatted_target }}</span>
                        <span>{{ $program->days_remaining }} hari lagi</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="text-center mt-10 md:hidden">
            <a href="{{ route('catalog') }}" class="btn-primary inline-flex items-center gap-2">Lihat Semua Program</a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 hero-pattern relative overflow-hidden" style="background-color: #004000;">
    <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-transparent"></div>
    <div class="absolute top-10 left-10 w-64 h-64 bg-nu-gold-500/10 rounded-full blur-3xl"></div>
    <div class="relative max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Mulai Berwakaf Hari Ini</h2>
        <p class="text-white/70 text-lg mb-8 max-w-2xl mx-auto">Setiap rupiah wakaf Anda adalah investasi akhirat yang pahalanya terus mengalir. Bersama WAKAF NUSA, berwakaf jadi mudah dan transparan.</p>
        <a href="{{ route('register') }}" class="btn-gold text-lg px-10 py-4 inline-flex items-center gap-3">
            Daftar & Berwakaf Sekarang
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
        </a>
    </div>
</section>
</x-public-layout>
