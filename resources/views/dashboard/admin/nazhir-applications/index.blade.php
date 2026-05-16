<x-dashboard-layout>
    @section('title', 'Manajemen Pengajuan Nazhir')
    @section('page-title', 'Manajemen Pengajuan Nazhir')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div x-data="{ 
        rejectModal: false,
        detailModal: false,
        docModal: false,
        docUrl: '',
        docType: '',
        selectedApplication: null,
        openDetail(app) {
            this.selectedApplication = app;
            this.detailModal = true;
        },
        openReject(app) {
            this.selectedApplication = app;
            this.rejectModal = true;
        },
        previewDoc(url) {
            this.docUrl = url;
            this.docType = url.toLowerCase().endsWith('.pdf') ? 'pdf' : 'image';
            this.docModal = true;
        }
    }" class="space-y-6">
        
        <!-- Filters -->
        <div class="bg-white rounded-3xl shadow-card border border-surface-100 p-8">
            <form action="{{ route('admin.nazhir-applications.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Nama Wakif</label>
                    <input type="text" name="name" value="{{ request('name') }}" class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600" placeholder="Cari nama wakif...">
                </div>
                <div class="w-48">
                    <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Tgl Pengajuan</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                </div>
                <div class="w-48">
                    <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Filter Status</label>
                    <select name="status" class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                        <option value="">Semua Status</option>
                        <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="APPROVED" {{ request('status') === 'APPROVED' ? 'selected' : '' }}>Disetujui</option>
                        <option value="REJECTED" {{ request('status') === 'REJECTED' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <button type="submit" class="bg-nu-green-600 text-white px-8 py-2.5 rounded-xl hover:bg-nu-green-700 transition-colors text-xs font-bold uppercase tracking-wider">
                    Filter
                </button>
                @if(request()->anyFilled(['status', 'name', 'date']))
                    <a href="{{ route('admin.nazhir-applications.index') }}" class="bg-surface-100 text-surface-500 px-8 py-2.5 rounded-xl hover:bg-surface-200 transition-colors text-xs font-bold uppercase tracking-wider flex items-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl shadow-card border border-surface-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-50 text-surface-400 text-[10px] uppercase font-bold tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Nama Wakif</th>
                            <th class="px-8 py-4">Tgl Pengajuan</th>
                            <th class="px-8 py-4">Status</th>
                            <th class="px-8 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse ($applications as $app)
                            <tr class="hover:bg-surface-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-nu-green-50 flex items-center justify-center text-nu-green-600 font-bold text-xs">
                                            {{ substr($app->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ $app->user->name }}</div>
                                            <div class="text-[10px] text-surface-400">{{ $app->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="space-y-1">
                                        <div class="text-[10px] font-medium text-surface-500 flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-surface-300"></span>
                                            Diajukan: {{ $app->created_at->format('d/m/Y H:i') }}
                                        </div>
                                        @if($app->status !== 'PENDING')
                                            <div class="text-[10px] font-black flex items-center gap-1.5 {{ $app->status === 'APPROVED' ? 'text-nu-green-600' : 'text-red-600' }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $app->status === 'APPROVED' ? 'bg-nu-green-500' : 'bg-red-500' }}"></span>
                                                {{ $app->status === 'APPROVED' ? 'Disetujui' : 'Ditolak' }}: {{ $app->updated_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    @if($app->status === 'PENDING')
                                        <span class="px-3 py-1 bg-nu-gold-50 text-nu-gold-600 rounded-full text-[10px] font-bold uppercase tracking-widest">Menunggu</span>
                                    @elseif($app->status === 'APPROVED')
                                        <span class="px-3 py-1 bg-nu-green-50 text-nu-green-600 rounded-full text-[10px] font-bold uppercase tracking-widest">Disetujui</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-bold uppercase tracking-widest">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button @click="openDetail({
                                            id: {{ $app->id }},
                                            name: '{{ addslashes($app->user->name) }}',
                                            institution: '{{ addslashes($app->institution_name) }}',
                                            short_profile: '{{ addslashes($app->institution_short_profile) }}',
                                            phone: '{{ $app->phone }}',
                                            address: '{{ addslashes($app->address) }}',
                                            bank_name: '{{ addslashes($app->bank_name) }}',
                                            bank_acc_name: '{{ addslashes($app->bank_account_name) }}',
                                            bank_no: '{{ $app->bank_account_number }}',
                                            profile: '{{ Storage::url($app->institution_profile_path) }}',
                                            cert: '{{ Storage::url($app->lsp_certificate_path) }}',
                                            status: '{{ $app->status }}',
                                            reason: '{{ addslashes($app->rejection_reason) }}',
                                            updated_at: '{{ $app->updated_at->format('d M Y H:i') }}'
                                        })" class="text-[10px] font-black text-nu-green-600 uppercase tracking-widest hover:underline">
                                            Detail & Tinjau
                                        </button>
                                        @if($app->status === 'PENDING')
                                            <form action="{{ route('admin.nazhir-applications.approve', $app) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Setujui pengajuan ini? Peran akun akan berubah menjadi Pelaksana.')" class="bg-nu-green-600 text-white px-3 py-1 rounded text-[10px] font-bold uppercase tracking-widest hover:bg-nu-green-700 transition-colors">Setujui</button>
                                            </form>
                                            <button @click="openReject({
                                                id: {{ $app->id }},
                                                name: '{{ addslashes($app->user->name) }}'
                                            })" class="bg-red-600 text-white px-3 py-1 rounded text-[10px] font-bold uppercase tracking-widest hover:bg-red-700 transition-colors">Tolak</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-8 py-16 text-center text-surface-400 italic">Belum ada pengajuan pelaksana nazhir.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($applications->hasPages())
                <div class="px-8 py-6 bg-surface-50 border-t border-surface-100">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>

        <!-- Modal: Detail Pengajuan -->
        <div x-show="detailModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 lg:p-6" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="detailModal = false"></div>
            <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl overflow-hidden animate-zoom-in flex flex-col max-h-[90vh]">
                <div class="px-8 py-6 border-b border-surface-50 bg-surface-50 flex items-center justify-between shrink-0">
                    <h3 class="text-xl font-bold text-surface-900">Detail Pengajuan Nazhir</h3>
                    <button @click="detailModal = false" class="p-2 hover:bg-surface-200 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <template x-if="selectedApplication">
                <div class="flex-1 overflow-y-auto p-8 space-y-8">
                    <!-- Section: Identitas -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-nu-green-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            <span class="text-xs font-black uppercase tracking-widest">Identitas Pelaksana Nazhir</span>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="p-4 bg-surface-50 rounded-2xl border border-surface-100">
                                <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Nama Lembaga</div>
                                <div class="text-sm font-bold text-surface-900" x-text="selectedApplication.institution"></div>
                            </div>
                            <div class="p-4 bg-surface-50 rounded-2xl border border-surface-100">
                                <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Nomor HP</div>
                                <div class="text-sm font-bold text-surface-900" x-text="selectedApplication.phone"></div>
                            </div>
                        </div>
                        <div class="p-4 bg-surface-50 rounded-2xl border border-surface-100">
                            <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Profil Singkat Lembaga</div>
                            <div class="text-sm text-surface-600 leading-relaxed" x-text="selectedApplication.short_profile"></div>
                        </div>
                        <div class="p-4 bg-surface-50 rounded-2xl border border-surface-100">
                            <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Alamat Lengkap</div>
                            <div class="text-sm text-surface-600 leading-relaxed" x-text="selectedApplication.address"></div>
                        </div>
                    </div>

                    <!-- Section: Rekening -->
                    <div class="space-y-4 pt-4 border-t border-surface-100">
                        <div class="flex items-center gap-2 text-nu-green-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            <span class="text-xs font-black uppercase tracking-widest">Administrasi Rekening</span>
                        </div>
                        <div class="p-4 bg-surface-50 rounded-2xl border border-surface-100">
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-[10px] text-surface-400 font-bold uppercase tracking-widest">Nama Bank</span>
                                    <span class="text-sm font-bold text-surface-900" x-text="selectedApplication.bank_name"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[10px] text-surface-400 font-bold uppercase tracking-widest">Atas Nama</span>
                                    <span class="text-sm font-bold text-surface-900" x-text="selectedApplication.bank_acc_name"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[10px] text-surface-400 font-bold uppercase tracking-widest">Nomor Rekening</span>
                                    <span class="text-sm font-black text-surface-900" x-text="selectedApplication.bank_no"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button @click="previewDoc(selectedApplication.profile)" class="p-4 bg-nu-green-50 rounded-2xl border border-nu-green-100 flex items-center gap-3 hover:bg-nu-green-100 transition-colors group">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-nu-green-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <div class="text-[10px] font-black text-nu-green-600 uppercase tracking-widest group-hover:underline">Profil / SK →</div>
                        </button>
                        <button @click="previewDoc(selectedApplication.cert)" class="p-4 bg-nu-green-50 rounded-2xl border border-nu-green-100 flex items-center gap-3 hover:bg-nu-green-100 transition-colors group">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-nu-green-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <div class="text-[10px] font-black text-nu-green-600 uppercase tracking-widest group-hover:underline">Sertifikat LSP →</div>
                        </button>
                    </div>

                    <template x-if="selectedApplication.status !== 'PENDING'">
                        <div class="p-4 rounded-2xl border" :class="selectedApplication.status === 'APPROVED' ? 'bg-nu-green-50 border-nu-green-100' : 'bg-red-50 border-red-100'">
                            <div class="flex justify-between items-center mb-2">
                                <div class="text-[10px] font-bold uppercase tracking-widest" :class="selectedApplication.status === 'APPROVED' ? 'text-nu-green-600' : 'text-red-600'">
                                    <span x-text="selectedApplication.status === 'APPROVED' ? 'Diverifikasi Pada' : 'Ditolak Pada'"></span>
                                </div>
                                <div class="text-sm font-bold text-surface-900" x-text="selectedApplication.updated_at"></div>
                            </div>
                            <template x-if="selectedApplication.status === 'REJECTED'">
                                <div class="mt-2 pt-2 border-t border-red-100">
                                    <div class="text-[10px] font-bold text-red-600 uppercase tracking-widest mb-1">Alasan Penolakan</div>
                                    <div class="text-sm text-surface-700 italic" x-text="'&quot;' + selectedApplication.reason + '&quot;'"></div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
                </template>
                <div class="p-8 bg-surface-50 border-t border-surface-100 shrink-0 flex gap-4" x-show="selectedApplication && selectedApplication.status === 'PENDING'">
                    <form :action="'/dashboard/admin/nazhir-applications/' + selectedApplication.id + '/approve'" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-4 bg-nu-green-600 text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-600/20">Setujui Pengajuan</button>
                    </form>
                    <button @click="detailModal = false; rejectModal = true" class="flex-1 py-4 bg-red-600 text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-red-700 transition-all">Tolak Pengajuan</button>
                </div>
                <div class="p-8 bg-surface-50 border-t border-surface-100 shrink-0" x-show="selectedApplication && selectedApplication.status !== 'PENDING'">
                    <button @click="detailModal = false" class="w-full py-4 bg-surface-900 text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-black transition-all">Tutup Detail</button>
                </div>
            </div>
        </div>

        <!-- Modal: Tolak Pengajuan -->
        <div x-show="rejectModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 lg:p-6" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="rejectModal = false"></div>
            <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden animate-zoom-in">
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-600 mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-surface-900 mb-2">Tolak Pengajuan Nazhir</h3>
                    <p class="text-sm text-surface-500 mb-6">Berikan alasan penolakan untuk <span class="font-bold text-surface-900" x-text="selectedApplication?.name"></span></p>
                    
                    <form :action="'/dashboard/admin/nazhir-applications/' + (selectedApplication ? selectedApplication.id : '') + '/reject'" method="POST">
                        @csrf
                        <textarea name="reason" rows="4" required class="w-full rounded-2xl border-surface-200 text-sm mb-6 focus:ring-red-600 focus:border-red-600" placeholder="Contoh: Dokumen Sertifikat LSP-BWI tidak valid atau sudah kadaluwarsa..."></textarea>
                        
                        <div class="flex gap-4">
                            <button type="button" @click="rejectModal = false" class="flex-1 py-3 border border-surface-200 text-surface-500 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-surface-50 transition-colors">Batal</button>
                            <button type="submit" class="flex-1 py-3 bg-red-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-red-700 transition-colors">Kirim Penolakan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Preview Dokumen -->
        <div x-show="docModal" class="fixed inset-0 z-[120] flex items-center justify-center p-4 lg:p-12" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="docModal = false"></div>
            <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-5xl h-full overflow-hidden animate-zoom-in flex flex-col">
                <div class="px-8 py-4 border-b border-surface-50 bg-surface-50 flex items-center justify-between shrink-0">
                    <h3 class="text-lg font-bold text-surface-900">Preview Dokumen</h3>
                    <div class="flex items-center gap-2">
                        <a :href="docUrl" target="_blank" class="px-4 py-2 bg-nu-green-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-nu-green-700 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            Buka Tab Baru
                        </a>
                        <button @click="docModal = false" class="p-2 hover:bg-surface-200 rounded-full transition-colors">
                            <svg class="w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
                <div class="flex-1 bg-surface-100 overflow-hidden relative">
                    <template x-if="docType === 'pdf'">
                        <embed :src="docUrl" type="application/pdf" class="w-full h-full">
                    </template>
                    <template x-if="docType === 'image'">
                        <div class="w-full h-full flex items-center justify-center p-8 overflow-auto">
                            <img :src="docUrl" class="max-w-full max-h-full shadow-2xl rounded-lg">
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
</x-dashboard-layout>
