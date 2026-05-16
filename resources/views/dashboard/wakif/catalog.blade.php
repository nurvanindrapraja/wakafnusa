<x-dashboard-layout>
    @section('title', 'Pilih Program Wakaf')
    @section('page-title', 'Tunaikan Wakaf')

    @section('sidebar-nav')
        @include($sidebar ?? 'dashboard.wakif.partials.nav')
    @endsection

    <div class="space-y-8">
        <!-- Filters -->
        <form method="GET" action="{{ route('catalog') }}" class="bg-white rounded-3xl shadow-card p-6">
            <div class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Cari Program</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kata kunci..." class="w-full rounded-2xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Kategori</label>
                    <select name="category" class="w-full rounded-2xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ ucfirst(strtolower($cat)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Urutkan</label>
                    <select name="sort" class="w-full rounded-2xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                        <option value="latest" @selected(request('sort') === 'latest')>Terbaru</option>
                        <option value="popular" @selected(request('sort') === 'popular')>Terpopuler</option>
                        <option value="ending" @selected(request('sort') === 'ending')>Segera Berakhir</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full py-2.5 bg-nu-green-600 text-white text-sm font-bold rounded-2xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-100">Cari Program</button>
                </div>
            </div>
        </form>

        <!-- Program Grid -->
        @if ($programs->count())
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($programs as $program)
            <div class="bg-white rounded-3xl overflow-hidden shadow-card card-hover group border border-surface-50 flex flex-col">
                <div class="relative h-44 overflow-hidden shrink-0">
                    @if ($program->cover_image)
                        <img src="{{ asset('storage/' . $program->cover_image) }}" alt="{{ $program->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-nu flex items-center justify-center">
                            <svg class="w-12 h-12 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-white/95 backdrop-blur-sm text-surface-900 text-[9px] font-black uppercase tracking-wider shadow-sm">
                            {{ $program->category }}
                        </span>
                    </div>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="font-bold text-surface-900 text-sm mb-1 line-clamp-2 leading-tight">{{ $program->title }}</h3>
                    <div class="flex items-center gap-1.5 text-[9px] font-bold text-nu-green-600 uppercase tracking-widest mb-3">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        {{ $program->pelaksana->name }}
                    </div>
                    
                    <div class="mt-auto space-y-3">
                        <div>
                            <div class="flex justify-between text-[10px] mb-1 font-bold">
                                <span class="text-nu-green-600">{{ $program->formatted_collected }}</span>
                                <span class="text-surface-400">{{ $program->progress_percentage }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-surface-100 rounded-full overflow-hidden">
                                <div class="h-full bg-nu-green-600 rounded-full" style="width: {{ $program->progress_percentage }}%"></div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center text-[9px] font-bold text-surface-400 uppercase tracking-tighter">
                            <span>Target: {{ $program->formatted_target }}</span>
                            <span class="flex items-center gap-1 {{ $program->days_remaining <= 3 ? 'text-red-500' : '' }}">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $program->days_remaining > 0 ? $program->days_remaining . ' Hari' : ($program->is_completed ? 'Selesai' : 'Berakhir') }}
                            </span>
                        </div>
                        @if($program->is_donation_open)
                            <a href="{{ route('program.show', $program->slug) }}" class="block w-full py-2.5 bg-surface-50 text-nu-green-700 text-center text-xs font-black uppercase tracking-widest rounded-xl hover:bg-nu-green-600 hover:text-white transition-all border border-nu-green-100">
                                Pilih Program
                            </a>
                        @else
                            <a href="{{ route('program.show', $program->slug) }}" class="block w-full py-2.5 bg-surface-100 text-surface-400 text-center text-xs font-black uppercase tracking-widest rounded-xl border border-surface-200">
                                {{ $program->is_completed ? 'Program Selesai' : 'Periode Berakhir' }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-10">{{ $programs->withQueryString()->links() }}</div>
        @else
        <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-surface-200">
            <svg class="w-16 h-16 mx-auto text-surface-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <h3 class="text-lg font-bold text-surface-600">Program tidak ditemukan</h3>
            <p class="text-sm text-surface-400 mt-1">Gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
        </div>
        @endif
    </div>
</x-dashboard-layout>
