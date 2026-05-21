@if($deletedUsers->count() > 0)
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto table-container">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-50">
                    <th class="px-6 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">ID</th>
                    <th class="px-6 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Nama</th>
                    <th class="px-6 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Email</th>
                    <th class="px-6 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">No HP</th>
                    <th class="px-6 py-4 text-center text-[9px] font-black text-slate-400 uppercase tracking-widest">Level</th>
                    <th class="px-6 py-4 text-center text-[9px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($deletedUsers as $user)
                <tr class="hover:bg-red-50/30 transition-colors">
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 bg-red-50 text-red-600 text-[10px] font-black uppercase rounded-full">#{{ $user->ID_USER }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-black text-slate-900">{{ $user->NAMA_USER }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-600">{{ $user->EMAIL_USER }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-600">{{ $user->TELP_USER ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $levelLabels = [
                                1 => 'Viewer',
                                2 => 'MWCNU',
                                3 => 'PCNU',
                                9 => 'Admin',
                                31 => 'PWNU',
                                91 => 'PBNU',
                            ];
                        @endphp
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-black uppercase rounded-full">{{ $levelLabels[$user->LEVEL] ?? 'Level '.$user->LEVEL }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button onclick="restoreUser({{ $user->ID_USER }})" class="p-2 text-nu-green-600 hover:bg-nu-green-50 rounded-xl transition-all" title="Pulihkan">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            </button>
                            <button onclick="permanentDeleteUser({{ $user->ID_USER }})" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Hapus Permanen">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6 trash-ajax-pagination" data-container="trashUserContainer">
    {{ $deletedUsers->appends(['tab' => 'users', 'search_user' => request('search_user')])->links() }}
</div>
@else
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-16 text-center">
    <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
    </div>
    <h3 class="text-lg font-black text-slate-900 mb-2">Tempat Sampah Kosong</h3>
    <p class="text-sm text-slate-400 font-medium">Tidak ada data pengguna yang dihapus.</p>
</div>
@endif
