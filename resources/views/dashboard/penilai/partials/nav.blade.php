<a href="{{ route('penilai.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg {{ request()->routeIs('penilai.dashboard') ? 'bg-nu-green-600/20 text-nu-green-400 font-medium' : 'text-surface-400 hover:bg-surface-800 hover:text-white transition-colors' }}">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
    Dashboard
</a>
<a href="{{ route('catalog') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg {{ request()->routeIs('catalog') || request()->routeIs('program.show') ? 'bg-nu-green-600/20 text-nu-green-400 font-medium' : 'text-surface-400 hover:bg-surface-800 hover:text-white transition-colors' }}">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
    Program Wakaf
</a>
<a href="{{ route('penilai.queue') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg {{ request()->routeIs('penilai.queue') || request()->routeIs('penilai.reviews.show') ? 'bg-nu-green-600/20 text-nu-green-400 font-medium' : 'text-surface-400 hover:bg-surface-800 hover:text-white transition-colors' }}">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
    Antrean Review
</a>
<a href="{{ route('penilai.history') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg {{ request()->routeIs('penilai.history') ? 'bg-nu-green-600/20 text-nu-green-400 font-medium' : 'text-surface-400 hover:bg-surface-800 hover:text-white transition-colors' }}">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    Riwayat Review
</a>
<a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-nu-green-600/20 text-nu-green-400 font-medium' : 'text-surface-400 hover:bg-surface-800 hover:text-white transition-colors' }}">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
    Profil Saya
</a>
