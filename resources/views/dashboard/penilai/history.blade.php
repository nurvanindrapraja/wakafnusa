<x-dashboard-layout>
    @section('title', 'Riwayat Review Program')
    @section('page-title', 'Riwayat Review Program')

    @section('sidebar-nav')
        @include('dashboard.penilai.partials.nav')
    @endsection

    <!-- Filters -->
    <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100 mb-8">
        <form action="{{ route('penilai.history') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-surface-400 uppercase mb-2">Keputusan</label>
                <select name="decision" 
                    class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600"
                    onchange="this.form.submit()">
                    <option value="">Semua Keputusan</option>
                    <option value="DISETUJU" {{ request('decision') == 'DISETUJU' ? 'selected' : '' }}>Direkomendasikan (Disetujui)</option>
                    <option value="REVISI" {{ request('decision') == 'REVISI' ? 'selected' : '' }}>Perlu Revisi</option>
                    <option value="DITOLAK" {{ request('decision') == 'DITOLAK' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-nu-green-600 text-white font-bold py-2.5 rounded-xl hover:bg-nu-green-700 transition-all">
                    Filter
                </button>
                <a href="{{ route('penilai.history') }}" class="px-4 py-2.5 bg-surface-100 text-surface-600 font-bold rounded-xl hover:bg-surface-200 transition-all text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-50 text-surface-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Program</th>
                        <th class="px-6 py-3 font-semibold">Keputusan</th>
                        <th class="px-6 py-3 font-semibold">Tanggal Review</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($programs as $program)
                        <tr class="hover:bg-surface-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-surface-900 text-sm">{{ $program->title }}</div>
                                <div class="text-xs text-surface-400">{{ $program->category }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ match($program->status) {
                                        'PROPOSED' => 'bg-surface-100 text-surface-600',
                                        'REVISION_NEEDED' => 'bg-amber-50 text-amber-600',
                                        'RECOMMENDED' => 'bg-blue-50 text-blue-600',
                                        'PKS_PENDING' => 'bg-indigo-50 text-indigo-600',
                                        'PUBLISHED' => 'bg-nu-green-50 text-nu-green-600',
                                        'FUNDED' => 'bg-nu-gold-50 text-nu-gold-600',
                                        'IMPLEMENTING' => 'bg-purple-50 text-purple-600',
                                        'COMPLETED' => 'bg-surface-900 text-white',
                                        'REJECTED' => 'bg-red-50 text-red-600',
                                        default => 'bg-surface-100 text-surface-600'
                                    } }}">
                                    {{ str_replace('_', ' ', $program->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-surface-500">{{ $program->reviews->where('reviewer_id', auth()->id())->first()->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('penilai.reviews.history', $program) }}" class="text-surface-400 hover:text-nu-green-600 transition-colors inline-block">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-surface-500 italic">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($programs->hasPages())
            <div class="px-6 py-4 border-t border-surface-100 bg-surface-50">
                {{ $programs->links() }}
            </div>
        @endif
    </div>
</x-dashboard-layout>
