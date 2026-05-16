<x-dashboard-layout>
    @section('title', 'Template Laporan')
    @section('page-title', 'Template Laporan')

    @section('sidebar-nav')
        @include('dashboard.pelaksana.partials.nav')
    @endsection

    <div class="space-y-6">
        <div class="bg-white p-8 rounded-[32px] shadow-card border border-surface-100 bg-gradient-to-br from-white to-surface-50/30">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-nu-green-100 flex items-center justify-center text-nu-green-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-surface-900">Daftar Template Resmi</h3>
                    <p class="text-sm text-surface-500">Unduh dokumen standar untuk pengusulan dan pelaporan program.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($templates as $template)
                <div class="group bg-white p-6 rounded-2xl border border-surface-100 hover:border-nu-green-200 hover:shadow-lg transition-all duration-300">
                    <div class="w-10 h-10 rounded-xl bg-surface-50 flex items-center justify-center text-surface-400 group-hover:bg-nu-green-50 group-hover:text-nu-green-600 mb-4 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    </div>
                    <h4 class="font-black text-surface-900 text-sm mb-1 group-hover:text-nu-green-700 transition-colors">{{ $template->name }}</h4>
                    <p class="text-[11px] text-surface-500 mb-6 min-h-[32px] line-clamp-2 italic">{{ $template->description ?? 'Template resmi WAKAF NUSA' }}</p>
                    
                    <a href="{{ Storage::url($template->file_path) }}" download class="flex items-center justify-center gap-2 w-full py-2 bg-surface-900 text-white text-[11px] font-black rounded-xl hover:bg-black transition-all shadow-md">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        UNDUH TEMPLATE
                    </a>
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-surface-400 italic text-sm">
                    Belum ada template yang tersedia. Silakan hubungi Admin.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-dashboard-layout>
