<x-dashboard-layout>
    @section('title', 'Manajemen Dana Offline')
    @section('page-title', 'Penerimaan Dana Offline')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div x-data="{ 
        previewModal: false, 
        previewUrl: '', 
        previewTitle: '',
        openPreview(url, title) {
            this.previewUrl = url;
            this.previewTitle = title;
            this.previewModal = true;
        }
    }" class="space-y-6">
        <!-- Filters -->
        <div class="bg-white rounded-3xl shadow-card border border-surface-100 p-6">
            <form action="{{ route('admin.offline-donations.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1.5 tracking-widest">Cari Pengirim</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama pengirim..."
                        class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1.5 tracking-widest">Program Wakaf</label>
                    <input type="text" name="program" value="{{ request('program') }}" placeholder="Judul program..."
                        class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1.5 tracking-widest">Tanggal Masuk</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2.5 bg-surface-900 text-white text-xs font-bold rounded-xl hover:bg-black transition-all">Filter</button>
                    <a href="{{ route('admin.offline-donations.index') }}" class="px-4 py-2.5 bg-surface-50 text-surface-600 text-xs font-bold rounded-xl border border-surface-200 hover:bg-surface-100 transition-all text-center">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl shadow-card border border-surface-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-50 text-surface-400 text-[10px] uppercase font-bold tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Pengirim</th>
                            <th class="px-8 py-4">Program Wakaf</th>
                            <th class="px-8 py-4">Jumlah Dana</th>
                            <th class="px-8 py-4">Tgl Masuk</th>
                            <th class="px-8 py-4 text-right">Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse ($donations as $donation)
                            <tr class="hover:bg-surface-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-nu-green-50 flex items-center justify-center text-nu-green-600 font-bold text-xs">
                                            {{ substr($donation->donor_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ $donation->donor_name }}</div>
                                            @if($donation->is_anonymous)
                                                <span class="text-[9px] font-bold text-nu-gold-600 uppercase tracking-widest">Anonim</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-medium text-surface-900 line-clamp-1 max-w-[200px]" title="{{ $donation->program->title }}">
                                        {{ $donation->program->title }}
                                    </div>
                                    <div class="text-[10px] text-surface-400 uppercase tracking-widest">{{ $donation->program->category }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-black text-nu-green-700">Rp {{ number_format($donation->amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-8 py-5 text-sm text-surface-600">
                                    {{ $donation->paid_at->format('d M Y') }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    @if($donation->payment_proof)
                                        <button @click="openPreview('{{ Storage::url($donation->payment_proof) }}', 'Bukti Dana Offline: {{ addslashes($donation->donor_name) }}')"
                                            class="p-2 bg-nu-green-50 rounded-lg text-nu-green-600 hover:bg-nu-green-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </button>
                                    @else
                                        <span class="text-[10px] text-surface-300 italic">No file</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center text-surface-400 italic">Belum ada penerimaan dana offline yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($donations->hasPages())
                <div class="px-8 py-4 bg-surface-50 border-t border-surface-100">
                    {{ $donations->links() }}
                </div>
            @endif
        </div>

        <!-- Preview Modal -->
        <div x-show="previewModal" class="fixed inset-0 z-[150] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-surface-900/90 backdrop-blur-md" @click="previewModal = false"></div>
            <div class="relative bg-white rounded-[2rem] w-full max-w-4xl max-h-[90vh] overflow-hidden shadow-2xl flex flex-col animate-zoom-in">
                <div class="px-8 py-5 border-b border-surface-100 flex items-center justify-between bg-surface-50">
                    <h3 class="font-black text-surface-900 truncate pr-8" x-text="previewTitle"></h3>
                    <button @click="previewModal = false" class="p-2 hover:bg-surface-200 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="flex-1 overflow-auto p-8 bg-surface-100 flex items-center justify-center">
                    <template x-if="previewUrl.toLowerCase().endsWith('.pdf')">
                        <iframe :src="previewUrl" class="w-full h-full min-h-[60vh] rounded-xl border-none shadow-inner bg-white"></iframe>
                    </template>
                    <template x-if="!previewUrl.toLowerCase().endsWith('.pdf')">
                        <img :src="previewUrl" class="max-w-full h-auto rounded-xl shadow-xl border-4 border-white">
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
