@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" class="w-full">
    {{-- Mobile Pagination (< sm) --}}
    <div class="flex sm:hidden items-center justify-between gap-3 w-full">
        {{-- Previous Icon Button --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" aria-label="Sebelumnya" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-300 cursor-not-allowed border border-slate-200/60 shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya" class="w-10 h-10 rounded-xl flex items-center justify-center bg-white text-slate-700 hover:text-nu-green-600 hover:bg-slate-50 border border-slate-200 shadow-sm active:scale-95 transition-all shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        @endif

        {{-- Page Counter Indicator --}}
        <div class="text-center px-3 py-2 rounded-xl bg-slate-50 border border-slate-100 flex-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hal</span>
            <span class="text-xs font-black text-nu-green-700 font-mono mx-1">{{ $paginator->currentPage() }}</span>
            <span class="text-[10px] font-bold text-slate-300">/</span>
            <span class="text-xs font-black text-slate-700 font-mono ml-1">{{ number_format($paginator->lastPage(), 0, ',', '.') }}</span>
        </div>

        {{-- Next Icon Button --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya" class="w-10 h-10 rounded-xl flex items-center justify-center bg-white text-slate-700 hover:text-nu-green-600 hover:bg-slate-50 border border-slate-200 shadow-sm active:scale-95 transition-all shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @else
            <span aria-disabled="true" aria-label="Berikutnya" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-300 cursor-not-allowed border border-slate-200/60 shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </span>
        @endif
    </div>

    {{-- Desktop Pagination (>= sm) --}}
    <div class="hidden sm:flex sm:items-center sm:justify-end gap-1">
        {{-- Previous Icon Button --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 text-slate-300 border border-slate-200/60 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-9 h-9 rounded-xl flex items-center justify-center bg-white text-slate-600 hover:text-nu-green-600 hover:bg-nu-green-50 border border-slate-200 transition-colors shadow-sm" title="Sebelumnya">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        @endif

        {{-- Page Numbers (Smart Slider) --}}
        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();
            
            $pages = [];
            $pages[] = 1;
            
            $start = max(2, $current - 1);
            $end = min($last - 1, $current + 1);
            
            for ($i = $start; $i <= $end; $i++) {
                $pages[] = $i;
            }
            
            if ($last > 1) {
                $pages[] = $last;
            }
            
            $pages = array_unique($pages);
            sort($pages);
        @endphp

        @php $prevPage = 0; @endphp
        @foreach ($pages as $p)
            @if ($prevPage !== 0 && $p - $prevPage > 1)
                <span class="w-9 h-9 flex items-center justify-center text-xs font-bold text-slate-400 select-none">...</span>
            @endif

            @if ($p == $current)
                <span class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black bg-nu-green-600 text-white shadow-md shadow-nu-green-600/20 select-none">
                    {{ $p }}
                </span>
            @else
                <a href="{{ $paginator->url($p) }}" class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold text-slate-700 hover:text-nu-green-700 hover:bg-nu-green-50 border border-slate-200 bg-white transition-colors">
                    {{ $p }}
                </a>
            @endif

            @php $prevPage = $p; @endphp
        @endforeach

        {{-- Next Icon Button --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-9 h-9 rounded-xl flex items-center justify-center bg-white text-slate-600 hover:text-nu-green-600 hover:bg-nu-green-50 border border-slate-200 transition-colors shadow-sm" title="Berikutnya">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @else
            <span aria-disabled="true" class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 text-slate-300 border border-slate-200/60 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </span>
        @endif
    </div>
</nav>
@endif
