<x-dashboard-layout>
    @section('title', 'Manajemen Anggota Tim')
    @section('page-title', 'Anggota Tim')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div x-data="{ 
        deleteModal: false,
        deleteUrl: '',
        deleteName: '',
        openDelete(url, name) {
            this.deleteUrl = url;
            this.deleteName = name;
            this.deleteModal = true;
        }
    }" class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.team-members.index') }}" class="px-4 py-2 rounded-xl border border-surface-200 text-sm font-bold {{ !request('category') ? 'bg-nu-green-600 text-white border-nu-green-600' : 'bg-white text-surface-600 hover:bg-surface-50' }} transition-all">Semua</a>
                <a href="{{ route('admin.team-members.index', ['category' => 'NAZHIR']) }}" class="px-4 py-2 rounded-xl border border-surface-200 text-sm font-bold {{ request('category') === 'NAZHIR' ? 'bg-nu-green-600 text-white border-nu-green-600' : 'bg-white text-surface-600 hover:bg-surface-50' }} transition-all">Tim Nazhir</a>
                <a href="{{ route('admin.team-members.index', ['category' => 'PENILAI']) }}" class="px-4 py-2 rounded-xl border border-surface-200 text-sm font-bold {{ request('category') === 'PENILAI' ? 'bg-nu-green-600 text-white border-nu-green-600' : 'bg-white text-surface-600 hover:bg-surface-50' }} transition-all">Tim Penilai</a>
                <a href="{{ route('admin.team-members.index', ['category' => 'PENGEMBANG']) }}" class="px-4 py-2 rounded-xl border border-surface-200 text-sm font-bold {{ request('category') === 'PENGEMBANG' ? 'bg-nu-green-600 text-white border-nu-green-600' : 'bg-white text-surface-600 hover:bg-surface-50' }} transition-all">Tim Pengembang</a>
            </div>
            <a href="{{ route('admin.team-members.create') }}" class="px-6 py-2.5 bg-nu-green-600 text-white text-sm font-bold rounded-xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-100 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Tambah Anggota
            </a>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl shadow-card border border-surface-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-50 text-surface-400 text-[10px] uppercase font-bold tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Nama & Foto</th>
                            <th class="px-8 py-4">Jabatan</th>
                            <th class="px-8 py-4">Kategori</th>
                            <th class="px-8 py-4">Urutan</th>
                            <th class="px-8 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse ($members as $member)
                            <tr class="hover:bg-surface-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-surface-100 overflow-hidden shrink-0 border border-surface-200">
                                            @if($member->photo)
                                                <img src="{{ Storage::url($member->photo) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-surface-300">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-sm font-bold text-surface-900">{{ $member->name }}</div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm text-surface-600 font-medium">{{ $member->position }}</td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                        {{ match($member->category) {
                                            'NAZHIR' => 'bg-nu-green-100 text-nu-green-700',
                                            'PENILAI' => 'bg-blue-100 text-blue-700',
                                            'PENGEMBANG' => 'bg-purple-100 text-purple-700',
                                            default => 'bg-surface-100 text-surface-500'
                                        } }}">
                                        {{ $member->category }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-sm font-bold text-surface-400">#{{ $member->order_weight }}</td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.team-members.edit', $member) }}" class="p-2 bg-surface-50 rounded-lg text-surface-400 hover:text-nu-green-600 hover:bg-nu-green-50 transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <button type="button" @click="openDelete('{{ route('admin.team-members.destroy', $member) }}', '{{ addslashes($member->name) }}')" 
                                            class="p-2 bg-surface-50 rounded-lg text-surface-400 hover:text-red-600 hover:bg-red-50 transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center text-surface-400 italic font-medium">Belum ada anggota tim yang ditambahkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($members->hasPages())
                <div class="px-8 py-4 bg-surface-50 border-t border-surface-100">
                    {{ $members->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="deleteModal" class="fixed inset-0 z-[130] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="deleteModal = false"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden animate-zoom-in p-8 text-center">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </div>
                <h3 class="text-xl font-bold text-surface-900 mb-2">Hapus Anggota Tim?</h3>
                <p class="text-sm text-surface-500 mb-8 leading-relaxed">
                    Apakah Anda yakin ingin menghapus <span class="font-bold text-surface-900" x-text="deleteName"></span>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex flex-col gap-3">
                    <form :action="deleteUrl" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-100">
                            Ya, Hapus Sekarang
                        </button>
                    </form>
                    <button @click="deleteModal = false" class="w-full py-3 bg-surface-50 text-surface-600 font-bold rounded-2xl hover:bg-surface-100 transition-all border border-surface-200">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
