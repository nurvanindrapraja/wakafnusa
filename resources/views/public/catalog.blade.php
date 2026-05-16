<x-public-layout>
@section('title', 'Program Wakaf')

<!-- Header -->
<section class="hero-pattern pt-32 pb-20 relative overflow-hidden" style="background-color: #004000;">
    <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4">Program Wakaf</h1>
        <p class="text-white max-w-2xl text-lg opacity-80">Pilih program wakaf yang sesuai dengan niat dan harapan Anda untuk kemaslahatan umat.</p>
    </div>
</section>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters -->
        <form method="GET" action="{{ route('catalog') }}" class="bg-white rounded-2xl shadow-card p-6 mb-10 -mt-16 relative z-10">
            <div class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-surface-600 mb-1">Cari Program</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kata kunci..." class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-600 mb-1">Kategori</label>
                    <select name="category" class="w-full rounded-xl border-surface-200 text-sm">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ ucfirst(strtolower($cat)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-600 mb-1">Urutkan</label>
                    <select name="sort" class="w-full rounded-xl border-surface-200 text-sm">
                        <option value="latest" @selected(request('sort') === 'latest')>Terbaru</option>
                        <option value="popular" @selected(request('sort') === 'popular')>Terpopuler</option>
                        <option value="ending" @selected(request('sort') === 'ending')>Segera Berakhir</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full text-sm py-2.5">Cari</button>
                </div>
            </div>
        </form>

        <!-- Program Grid -->
        @if ($programs->count())
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($programs as $program)
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
                        <div class="progress-bar"><div class="progress-bar-fill" style="width: {{ $program->progress_percentage }}%"></div></div>
                    </div>
                    <div class="flex justify-between items-center text-xs text-surface-400">
                        <span>{{ $program->formatted_target }}</span>
                        <span>{{ $program->days_remaining }} hari lagi</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-10">{{ $programs->withQueryString()->links() }}</div>
        @else
        <div class="text-center py-20">
            <svg class="w-16 h-16 mx-auto text-surface-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <h3 class="text-lg font-semibold text-surface-600">Tidak ada program ditemukan</h3>
            <p class="text-surface-400 mt-1">Coba ubah filter pencarian Anda.</p>
        </div>
        @endif
    </div>
</section>
</x-public-layout>
