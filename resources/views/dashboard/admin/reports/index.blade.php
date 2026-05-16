<x-dashboard-layout>
    @section('title', 'Validasi Laporan')
    @section('page-title', 'Validasi Laporan')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div x-data="{ pdfModal: false, pdfUrl: '', pdfTitle: '' }" class="relative">
        <div class="space-y-6">
        @forelse ($pendingReports as $report)
            <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
                <div class="p-6 border-b border-surface-100 flex items-center justify-between bg-surface-50">
                    <div>
                        <h3 class="font-bold text-surface-900">{{ $report->title }}</h3>
                        <div class="text-xs text-surface-500">Program: <span class="font-bold text-nu-green-600">{{ $report->program->title }}</span> | Periode: {{ $report->report_period }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-[10px] uppercase font-bold text-surface-400">Realisasi Dana</div>
                        <div class="text-lg font-bold text-nu-green-700">Rp {{ number_format($report->realized_amount, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-sm font-bold text-surface-800 mb-2">Deskripsi Kegiatan</h4>
                        <p class="text-sm text-surface-600 leading-relaxed">{{ $report->description }}</p>
                        
                        <div class="mt-6">
                            <h4 class="text-xs font-bold text-surface-800 mb-3 uppercase tracking-wider">Bukti Dokumentasi</h4>
                            <div class="flex flex-wrap gap-2">
                                @if($report->evidence_photos)
                                    @foreach($report->evidence_photos as $photo)
                                        <button type="button" 
                                            @click="pdfModal = true; pdfUrl = '{{ Storage::url($photo) }}'; pdfTitle = 'Bukti Dokumentasi'"
                                            class="w-16 h-16 rounded-lg overflow-hidden border border-surface-100 hover:border-nu-green-600 transition-all">
                                            <img src="{{ Storage::url($photo) }}" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                @endif
                                @if($report->evidence_docs)
                                    @foreach($report->evidence_docs as $doc)
                                        <button type="button"
                                            @click="pdfModal = true; pdfUrl = '{{ Storage::url($doc) }}'; pdfTitle = 'Dokumen Pendukung'"
                                            class="w-16 h-16 rounded-lg bg-red-50 flex items-center justify-center text-red-600 border border-red-100 hover:bg-red-100 transition-all">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                        </button>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-surface-50 p-6 rounded-2xl border border-surface-100">
                        <h4 class="text-sm font-bold text-surface-800 mb-4">Validasi Admin</h4>
                        <form action="{{ route('admin.reports.validate', $report) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-surface-600 mb-2 uppercase">Keputusan</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status" value="VALIDATED" class="sr-only peer" required>
                                        <div class="p-3 rounded-xl border-2 border-surface-200 text-center transition-all peer-checked:border-nu-green-600 peer-checked:bg-nu-green-600 peer-checked:text-white font-bold text-xs">
                                            Setujui
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status" value="REJECTED" class="sr-only peer">
                                        <div class="p-3 rounded-xl border-2 border-surface-200 text-center transition-all peer-checked:border-red-600 peer-checked:bg-red-600 peer-checked:text-white font-bold text-xs">
                                            Tolak / Revisi
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-surface-600 mb-2 uppercase">Catatan (Jika ditolak)</label>
                                <textarea name="rejection_notes" rows="3" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Berikan alasan jika laporan memerlukan revisi..."></textarea>
                            </div>
                            <button type="submit" class="w-full py-3 bg-surface-900 text-white font-bold rounded-xl hover:bg-black transition-all">
                                Simpan Validasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-2xl shadow-card border border-surface-100 text-center">
                <div class="w-16 h-16 rounded-full bg-nu-green-50 text-nu-green-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h3 class="font-bold text-surface-800 text-lg">Semua Laporan Tervalidasi</h3>
                <p class="text-sm text-surface-500">Tidak ada laporan baru yang memerlukan tindakan saat ini.</p>
            </div>
        @endforelse
    </div>

    <!-- PDF/Image Modal -->
    <div x-show="pdfModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-surface-900/60 backdrop-blur-xl p-4 lg:p-6"
        x-cloak>
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-6xl h-[98vh] flex flex-col overflow-hidden border border-white/20"
            @click.away="pdfModal = false">
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
                        <span class="text-[10px] text-surface-400 uppercase tracking-widest font-bold">Preview Dokumen</span>
                    </div>
                </h3>
                <div class="flex items-center gap-3">
                    <a :href="pdfUrl" target="_blank"
                        class="px-5 py-2.5 bg-nu-green-50 text-nu-green-600 hover:bg-nu-green-100 text-xs font-bold rounded-xl flex items-center gap-2 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Buka Penuh
                    </a>
                    <button @click="pdfModal = false"
                        class="px-5 py-2.5 bg-surface-900 text-white hover:bg-black text-xs font-bold rounded-xl transition-all shadow-lg shadow-black/10">
                        Tutup
                    </button>
                </div>
            </div>
            <div class="flex-1 bg-surface-100 p-4 overflow-hidden">
                <div class="w-full h-full bg-white rounded-xl shadow-inner overflow-hidden border border-surface-200">
                    <iframe :src="pdfUrl" class="w-full h-full border-none"></iframe>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-dashboard-layout>
