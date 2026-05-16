<x-public-layout>
@section('title', 'Anggota Tim WAKAF NUSA')

<!-- Header -->
<section class="hero-pattern pt-32 pb-20 relative overflow-hidden" style="background-color: #004000;">
    <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4">Anggota Tim</h1>
        <p class="text-white max-w-2xl mx-auto text-lg opacity-80">Orang-orang di balik WAKAF NUSA yang memastikan setiap amanah wakaf dikelola dengan profesional.</p>
    </div>
</section>

<!-- Tim Penilai -->
<section class="py-24 bg-surface-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-16">
            <div class="h-px flex-1 bg-surface-200"></div>
            <h2 class="text-2xl font-black text-surface-900 uppercase tracking-widest px-6">Tim Penilai</h2>
            <div class="h-px flex-1 bg-surface-200"></div>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($groups['PENILAI'] as $member)
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-surface-100 flex items-center gap-6 group hover:border-nu-green-500 transition-all">
                <div class="w-20 h-20 rounded-2xl bg-nu-gold-500/10 flex items-center justify-center text-nu-gold-600 overflow-hidden shrink-0">
                    @if($member->photo)
                        <img src="{{ Storage::url($member->photo) }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-10 h-10 text-nu-gold-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-bold text-surface-900 group-hover:text-nu-green-600 transition-colors">{{ $member->name }}</h3>
                    <p class="text-sm text-surface-500 font-medium">{{ $member->position }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 text-surface-400 italic">Data anggota Tim Penilai sedang diperbarui.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Tim Nadzir NU -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-16">
            <div class="h-px flex-1 bg-surface-100"></div>
            <h2 class="text-2xl font-black text-surface-900 uppercase tracking-widest px-6">Tim Nazhir NU</h2>
            <div class="h-px flex-1 bg-surface-100"></div>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12">
            @forelse($groups['NAZHIR'] as $member)
            <div class="group text-center">
                <div class="relative mb-6 mx-auto w-48 h-48">
                    <div class="w-full h-full rounded-[2.5rem] bg-surface-50 flex items-center justify-center overflow-hidden border border-surface-100 group-hover:border-nu-green-500 group-hover:shadow-xl group-hover:shadow-nu-green-100 transition-all duration-500 transform group-hover:-translate-y-2">
                        @if($member->photo)
                            <img src="{{ Storage::url($member->photo) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-24 h-24 text-surface-100 group-hover:text-nu-green-50 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        @endif
                    </div>
                </div>
                <h3 class="text-xl font-bold text-surface-900 mb-1">{{ $member->name }}</h3>
                <p class="text-sm text-nu-green-600 font-bold uppercase tracking-widest">{{ $member->position }}</p>
            </div>
            @empty
            <div class="col-span-full text-center py-12 text-surface-400 italic">Data anggota Tim Nazhir NU sedang diperbarui.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Tim Pengembang Sistem -->
<section class="py-24 bg-surface-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-nu-green-600 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-nu-gold-500 rounded-full blur-[100px] translate-x-1/2 translate-y-1/2"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex items-center gap-4 mb-16">
            <div class="h-px flex-1 bg-surface-800"></div>
            <h2 class="text-2xl font-black text-white uppercase tracking-widest px-6">Tim Pengembang Sistem</h2>
            <div class="h-px flex-1 bg-surface-800"></div>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($groups['PENGEMBANG'] as $member)
            <div class="bg-surface-800/50 backdrop-blur-sm p-8 rounded-3xl border border-white/5 hover:border-nu-green-500/50 transition-all group">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-nu-green-600/20 flex items-center justify-center overflow-hidden shrink-0 border border-white/10 group-hover:border-nu-green-500 transition-colors">
                        @if($member->photo)
                            <img src="{{ Storage::url($member->photo) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8 text-nu-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white group-hover:text-nu-green-400 transition-colors">{{ $member->name }}</h3>
                        <p class="text-sm text-surface-400 font-medium">{{ $member->position }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 text-surface-500 italic">Data anggota Tim Pengembang sedang diperbarui.</div>
            @endforelse
        </div>
    </div>
</section>

</x-public-layout>
