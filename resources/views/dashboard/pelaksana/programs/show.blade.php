<x-dashboard-layout>
    @section('title', 'Detail Program — ' . $program->title)
    @section('page-title', 'Detail Program')

    @section('sidebar-nav')
        @include('dashboard.pelaksana.partials.nav')
    @endsection

    <div class="space-y-6" x-data="{
        reportModal: false,
        selectedReport: null,
        openReport(report) {
            this.selectedReport = report;
            this.reportModal = true;
        }
    }">
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
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-surface-50 pt-6">
                        <div>
                            <div class="text-[10px] uppercase font-bold text-surface-400 mb-1">Target Dana</div>
                            <div class="text-base font-bold text-surface-900">Rp {{ number_format($program->target_amount, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-surface-400 mb-1">Terkumpul</div>
                            <div class="text-base font-bold text-nu-green-600">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-surface-400 mb-1">Penerima Manfaat</div>
                            <div class="text-base font-bold text-surface-900">{{ number_format($program->beneficiary_count) }} Orang</div>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-surface-400 mb-1">Durasi</div>
                            <div class="text-base font-bold text-surface-900 whitespace-nowrap">{{ $program->start_date->format('d M Y') }} - {{ $program->end_date->format('d M Y') }}</div>
                        </div>
                    </div>

                    @php
                        $totalDisbursed = $program->disbursements->sum('amount');
                        $remaining = $program->collected_amount - $totalDisbursed;
                    @endphp
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-surface-50">
                        <div class="col-span-2">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-2 h-2 rounded-full bg-nu-green-600"></div>
                                <div class="text-[10px] uppercase font-bold text-surface-400">Dana Sudah Tersalur</div>
                            </div>
                            <div class="text-xl font-black text-nu-green-600">Rp {{ number_format($totalDisbursed, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-span-2">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-2 h-2 rounded-full bg-surface-400"></div>
                                <div class="text-[10px] uppercase font-bold text-surface-400">Sisa Dana Belum Salur</div>
                            </div>
                            <div class="text-xl font-black text-surface-900">Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Left Col: Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Location & Details -->
                <div class="bg-white rounded-2xl shadow-card border border-surface-100 p-6">
                    <h3 class="font-bold text-surface-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Lokasi & Rincian
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <div class="text-xs text-surface-400 mb-1">Provinsi</div>
                                <div class="text-sm font-medium text-surface-800">{{ $program->location_province }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-surface-400 mb-1">Kota/Kabupaten</div>
                                <div class="text-sm font-medium text-surface-800">{{ $program->location_city }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-surface-400 mb-1">Alamat Detail</div>
                                <div class="text-sm font-medium text-surface-800">{{ $program->location_detail ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <div class="text-xs text-surface-400 mb-1">Total RAB</div>
                                <div class="text-sm font-medium text-surface-800">Rp {{ number_format($program->rab_total, 0, ',', '.') }}</div>
                                @if($program->rab_document)
                                <button type="button" 
                                    @click="$dispatch('open-doc-preview', { url: '{{ Storage::url($program->rab_document) }}', title: 'Rencana Anggaran Biaya (RAB)' })"
                                    class="mt-1 flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold hover:bg-blue-100 transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    Lihat Dokumen RAB
                                </button>
                                @endif
                            </div>
                            @if($program->implementation_plan_doc)
                            <div>
                                <div class="text-xs text-surface-400 mb-1">Tahapan Pelaksanaan</div>
                                <button type="button" 
                                    @click="$dispatch('open-doc-preview', { url: '{{ Storage::url($program->implementation_plan_doc) }}', title: 'Rencana Tahapan Pelaksanaan' })"
                                    class="mt-1 flex items-center gap-2 px-3 py-1.5 rounded-lg bg-nu-green-50 text-nu-green-700 text-xs font-bold hover:bg-nu-green-100 transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    Lihat Dokumen
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reports History -->
                <div class="bg-white rounded-2xl shadow-card border border-surface-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-surface-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Laporan Realisasi
                        </h3>
                        @if(in_array($program->status, ['IMPLEMENTING', 'FUNDED', 'PUBLISHED']))
                            <a href="{{ route('pelaksana.reports.create', $program) }}" class="text-xs font-bold text-nu-green-600 hover:underline">+ Tambah Laporan</a>
                        @endif
                    </div>
                    <div class="space-y-4">
                        @forelse($program->reports()->latest()->get() as $report)
                            <div class="p-4 rounded-xl border border-surface-100 bg-surface-50">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="text-sm font-bold text-surface-900">{{ $report->title }}</div>
                                        <div class="text-[10px] text-surface-400 uppercase font-bold">{{ $report->report_period }}</div>
                                        <div class="mt-2 space-y-1">
                                            <div class="flex items-center gap-1.5 text-[9px] font-bold text-surface-400 uppercase">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                                Kirim: {{ $report->created_at->format('d/m/Y H:i') }}
                                            </div>
                                            @if($report->validated_at)
                                                <div class="flex items-center gap-1.5 text-[9px] font-bold text-nu-green-600 uppercase">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    Verifikasi: {{ $report->validated_at->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase
                                        {{ $report->status === 'VALIDATED' ? 'bg-nu-green-100 text-nu-green-700' : ($report->status === 'REJECTED' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ $report->status }}
                                    </span>
                                </div>
                                <p class="text-xs text-surface-600 line-clamp-2 mb-3">{{ $report->description }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="text-xs font-bold text-nu-green-700">Realisasi: Rp {{ number_format($report->realized_amount, 0, ',', '.') }}</div>
                                    <button type="button" @click="openReport({
                                        title: '{{ addslashes($report->title) }}',
                                        period: '{{ addslashes($report->report_period) }}',
                                        date: '{{ $report->created_at->format('d M Y') }}',
                                        description: '{{ addslashes(str_replace(["\r", "\n"], ["", "\\n"], $report->description)) }}',
                                        amount: 'Rp {{ number_format($report->realized_amount, 0, ',', '.') }}',
                                        status: '{{ $report->status }}',
                                        photos: {{ json_encode(array_map(fn($p) => Storage::url($p), $report->evidence_photos ?? [])) }},
                                        docs: {{ json_encode(array_map(fn($d) => Storage::url($d), $report->evidence_docs ?? [])) }},
                                    })" class="text-[10px] font-bold text-surface-400 hover:text-nu-green-600 transition-colors">
                                        Detail Laporan &raquo;
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-surface-400 text-sm italic">Belum ada laporan yang dikirim.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Col: Actions & Sidebar -->
            <div class="space-y-6">
                <!-- PKS Management -->
                @if(in_array($program->status, ['RECOMMENDED', 'PKS_PENDING', 'PUBLISHED', 'FUNDED', 'IMPLEMENTING']))
                <div class="bg-white rounded-2xl shadow-card border border-surface-100 p-6">
                    <h3 class="text-sm font-bold text-surface-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Administrasi PKS
                    </h3>
                    
                    @if(!$program->pks)
                        <div class="p-4 bg-surface-50 rounded-xl border border-surface-100 text-center">
                            <div class="text-xs font-bold text-surface-400">Menunggu Draft PKS</div>
                            <div class="text-[10px] text-surface-400 mt-1">Admin sedang menyiapkan draf perjanjian kerja sama untuk program ini.</div>
                        </div>
                    @elseif($program->pks->status === 'DRAFT')
                        <div class="space-y-4">
                            <div class="p-3 bg-nu-green-50 rounded-xl border border-nu-green-100">
                                <div class="text-[10px] font-bold text-nu-green-600 uppercase mb-1">Draft PKS Tersedia</div>
                                <div class="text-xs text-nu-green-800 mb-3">Silakan unduh draft, cetak, tanda tangani di atas materai, lalu unggah kembali dalam format PDF.</div>
                                <a href="{{ Storage::url($program->pks->document_path) }}" download 
                                    class="flex items-center justify-center gap-2 w-full py-2 bg-nu-green-600 text-white text-xs font-bold rounded-lg hover:bg-nu-green-700 transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    Unduh Draft (.docx)
                                </a>
                            </div>

                            <form action="{{ route('pelaksana.programs.pks.upload', $program) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-bold text-surface-400 uppercase mb-2">Unggah PKS Bertanda Tangan</label>
                                    <input type="file" name="signed_pks" accept=".pdf" required
                                        class="block w-full text-xs text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-surface-900 file:text-white hover:file:bg-black cursor-pointer bg-surface-50 rounded-xl border border-surface-100 p-2">
                                    <p class="mt-1 text-[10px] text-surface-400 italic">* Format PDF, Max 5MB</p>
                                </div>
                                <button type="submit" class="w-full py-2.5 bg-surface-900 text-white text-sm font-bold rounded-xl hover:bg-black transition-all">
                                    Unggah PKS Selesai
                                </button>
                            </form>
                        </div>
                    @elseif($program->pks->status === 'SIGNED')
                        <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 text-center">
                            <svg class="w-8 h-8 text-amber-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div class="text-xs font-bold text-amber-800">PKS Telah Diunggah</div>
                            <div class="text-[10px] text-amber-600 mt-1">Sedang dalam proses verifikasi oleh Admin.</div>
                        </div>
                    @elseif($program->pks->status === 'ACTIVE' || $program->pks->status === 'SIGNED')
                        <div class="p-4 bg-nu-green-50 rounded-xl border border-nu-green-100">
                            <div class="text-[10px] font-bold text-nu-green-600 uppercase mb-2">PKS Aktif</div>
                            <button type="button" @click="$dispatch('open-doc-preview', { url: '{{ Storage::url($program->pks->document_path) }}', title: 'PKS: {{ $program->pks->pks_number }}' })"
                                class="flex items-center gap-2 text-xs font-bold text-nu-green-700 hover:underline">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                Lihat Dokumen PKS
                            </button>
                        </div>
                    @endif
                </div>
                @endif

                <!-- Actions -->
                @if(in_array($program->status, ['PROPOSED', 'REVISION_NEEDED']))
                <div class="bg-white rounded-2xl shadow-card border border-surface-100 p-6">
                    <h3 class="text-sm font-bold text-surface-900 mb-4">Tindakan</h3>
                    <div class="space-y-3">
                        <a href="{{ route('pelaksana.programs.edit', $program) }}" class="block w-full py-2.5 text-center rounded-xl bg-surface-900 text-white text-sm font-bold hover:bg-black transition-all">Edit Program</a>
                        <form action="{{ route('pelaksana.programs.destroy', $program) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan usulan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full py-2.5 text-center rounded-xl border border-red-200 text-red-600 text-sm font-bold hover:bg-red-50 transition-all">Batalkan Usulan</button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Feedback -->
                @if($program->status === 'REVISION_NEEDED')
                <div class="bg-amber-50 rounded-2xl border border-amber-200 p-6">
                    <h3 class="text-sm font-bold text-amber-900 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        Catatan Revisi
                    </h3>
                    @php $latestReview = $program->reviews()->latest()->first(); @endphp
                    <p class="text-xs text-amber-800 leading-relaxed italic">
                        "{{ $latestReview->rejection_notes ?? 'Silakan periksa kembali rincian RAB dan deskripsi program Anda.' }}"
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Report Detail Modal -->
        <template x-if="reportModal">
            <div class="fixed inset-0 z-[150] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="reportModal = false"></div>
                <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-zoom-in flex flex-col max-h-[90vh]">
                    <div class="px-8 py-6 border-b border-surface-50 bg-surface-50 flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-surface-900" x-text="selectedReport.title"></h3>
                            <p class="text-xs text-surface-500 mt-1" x-text="selectedReport.period + ' | ' + selectedReport.date"></p>
                        </div>
                        <button @click="reportModal = false" class="p-2 text-surface-400 hover:text-surface-900 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <div class="p-8 overflow-y-auto space-y-6">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest"
                                :class="{
                                    'bg-nu-green-100 text-nu-green-700': selectedReport.status === 'VALIDATED',
                                    'bg-red-100 text-red-700': selectedReport.status === 'REJECTED',
                                    'bg-amber-100 text-amber-700': selectedReport.status === 'SUBMITTED'
                                }" x-text="selectedReport.status"></span>
                            <div class="text-lg font-black text-nu-green-600" x-text="selectedReport.amount"></div>
                        </div>

                        <div>
                            <h4 class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Deskripsi Realisasi</h4>
                            <p class="text-sm text-surface-600 leading-relaxed whitespace-pre-line" x-text="selectedReport.description"></p>
                        </div>

                        <template x-if="selectedReport.photos.length > 0">
                            <div>
                                <h4 class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-3">Dokumentasi Foto</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <template x-for="photo in selectedReport.photos">
                                        <div class="aspect-video rounded-xl bg-surface-100 overflow-hidden cursor-pointer group relative"
                                            @click="$dispatch('open-doc-preview', { url: photo, title: 'Dokumentasi Foto', type: 'image' })">
                                            <img :src="photo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="selectedReport.docs.length > 0">
                            <div>
                                <h4 class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-3">Dokumen Pendukung</h4>
                                <div class="space-y-2">
                                    <template x-for="(doc, index) in selectedReport.docs">
                                        <button type="button" @click="$dispatch('open-doc-preview', { url: doc, title: 'Dokumen Pendukung ' + (index + 1), type: 'pdf' })"
                                            class="w-full flex items-center gap-3 p-3 rounded-xl bg-surface-50 border border-surface-100 hover:bg-surface-100 transition-all text-left">
                                            <svg class="w-5 h-5 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            <span class="text-xs font-bold text-surface-700" x-text="'Lihat Dokumen ' + (index + 1)"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-dashboard-layout>
