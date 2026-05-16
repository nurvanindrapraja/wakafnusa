<x-dashboard-layout>
    @section('title', 'Manajemen Pengguna')
    @section('page-title', 'Manajemen Pengguna')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div class="space-y-6" x-data="{
        deleteModal: false,
        verifyModal: false,
        actionUrl: '',
        userName: '',
        confirmDelete(url, name) {
            this.actionUrl = url;
            this.userName = name;
            this.deleteModal = true;
        },
        confirmVerify(url, name) {
            this.actionUrl = url;
            this.userName = name;
            this.verifyModal = true;
        }
    }">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-surface-900">Daftar Pengguna Sistem</h3>
            <a href="{{ route('admin.users.create') }}" class="px-6 py-2.5 bg-nu-green-600 text-white text-sm font-bold rounded-xl hover:bg-nu-green-700 shadow-lg shadow-nu-green-600/20 transition-all">
                + Tambah Pengguna
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-card border border-surface-100 p-6">
            <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, email, atau lokasi..." class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1">Peran</label>
                    <select name="role" class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                        <option value="">Semua Peran</option>
                        <option value="ADMIN" @selected(request('role') === 'ADMIN')>Admin</option>
                        <option value="PELAKSANA" @selected(request('role') === 'PELAKSANA')>Pelaksana Nazhir</option>
                        <option value="PENILAI" @selected(request('role') === 'PENILAI')>Tim Penilai</option>
                        <option value="WAKIF" @selected(request('role') === 'WAKIF')>Wakif</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2 bg-surface-900 text-white text-sm font-bold rounded-xl hover:bg-black transition-all">Filter</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-50 text-surface-500 text-[10px] uppercase font-bold">
                        <tr>
                            <th class="px-6 py-3">Nama & Email</th>
                            <th class="px-6 py-3">Kontak & Alamat</th>
                            <th class="px-6 py-3">Peran</th>
                            <th class="px-6 py-3">Status Akun</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @foreach ($users as $user)
                            <tr class="hover:bg-surface-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-nu-green-50 text-nu-green-600 flex items-center justify-center text-xs font-bold">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ $user->name }}</div>
                                            <div class="text-[10px] text-surface-400 font-medium">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-surface-900">{{ $user->phone ?? '-' }}</div>
                                    <div class="text-[10px] text-surface-400 truncate max-w-[150px]">{{ $user->address ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        {{ match($user->role) {
                                            'ADMIN' => 'bg-surface-900 text-white',
                                            'PELAKSANA' => 'bg-nu-green-100 text-nu-green-700',
                                            'PENILAI' => 'bg-nu-gold-100 text-nu-gold-700',
                                            'WAKIF' => 'bg-blue-100 text-blue-700',
                                            default => 'bg-surface-100 text-surface-600'
                                        } }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-nu-green-100 text-nu-green-700 text-[10px] font-bold uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-nu-green-600"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-[10px] font-bold uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        @if(!$user->email_verified_at)
                                            <button type="button" 
                                                @click="confirmVerify('{{ route('admin.users.verify-email', $user) }}', '{{ addslashes($user->name) }}')"
                                                class="p-2 text-surface-400 hover:text-blue-600 transition-colors" title="Verifikasi Email Manual">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-surface-400 hover:text-nu-green-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button type="button" 
                                                @click="confirmDelete('{{ route('admin.users.destroy', $user) }}', '{{ addslashes($user->name) }}')"
                                                class="p-2 text-surface-400 hover:text-red-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="deleteModal" class="fixed inset-0 z-[130] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="deleteModal = false"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden animate-zoom-in p-8 text-center">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </div>
                <h3 class="text-xl font-bold text-surface-900 mb-2">Hapus Pengguna?</h3>
                <p class="text-sm text-surface-500 mb-8 leading-relaxed">
                    Anda akan menghapus akun <span class="font-bold text-surface-900" x-text="userName"></span>. Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex flex-col gap-3">
                    <form :action="actionUrl" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-100">
                            Ya, Hapus Permanen
                        </button>
                    </form>
                    <button @click="deleteModal = false" class="w-full py-3 bg-surface-50 text-surface-600 font-bold rounded-2xl hover:bg-surface-100 transition-all border border-surface-200">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>

        <!-- Verify Email Modal -->
        <div x-show="verifyModal" class="fixed inset-0 z-[130] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="verifyModal = false"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden animate-zoom-in p-8 text-center">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 text-blue-600">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-surface-900 mb-2">Verifikasi Email?</h3>
                <p class="text-sm text-surface-500 mb-8 leading-relaxed">
                    Verifikasi email untuk <span class="font-bold text-surface-900" x-text="userName"></span> secara manual? Pengguna akan langsung aktif.
                </p>
                <div class="flex flex-col gap-3">
                    <form :action="actionUrl" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                            Ya, Verifikasi Sekarang
                        </button>
                    </form>
                    <button @click="verifyModal = false" class="w-full py-3 bg-surface-50 text-surface-600 font-bold rounded-2xl hover:bg-surface-100 transition-all border border-surface-200">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
