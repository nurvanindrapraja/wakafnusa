<x-dashboard-layout>
    @section('title', $program->title)
    @section('page-title', 'Detail Program')

    @section('sidebar-nav')
        @include($sidebar ?? 'dashboard.wakif.partials.nav')
    @endsection

    <div class="space-y-8">
        <!-- Top Section: Header & Financial Info -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Cover & Title -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[32px] overflow-hidden shadow-card border border-surface-100 aspect-video relative group">
                    @if ($program->cover_image)
                        <img src="{{ asset('storage/' . $program->cover_image) }}" alt="{{ $program->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-nu flex items-center justify-center">
                            <svg class="w-24 h-24 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-xl bg-white/95 backdrop-blur-sm text-surface-900 text-[10px] font-black uppercase tracking-widest shadow-lg">
                            {{ $program->category }}
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] p-8 shadow-card border border-surface-100">
                    <h1 class="text-2xl font-black text-surface-900 mb-4 tracking-tight leading-tight">{{ $program->title }}</h1>
                    <div class="flex flex-wrap items-center gap-6 text-[11px] font-bold text-surface-400 uppercase tracking-widest">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                            {{ $program->location_city }}, {{ $program->location_province }}
                        </span>
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            {{ number_format($program->beneficiary_count) }} Penerima Manfaat
                        </span>
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            {{ $program->start_date->format('d M Y') }} - {{ $program->end_date->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Financial Action Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[32px] shadow-card p-8 border border-surface-100 flex flex-col h-full">
                    <div class="mb-8 bg-nu-green-50 p-6 rounded-3xl border border-nu-green-100">
                        <div class="flex justify-between items-end mb-4">
                            <div>
                                <div class="text-[10px] font-black text-nu-green-600 uppercase tracking-widest mb-1">Terkumpul</div>
                                <div class="text-2xl font-black text-nu-green-900 tracking-tight">{{ $program->formatted_collected }}</div>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-black text-nu-gold-600">{{ $program->progress_percentage }}%</span>
                            </div>
                        </div>
                        <div class="w-full h-3 bg-white rounded-full overflow-hidden shadow-inner border border-nu-green-100/50">
                            <div class="h-full bg-gradient-to-r from-nu-gold-400 to-nu-gold-600" style="width: {{ $program->progress_percentage }}%"></div>
                        </div>
                        <div class="flex justify-between mt-3">
                            <div class="text-[9px] text-surface-400 font-bold uppercase tracking-widest">Target: {{ $program->formatted_target }}</div>
                            <div class="text-[9px] text-nu-green-700 font-black uppercase tracking-widest">{{ $program->donor_count }} Wakif</div>
                        </div>
                    </div>

                    <div class="space-y-4 mb-8">
                        @if($program->is_donation_open)
                            <a href="{{ route('wakaf.create', $program->slug) }}" class="block w-full py-4 bg-nu-gold-500 text-white text-center text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-nu-gold-600 transition-all shadow-lg shadow-nu-gold-500/20">
                                Wakaf Sekarang
                            </a>
                        @else
                            <button disabled class="w-full py-4 bg-surface-100 text-surface-400 text-sm font-black uppercase tracking-widest rounded-2xl border border-surface-200 cursor-not-allowed">
                                {{ $program->is_completed ? 'Program Selesai' : 'Periode Berakhir' }}
                            </button>
                        @endif
                        <div class="p-4 bg-surface-50 rounded-2xl border border-surface-100 text-center">
                            <div class="text-[10px] text-surface-400 font-bold uppercase tracking-widest mb-1">Pelaksana Program</div>
                            <div class="text-sm font-black text-surface-900">{{ $program->pelaksana->name }}</div>
                        </div>
                    </div>

                    <div class="mt-auto pt-6 border-t border-surface-100 flex items-center justify-between" x-data="{ showShare: false }">
                        <div class="text-[10px] text-surface-400 font-bold uppercase tracking-widest">Bagikan Program</div>
                        <div class="flex gap-2">
                            <button @click="showShare = true" class="w-8 h-8 rounded-lg bg-surface-50 flex items-center justify-center text-surface-400 hover:text-nu-green-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                            </button>

                            <!-- Share Modal -->
                            <div x-show="showShare" x-cloak class="fixed inset-0 z-[150] flex items-center justify-center p-4">
                                <div @click="showShare = false" class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm"></div>
                                <div class="relative bg-white rounded-[40px] w-full max-w-sm overflow-hidden shadow-2xl" x-transition>
                                    <div class="px-8 pt-8 pb-6 text-center bg-nu-green-50 border-b border-nu-green-100">
                                        <h3 class="text-xl font-black text-nu-green-800 tracking-tight">Bagikan Program</h3>
                                        <p class="text-[10px] text-nu-green-600 font-black uppercase tracking-widest mt-1">Tebarkan Kebaikan</p>
                                    </div>
                                    <div class="p-6 space-y-3">
                                        @php
                                            $shareUrl = route('program.show', $program->slug);
                                            $inviteText = "Bismillah, mari raih pahala jariyah dengan berwakaf untuk program: *" . $program->title . "*\n\nTunaikan wakaf Anda dengan mudah dan aman melalui WAKAF NUSA di link berikut:\n" . $shareUrl;
                                            $encodedText = urlencode($inviteText);
                                        @endphp
                                        <button @click="navigator.clipboard.writeText('{{ $inviteText }}'); showShare = false" class="w-full group flex items-center gap-4 p-4 rounded-2xl bg-surface-50 border border-transparent hover:border-nu-green-600/30 transition-all text-left">
                                            <div class="w-10 h-10 rounded-xl bg-nu-green-600 flex items-center justify-center text-white shadow-md">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-black text-surface-900">Salin Teks Ajakan</div>
                                                <div class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Copy invitation text</div>
                                            </div>
                                        </button>
                                        <a href="https://wa.me/?text={{ $encodedText }}" target="_blank" class="w-full group flex items-center gap-4 p-4 rounded-2xl bg-surface-50 border border-transparent hover:border-green-500/30 transition-all text-left">
                                            <div class="w-10 h-10 rounded-xl bg-green-500 flex items-center justify-center text-white shadow-md">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.29-4.139l.363.216c1.552.923 3.33 1.411 5.14 1.412 5.433 0 9.853-4.42 9.856-9.853 0-2.633-1.025-5.107-2.887-6.97s-4.337-2.887-6.971-2.887c-5.433 0-9.854 4.42-9.856 9.854 0 2.115.56 4.177 1.62 5.955l.235.394-1.063 3.882 3.974-1.042z"/></svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-black text-surface-900">WhatsApp</div>
                                                <div class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Bagikan via Chat</div>
                                            </div>
                                        </a>
                                        <a href="https://x.com/intent/tweet?text={{ $encodedText }}" target="_blank" class="w-full group flex items-center gap-4 p-4 rounded-2xl bg-surface-50 border border-transparent hover:border-black/30 transition-all text-left">
                                            <div class="w-10 h-10 rounded-xl bg-black flex items-center justify-center text-white shadow-md">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932 6.064-6.933zm-1.292 19.49h2.039L6.486 3.24H4.298l13.311 17.403z"/></svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-black text-surface-900">X (Twitter)</div>
                                                <div class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Bagikan ke X</div>
                                            </div>
                                        </a>
                                        <a href="https://www.threads.net/intent/post?text={{ $encodedText }}" target="_blank" class="w-full group flex items-center gap-4 p-4 rounded-2xl bg-surface-50 border border-transparent hover:border-black/30 transition-all text-left">
                                            <div class="w-10 h-10 rounded-xl bg-black flex items-center justify-center text-white shadow-md">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M14.28 10.37c-.36-.08-.73-.13-1.11-.13-.33 0-.66.04-.98.11l.03.01c.21.05.41.11.6.19.43.19.82.47 1.14.81.31.33.56.71.74 1.13.18.42.27.87.27 1.34 0 .97-.37 1.83-1.01 2.47-.64.64-1.5 1.01-2.47 1.01-.97 0-1.83-.37-2.47-1.01-.64-.64-1.01-1.5-1.01-2.47V9.43c0-1.49 1.21-2.7 2.7-2.7 1.49 0 2.7 1.21 2.7 2.7v2.13h-2.13v-2.13c0-.31-.26-.57-.57-.57s-.57.26-.57.57v4.41c0 .33.13.63.34.84.21.21.51.34.84.34.33 0 .63-.13.84-.34.21-.21.34-.51.34-.84 0-.31-.12-.59-.31-.8-.19-.21-.46-.35-.76-.39l-.02-.01c.36.08.73.13 1.11.13.33 0 .66-.04.98-.11l-.03-.01c-.21-.05-.41-.11-.6-.19-.43-.19-.82-.47-1.14-.81-.31-.33-.56-.71-.74-1.13-.18-.42-.27-.87-.27-1.34 0-.97.37-1.83 1.01-2.47.64-.64 1.5-1.01 2.47-1.01.97 0 1.83.37 2.47 1.01.64.64 1.01 1.5 1.01 2.47V13.7c0 1.93-1.57 3.5-3.5 3.5s-3.5-1.57-3.5-3.5V9.43c0-2.48 2.01-4.49 4.49-4.49 2.48 0 4.49 2.01 4.49 4.49v2.13H24V9.43c0-4.1-3.33-7.43-7.43-7.43S9.14 5.33 9.14 9.43v4.27c0 2.8 2.27 5.07 5.07 5.07 2.8 0 5.07-2.27 5.07-5.07V11.56h-2.13v2.14c0 1.63-1.32 2.95-2.95 2.95-1.63 0-2.95-1.32-2.95-2.95V9.43c0-2.21 1.79-4 4-4 2.21 0 4 1.79 4 4v2.13H24V9.43c0-4.1-3.33-7.43-7.43-7.43S9.14 5.33 9.14 9.43v4.27c0 2.8 2.27 5.07 5.07 5.07 2.8 0 5.07-2.27 5.07-5.07V11.56z"/></svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-black text-surface-900">Threads</div>
                                                <div class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Bagikan ke Threads</div>
                                            </div>
                                        </a>
                                        <a href="https://www.instagram.com/" target="_blank" class="w-full group flex items-center gap-4 p-4 rounded-2xl bg-surface-50 border border-transparent hover:border-[#E4405F]/30 transition-all text-left">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#FFDC80] via-[#E1306C] to-[#833AB4] flex items-center justify-center text-white shadow-md">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-black text-surface-900">Instagram</div>
                                                <div class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Buka Instagram</div>
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
                </div>
            </div>
        </div>

        <!-- Detailed Content Tabs -->
        <div class="bg-white rounded-[32px] shadow-card overflow-hidden" x-data="{ tab: 'deskripsi', previewModal: { open: false, type: 'image', url: '', title: '' } }">
            <div class="flex border-b border-surface-100 bg-surface-50/50 overflow-x-auto no-scrollbar">
                <button @click="tab = 'deskripsi'" :class="tab === 'deskripsi' ? 'text-nu-green-700 bg-white border-x border-surface-100' : 'text-surface-500'" class="px-8 py-5 text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">Deskripsi</button>
                <button @click="tab = 'rab'" :class="tab === 'rab' ? 'text-nu-green-700 bg-white border-x border-surface-100' : 'text-surface-500'" class="px-8 py-5 text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">RAB</button>
                <button @click="tab = 'pelaksana'" :class="tab === 'pelaksana' ? 'text-nu-green-700 bg-white border-x border-surface-100' : 'text-surface-500'" class="px-8 py-5 text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">Pelaksana</button>
                <button @click="tab = 'wakif'" :class="tab === 'wakif' ? 'text-nu-green-700 bg-white border-x border-surface-100' : 'text-surface-500'" class="px-8 py-5 text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">Daftar Wakif</button>
                <button @click="tab = 'laporan'" :class="tab === 'laporan' ? 'text-nu-green-700 bg-white border-x border-surface-100' : 'text-surface-500'" class="px-8 py-5 text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">Laporan Realisasi</button>
            </div>
            
            <div class="p-8">
                <!-- Deskripsi Tab -->
                <div x-show="tab === 'deskripsi'">
                    <div class="prose prose-sm max-w-none text-surface-600 leading-relaxed font-medium">
                        {{ $program->description }}
                    </div>
                </div>

                <!-- RAB Tab -->
                <div x-show="tab === 'rab'" x-cloak>
                    <div class="max-w-xl space-y-6">
                        <div class="p-6 bg-surface-50 rounded-3xl border border-surface-100 flex items-center justify-between">
                            <div>
                                <div class="text-[10px] text-surface-400 font-bold uppercase tracking-widest mb-1">Total Kebutuhan Dana</div>
                                <div class="text-2xl font-black text-nu-green-700">Rp {{ number_format($program->rab_total, 0, ',', '.') }}</div>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-nu-green-100 flex items-center justify-center text-nu-green-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                        </div>
                        @if($program->rab_document)
                            <button @click="previewModal = { open: true, type: 'pdf', url: '{{ Storage::url($program->rab_document) }}', title: 'Dokumen RAB' }" class="w-full p-4 flex items-center justify-between border-2 border-surface-100 rounded-2xl hover:border-nu-green-600 transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-surface-50 flex items-center justify-center text-surface-400 group-hover:bg-nu-green-100 group-hover:text-nu-green-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    </div>
                                    <span class="text-sm font-bold text-surface-700">Lampiran Dokumen RAB</span>
                                </div>
                                <svg class="w-5 h-5 text-surface-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Pelaksana Tab -->
                <div x-show="tab === 'pelaksana'" x-cloak>
                    <div class="bg-surface-50 rounded-3xl p-8 border border-surface-100">
                        <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                            <div class="w-20 h-20 rounded-3xl bg-nu-green-600 shadow-xl shadow-nu-green-600/20 flex items-center justify-center text-white text-2xl font-black shrink-0 border-4 border-white">
                                {{ substr($program->pelaksana->name, 0, 1) }}
                            </div>
                            <div class="flex-1 text-center md:text-left">
                                <h4 class="text-xl font-black text-surface-900 mb-1">{{ $program->pelaksana->name }}</h4>
                                <div class="inline-flex items-center px-3 py-1 rounded-full bg-nu-green-100 text-nu-green-700 text-[9px] font-black uppercase tracking-widest mb-4">
                                    Pelaksana Nazhir Resmi
                                </div>
                                
                                @if($program->pelaksana->institution_short_profile)
                                    <div class="mb-6">
                                        <div class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-2 flex items-center justify-center md:justify-start gap-2">
                                            <span class="w-1 h-3 bg-nu-gold-500 rounded-full"></span>
                                            Profil Lembaga
                                        </div>
                                        <p class="text-xs text-surface-600 leading-relaxed italic">
                                            "{{ $program->pelaksana->institution_short_profile }}"
                                        </p>
                                    </div>
                                @endif
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 mt-2">
                                    <div class="flex items-center justify-center md:justify-start gap-3 text-surface-600">
                                        <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-nu-green-600 shadow-sm border border-surface-100">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                        </div>
                                        <span class="text-xs font-medium">{{ $program->pelaksana->city }}, {{ $program->pelaksana->province }}</span>
                                    </div>
                                    <div class="flex items-center justify-center md:justify-start gap-3 text-surface-600">
                                        <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-nu-green-600 shadow-sm border border-surface-100">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                        </div>
                                        <span class="text-xs font-medium">{{ Str::mask($program->pelaksana->phone ?? '-', '*', 4, 4) }}</span>
                                    </div>
                                </div>

                                @if($program->pelaksana->institution_profile_doc)
                                    <button @click="previewModal = { open: true, type: 'pdf', url: '{{ Storage::url($program->pelaksana->institution_profile_doc) }}', title: 'Profil Lembaga: {{ $program->pelaksana->name }}' }" 
                                        class="mt-8 inline-flex items-center gap-3 px-6 py-3 bg-white border border-surface-200 text-nu-green-700 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-nu-green-600 hover:text-white hover:border-nu-green-600 transition-all group">
                                        <svg class="w-4 h-4 group-hover:scale-125 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        Lihat Dokumen Profil
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wakif Tab -->
                <div x-show="tab === 'wakif'" x-cloak>
                    <div class="space-y-4">
                        @forelse ($program->donations->where('payment_status', 'PAID')->sortByDesc('paid_at') as $donation)
                            <div class="flex items-center justify-between p-4 bg-surface-50 rounded-2xl border border-surface-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-nu-green-100 flex items-center justify-center text-nu-green-600 font-black text-sm">
                                        {{ substr($donation->donor_display_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-surface-900">{{ $donation->donor_display_name }}</div>
                                        <div class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">{{ $donation->paid_at?->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-black text-nu-green-600">Rp {{ number_format($donation->amount, 0, ',', '.') }}</div>
                                    @if($donation->is_anonymous)
                                        <div class="text-[9px] text-surface-400 font-bold italic uppercase">Anonim</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-surface-400 italic text-sm">Belum ada riwayat wakif untuk program ini.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Laporan Tab -->
                <div x-show="tab === 'laporan'" x-cloak>
                    @if ($program->reports->where('status', 'VALIDATED')->count())
                        <div class="grid sm:grid-cols-2 gap-6">
                            @foreach ($program->reports->where('status', 'VALIDATED')->sortByDesc('created_at') as $report)
                                <div class="p-6 bg-surface-50 rounded-3xl border border-surface-100">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="font-bold text-surface-900 text-sm">{{ $report->title }}</h4>
                                            <div class="text-[9px] font-bold text-surface-400 uppercase tracking-widest mt-1">{{ $report->report_period }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-[10px] text-surface-400 font-bold uppercase tracking-widest mb-1">Realisasi</div>
                                            <div class="text-sm font-black text-nu-green-600">Rp {{ number_format($report->realized_amount, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-surface-600 leading-relaxed mb-6 line-clamp-3">{{ $report->description }}</p>
                                    <button @click="previewModal = { open: true, type: 'image', url: '{{ Storage::url($report->evidence_photos[0] ?? '') }}', title: 'Laporan: {{ $report->title }}' }" 
                                        class="w-full py-2.5 bg-white border border-surface-200 text-nu-green-700 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-nu-green-600 hover:text-white transition-all">
                                        Lihat Dokumentasi
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 text-surface-400 italic text-sm">Belum ada laporan realisasi yang divalidasi.</div>
                    @endif
                </div>
            </div>

            <!-- Global Preview Modal -->
            <div x-show="previewModal.open" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center p-4">
                <div @click="previewModal.open = false" class="absolute inset-0 bg-surface-900/90 backdrop-blur-md"></div>
                <div class="relative bg-white rounded-[2rem] w-full max-w-4xl max-h-[90vh] overflow-hidden shadow-2xl flex flex-col">
                    <div class="px-8 py-5 border-b border-surface-100 flex items-center justify-between">
                        <h3 class="font-black text-surface-900 truncate pr-8" x-text="previewModal.title"></h3>
                        <button @click="previewModal.open = false" class="p-2 hover:bg-surface-50 rounded-full transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div class="flex-1 overflow-auto p-6 bg-surface-50 flex items-center justify-center">
                        <template x-if="previewModal.type === 'image'">
                            <img :src="previewModal.url" class="max-w-full h-auto rounded-xl shadow-lg border-4 border-white">
                        </template>
                        <template x-if="previewModal.type === 'pdf'">
                            <iframe :src="previewModal.url" class="w-full h-full min-h-[60vh] rounded-xl border-none"></iframe>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
