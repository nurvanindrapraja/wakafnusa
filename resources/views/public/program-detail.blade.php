<x-public-layout>
@section('title', $program->title)

<!-- Program Header -->
<section class="hero-pattern pt-32 pb-20 relative overflow-hidden" style="background-color: #004000;">
    <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm mb-6 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali ke Katalog
        </a>
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-nu-gold-500/20 text-nu-gold-400 text-[10px] font-black uppercase tracking-widest border border-nu-gold-500/30 mb-4">
            {{ $program->category }}
        </span>
        <h1 class="text-2xl md:text-4xl font-bold text-white mb-4">{{ $program->title }}</h1>
        <div class="flex flex-wrap items-center gap-4 text-sm text-white/60">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                {{ $program->location_city }}, {{ $program->location_province }}
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                {{ number_format($program->beneficiary_count) }} penerima manfaat
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                {{ $program->days_remaining }} hari lagi
            </span>
        </div>
    </div>
</section>

<section class="py-12 -mt-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Top Section: Cover & Financial Card -->
        <div class="grid lg:grid-cols-3 gap-8 mb-8">
            <!-- Cover Image -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[32px] overflow-hidden shadow-card h-full min-h-[350px] md:min-h-[450px] border border-surface-100">
                    @if ($program->cover_image)
                        <img src="{{ asset('storage/' . $program->cover_image) }}" alt="{{ $program->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-nu flex items-center justify-center">
                            <svg class="w-24 h-24 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Financial Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[32px] shadow-card-hover p-6 border border-surface-100 h-full flex flex-col">
                    <!-- Progress Section -->
                    <div class="mb-6 bg-nu-green-50/50 p-5 rounded-2xl border border-nu-green-100/50">
                        <div class="flex justify-between items-end mb-4">
                            <div>
                                <div class="text-[10px] font-black text-nu-green-600 uppercase tracking-widest mb-1">Dana Terkumpul</div>
                                <div class="text-2xl font-black text-nu-green-800 tracking-tight">{{ $program->formatted_collected }}</div>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-black text-nu-gold-600">{{ $program->progress_percentage }}%</span>
                            </div>
                        </div>
                        <div class="progress-bar h-3 bg-white border border-nu-green-100/30 overflow-hidden shadow-inner">
                            <div class="progress-bar-fill bg-gradient-to-r from-nu-gold-400 to-nu-gold-600 shadow-sm" style="width: {{ $program->progress_percentage }}%"></div>
                        </div>
                        <div class="flex justify-between mt-3">
                            <div class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Target: {{ $program->formatted_target }}</div>
                            <div class="text-[10px] text-nu-green-700 font-black uppercase tracking-wider">{{ $program->donor_count }} Wakif</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center p-3 bg-surface-50 rounded-2xl border border-surface-100">
                            <div class="text-lg font-black text-surface-900">{{ $program->donor_count }}</div>
                            <div class="text-[10px] text-surface-400 font-bold uppercase tracking-widest">Wakif</div>
                        </div>
                        <div class="text-center p-3 bg-surface-50 rounded-2xl border border-surface-100">
                            <div class="text-lg font-black text-surface-900">{{ $program->days_remaining }}</div>
                            <div class="text-[10px] text-surface-400 font-bold uppercase tracking-widest">Hari Lagi</div>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        @auth
                            <a href="{{ route('wakaf.create', $program->slug) }}" class="btn-gold w-full text-center block text-sm py-4 font-black uppercase tracking-widest shadow-lg shadow-nu-gold-500/20">Wakaf Sekarang</a>
                        @else
                            <a href="{{ route('wakaf.create', $program->slug) }}" class="btn-gold w-full text-center block text-sm py-4 font-black uppercase tracking-widest shadow-lg shadow-nu-gold-500/20">Wakaf Sekarang (Guest)</a>
                        @endauth
                        
                        <div x-data="{ showShare: false }">
                            <button @click="showShare = true" class="w-full bg-white border-2 border-surface-100 rounded-2xl py-3.5 text-[11px] font-black uppercase tracking-widest text-surface-500 hover:text-nu-green-600 hover:border-nu-green-600/30 hover:bg-nu-green-50/30 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                                Bagikan Program
                            </button>

                            <!-- Share Modal -->
                            <div x-show="showShare" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
                                <div @click="showShare = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm"></div>
                                
                                <div class="relative bg-white rounded-[40px] w-full max-w-sm overflow-hidden shadow-2xl transition-all" 
                                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                                    
                                    <div class="px-8 pt-8 pb-6 text-center bg-nu-green-50 border-b border-nu-green-100">
                                        <h3 class="text-xl font-black text-nu-green-800 tracking-tight">Bagikan Program</h3>
                                        <p class="text-[10px] text-nu-green-600 font-black uppercase tracking-widest mt-1">Tebarkan Kebaikan</p>
                                    </div>

                                    <div class="p-6 space-y-3">
                                        @php
                                            $shareUrl = url()->current();
                                            $shareText = "Ayo berwakaf untuk program: " . $program->title . ". Mari tebar kebaikan bersama WAKAF NUSA.";
                                            $encodedUrl = urlencode($shareUrl);
                                            $encodedText = urlencode($shareText);
                                        @endphp
                                        
                                        <button @click="navigator.clipboard.writeText('{{ $shareUrl }}'); $dispatch('notify', {message: 'Link Instagram tersalin!'}); showShare = false" 
                                            class="w-full group flex items-center gap-4 p-4 rounded-2xl bg-surface-50 border border-transparent hover:border-[#ee2a7b]/30 transition-all">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#f9ce34] via-[#ee2a7b] to-[#6228d7] flex items-center justify-center text-white shadow-md">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                            </div>
                                            <div class="flex-1 text-left">
                                                <div class="text-sm font-black text-surface-900 group-hover:text-[#ee2a7b] transition-colors">Instagram</div>
                                                <div class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Salin Link Story</div>
                                            </div>
                                        </button>

                                        <a href="https://twitter.com/intent/tweet?text={{ $encodedText }}&url={{ $encodedUrl }}" target="_blank" @click="showShare = false"
                                            class="w-full group flex items-center gap-4 p-4 rounded-2xl bg-surface-50 border border-transparent hover:border-surface-200 transition-all">
                                            <div class="w-10 h-10 rounded-xl bg-surface-900 flex items-center justify-center text-white shadow-md">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                            </div>
                                            <div class="flex-1 text-left">
                                                <div class="text-sm font-black text-surface-900 group-hover:text-surface-600 transition-colors">X / Twitter</div>
                                                <div class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Bagikan ke Feed</div>
                                            </div>
                                        </a>

                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" target="_blank" @click="showShare = false"
                                            class="w-full group flex items-center gap-4 p-4 rounded-2xl bg-surface-50 border border-transparent hover:border-[#1877F2]/30 transition-all">
                                            <div class="w-10 h-10 rounded-xl bg-[#1877F2] flex items-center justify-center text-white shadow-md">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                            </div>
                                            <div class="flex-1 text-left">
                                                <div class="text-sm font-black text-surface-900 group-hover:text-[#1877F2] transition-colors">Facebook</div>
                                                <div class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Bagikan ke Facebook</div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="px-8 py-5 bg-surface-100 flex items-center justify-center">
                                        <button @click="showShare = false" class="text-xs font-black text-surface-500 hover:text-surface-900 transition-colors uppercase tracking-widest">Mungkin Nanti</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Compact Recent Donors -->
                    @if ($program->donations->count())
                    <div class="mt-auto pt-4 border-t border-surface-100">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-1 h-4 bg-nu-green-600 rounded-full"></span>
                            <h4 class="text-[10px] font-black text-surface-900 uppercase tracking-widest">Wakif Terbaru</h4>
                        </div>
                        <div class="flex -space-x-2">
                            @foreach ($program->donations->where('payment_status', 'PAID')->take(5) as $donation)
                                <div class="w-8 h-8 rounded-full bg-nu-green-100 border-2 border-white flex items-center justify-center text-[10px] font-black text-nu-green-600" title="{{ $donation->donor_display_name }}">
                                    {{ substr($donation->donor_display_name, 0, 1) }}
                                </div>
                            @endforeach
                            @if($program->donor_count > 5)
                                <div class="w-8 h-8 rounded-full bg-surface-100 border-2 border-white flex items-center justify-center text-[10px] font-black text-surface-600">
                                    +{{ $program->donor_count - 5 }}
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Tabs & Detailed Info -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-[32px] shadow-card overflow-hidden" x-data="{ tab: 'deskripsi', previewModal: { open: false, type: 'image', url: '', title: '' } }">
                    <div class="flex border-b border-surface-100 bg-surface-50/50 overflow-x-auto no-scrollbar px-2">
                        <button @click="tab = 'deskripsi'" :class="tab === 'deskripsi' ? 'text-nu-green-700 bg-white shadow-sm border-x border-surface-100' : 'text-surface-500 border-transparent'" class="px-8 py-5 text-[11px] font-black uppercase tracking-widest transition-all whitespace-nowrap">Deskripsi</button>
                        <button @click="tab = 'rab'" :class="tab === 'rab' ? 'text-nu-green-700 bg-white shadow-sm border-x border-surface-100' : 'text-surface-500 border-transparent'" class="px-8 py-5 text-[11px] font-black uppercase tracking-widest transition-all whitespace-nowrap">RAB</button>
                        <button @click="tab = 'pelaksana'" :class="tab === 'pelaksana' ? 'text-nu-green-700 bg-white shadow-sm border-x border-surface-100' : 'text-surface-500 border-transparent'" class="px-8 py-5 text-[11px] font-black uppercase tracking-widest transition-all whitespace-nowrap">Pelaksana</button>
                        <button @click="tab = 'wakif'" :class="tab === 'wakif' ? 'text-nu-green-700 bg-white shadow-sm border-x border-surface-100' : 'text-surface-500 border-transparent'" class="px-8 py-5 text-[11px] font-black uppercase tracking-widest transition-all whitespace-nowrap">Wakif ({{ $program->donations->where('payment_status', 'PAID')->count() }})</button>
                        <button @click="tab = 'laporan'" :class="tab === 'laporan' ? 'text-nu-green-700 bg-white shadow-sm border-x border-surface-100' : 'text-surface-500 border-transparent'" class="px-8 py-5 text-[11px] font-black uppercase tracking-widest transition-all whitespace-nowrap">Laporan</button>
                    </div>
                    
                    <div class="p-8">
                        <div x-show="tab === 'deskripsi'">
                            <div class="prose prose-sm max-w-none text-surface-700 leading-relaxed">{{ $program->description }}</div>
                            <div class="mt-6 grid grid-cols-2 gap-4">
                                <div class="bg-surface-50 rounded-xl p-4">
                                    <div class="text-xs text-surface-500">Lokasi Detail</div>
                                    <div class="font-medium text-sm mt-1">{{ $program->location_detail ?? '-' }}</div>
                                </div>
                                <div class="bg-surface-50 rounded-xl p-4">
                                    <div class="text-xs text-surface-500">Periode</div>
                                    <div class="font-medium text-sm mt-1">{{ $program->start_date->format('d M Y') }} — {{ $program->end_date->format('d M Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div x-show="tab === 'rab'" x-cloak>
                            <div class="space-y-4">
                                <div class="flex justify-between p-5 bg-surface-50 rounded-2xl border border-surface-100">
                                    <div>
                                        <span class="text-xs text-surface-500 uppercase font-black tracking-widest mb-1 block">Total Rencana Anggaran</span>
                                        <span class="text-xl font-black text-nu-green-700">Rp {{ number_format($program->rab_total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-right">
                                        <svg class="w-8 h-8 text-nu-green-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    </div>
                                </div>
                                
                                @if($program->rab_document)
                                    @php
                                        $isPdf = Str::endsWith(strtolower($program->rab_document), '.pdf');
                                    @endphp
                                    <button @click="previewModal = { open: true, type: '{{ $isPdf ? 'pdf' : 'image' }}', url: '{{ Storage::url($program->rab_document) }}', title: 'Dokumen RAB: {{ $program->title }}' }" 
                                        class="w-full group flex items-center justify-between p-5 bg-white border-2 border-surface-100 rounded-2xl hover:border-nu-green-600/30 hover:bg-nu-green-50/30 transition-all">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl bg-nu-green-100 flex items-center justify-center text-nu-green-600 group-hover:scale-110 transition-transform">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            </div>
                                            <div class="text-left">
                                                <div class="text-sm font-black text-surface-900">Lihat Detail Dokumen RAB</div>
                                                <div class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Format: {{ $isPdf ? 'PDF Document' : 'Image File' }}</div>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-surface-300 group-hover:text-nu-green-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                    </button>
                                @else
                                    <div class="p-5 bg-surface-50 rounded-2xl border border-dashed border-surface-200 text-center">
                                        <p class="text-xs text-surface-400 font-medium italic">Dokumen RAB belum tersedia untuk publik</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div x-show="tab === 'pelaksana'" x-cloak>
                            <div class="bg-surface-50 rounded-3xl p-8 border border-surface-100">
                                <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                                    <div class="w-24 h-24 rounded-[2rem] bg-nu-green-600 shadow-xl shadow-nu-green-600/20 flex items-center justify-center text-white text-3xl font-black shrink-0 border-4 border-white">
                                        {{ substr($program->pelaksana->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 text-center md:text-left">
                                        <h4 class="text-2xl font-black text-surface-900 mb-1">{{ $program->pelaksana->name }}</h4>
                                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-nu-green-100 text-nu-green-700 text-[10px] font-black uppercase tracking-widest mb-4">
                                            Pelaksana Nazhir Resmi
                                        </div>
                                        
                                        @if($program->pelaksana->institution_short_profile)
                                            <div class="mb-6">
                                                <div class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2 flex items-center justify-center md:justify-start gap-2">
                                                    <span class="w-1 h-3 bg-nu-gold-500 rounded-full"></span>
                                                    Profil Lembaga
                                                </div>
                                                <p class="text-sm text-surface-600 leading-relaxed italic">
                                                    "{{ $program->pelaksana->institution_short_profile }}"
                                                </p>
                                            </div>
                                        @endif
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 mt-2">
                                            <div class="flex items-center justify-center md:justify-start gap-3 text-surface-600">
                                                <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-nu-green-600 shadow-sm border border-surface-100">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002-2z" /></svg>
                                                </div>
                                                <span class="text-sm font-medium">{{ Str::mask($program->pelaksana->email, '*', 3, 5) }}</span>
                                            </div>
                                            <div class="flex items-center justify-center md:justify-start gap-3 text-surface-600">
                                                <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-nu-green-600 shadow-sm border border-surface-100">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                                </div>
                                                <span class="text-sm font-medium">{{ $program->pelaksana->city }}, {{ $program->pelaksana->province }}</span>
                                            </div>
                                            <div class="flex items-center justify-center md:justify-start gap-3 text-surface-600">
                                                <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-nu-green-600 shadow-sm border border-surface-100">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                                </div>
                                                <span class="text-sm font-medium">{{ Str::mask($program->pelaksana->phone ?? '-', '*', 4, 4) }}</span>
                                            </div>
                                        </div>

                                        @if($program->pelaksana->institution_profile_doc && Storage::disk('public')->exists($program->pelaksana->institution_profile_doc))
                                            <button @click="previewModal = { open: true, type: 'pdf', url: '{{ Storage::url($program->pelaksana->institution_profile_doc) }}', title: 'Profil Lembaga: {{ $program->pelaksana->name }}' }" 
                                                class="mt-8 inline-flex items-center gap-3 px-6 py-3 bg-white border-2 border-nu-green-600/20 text-nu-green-700 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-nu-green-600 hover:text-white hover:border-nu-green-600 transition-all group">
                                                <svg class="w-4 h-4 group-hover:scale-125 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                Lihat Profil Lembaga
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div x-show="tab === 'wakif'" x-cloak>
                            <div class="space-y-4">
                                @forelse ($program->donations->where('payment_status', 'PAID')->sortByDesc('paid_at') as $donation)
                                    <div class="flex items-center justify-between p-4 bg-surface-50 rounded-2xl border border-surface-100">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-nu-green-100 flex items-center justify-center text-nu-green-600 font-bold">
                                                {{ substr($donation->donor_display_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-surface-900">{{ $donation->donor_display_name }}</div>
                                                <div class="text-xs text-surface-500">{{ $donation->paid_at?->format('d M Y, H:i') }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-nu-green-600">Rp {{ number_format($donation->amount, 0, ',', '.') }}</div>
                                            @if($donation->is_anonymous)
                                                <div class="text-[10px] text-surface-400 font-medium italic">Wakif Anonim</div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-12">
                                        <svg class="w-12 h-12 mx-auto text-surface-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                        <p class="text-surface-500 text-sm">Belum ada wakif untuk program ini. Jadilah yang pertama!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div x-show="tab === 'laporan'" x-cloak>
                            @if ($program->reports->count())
                                <div class="space-y-6">
                                    @foreach ($program->reports as $report)
                                        <div class="p-6 bg-surface-50 rounded-2xl border border-surface-100">
                                            <div class="flex justify-between items-start mb-4">
                                                <div>
                                                    <h4 class="font-bold text-surface-900">{{ $report->title }}</h4>
                                                    <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mt-1">Periode: {{ $report->report_period }}</div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-xs text-surface-500 mb-1">Dana Terealisasi</div>
                                                    <div class="text-lg font-bold text-nu-green-600">Rp {{ number_format($report->realized_amount, 0, ',', '.') }}</div>
                                                </div>
                                            </div>
                                            
                                            <p class="text-sm text-surface-600 leading-relaxed mb-6">{{ $report->description }}</p>

                                            @if($report->evidence_photos)
                                                <div class="mb-6">
                                                    <div class="text-[10px] font-bold text-surface-400 uppercase mb-3">Dokumentasi Foto</div>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($report->evidence_photos as $photo)
                                                            <button @click="previewModal = { open: true, type: 'image', url: '{{ Storage::url($photo) }}', title: 'Dokumentasi Foto: {{ $report->title }}' }" 
                                                                class="w-20 h-20 rounded-lg overflow-hidden border border-surface-200 hover:border-nu-green-600 transition-colors group relative">
                                                                <img src="{{ Storage::url($photo) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                                                                </div>
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if($report->evidence_docs)
                                                <div>
                                                    <div class="text-[10px] font-bold text-surface-400 uppercase mb-3">Dokumen Pendukung</div>
                                                    <div class="flex flex-wrap gap-3">
                                                        @foreach($report->evidence_docs as $doc)
                                                            @php
                                                                $isPdf = Str::endsWith(strtolower($doc), '.pdf');
                                                            @endphp
                                                            <button @click="previewModal = { open: true, type: '{{ $isPdf ? 'pdf' : 'image' }}', url: '{{ Storage::url($doc) }}', title: 'Dokumen: {{ basename($doc) }}' }" 
                                                                class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-surface-200 text-xs font-medium text-surface-700 hover:text-nu-green-600 hover:border-nu-green-600 transition-all">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                                {{ basename($doc) }}
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="w-12 h-12 mx-auto text-surface-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <p class="text-surface-500 text-sm">Belum ada laporan realisasi yang divalidasi untuk program ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Preview Modal -->
                    <div x-show="previewModal.open" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center p-4">
                        <div @click="previewModal.open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-surface-900/90 backdrop-blur-md"></div>
                        
                        <div class="relative bg-white rounded-[32px] w-full max-w-5xl max-h-[90vh] overflow-hidden shadow-2xl flex flex-col"
                            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                            
                            <div class="px-8 py-5 border-b border-surface-100 flex items-center justify-between bg-surface-50">
                                <h3 class="font-black text-surface-900 text-lg truncate pr-8" x-text="previewModal.title"></h3>
                                <button @click="previewModal.open = false" class="p-2 hover:bg-surface-200 rounded-full transition-colors">
                                    <svg class="w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>

                            <div class="flex-1 overflow-auto p-4 md:p-8 bg-surface-100 flex items-center justify-center">
                                <template x-if="previewModal.type === 'image'">
                                    <img :src="previewModal.url" class="max-w-full h-auto rounded-2xl shadow-xl border-4 border-white">
                                </template>
                                <template x-if="previewModal.type === 'pdf'">
                                    <iframe :src="previewModal.url" class="w-full h-full min-h-[70vh] rounded-2xl border-none bg-white shadow-inner"></iframe>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Content -->
            <div class="lg:col-span-1 space-y-8" x-data="{ copied: false }">
                <!-- Share Call to Action -->
                <div class="bg-gradient-nu p-8 rounded-[32px] text-white shadow-xl relative overflow-hidden group">
                    <div class="absolute inset-0 bg-nu-gold-500 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <h4 class="text-lg font-black mb-2 relative z-10">Tebarkan Kebaikan</h4>
                    <p class="text-xs text-white/80 leading-relaxed mb-6 relative z-10">Setiap satu kali berbagi link, Anda telah membantu ribuan penerima manfaat program ini.</p>
                    <button @click="
                        const text = 'Ayo berwakaf untuk program: {{ $program->title }}. Mari tebar kebaikan bersama WAKAF NUSA. {{ url()->current() }}';
                        navigator.clipboard.writeText(text);
                        copied = true;
                        setTimeout(() => copied = false, 3000);
                    " class="w-full bg-white text-nu-green-800 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg relative z-10 transition-transform active:scale-95">
                        <span x-show="!copied">Salin Link Sekarang</span>
                        <span x-show="copied" x-cloak class="flex items-center justify-center gap-2 text-nu-gold-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            Tersalin!
                        </span>
                    </button>
                </div>

                <!-- Toast Notification (Floating) -->
                <div x-show="copied" x-cloak 
                    x-transition:enter="ease-out duration-300" 
                    x-transition:enter-start="opacity-0 translate-y-4" 
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4"
                    class="fixed bottom-8 right-8 z-[100] bg-surface-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-white/10 backdrop-blur-xl">
                    <div class="w-10 h-10 rounded-xl bg-nu-green-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                    </div>
                    <div>
                        <div class="text-sm font-black">Berhasil Disalin!</div>
                        <div class="text-[10px] text-white/60 font-bold uppercase tracking-wider">Siap dipaste ke media apapun</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Programs -->
@if ($relatedPrograms->count())
<section class="py-16 bg-surface-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-surface-900 mb-8">Program Serupa</h2>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach ($relatedPrograms as $related)
            <a href="{{ route('program.show', $related->slug) }}" class="bg-white rounded-2xl overflow-hidden shadow-card card-hover block group">
                <div class="relative h-40 bg-gradient-nu flex items-center justify-center overflow-hidden">
                    @if ($related->cover_image)
                        <img src="{{ asset('storage/' . $related->cover_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <svg class="w-12 h-12 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center px-3 py-1 rounded bg-white/95 backdrop-blur-sm text-surface-900 text-[9px] font-black uppercase tracking-wider shadow-md">
                            {{ $related->category }}
                        </span>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-sm text-surface-900 mb-2 line-clamp-2">{{ $related->title }}</h3>
                    <div class="progress-bar mb-2"><div class="progress-bar-fill" style="width: {{ $related->progress_percentage }}%"></div></div>
                    <div class="text-xs text-surface-400">{{ $related->formatted_collected }} / {{ $related->formatted_target }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
</x-public-layout>
