<x-dashboard-layout>
    @section('title', 'Detail Laporan — ' . $report->title)
    @section('page-title', 'Detail Laporan Realisasi')

    @section('sidebar-nav')
        @include('dashboard.pelaksana.partials.nav')
    @endsection

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white rounded-[32px] shadow-card border border-surface-100 overflow-hidden">
            <!-- Header -->
            <div class="p-8 border-b border-surface-50 bg-surface-50/50">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ match($report->status) {
                                    'SUBMITTED' => 'bg-amber-100 text-amber-700',
                                    'VALIDATED' => 'bg-nu-green-100 text-nu-green-700',
                                    'REJECTED' => 'bg-red-100 text-red-700',
                                    default => 'bg-surface-100 text-surface-600'
                                } }}">
                                {{ $report->status }}
                            </span>
                            <span class="text-xs text-surface-400 font-bold uppercase tracking-wider">{{ $report->report_period }}</span>
                        </div>
                        <h2 class="text-2xl font-black text-surface-900">{{ $report->title }}</h2>
                        <p class="text-nu-green-600 font-bold text-sm mt-1">{{ $report->program->title }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-[10px] uppercase font-bold text-surface-400 mb-1">Total Realisasi</div>
                        <div class="text-2xl font-black text-surface-900">Rp {{ number_format($report->realized_amount, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-surface-100">
                    <div>
                        <div class="text-[10px] uppercase font-bold text-surface-400 mb-1">Tanggal Kirim</div>
                        <div class="text-sm font-bold text-surface-700">{{ $report->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    @if($report->validated_at)
                    <div>
                        <div class="text-[10px] uppercase font-bold text-surface-400 mb-1">Divalidasi Pada</div>
                        <div class="text-sm font-bold text-surface-700">{{ $report->validated_at->format('d M Y') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 space-y-8">
                <div>
                    <h3 class="text-sm font-black text-surface-900 uppercase tracking-widest mb-4">Deskripsi Kegiatan</h3>
                    <p class="text-surface-600 leading-relaxed text-sm whitespace-pre-line">{{ $report->description }}</p>
                </div>

                @if($report->status === 'REJECTED')
                <div class="p-6 rounded-2xl bg-red-50 border border-red-100">
                    <h4 class="text-sm font-bold text-red-800 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Catatan Penolakan
                    </h4>
                    <p class="text-xs text-red-700 italic">"{{ $report->rejection_notes ?? 'Laporan memerlukan perbaikan pada bagian dokumentasi bukti.' }}"</p>
                </div>
                @endif

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Photos -->
                    <div>
                        <h3 class="text-sm font-black text-surface-900 uppercase tracking-widest mb-4">Foto Dokumentasi</h3>
                        @if(!empty($report->evidence_photos))
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($report->evidence_photos as $photo)
                            <div class="group relative aspect-square rounded-xl overflow-hidden bg-surface-100 cursor-pointer border border-surface-200"
                                 @click="$dispatch('open-doc-preview', { url: '{{ Storage::url($photo) }}', title: 'Foto Bukti', type: 'image' })">
                                <img src="{{ Storage::url($photo) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-xs text-surface-400 italic">Tidak ada foto dokumentasi.</p>
                        @endif
                    </div>

                    <!-- Documents -->
                    <div>
                        <h3 class="text-sm font-black text-surface-900 uppercase tracking-widest mb-4">Dokumen Pendukung</h3>
                        @if(!empty($report->evidence_docs))
                        <div class="space-y-3">
                            @foreach($report->evidence_docs as $doc)
                            <div class="flex items-center justify-between p-4 rounded-xl border border-surface-100 bg-surface-50 group hover:border-nu-green-200 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-nu-green-100 flex items-center justify-center text-nu-green-600">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                    <span class="text-xs font-bold text-surface-700 truncate max-w-[150px]">Lampiran PDF</span>
                                </div>
                                <button type="button" 
                                        @click="$dispatch('open-doc-preview', { url: '{{ Storage::url($doc) }}', title: 'Lampiran Laporan', type: 'pdf' })"
                                        class="text-xs font-black text-nu-green-600 hover:text-nu-green-700">PRATINJAU</button>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-xs text-surface-400 italic">Tidak ada dokumen pendukung.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="px-8 py-6 bg-surface-50 border-t border-surface-100 flex justify-end">
                <a href="{{ route('pelaksana.reports.index') }}" class="px-6 py-2.5 rounded-xl border border-surface-200 text-sm font-bold text-surface-600 hover:bg-surface-100 transition-all">Kembali ke Daftar</a>
            </div>
        </div>
    </div>
</x-dashboard-layout>
