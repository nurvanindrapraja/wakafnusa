<x-dashboard-layout>
    @section('title', 'Detail Hasil Review')
    @section('page-title', 'Detail Hasil Review')

    @section('sidebar-nav')
        @include('dashboard.penilai.partials.nav')
    @endsection

    <div x-data="{ pdfModal: false, pdfUrl: '', pdfTitle: '' }" class="relative">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Program Details Column -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
                    <div class="aspect-video relative">
                        @if($program->cover_image)
                            <img src="{{ Storage::url($program->cover_image) }}" class="w-full h-full object-cover" alt="{{ $program->title }}">
                        @else
                            <div class="w-full h-full bg-surface-100 flex items-center justify-center text-surface-400">
                                No Image
                            </div>
                        @endif
                        <div class="absolute top-4 left-4">
                            <span class="badge badge-amber text-[10px]">{{ $program->category }}</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-surface-900 mb-2">{{ $program->title }}</h3>
                        <p class="text-sm text-surface-600 mb-6 line-clamp-3">{{ $program->description }}</p>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between text-xs">
                                <span class="text-surface-500">Target Dana</span>
                                <span class="font-bold text-surface-900">Rp {{ number_format($program->target_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-surface-500">Pelaksana</span>
                                <span class="font-bold text-nu-green-600">{{ $program->pelaksana->name }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-surface-500">Lokasi</span>
                                <span class="font-bold text-surface-900">{{ $program->location_city }}, {{ $program->location_province }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-card border border-surface-100 p-6">
                    <h4 class="font-bold text-surface-800 mb-4 text-sm">Dokumen & RAB</h4>
                    <div class="space-y-3">
                        @if($program->rab_document)
                            <button type="button" @click="pdfModal = true; pdfUrl = '{{ Storage::url($program->rab_document) }}'; pdfTitle = 'Dokumen RAB Detail'" class="w-full flex items-center gap-3 p-3 rounded-xl border border-surface-100 hover:bg-surface-50 transition-colors text-left">
                                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-surface-800 truncate">RAB Detail</div>
                                    <div class="text-[10px] text-surface-400">Klik untuk melihat PDF</div>
                                </div>
                            </button>
                        @endif

                        @php
                            $lspCert = $program->pelaksana->lsp_certificate;
                            $isCertFile = $lspCert && 
                                         (str_ends_with(strtolower($lspCert), '.pdf') || str_ends_with(strtolower($lspCert), '.jpg') || str_ends_with(strtolower($lspCert), '.png') || str_ends_with(strtolower($lspCert), '.jpeg')) &&
                                         Storage::disk('public')->exists($lspCert);
                                         
                            $instProfile = $program->pelaksana->institution_profile_doc;
                            $isProfileFile = $instProfile && 
                                            (str_ends_with(strtolower($instProfile), '.pdf') || str_ends_with(strtolower($instProfile), '.jpg') || str_ends_with(strtolower($instProfile), '.png') || str_ends_with(strtolower($instProfile), '.jpeg')) &&
                                            Storage::disk('public')->exists($instProfile);
                        @endphp

                        @if($isCertFile)
                            <button type="button" @click="pdfModal = true; pdfUrl = '{{ Storage::url($lspCert) }}'; pdfTitle = 'SK Pelaksana Nazhir'" class="w-full flex items-center gap-3 p-3 rounded-xl border border-surface-100 hover:bg-surface-50 transition-colors text-left">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                     <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                 </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-surface-800 truncate">SK Pelaksana</div>
                                    <div class="text-[10px] text-surface-400">Klik untuk melihat Dokumen</div>
                                </div>
                            </button>
                        @else
                            <div class="w-full flex items-center gap-3 p-3 rounded-xl border border-surface-50 bg-surface-50/50">
                                <div class="w-8 h-8 rounded-lg bg-surface-100 flex items-center justify-center text-surface-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-surface-400 truncate">SK Pelaksana</div>
                                    <div class="text-[10px] text-surface-400">{{ $program->pelaksana->sk_number ?? ($lspCert ?? 'Belum ada dokumen') }}</div>
                                </div>
                            </div>
                        @endif

                        @if($isProfileFile)
                            <button type="button" @click="pdfModal = true; pdfUrl = '{{ Storage::url($instProfile) }}'; pdfTitle = 'Profil Lembaga'" class="w-full flex items-center gap-3 p-3 rounded-xl border border-surface-100 hover:bg-surface-50 transition-colors text-left">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-surface-800 truncate">Profil Lembaga</div>
                                    <div class="text-[10px] text-surface-400">Klik untuk melihat Profil</div>
                                </div>
                            </button>
                        @endif

                        @if($program->implementation_plan_doc)
                            <button type="button" @click="pdfModal = true; pdfUrl = '{{ Storage::url($program->implementation_plan_doc) }}'; pdfTitle = 'Rencana Pelaksanaan Program'" class="w-full flex items-center gap-3 p-3 rounded-xl border border-surface-100 hover:bg-surface-50 transition-colors text-left">
                                <div class="w-8 h-8 rounded-lg bg-nu-green-50 flex items-center justify-center text-nu-green-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-surface-800 truncate">Rencana Pelaksanaan</div>
                                    <div class="text-[10px] text-surface-400">Klik untuk melihat PDF</div>
                                </div>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Review Results Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Analysis 5C Results -->
                <div class="bg-white rounded-2xl shadow-card border border-surface-100 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-nu-green-600 text-white flex items-center justify-center font-bold text-sm">5C</div>
                        <h3 class="text-lg font-bold text-surface-900">Hasil Analisis Kelayakan (5C)</h3>
                    </div>
                    
                    <div class="space-y-6">
                        @php
                            $c_criteria = [
                                'score_character' => ['Character', 'Integritas dan rekam jejak pelaksana.'],
                                'score_capacity' => ['Capacity', 'Kemampuan teknis dan manajerial program.'],
                                'score_capital' => ['Capital', 'Kapasitas finansial dan sumber daya awal.'],
                                'score_collateral' => ['Collateral', 'Jaminan atau mitigasi risiko kegagalan.'],
                                'score_condition' => ['Condition', 'Kondisi ekonomi, sosial, dan lingkungan.'],
                            ];
                        @endphp

                        @foreach($c_criteria as $name => $info)
                        <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-surface-50 border border-surface-100">
                            <div>
                                <div class="font-bold text-surface-800 text-sm">{{ $info[0] }}</div>
                                <div class="text-xs text-surface-500">{{ $info[1] }}</div>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-nu-green-600 text-white flex items-center justify-center text-xl font-black shadow-lg shadow-nu-green-600/20">
                                {{ $review->$name }}
                            </div>
                        </div>
                        @endforeach

                        <div class="mt-6 p-4 rounded-xl bg-nu-green-50/50 border border-nu-green-100">
                            <label class="block text-xs font-bold text-nu-green-800 uppercase tracking-wider mb-2">Catatan Analisis 5C</label>
                            <p class="text-sm text-surface-700 leading-relaxed">{{ $review->notes_5c }}</p>
                        </div>
                    </div>
                </div>

                <!-- Analysis 3P Results -->
                <div class="bg-white rounded-2xl shadow-card border border-surface-100 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-nu-green-600 text-white flex items-center justify-center font-bold text-sm">3P</div>
                        <h3 class="text-lg font-bold text-surface-900">Hasil Analisis Dampak (3P)</h3>
                    </div>
                    
                    <div class="space-y-6">
                        @php
                            $p_criteria = [
                                'score_people' => ['People', 'Dampak sosial dan manfaat bagi umat.'],
                                'score_planet' => ['Planet', 'Kepedulian terhadap lingkungan dan keberlanjutan ekosistem.'],
                                'score_profit' => ['Profit', 'Keberlanjutan finansial atau kemandirian ekonomi program.'],
                            ];
                        @endphp

                        @foreach($p_criteria as $name => $info)
                        <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-surface-50 border border-surface-100">
                            <div>
                                <div class="font-bold text-surface-800 text-sm">{{ $info[0] }}</div>
                                <div class="text-xs text-surface-500">{{ $info[1] }}</div>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-nu-green-600 text-white flex items-center justify-center text-xl font-black shadow-lg shadow-nu-green-600/20">
                                {{ $review->$name }}
                            </div>
                        </div>
                        @endforeach

                        <div class="mt-6 p-4 rounded-xl bg-nu-green-50/50 border border-nu-green-100">
                            <label class="block text-xs font-bold text-nu-green-800 uppercase tracking-wider mb-2">Catatan Analisis 3P</label>
                            <p class="text-sm text-surface-700 leading-relaxed">{{ $review->notes_3p }}</p>
                        </div>
                    </div>
                </div>

                <!-- Final Decision Result -->
                <div class="bg-white rounded-2xl shadow-card border border-surface-100 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-surface-900">Keputusan Akhir</h3>
                        <span class="px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-widest
                            {{ $review->decision === 'RECOMMENDED' ? 'bg-nu-green-100 text-nu-green-700' : '' }}
                            {{ $review->decision === 'REVISION' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $review->decision === 'REJECTED' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $review->decision }}
                        </span>
                    </div>
                    
                    <div class="p-6 rounded-2xl bg-surface-50 border border-surface-100">
                        <label class="block text-xs font-bold text-surface-400 uppercase tracking-wider mb-3">Alasan Keputusan</label>
                        <p class="text-surface-700 leading-relaxed">{{ $review->decision_notes }}</p>
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('penilai.dashboard') }}" class="px-8 py-3 bg-surface-900 text-white font-bold rounded-xl hover:bg-black transition-all">
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- PDF Modal -->
        <div x-show="pdfModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-surface-900/60 backdrop-blur-xl p-4 lg:p-6"
            x-cloak>
            <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-6xl h-[98vh] flex flex-col overflow-hidden border border-white/20 animate-zoom-in"
                @click.away="pdfModal = false">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-surface-100 flex items-center justify-between bg-surface-50 shrink-0">
                    <h3 class="font-bold flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-nu-green-600 flex items-center justify-center shadow-lg shadow-nu-green-600/20">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <span x-text="pdfTitle" class="text-lg text-surface-900 block"></span>
                            <span class="text-[10px] text-surface-400 uppercase tracking-widest font-bold">Document Preview</span>
                        </div>
                    </h3>
                    <div class="flex items-center gap-3">
                        <a :href="pdfUrl" target="_blank"
                            class="px-5 py-2.5 bg-nu-green-50 text-nu-green-600 hover:bg-nu-green-100 text-xs font-bold rounded-xl flex items-center gap-2 transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Buka di Tab Baru
                        </a>
                        <button @click="pdfModal = false"
                            class="px-5 py-2.5 bg-surface-900 text-white hover:bg-black text-xs font-bold rounded-xl transition-all shadow-lg shadow-black/10">
                            Tutup
                        </button>
                    </div>
                </div>
                <!-- Modal Body (Iframe) -->
                <div class="flex-1 bg-surface-100 p-4 overflow-hidden">
                    <div class="w-full h-full bg-white rounded-xl shadow-inner overflow-hidden border border-surface-200">
                        <iframe :src="pdfUrl" class="w-full h-full border-none"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
