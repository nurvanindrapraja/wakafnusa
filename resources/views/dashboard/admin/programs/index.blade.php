<x-dashboard-layout>
    @section('title', 'Manajemen Program')
    @section('page-title', 'Manajemen Program')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div x-data="{ 
        publishModal: false, 
        unpublishModal: false,
        selectedProgram: null,
        reviewData: null,
        openPublish(program) {
            this.selectedProgram = program;
            this.reviewData = program.latest_review;
            this.publishModal = true;
        },
        openUnpublish(program) {
            this.selectedProgram = program;
            this.unpublishModal = true;
        }
    }">
        <!-- Filters -->
        <div class="bg-white p-6 rounded-2xl shadow-card border border-surface-100 mb-8">
            <form action="{{ route('admin.programs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold text-surface-400 uppercase mb-2">Jenis Program</label>
                    <select name="category" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                        <option value="">Semua Jenis</option>
                        <option value="EKONOMI" {{ request('category') == 'EKONOMI' ? 'selected' : '' }}>Ekonomi</option>
                        <option value="IBADAH" {{ request('category') == 'IBADAH' ? 'selected' : '' }}>Ibadah</option>
                        <option value="PENDIDIKAN" {{ request('category') == 'PENDIDIKAN' ? 'selected' : '' }}>Pendidikan</option>
                        <option value="KESEHATAN" {{ request('category') == 'KESEHATAN' ? 'selected' : '' }}>Kesehatan</option>
                        <option value="SOSIAL" {{ request('category') == 'SOSIAL' ? 'selected' : '' }}>Sosial</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-surface-400 uppercase mb-2">Status</label>
                    <select name="status" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                        <option value="">Semua Status</option>
                        <option value="PROPOSED" {{ request('status') == 'PROPOSED' ? 'selected' : '' }}>Proposed</option>
                        <option value="REVISION_NEEDED" {{ request('status') == 'REVISION_NEEDED' ? 'selected' : '' }}>Revision Needed</option>
                        <option value="RECOMMENDED" {{ request('status') == 'RECOMMENDED' ? 'selected' : '' }}>Recommended</option>
                        <option value="PKS_PENDING" {{ request('status') == 'PKS_PENDING' ? 'selected' : '' }}>PKS Pending</option>
                        <option value="PUBLISHED" {{ request('status') == 'PUBLISHED' ? 'selected' : '' }}>Published</option>
                        <option value="IMPLEMENTING" {{ request('status') == 'IMPLEMENTING' ? 'selected' : '' }}>Implementing</option>
                        <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                        <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-surface-400 uppercase mb-2">Cari Program / Pelaksana</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" 
                        placeholder="Nama program/pelaksana...">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-nu-green-600 text-white font-bold py-2.5 rounded-xl hover:bg-nu-green-700 transition-all">
                        Filter
                    </button>
                    <a href="{{ route('admin.programs.index') }}" class="px-4 py-2.5 bg-surface-100 text-surface-600 font-bold rounded-xl hover:bg-surface-200 transition-all text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-100 flex items-center justify-between">
                <h3 class="font-bold text-surface-800">Daftar Program</h3>
                <span class="badge badge-amber text-[10px]">Total: {{ $programs->total() }} Data</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-50 text-surface-500 text-[10px] uppercase font-bold">
                        <tr>
                            <th class="px-6 py-3">Program</th>
                            <th class="px-6 py-3">Pelaksana</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Dana</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @foreach ($programs as $program)
                            <tr class="hover:bg-surface-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-surface-900">{{ $program->title }}</div>
                                    <div class="text-[10px] text-surface-400 uppercase tracking-wider">{{ $program->category }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-surface-600">{{ $program->pelaksana->name }}</div>
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
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="text-[10px] text-surface-400 font-medium">Target: Rp {{ number_format($program->target_amount, 0, ',', '.') }}</div>
                                    <div class="text-xs font-bold text-nu-green-600 mb-1">Terkumpul: Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</div>
                                    @php
                                        $totalDisbursed = $program->disbursements->sum('amount');
                                        $remaining = $program->collected_amount - $totalDisbursed;
                                    @endphp
                                    @if($totalDisbursed > 0)
                                        <div class="text-[9px] font-bold text-amber-600">Salur: Rp {{ number_format($totalDisbursed, 0, ',', '.') }}</div>
                                        <div class="text-[9px] font-bold text-surface-900">Sisa: Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($program->status === 'RECOMMENDED')
                                            <form action="{{ route('admin.programs.pks', $program) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-amber-500 text-white text-[10px] font-bold rounded-lg hover:bg-amber-600 transition-all">
                                                    PKS
                                                </button>
                                            </form>
                                        @elseif($program->status === 'PKS_PENDING')
                                            <button @click="openPublish({{ json_encode($program->load('latestReview')) }})" class="px-3 py-1.5 bg-nu-green-600 text-white text-[10px] font-bold rounded-lg hover:bg-nu-green-700 transition-all">
                                                Publish
                                            </button>
                                        @elseif($program->status === 'PUBLISHED')
                                            <button @click="openUnpublish({{ json_encode($program) }})" class="px-3 py-1.5 bg-red-600 text-white text-[10px] font-bold rounded-lg hover:bg-red-700 transition-all">
                                                Unpublish
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.programs.show', $program) }}" class="p-1.5 text-surface-400 hover:text-nu-green-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($programs->hasPages())
                <div class="px-6 py-4 border-t border-surface-100 bg-surface-50">
                    {{ $programs->links() }}
                </div>
            @endif
        </div>

        <!-- Publish Modal -->
        <template x-if="publishModal">
            <div class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="publishModal = false"></div>
                <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-zoom-in">
                    <div class="px-8 py-6 border-b border-surface-50 bg-surface-50">
                        <h3 class="text-xl font-bold text-surface-900">Konfirmasi Publikasi</h3>
                        <p class="text-sm text-surface-500" x-text="selectedProgram.title"></p>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="p-4 rounded-2xl bg-nu-green-50 border border-nu-green-100">
                            <h4 class="text-xs font-bold text-nu-green-700 uppercase tracking-widest mb-3">Hasil Penilaian Penilai</h4>
                            <template x-if="reviewData">
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-[10px] text-nu-green-600 font-bold uppercase">Skor Kelayakan (5C)</div>
                                            <div class="text-2xl font-black text-nu-green-800" x-text="(parseInt(reviewData.score_character) + parseInt(reviewData.score_capacity) + parseInt(reviewData.score_capital) + parseInt(reviewData.score_collateral) + parseInt(reviewData.score_condition)) + '/25'"></div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-nu-green-600 font-bold uppercase">Skor Dampak (3P)</div>
                                            <div class="text-2xl font-black text-nu-green-800" x-text="(parseInt(reviewData.score_people) + parseInt(reviewData.score_planet) + parseInt(reviewData.score_profit)) + '/15'"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] text-nu-green-600 font-bold uppercase mb-1">Catatan Penilai</div>
                                        <p class="text-xs text-nu-green-800 leading-relaxed italic" x-text="'&ldquo;' + reviewData.decision_notes + '&rdquo;'"></p>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!reviewData">
                                <p class="text-sm text-surface-400 italic">Data penilaian tidak ditemukan.</p>
                            </template>
                        </div>

                        <p class="text-sm text-surface-600">Apakah Anda yakin ingin mempublikasikan program ini? Program yang sudah dipublikasikan akan muncul di katalog publik dan mulai menerima donasi.</p>
                    </div>
                    <div class="px-8 py-6 bg-surface-50 border-t border-surface-100 flex gap-3">
                        <button @click="publishModal = false" class="flex-1 px-6 py-3 bg-white border border-surface-200 text-surface-600 font-bold rounded-2xl hover:bg-surface-100 transition-all">
                            Batal
                        </button>
                        <form :action="'/dashboard/admin/programs/' + selectedProgram.id + '/publish'" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full px-6 py-3 bg-nu-green-600 text-white font-bold rounded-2xl hover:bg-nu-green-700 shadow-lg shadow-nu-green-600/20 transition-all">
                                Ya, Publish
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <!-- Unpublish Modal -->
        <template x-if="unpublishModal">
            <div class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="unpublishModal = false"></div>
                <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-zoom-in">
                    <div class="p-8 text-center">
                        <div class="w-20 h-20 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-surface-900 mb-2">Konfirmasi Unpublish</h3>
                        <p class="text-sm text-surface-500 mb-6">Program <span class="font-bold text-surface-900" x-text="selectedProgram.title"></span> akan disembunyikan dari publik. Anda yakin?</p>
                        
                        <div class="flex gap-3">
                            <button @click="unpublishModal = false" class="flex-1 px-6 py-3 bg-surface-100 text-surface-600 font-bold rounded-2xl hover:bg-surface-200 transition-all">
                                Batal
                            </button>
                            <form :action="'/dashboard/admin/programs/' + selectedProgram.id + '/unpublish'" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-6 py-3 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 shadow-lg shadow-red-600/20 transition-all">
                                    Ya, Unpublish
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-dashboard-layout>
