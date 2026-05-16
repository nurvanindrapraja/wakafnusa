<x-dashboard-layout>
    @section('title', 'Antrean Review Program')
    @section('page-title', 'Antrean Review Program')

    @section('sidebar-nav')
        @include('dashboard.penilai.partials.nav')
    @endsection

    <!-- Filters -->
    <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100 mb-8">
        <form action="{{ route('penilai.queue') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-surface-400 uppercase mb-2">Cari Program / Pelaksana</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" 
                    placeholder="Nama program atau pelaksana...">
            </div>
            <div>
                <label class="block text-xs font-bold text-surface-400 uppercase mb-2">Tanggal Masuk</label>
                <input type="date" name="date" value="{{ request('date') }}" 
                    class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-nu-green-600 text-white font-bold py-2.5 rounded-xl hover:bg-nu-green-700 transition-all">
                    Filter
                </button>
                <a href="{{ route('penilai.queue') }}" class="px-4 py-2.5 bg-surface-100 text-surface-600 font-bold rounded-xl hover:bg-surface-200 transition-all text-center">
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
                        <th class="px-6 py-3 font-semibold">Pelaksana</th>
                        <th class="px-6 py-3 font-semibold">Target Dana</th>
                        <th class="px-6 py-3 font-semibold">Tanggal Masuk</th>
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
                                <div class="text-sm text-surface-600">{{ $program->pelaksana->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-surface-900">Rp {{ number_format($program->target_amount, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-surface-500">{{ $program->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('penilai.reviews.show', $program) }}" class="inline-flex items-center gap-2 px-4 py-1.5 bg-nu-green-600 text-white text-xs font-bold rounded-lg hover:bg-nu-green-700 transition-all">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-surface-500 italic">Data tidak ditemukan.</td>
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
