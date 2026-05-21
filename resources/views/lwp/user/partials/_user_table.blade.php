<div class="overflow-x-auto table-container">
    <table class="w-full border-collapse text-left">
        <thead>
            <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                <th class="px-8 py-5 text-center" width="8%">No</th>
                <th class="px-8 py-5">Nama Pengguna</th>
                <th class="px-8 py-5">Username</th>
                <th class="px-8 py-5">Email / Telepon</th>
                <th class="px-8 py-5">Level / Cabang</th>
                <th class="px-8 py-5">Wilayah</th>
                <th class="px-8 py-5 text-center" width="20%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $usr)
            <tr class="hover:bg-slate-50/30 transition-colors border-b border-slate-50 group {{ $usr->ID_USER == Auth::guard('lwp')->id() ? 'bg-nu-green-50/20' : '' }}">
                <td class="px-8 py-5 text-center font-bold text-slate-400 text-xs">
                    {{ $users->firstItem() + $index }}
                </td>
                <td class="px-8 py-5">
                    <span class="text-xs font-black text-slate-900 group-hover:text-nu-green-700 transition-colors inline-flex items-center gap-1.5">
                        {{ $usr->NAMA_USER }}
                        @if($usr->ID_USER == Auth::guard('lwp')->id())
                            <span class="inline-flex px-1.5 py-0.5 rounded-md text-[8px] font-black bg-nu-green-600 text-white uppercase tracking-wider">Anda</span>
                        @endif
                    </span>
                    @if($usr->NO_KARTANU)
                        <span class="text-[9px] font-bold text-slate-400 uppercase block mt-0.5">Kartanu: {{ $usr->NO_KARTANU }}</span>
                    @endif
                </td>
                <td class="px-8 py-5">
                    <span class="text-xs font-bold text-slate-600 block">{{ $usr->USERNAME_USER }}</span>
                </td>
                <td class="px-8 py-5">
                    <span class="text-xs font-medium text-slate-700 block">{{ $usr->EMAIL_USER }}</span>
                    @if($usr->TELP_USER)
                        <span class="text-[10px] font-semibold text-slate-400 block mt-0.5">{{ $usr->TELP_USER }}</span>
                    @endif
                </td>
                <td class="px-8 py-5">
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $usr->LEVEL == 9 ? 'bg-red-50 text-red-700' : ($usr->LEVEL == 91 ? 'bg-amber-50 text-amber-700' : ($usr->LEVEL == 31 ? 'bg-nu-green-50 text-nu-green-700' : 'bg-blue-50 text-blue-700')) }}">
                        {{ $usr->NAMA_LEVEL }}
                    </span>
                    @if($usr->NAMA_CABANG)
                        <span class="text-[10px] font-bold text-slate-400 block mt-1">{{ $usr->NAMA_CABANG }}</span>
                    @endif
                </td>
                <td class="px-8 py-5">
                    <span class="text-xs font-black text-slate-800">{{ $usr->KODE_LOKASI ?: '-' }}</span>
                    @if($usr->PROV)
                        <span class="text-[9px] font-semibold text-slate-400 block mt-0.5">
                            {{ $usr->PROV }}{{ $usr->KAB ? ' > '.$usr->KAB : '' }}
                        </span>
                    @endif
                </td>
                <td class="px-8 py-5 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                        <!-- Detail Button -->
                        <button onclick="showUserDetail({{ $usr->ID_USER }})" class="p-2 text-slate-400 hover:text-nu-green-600 hover:bg-slate-50 rounded-xl transition-all" title="Detail Pengguna">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        <!-- Edit Button -->
                        <button onclick="editUser({{ $usr->ID_USER }})" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-slate-50 rounded-xl transition-all" title="Ubah Pengguna">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <!-- Reset Password Button -->
                        <button onclick="openResetPassword({{ $usr->ID_USER }}, '{{ $usr->NAMA_USER }}')" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-slate-50 rounded-xl transition-all" title="Reset Sandi">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-5-4v1a3 3 0 00-3 3v1M8 11v-5a2 2 0 012-2h2a2 2 0 012 2v5m-6 0h6m-6 0a6 6 0 1012 0H6z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </button>
                        <!-- Delete Button -->
                        <button onclick="confirmDeleteUser({{ $usr->ID_USER }}, '{{ $usr->NAMA_USER }}')" class="p-2 text-slate-400 hover:text-red-600 hover:bg-slate-50 rounded-xl transition-all" title="Hapus Pengguna">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-8 py-20 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-black text-slate-800 mb-1">Tidak Ada Pengguna</h4>
                    <p class="text-slate-400 text-xs font-medium max-w-xs mx-auto">Kami tidak dapat menemukan data pengguna yang cocok atau aktif.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Footer -->
@if($users->hasPages())
<div class="p-8 border-t border-slate-50 user-pagination">
    {{ $users->links() }}
</div>
@endif
