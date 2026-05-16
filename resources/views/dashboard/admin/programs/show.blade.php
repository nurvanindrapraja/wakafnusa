<x-dashboard-layout>
    @section('title', 'Detail Program — ' . $program->title)
    @section('page-title', 'Detail Program (Admin)')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div x-data="{ 
        reportModal: false, 
        reportData: { title: '', period: '', description: '', amount: 0, photos: [], docs: [] },
        reviewDetailModal: false,
        reviewData: null,
        pksModal: false,
        publishConfirmModal: false,
        unpublishConfirmModal: false,
        extendModal: false,
        offlineModal: false,
        openReport(data) {
            this.reportData = data;
            this.reportModal = true;
        },
        openDoc(url, title, type = 'pdf') {
            window.dispatchEvent(new CustomEvent('open-doc-preview', { 
                detail: { url, title, type } 
            }));
        }
    }" class="relative space-y-6">
        <!-- Header Info -->
        <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
            <div class="p-8 flex flex-col md:flex-row gap-8 items-start">
                <div class="w-full md:w-64 h-40 rounded-xl bg-surface-100 overflow-hidden shrink-0">
                    @if($program->cover_image)
                        <img src="{{ Storage::url($program->cover_image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-surface-400">
                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="badge badge-{{ strtolower($program->category) }}">{{ $program->category }}</span>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ match($program->status) {
                                'PROPOSED' => 'bg-surface-100 text-surface-600',
                                'RECOMMENDED' => 'bg-blue-50 text-blue-600',
                                'REVISION_NEEDED' => 'bg-amber-50 text-amber-600',
                                'REJECTED' => 'bg-red-50 text-red-600',
                                'PUBLISHED' => 'bg-nu-green-50 text-nu-green-600',
                                'FUNDED' => 'bg-nu-gold-50 text-nu-gold-600',
                                'IMPLEMENTING' => 'bg-purple-50 text-purple-600',
                                'COMPLETED' => 'bg-surface-900 text-white',
                                default => 'bg-surface-100 text-surface-600'
                            } }}">
                            {{ str_replace('_', ' ', $program->status) }}
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold text-surface-900 mb-4">{{ $program->title }}</h2>
                    <p class="text-surface-600 text-sm leading-relaxed mb-6">{{ $program->description }}</p>
                    
                    <div class="flex flex-wrap gap-4 pt-6 border-t border-surface-50">
                        @if($program->status === 'RECOMMENDED')
                            <button type="button" @click="pksModal = true" class="px-6 py-2 bg-amber-500 text-white text-sm font-bold rounded-xl hover:bg-amber-600 transition-all">Upload Draft PKS</button>
                        @elseif($program->status === 'PKS_PENDING')
                            <button type="button" 
                                @click="publishConfirmModal = true"
                                @disabled(!$program->pks || $program->pks->status !== 'ACTIVE')
                                class="px-6 py-2 bg-nu-green-600 text-white text-sm font-bold rounded-xl hover:bg-nu-green-700 transition-all disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed">
                                Publish Program
                            </button>
                            @if(!$program->pks || $program->pks->status !== 'ACTIVE')
                                <div class="text-[10px] text-amber-600 font-bold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    Verifikasi PKS untuk Publish
                                </div>
                            @endif
                        @elseif($program->status === 'PUBLISHED')
                            <button type="button" 
                                @click="unpublishConfirmModal = true"
                                class="px-6 py-2 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition-all">
                                Unpublish Program
                            </button>
                        @endif

                        @if(in_array($program->status, ['PUBLISHED', 'COMPLETED', 'FUNDED', 'IMPLEMENTING']))
                            <button type="button" 
                                @click="extendModal = true"
                                class="px-6 py-2 bg-nu-gold-500 text-white text-sm font-bold rounded-xl hover:bg-nu-gold-600 transition-all shadow-sm">
                                Perpanjang Periode
                            </button>
                        @endif

                        @if($program->rab_document)
                            <button type="button" 
                                @click="openDoc('{{ Storage::url($program->rab_document) }}', 'Dokumen RAB: {{ addslashes($program->title) }}')"
                                class="px-6 py-2 border border-surface-200 text-surface-700 text-sm font-bold rounded-xl hover:bg-surface-50 transition-all">
                                Lihat RAB
                            </button>
                        @endif

                        @if($program->implementation_plan_doc)
                            <button type="button" 
                                @click="openDoc('{{ Storage::url($program->implementation_plan_doc) }}', 'Rencana Pelaksanaan: {{ addslashes($program->title) }}')"
                                class="px-6 py-2 border border-surface-200 text-surface-700 text-sm font-bold rounded-xl hover:bg-surface-50 transition-all">
                                Lihat Rencana Pelaksanaan
                            </button>
                        @endif

                        @if($program->pelaksana->institution_profile_doc)
                            <button type="button" 
                                @click="openDoc('{{ Storage::url($program->pelaksana->institution_profile_doc) }}', 'Profil Lembaga: {{ addslashes($program->pelaksana->name) }}')"
                                class="px-6 py-2 border border-surface-200 text-surface-700 text-sm font-bold rounded-xl hover:bg-surface-50 transition-all">
                                Lihat Profil Lembaga
                            </button>
                        @endif

                        @if($program->pelaksana->lsp_certificate)
                            <button type="button" 
                                @click="openDoc('{{ Storage::url($program->pelaksana->lsp_certificate) }}', 'SK Pelaksana: {{ addslashes($program->pelaksana->name) }}')"
                                class="px-6 py-2 border border-surface-200 text-surface-700 text-sm font-bold rounded-xl hover:bg-surface-50 transition-all">
                                Lihat SK Pelaksana
                            </button>
                        @endif

                        @if(in_array($program->status, ['PUBLISHED', 'FUNDED', 'IMPLEMENTING']))
                            <button type="button" 
                                @click="offlineModal = true"
                                class="px-6 py-2 bg-nu-green-600 text-white text-sm font-bold rounded-xl hover:bg-nu-green-700 transition-all shadow-md flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                Catat Dana Offline
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Financial Stats -->
                <div class="bg-white rounded-2xl shadow-card border border-surface-100 p-6">
                    <h3 class="font-bold text-surface-900 mb-6">Informasi Keuangan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-4 rounded-xl bg-surface-50 border border-surface-100 text-center">
                            <div class="text-[10px] text-surface-400 uppercase font-bold mb-1">Target Dana</div>
                            <div class="text-lg font-bold text-surface-900">Rp {{ number_format($program->target_amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="p-4 rounded-xl bg-nu-green-50 border border-nu-green-100 text-center">
                            <div class="text-[10px] text-nu-green-600 uppercase font-bold mb-1">Terkumpul</div>
                            <div class="text-lg font-bold text-nu-green-700">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="p-4 rounded-xl bg-surface-50 border border-surface-100 text-center">
                            <div class="text-[10px] text-surface-400 uppercase font-bold mb-1">Penerima Manfaat</div>
                            <div class="text-lg font-bold text-surface-900">{{ number_format($program->beneficiary_count) }} Jiwa</div>
                        </div>
                    </div>
                </div>

                <!-- PKS Verification -->
                @if($program->pks)
                <div class="bg-white rounded-2xl shadow-card border border-surface-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-surface-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Verifikasi PKS
                        </h3>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase
                            {{ match($program->pks->status) {
                                'DRAFT' => 'bg-surface-100 text-surface-500',
                                'SIGNED' => 'bg-amber-100 text-amber-700',
                                'ACTIVE' => 'bg-nu-green-100 text-nu-green-700',
                                default => 'bg-surface-50 text-surface-400'
                            } }}">
                            {{ $program->pks->status }}
                        </span>
                    </div>

                    <div class="p-4 rounded-xl border border-surface-100 bg-surface-50 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-[10px] font-bold text-surface-400 uppercase">Nomor PKS</div>
                                <div class="text-sm font-bold text-surface-900">{{ $program->pks->pks_number }}</div>
                            </div>
                            <button type="button" @click="openDoc('{{ Storage::url($program->pks->document_path) }}', 'PKS: {{ $program->pks->pks_number }}')"
                                class="p-2 bg-white rounded-lg border border-surface-200 text-nu-green-600 hover:text-nu-green-700 transition-colors shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                        </div>

                        @if($program->pks->status === 'SIGNED')
                            <form action="{{ route('admin.programs.pks.verify', $program) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-2.5 bg-nu-green-600 text-white text-sm font-bold rounded-xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-100">
                                    Verifikasi & Aktifkan PKS
                                </button>
                            </form>
                        @elseif($program->pks->status === 'DRAFT')
                            <div class="text-[10px] text-surface-400 italic text-center">Menunggu pelaksana mengunggah PKS bertanda tangan.</div>
                        @else
                            <div class="flex items-center gap-2 text-nu-green-600 justify-center py-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span class="text-xs font-bold uppercase tracking-widest">Telah Diverifikasi</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Reports History -->
                <div class="bg-white rounded-2xl shadow-card border border-surface-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-surface-900 flex items-center gap-2 text-lg">
                            <svg class="w-6 h-6 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Laporan Realisasi
                        </h3>
                    </div>
                    <div class="space-y-4">
                        @forelse($program->reports()->latest()->get() as $report)
                            <div class="p-6 rounded-2xl border border-surface-100 bg-surface-50/50">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="font-bold text-surface-900 text-base">{{ $report->title }}</div>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                                        {{ $report->status === 'VALIDATED' ? 'bg-nu-green-100 text-nu-green-700' : ($report->status === 'REJECTED' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ $report->status }}
                                    </span>
                                </div>
                                <div class="text-[10px] text-surface-400 font-bold uppercase tracking-widest mb-3">{{ $report->report_period }}</div>
                                
                                <div class="flex items-center gap-2 text-[10px] font-bold text-surface-400 uppercase mb-3">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    KIRIM: {{ $report->created_at->format('d/m/Y H:i') }}
                                </div>

                                <p class="text-sm text-surface-600 leading-relaxed mb-4 line-clamp-3 italic">
                                    {{ $report->description }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-black text-nu-green-700">
                                        Realisasi: Rp {{ number_format($report->realized_amount, 0, ',', '.') }}
                                    </div>
                                    <button type="button" 
                                        @click="openReport({ 
                                            title: '{{ addslashes($report->title) }}', 
                                            period: '{{ addslashes($report->report_period) }}', 
                                            date: '{{ $report->created_at->format('d M Y') }}',
                                            description: '{{ addslashes(str_replace(["\r", "\n"], ["", "\\n"], $report->description)) }}',
                                            amount: 'Rp {{ number_format($report->realized_amount, 0, ',', '.') }}',
                                            status: '{{ $report->status }}',
                                            photos: {{ json_encode($report->evidence_photos ? array_map(fn($p) => Storage::url($p), $report->evidence_photos) : []) }},
                                            docs: {{ json_encode($report->evidence_docs ? array_map(fn($d) => ["url" => Storage::url($d), "name" => basename($d)], $report->evidence_docs) : []) }}
                                        })"
                                        class="text-[11px] font-bold text-surface-400 hover:text-nu-green-600 transition-colors">
                                        Detail Laporan &raquo;
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-surface-400 text-sm italic bg-surface-50 rounded-2xl border border-dashed border-surface-200">
                                Belum ada laporan yang dikirim oleh pelaksana.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Pelaksana Info -->
                <div class="bg-white rounded-2xl shadow-card border border-surface-100 p-6">
                    <h3 class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-4">Profil Pelaksana</h3>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-nu-green-600 flex items-center justify-center text-white font-bold">
                            {{ substr($program->pelaksana->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold text-surface-900">{{ $program->pelaksana->name }}</div>
                            <div class="text-[10px] text-surface-500">{{ $program->pelaksana->email }}</div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-surface-50 text-[11px] text-surface-600 space-y-2">
                        <div class="flex justify-between"><span>Lokasi</span><span>{{ $program->location_city }}</span></div>
                        <div class="flex justify-between"><span>Diusulkan</span><span>{{ $program->created_at->format('d M Y') }}</span></div>
                    </div>
                </div>

                <!-- Admin Status Log Timeline -->
                <div class="bg-surface-50 rounded-2xl border border-surface-200 p-6">
                    <h3 class="text-xs font-bold text-surface-900 uppercase tracking-wider mb-6">Riwayat Status & Log</h3>
                    <div class="space-y-6 relative before:absolute before:left-1.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-surface-200">
                        <!-- Submission Log -->
                        <div class="relative pl-8">
                            <div class="absolute left-0 top-1.5 w-3 h-3 rounded-full bg-nu-green-600 border-2 border-white shadow-sm ring-4 ring-nu-green-50"></div>
                            <div>
                                <div class="text-[10px] font-black text-surface-400 uppercase">{{ $program->created_at->format('d/m/Y H:i') }}</div>
                                <div class="text-xs font-bold text-surface-900">Proposal Diajukan</div>
                                <div class="text-[10px] text-surface-500">Oleh: {{ $program->pelaksana->name }}</div>
                            </div>
                        </div>

                        <!-- Review Logs -->
                        @foreach($program->reviews as $review)
                            <div class="relative pl-8 group cursor-pointer" @click="reviewData = @js($review); reviewDetailModal = true;">
                                <div class="absolute left-0 top-1.5 w-3 h-3 rounded-full border-2 border-white shadow-sm ring-4 
                                    {{ match($review->decision) {
                                        'RECOMMENDED' => 'bg-nu-green-500 ring-nu-green-50',
                                        'REVISION_NEEDED' => 'bg-amber-500 ring-amber-50',
                                        'REJECTED' => 'bg-red-500 ring-red-50',
                                        default => 'bg-surface-400 ring-surface-50'
                                    } }}"></div>
                                <div>
                                    <div class="text-[10px] font-black text-surface-400 uppercase">{{ $review->created_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-xs font-bold flex items-center gap-2 
                                        {{ match($review->decision) {
                                            'RECOMMENDED' => 'text-nu-green-700',
                                            'REVISION_NEEDED' => 'text-amber-700',
                                            'REJECTED' => 'text-red-700',
                                            default => 'text-surface-900'
                                        } }}">
                                        {{ str_replace('_', ' ', $review->decision) }}
                                        <span class="px-1.5 py-0.5 rounded bg-surface-100 text-surface-500 text-[9px] font-black">Score: {{ $review->total_score }}/40</span>
                                    </div>
                                    <div class="text-[10px] text-surface-500">Penilai: {{ $review->reviewer->name }}</div>
                                    <div class="mt-1 text-[10px] text-nu-green-600 font-bold flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        Klik untuk detail <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Publication Log -->
                        @if($program->published_at)
                            <div class="relative pl-8">
                                <div class="absolute left-0 top-1.5 w-3 h-3 rounded-full bg-blue-600 border-2 border-white shadow-sm ring-4 ring-blue-50"></div>
                                <div>
                                    <div class="text-[10px] font-black text-surface-400 uppercase">{{ $program->published_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-xs font-bold text-surface-900">Program Dipublikasikan</div>
                                    <div class="text-[10px] text-surface-500">Oleh: Admin</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Offline Donations History -->
                <div class="bg-white rounded-2xl border border-surface-200 p-6 shadow-sm mt-6">
                    <h3 class="text-xs font-bold text-surface-900 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Riwayat Dana Offline
                    </h3>
                    <div class="space-y-4">
                        @forelse($program->donations as $offDonation)
                            <div class="p-4 rounded-xl bg-surface-50 border border-surface-100 flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-nu-green-100 flex items-center justify-center text-nu-green-700 font-bold text-xs">
                                        {{ substr($offDonation->donor_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-surface-900">{{ $offDonation->donor_name }}</div>
                                        <div class="text-[10px] text-surface-400 font-medium uppercase tracking-widest">{{ $offDonation->paid_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-black text-nu-green-700">Rp {{ number_format($offDonation->amount, 0, ',', '.') }}</div>
                                    @if($offDonation->payment_proof)
                                        <button type="button" @click="openDoc('{{ Storage::url($offDonation->payment_proof) }}', 'Bukti Offline: {{ addslashes($offDonation->donor_name) }}', '{{ Str::endsWith(strtolower($offDonation->payment_proof), '.pdf') ? 'pdf' : 'image' }}')"
                                            class="text-[9px] font-bold text-nu-green-600 hover:underline uppercase tracking-tighter">
                                            Lihat Bukti &raquo;
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-surface-400 text-xs italic">Belum ada dana offline untuk program ini.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>


        <!-- Report Detail Modal -->
        <div x-show="reportModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 lg:p-6"
            x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-xl" @click="reportModal = false"></div>
            <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden border border-white/20">
                <div class="px-8 py-6 border-b border-surface-100 flex items-center justify-between bg-surface-50 shrink-0">
                    <div>
                        <h3 class="text-xl font-bold text-surface-900" x-text="reportData.title"></h3>
                        <div class="text-xs text-surface-400 font-bold uppercase tracking-widest mt-1" x-text="reportData.period + ' | ' + reportData.date"></div>
                    </div>
                    <button @click="reportModal = false" class="p-2 hover:bg-surface-200 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-8 space-y-6">
                    <div class="flex justify-between items-center p-4 bg-nu-green-50 rounded-2xl border border-nu-green-100">
                        <span class="text-xs font-bold text-nu-green-600 uppercase tracking-wider">Total Realisasi</span>
                        <span class="text-xl font-black text-nu-green-700" x-text="reportData.amount"></span>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-surface-400 uppercase tracking-widest mb-2">Deskripsi Kegiatan</h4>
                        <p class="text-sm text-surface-600 leading-relaxed bg-surface-50 p-4 rounded-xl border border-surface-100" x-text="reportData.description"></p>
                    </div>

                    <template x-if="reportData.photos.length > 0">
                        <div>
                            <h4 class="text-xs font-bold text-surface-400 uppercase tracking-widest mb-3">Dokumentasi Foto</h4>
                            <div class="grid grid-cols-4 gap-2">
                                <template x-for="photo in reportData.photos" :key="photo">
                                    <button @click="openDoc(photo, 'Dokumentasi: ' + reportData.title, 'image')" 
                                        class="aspect-square rounded-lg overflow-hidden border border-surface-200 hover:border-nu-green-600 transition-all">
                                        <img :src="photo" class="w-full h-full object-cover">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="reportData.docs.length > 0">
                        <div>
                            <h4 class="text-xs font-bold text-surface-400 uppercase tracking-widest mb-3">Dokumen Pendukung</h4>
                            <div class="space-y-2">
                                <template x-for="doc in reportData.docs" :key="doc.url">
                                    <button @click="openDoc(doc.url, 'Dokumen: ' + doc.name)"
                                        class="w-full flex items-center justify-between p-3 bg-surface-50 rounded-xl border border-surface-100 hover:border-nu-green-600 group transition-all">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            <span class="text-xs font-medium text-surface-700" x-text="doc.name"></span>
                                        </div>
                                        <svg class="w-4 h-4 text-surface-300 group-hover:text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Review Detail Modal -->
        <template x-if="reviewDetailModal">
            <div class="fixed inset-0 z-[120] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="reviewDetailModal = false"></div>
                <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden animate-zoom-in flex flex-col max-h-[90vh]">
                    <div class="px-8 py-6 border-b border-surface-50 bg-surface-50 shrink-0 flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-surface-900">Detail Penilaian</h3>
                            <div class="text-xs text-surface-400 font-bold uppercase tracking-widest mt-1" x-text="'Penilai: ' + reviewData.reviewer.name"></div>
                        </div>
                        <button @click="reviewDetailModal = false" class="p-2 hover:bg-surface-200 rounded-full transition-colors">
                            <svg class="w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto p-8 space-y-8">
                        <!-- Scores Grid -->
                        <div class="grid grid-cols-2 gap-6">
                            <div class="p-5 rounded-2xl bg-nu-green-50 border border-nu-green-100">
                                <div class="text-[10px] text-nu-green-600 font-bold uppercase mb-2 tracking-wider">Skor Kelayakan (5C)</div>
                                <div class="text-3xl font-black text-nu-green-700" x-text="(parseInt(reviewData.score_character) + parseInt(reviewData.score_capacity) + parseInt(reviewData.score_capital) + parseInt(reviewData.score_collateral) + parseInt(reviewData.score_condition)) + '/25'"></div>
                                <div class="mt-4 space-y-1.5">
                                    <div class="flex justify-between text-[9px] font-bold text-nu-green-600/70"><span>CHARACTER</span><span x-text="reviewData.score_character"></span></div>
                                    <div class="flex justify-between text-[9px] font-bold text-nu-green-600/70"><span>CAPACITY</span><span x-text="reviewData.score_capacity"></span></div>
                                    <div class="flex justify-between text-[9px] font-bold text-nu-green-600/70"><span>CAPITAL</span><span x-text="reviewData.score_capital"></span></div>
                                    <div class="flex justify-between text-[9px] font-bold text-nu-green-600/70"><span>COLLATERAL</span><span x-text="reviewData.score_collateral"></span></div>
                                    <div class="flex justify-between text-[9px] font-bold text-nu-green-600/70"><span>CONDITION</span><span x-text="reviewData.score_condition"></span></div>
                                </div>
                            </div>
                            <div class="p-5 rounded-2xl bg-purple-50 border border-purple-100">
                                <div class="text-[10px] text-purple-600 font-bold uppercase mb-2 tracking-wider">Skor Dampak (3P)</div>
                                <div class="text-3xl font-black text-purple-700" x-text="(parseInt(reviewData.score_people) + parseInt(reviewData.score_planet) + parseInt(reviewData.score_profit)) + '/15'"></div>
                                <div class="mt-4 space-y-1.5">
                                    <div class="flex justify-between text-[9px] font-bold text-purple-600/70"><span>PEOPLE</span><span x-text="reviewData.score_people"></span></div>
                                    <div class="flex justify-between text-[9px] font-bold text-purple-600/70"><span>PLANET</span><span x-text="reviewData.score_planet"></span></div>
                                    <div class="flex justify-between text-[9px] font-bold text-purple-600/70"><span>PROFIT</span><span x-text="reviewData.score_profit"></span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-xs font-bold text-surface-400 uppercase tracking-widest mb-3">Analisis 5C</h4>
                                <p class="text-sm text-surface-600 leading-relaxed bg-surface-50 p-4 rounded-xl border border-surface-100 italic" x-text="'&ldquo;' + reviewData.notes_5c + '&rdquo;'"></p>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-surface-400 uppercase tracking-widest mb-3">Analisis 3P</h4>
                                <p class="text-sm text-surface-600 leading-relaxed bg-surface-50 p-4 rounded-xl border border-surface-100 italic" x-text="'&ldquo;' + reviewData.notes_3p + '&rdquo;'"></p>
                            </div>
                            <div class="pt-6 border-t border-surface-100">
                                <h4 class="text-xs font-bold text-surface-400 uppercase tracking-widest mb-3">Keputusan & Alasan</h4>
                                <div class="p-4 rounded-xl flex items-start gap-4" :class="reviewData.decision === 'RECOMMENDED' ? 'bg-nu-green-50 border border-nu-green-100' : (reviewData.decision === 'REVISION_NEEDED' ? 'bg-amber-50 border border-amber-100' : 'bg-red-50 border border-red-100')">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" :class="reviewData.decision === 'RECOMMENDED' ? 'bg-nu-green-600 text-white' : (reviewData.decision === 'REVISION_NEEDED' ? 'bg-amber-600 text-white' : 'bg-red-600 text-white')">
                                        <svg x-show="reviewData.decision === 'RECOMMENDED'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        <svg x-show="reviewData.decision === 'REVISION_NEEDED'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        <svg x-show="reviewData.decision === 'REJECTED'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold" :class="reviewData.decision === 'RECOMMENDED' ? 'text-nu-green-900' : (reviewData.decision === 'REVISION_NEEDED' ? 'text-amber-900' : 'text-red-900')" x-text="reviewData.decision.replace('_', ' ')"></div>
                                        <p class="text-xs mt-1" :class="reviewData.decision === 'RECOMMENDED' ? 'text-nu-green-700' : (reviewData.decision === 'REVISION_NEEDED' ? 'text-amber-700' : 'text-red-700')" x-text="reviewData.decision_notes"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-8 py-6 bg-surface-50 border-t border-surface-100 shrink-0">
                        <button @click="reviewDetailModal = false" class="w-full py-3 bg-surface-900 text-white font-bold rounded-2xl hover:bg-black transition-all">
                            Tutup Detail
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <!-- PKS Upload Modal -->
        <div x-show="pksModal" class="fixed inset-0 z-[120] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="pksModal = false"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-zoom-in">
                <div class="px-8 py-6 border-b border-surface-50 bg-surface-50 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-surface-900">Upload Draft PKS</h3>
                    <button @click="pksModal = false" class="p-2 hover:bg-surface-200 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form action="{{ route('admin.programs.pks', $program) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 mb-4">
                        <p class="text-xs text-amber-800 leading-relaxed">
                            Silakan unggah draft PKS dalam format <strong>Word (.doc, .docx)</strong>. Dokumen ini akan diunduh dan ditandatangani oleh pelaksana.
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-surface-400 uppercase mb-2">Pilih File Word</label>
                        <input type="file" name="pks_document" accept=".doc,.docx" required
                            class="block w-full text-sm text-surface-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-surface-900 file:text-white hover:file:bg-black cursor-pointer bg-surface-50 rounded-2xl border border-surface-200 p-2">
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 bg-nu-green-600 text-white font-bold rounded-2xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-200">
                            Simpan & Kirim ke Pelaksana
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Publish Confirm Modal -->
        <div x-show="publishConfirmModal" class="fixed inset-0 z-[130] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="publishConfirmModal = false"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden animate-zoom-in p-8 text-center">
                <div class="w-20 h-20 bg-nu-green-50 rounded-full flex items-center justify-center mx-auto mb-6 text-nu-green-600">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-surface-900 mb-2">Publikasikan Program?</h3>
                <p class="text-sm text-surface-500 mb-8 leading-relaxed">Program ini akan tampil di katalog publik dan dapat mulai menerima donasi dari wakif.</p>
                <div class="flex flex-col gap-3">
                    <form action="{{ route('admin.programs.publish', $program) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-nu-green-600 text-white font-bold rounded-2xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-100">
                            Ya, Publikasikan Sekarang
                        </button>
                    </form>
                    <button @click="publishConfirmModal = false" class="w-full py-3 bg-surface-50 text-surface-600 font-bold rounded-2xl hover:bg-surface-100 transition-all border border-surface-200">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>

        <!-- Unpublish Confirm Modal -->
        <div x-show="unpublishConfirmModal" class="fixed inset-0 z-[130] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="unpublishConfirmModal = false"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-sm overflow-hidden animate-zoom-in p-8 text-center">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-surface-900 mb-2">Hentikan Publikasi?</h3>
                <p class="text-sm text-surface-500 mb-8 leading-relaxed">Program akan disembunyikan dari katalog publik dan tidak dapat menerima donasi baru.</p>
                <div class="flex flex-col gap-3">
                    <form action="{{ route('admin.programs.unpublish', $program) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-100">
                            Ya, Hentikan Publikasi
                        </button>
                    </form>
                    <button @click="unpublishConfirmModal = false" class="w-full py-3 bg-surface-50 text-surface-600 font-bold rounded-2xl hover:bg-surface-100 transition-all border border-surface-200">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>

        <!-- Extend Period Modal -->
        <div x-show="extendModal" class="fixed inset-0 z-[130] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="extendModal = false"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-zoom-in">
                <div class="px-8 py-6 border-b border-surface-50 bg-surface-50 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-surface-900">Perpanjang Periode</h3>
                    <button @click="extendModal = false" class="p-2 hover:bg-surface-200 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form action="{{ route('admin.programs.extend', $program) }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-surface-400 uppercase mb-2">Periode Berakhir Saat Ini</label>
                        <div class="p-3 bg-surface-50 rounded-xl border border-surface-200 text-sm font-bold text-surface-600">
                            {{ $program->end_date->format('d M Y') }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-surface-400 uppercase mb-2">Pilih Tanggal Baru</label>
                        <input type="date" name="end_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            value="{{ $program->end_date->addMonth()->format('Y-m-d') }}"
                            class="w-full rounded-2xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                        <p class="text-[10px] text-surface-400 mt-2 italic">* Program akan kembali dapat menerima wakaf jika diperpanjang (dan belum 100%).</p>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 bg-nu-green-600 text-white font-bold rounded-2xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-200">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Offline Donation Modal -->
        <div x-show="offlineModal" class="fixed inset-0 z-[130] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="offlineModal = false"></div>
            <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden animate-zoom-in">
                <div class="px-8 py-6 border-b border-surface-50 bg-surface-50 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-surface-900">Catat Dana Offline</h3>
                    <button @click="offlineModal = false" class="p-2 hover:bg-surface-200 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form action="{{ route('admin.programs.offline-donation', $program) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
                    @csrf
                    <div class="p-4 bg-nu-green-50 rounded-2xl border border-nu-green-100 text-xs text-nu-green-800 leading-relaxed">
                        Fitur ini digunakan untuk mencatat dana yang masuk langsung ke rekening LWP PBNU (Offline) agar tercatat dalam sistem dan menambah progres program.
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1.5 tracking-widest">Identitas Pengirim / Nama Wakif</label>
                            <input type="text" name="donor_name" required placeholder="Contoh: H. Ahmad"
                                class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                        </div>
                        
                        <div x-data="{ 
                            rawAmount: '', 
                            get formattedAmount() {
                                if (!this.rawAmount) return '';
                                return new Intl.NumberFormat('id-ID').format(this.rawAmount);
                            },
                            updateAmount(val) {
                                this.rawAmount = val.replace(/\D/g, '');
                            }
                        }">
                            <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1.5 tracking-widest">Jumlah Dana (Rp)</label>
                            <input type="text" 
                                :value="formattedAmount"
                                @input="updateAmount($event.target.value)"
                                required placeholder="Contoh: 1.000.000"
                                class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600 font-bold text-nu-green-700">
                            <input type="hidden" name="amount" :value="rawAmount">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1.5 tracking-widest">Tanggal Transfer</label>
                            <input type="date" name="paid_at" required max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                                class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1.5 tracking-widest">Bukti Transfer (Image/PDF)</label>
                            <input type="file" name="payment_proof" required accept="image/*,application/pdf"
                                class="block w-full text-xs text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-surface-900 file:text-white hover:file:bg-black cursor-pointer bg-surface-50 rounded-xl border border-surface-200 p-1.5">
                        </div>

                        <div class="col-span-2">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="is_anonymous" value="1" class="rounded text-nu-green-600 focus:ring-nu-green-500">
                                <span class="text-xs text-surface-600 font-medium group-hover:text-surface-900 transition-colors">Tampilkan sebagai Hamba Allah (Anonim)</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-surface-50 flex gap-3">
                        <button type="button" @click="offlineModal = false" class="flex-1 py-3 bg-surface-50 text-surface-600 font-bold rounded-2xl hover:bg-surface-100 transition-all text-sm border border-surface-200">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-3 bg-nu-green-600 text-white font-bold rounded-2xl hover:bg-nu-green-700 transition-all text-sm shadow-lg shadow-nu-green-100">
                            Simpan Dana
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>
