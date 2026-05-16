<x-dashboard-layout>
    @section('title', 'Penyaluran Dana')
    @section('page-title', 'Penyaluran Dana ke Pelaksana')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div class="space-y-6" x-data="{
        disburseModal: false,
        historyModal: false,
        proofModal: false,
        selectedProgram: null,
        historyData: [],
        selectedProof: null,
        amount: '',
        openModal(program) {
            this.selectedProgram = program;
            this.amount = program.max_disburse;
            this.disburseModal = true;
        },
        openHistory(program, history) {
            this.selectedProgram = program;
            this.historyData = history;
            this.historyModal = true;
        },
        openProof(url) {
            this.selectedProof = url;
            this.proofModal = true;
        }
    }">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-surface-100">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-nu-green-50 rounded-2xl flex items-center justify-center text-nu-green-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Total Disalurkan</div>
                        <div class="text-2xl font-black text-nu-green-600">Rp {{ number_format($stats['total_disbursed'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="text-[10px] text-surface-400">Akumulasi dana yang telah dikirim ke Nazhir</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-surface-100">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-nu-gold-50 rounded-2xl flex items-center justify-center text-nu-gold-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Program Terbantu</div>
                        <div class="text-2xl font-black text-surface-900">{{ $stats['program_count'] }} <span class="text-sm font-bold text-surface-400">Program</span></div>
                    </div>
                </div>
                <div class="text-[10px] text-surface-400">Jumlah program yang telah menerima dana</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-surface-100">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Nazhir Penerima</div>
                        <div class="text-2xl font-black text-surface-900">{{ $stats['pelaksana_count'] }} <span class="text-sm font-bold text-surface-400">Lembaga</span></div>
                    </div>
                </div>
                <div class="text-[10px] text-surface-400">Jumlah Pelaksana Nazhir yang menerima dana</div>
            </div>
        </div>

        <!-- Programs List -->
        <div class="bg-white rounded-3xl shadow-sm border border-surface-100 overflow-hidden">
            <div class="p-6 border-b border-surface-50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-surface-900">Daftar Program Siap Salur</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-50 text-[10px] uppercase font-bold text-surface-500">
                        <tr>
                            <th class="px-6 py-4">Program & Pelaksana</th>
                            <th class="px-6 py-4">Progress Dana</th>
                            <th class="px-6 py-4">Total Disalurkan</th>
                            <th class="px-6 py-4">Sisa Dana</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse($programs as $program)
                            @php
                                $totalDisbursed = $program->disbursements->sum('amount');
                                $maxDisburse = $program->collected_amount - $totalDisbursed;
                                $percentage = ($program->collected_amount / $program->target_amount) * 100;
                                
                                $nextPhase = $program->disbursements->count() + 1;
                                $requiredThreshold = $nextPhase * 25;
                                $thresholdMet = $percentage >= $requiredThreshold;
                                
                                $hasPendingReport = $program->disbursements->whereNull('report_id')->count() > 0 || 
                                                  $program->disbursements->whereNotNull('report_id')->filter(fn($d) => $d->report->status !== 'VALIDATED')->count() > 0;
                            @endphp
                            <tr class="hover:bg-surface-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-surface-900">{{ $program->title }}</div>
                                    <div class="flex items-center gap-2">
                                        <div class="text-[10px] text-nu-green-600 font-bold uppercase tracking-wider">{{ $program->pelaksana->name }}</div>
                                        @if(!$program->pelaksana->bank_account_number)
                                            <span class="px-1.5 py-0.5 rounded bg-red-50 text-red-600 text-[8px] font-bold border border-red-100 uppercase tracking-tighter">Rekening Belum Diisi</span>
                                        @elseif($hasPendingReport)
                                            <span class="px-1.5 py-0.5 rounded bg-amber-50 text-amber-600 text-[8px] font-bold border border-amber-100 uppercase tracking-tighter">Menunggu Laporan</span>
                                        @elseif(!$thresholdMet)
                                            <span class="px-1.5 py-0.5 rounded bg-surface-50 text-surface-500 text-[8px] font-bold border border-surface-100 uppercase tracking-tighter">Belum Capai {{ $requiredThreshold }}%</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-full h-1.5 bg-surface-100 rounded-full overflow-hidden mb-1.5">
                                        <div class="h-full bg-nu-green-600 rounded-full" style="width: {{ min(100, $percentage) }}%"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px]">
                                        <span class="font-bold text-surface-900">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</span>
                                        <span class="text-surface-400">Target: Rp {{ number_format($program->target_amount, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-nu-green-600">Rp {{ number_format($totalDisbursed, 0, ',', '.') }}</div>
                                    <div class="text-[10px] text-surface-400">{{ $program->disbursements->count() }} Tahap</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-surface-900">Rp {{ number_format($maxDisburse, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            @click="openHistory({
                                                title: '{{ addslashes($program->title) }}',
                                                pelaksana: '{{ addslashes($program->pelaksana->name) }}'
                                            }, {{ $program->disbursements->map(fn($d) => [
                                                'phase' => $d->phase,
                                                'amount' => 'Rp ' . number_format($d->amount, 0, ',', '.'),
                                                'date' => $d->disbursed_at->format('d M Y H:i'),
                                                'proof' => Storage::url($d->proof_path),
                                                'notes' => $d->notes ?? '-'
                                            ])->toJson() }})"
                                            class="p-2 text-surface-400 hover:text-nu-green-600 transition-colors"
                                            title="Lihat Riwayat Penyaluran">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </button>
                                        <button 
                                            @click="openModal({
                                                id: {{ $program->id }},
                                                title: '{{ addslashes($program->title) }}',
                                                pelaksana: '{{ addslashes($program->pelaksana->name) }}',
                                                bank: '{{ $program->pelaksana->bank_name ?? 'Belum Diatur' }}',
                                                account: '{{ $program->pelaksana->bank_account_number ?? '-' }}',
                                                account_name: '{{ addslashes($program->pelaksana->bank_account_name ?? '-') }}',
                                                max_disburse: {{ $maxDisburse }},
                                                max_formatted: 'Rp {{ number_format($maxDisburse, 0, ',', '.') }}',
                                                action: '{{ route('admin.disbursements.store', $program) }}'
                                            })"
                                            class="px-4 py-2 bg-nu-green-600 text-white text-xs font-bold rounded-xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-100 disabled:opacity-50 disabled:grayscale"
                                            @disabled($maxDisburse <= 0 || !$program->pelaksana->bank_account_number || $hasPendingReport || !$thresholdMet)>
                                            Salurkan Dana
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-surface-400 text-sm italic">Belum ada program yang mencapai threshold penyaluran (25%).</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Disbursements History Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-surface-100 overflow-hidden">
            <div class="p-6 border-b border-surface-50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-lg font-bold text-surface-900">Riwayat Penyaluran Dana Terbaru</h3>
                    
                    <!-- Filters -->
                    <form action="{{ route('admin.disbursements.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="pl-10 pr-4 py-2 text-xs rounded-xl border-surface-200 focus:border-nu-green-600 focus:ring-nu-green-600 w-64" 
                                placeholder="Cari Program atau Pelaksana...">
                            <svg class="w-4 h-4 text-surface-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <div class="relative">
                            <input type="date" name="date" value="{{ request('date') }}" 
                                class="pl-10 pr-4 py-2 text-xs rounded-xl border-surface-200 focus:border-nu-green-600 focus:ring-nu-green-600">
                            <svg class="w-4 h-4 text-surface-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-nu-green-600 text-white text-xs font-bold rounded-xl hover:bg-nu-green-700 transition-all">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'date']))
                            <a href="{{ route('admin.disbursements.index') }}" class="px-4 py-2 bg-surface-100 text-surface-600 text-xs font-bold rounded-xl hover:bg-surface-200 transition-all">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-50 text-[10px] uppercase font-bold text-surface-500">
                        <tr>
                            <th class="px-6 py-4">Tgl & Waktu</th>
                            <th class="px-6 py-4">Program</th>
                            <th class="px-6 py-4 text-center">Tahap</th>
                            <th class="px-6 py-4">Jumlah Disalurkan</th>
                            <th class="px-6 py-4 text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse($disbursements as $disbursement)
                            <tr class="hover:bg-surface-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-surface-900">{{ $disbursement->disbursed_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-surface-900">{{ $disbursement->program->title }}</div>
                                    <div class="text-[10px] text-nu-green-600 font-bold uppercase tracking-wider">{{ $disbursement->program->pelaksana->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="w-7 h-7 inline-flex items-center justify-center rounded-full bg-nu-green-50 text-nu-green-600 text-xs font-bold">{{ $disbursement->phase }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-black text-nu-green-600">Rp {{ number_format($disbursement->amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button 
                                        @click="openHistory({
                                            title: '{{ addslashes($disbursement->program->title) }}',
                                            pelaksana: '{{ addslashes($disbursement->program->pelaksana->name) }}'
                                        }, [{
                                            phase: {{ $disbursement->phase }},
                                            amount: 'Rp {{ number_format($disbursement->amount, 0, ',', '.') }}',
                                            date: '{{ $disbursement->disbursed_at->format('d M Y H:i') }}',
                                            proof: '{{ Storage::url($disbursement->proof_path) }}',
                                            notes: '{{ addslashes($disbursement->notes ?? '-') }}'
                                        }])"
                                        class="p-2 text-surface-400 hover:text-nu-green-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-surface-400 text-sm italic">Belum ada data penyaluran dana.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($disbursements->hasPages())
                <div class="px-6 py-4 bg-surface-50 border-t border-surface-100">
                    {{ $disbursements->links() }}
                </div>
            @endif
        </div>

        <!-- Disburse Modal -->
        <div x-show="disburseModal" class="fixed inset-0 z-[150] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="disburseModal = false"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-zoom-in">
                <div class="p-6 border-b border-surface-50 bg-surface-50/50">
                    <h3 class="text-xl font-bold text-surface-900">Penyaluran Dana</h3>
                    <p class="text-xs text-surface-500 mt-1" x-text="selectedProgram?.title"></p>
                </div>
                
                <form :action="selectedProgram?.action" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    
                    <!-- Bank Info -->
                    <div class="p-4 bg-nu-green-50 rounded-2xl border border-nu-green-100">
                        <div class="text-[10px] font-bold text-nu-green-600 uppercase tracking-widest mb-3">Rekening Tujuan (Pelaksana)</div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-[10px] text-nu-green-700/60 font-medium">Bank</div>
                                <div class="text-sm font-bold text-nu-green-900" x-text="selectedProgram?.bank"></div>
                            </div>
                            <div>
                                <div class="text-[10px] text-nu-green-700/60 font-medium">No. Rekening</div>
                                <div class="text-sm font-bold text-nu-green-900" x-text="selectedProgram?.account"></div>
                            </div>
                            <div class="col-span-2">
                                <div class="text-[10px] text-nu-green-700/60 font-medium">Atas Nama (Lembaga)</div>
                                <div class="text-sm font-bold text-nu-green-900 uppercase" x-text="selectedProgram?.account_name"></div>
                            </div>
                        </div>
                    </div>

                    <div x-data="{ 
                        rawAmount: '',
                        get formattedAmount() {
                            return this.rawAmount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        },
                        setAmount(val) {
                            this.rawAmount = val.replace(/\./g, '').replace(/[^\d]/g, '');
                        }
                    }" x-init="rawAmount = selectedProgram?.max_disburse || ''">
                        <label class="block text-sm font-bold text-surface-700 mb-2">Jumlah Dana yang Disalurkan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400 font-bold">Rp</span>
                            <input type="text" 
                                :value="formattedAmount"
                                @input="setAmount($event.target.value)"
                                class="w-full pl-12 pr-4 py-3 rounded-2xl border-surface-200 focus:border-nu-green-600 focus:ring-nu-green-600 font-bold text-surface-900"
                                placeholder="0">
                            <input type="hidden" name="amount" :value="rawAmount">
                        </div>
                        <p class="text-[10px] text-surface-400 mt-2 italic">Maksimal penyaluran: <span class="text-nu-green-600 font-bold" x-text="selectedProgram?.max_formatted"></span></p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-2">Upload Bukti Transfer <span class="text-red-500">*</span></label>
                        <input type="file" name="proof_path" accept="image/*" required
                            class="w-full text-sm text-surface-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-surface-900 file:text-white hover:file:bg-black cursor-pointer bg-surface-50 rounded-2xl border border-surface-100 p-2">
                        <p class="text-[10px] text-surface-400 mt-2">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="notes" rows="2" class="w-full rounded-2xl border-surface-200 focus:border-nu-green-600 focus:ring-nu-green-600 text-sm" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="button" @click="disburseModal = false" class="flex-1 py-3 bg-surface-100 text-surface-600 font-bold rounded-2xl hover:bg-surface-200 transition-all">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-3 bg-nu-green-600 text-white font-bold rounded-2xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-100">
                            Proses Penyaluran
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- History Modal -->
        <div x-show="historyModal" class="fixed inset-0 z-[150] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="historyModal = false"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden animate-zoom-in flex flex-col max-h-[80vh]">
                <div class="p-6 border-b border-surface-50 bg-surface-50/50 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-surface-900">Riwayat Penyaluran</h3>
                        <p class="text-xs text-surface-500 mt-1" x-text="selectedProgram?.title"></p>
                    </div>
                    <button @click="historyModal = false" class="p-2 text-surface-400 hover:text-surface-900 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18" /></svg>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto">
                    <template x-if="historyData.length === 0">
                        <div class="text-center py-12">
                            <div class="text-surface-300 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <p class="text-surface-500 italic">Belum ada riwayat penyaluran dana.</p>
                        </div>
                    </template>

                    <div class="space-y-6">
                        <template x-for="item in historyData" :key="item.phase">
                            <div class="flex gap-6 relative">
                                <!-- Timeline Line -->
                                <div class="absolute left-[19px] top-10 bottom-[-24px] w-0.5 bg-surface-100 last:hidden"></div>
                                
                                <div class="w-10 h-10 rounded-full bg-nu-green-600 text-white flex items-center justify-center font-bold text-sm z-10 shrink-0" x-text="item.phase"></div>
                                
                                <div class="flex-1 pb-8">
                                    <div class="bg-surface-50 rounded-2xl p-6 border border-surface-100">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="text-lg font-bold text-nu-green-600" x-text="item.amount"></div>
                                            <div class="text-[10px] text-surface-400 font-bold uppercase tracking-widest" x-text="item.date"></div>
                                        </div>
                                        <div class="space-y-3">
                                            <div>
                                                <div class="text-[10px] text-surface-400 font-bold uppercase tracking-widest mb-1">Catatan</div>
                                                <div class="text-sm text-surface-700" x-text="item.notes"></div>
                                            </div>
                                            <div class="pt-3 border-t border-surface-200 flex items-center justify-between">
                                                <button type="button" @click="openProof(item.proof)" class="inline-flex items-center gap-2 text-xs font-bold text-nu-green-600 hover:text-nu-green-700 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                    Lihat Bukti Transfer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Proof Modal -->
        <div x-show="proofModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="proofModal = false"></div>
            <div class="relative max-w-md w-full animate-zoom-in">
                <button @click="proofModal = false" class="absolute -top-10 right-0 p-2 text-white hover:text-surface-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <div class="bg-white p-2 rounded-[2rem] shadow-2xl">
                    <img :src="selectedProof" class="w-full rounded-[1.5rem] overflow-hidden shadow-inner" alt="Bukti Transfer">
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
