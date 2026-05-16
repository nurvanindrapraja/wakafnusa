<x-dashboard-layout>
    @section('title', 'Monitoring Infaq Layanan')
    @section('page-title', 'Monitoring Infaq Layanan')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div x-data="{ 
        activeTab: '{{ (request('withdrawal_active') || request('withdrawal_page')) ? 'withdrawal' : 'income' }}',
        withdrawalModal: false,
        detailModal: false,
        selectedWithdrawal: null,
        openDetail(data) {
            this.selectedWithdrawal = data;
            this.detailModal = true;
        }
    }" class="relative space-y-6">
        
        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-nu-green-600 rounded-3xl p-6 text-white shadow-lg shadow-nu-green-600/20 relative overflow-hidden">
                <div class="absolute right-0 top-0 h-full opacity-10">
                    <svg class="h-full w-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.82v-1.91c-1.35-.14-2.58-.65-3.59-1.42l1.39-1.41c.8.56 1.73.91 2.76 1.01V13.1c-1.9-.45-3.51-1.2-3.51-3.4 0-1.79 1.4-3.15 3.51-3.41V4h2.82v1.94c1.17.16 2.21.61 3.09 1.22l-1.3 1.4c-.65-.41-1.35-.67-2.19-.74v3.19c1.9.46 3.79 1.15 3.79 3.53 0 2.05-1.63 3.33-3.79 3.55z"/></svg>
                </div>
                <div class="relative">
                    <div class="text-white/70 text-[10px] font-bold uppercase tracking-widest mb-1">Total Infaq Terkumpul</div>
                    <div class="text-2xl font-black">Rp {{ number_format($totalInfaq, 0, ',', '.') }}</div>
                    <div class="mt-2 text-[10px] bg-white/20 px-2 py-0.5 rounded inline-block font-bold uppercase">Dana Masuk</div>
                </div>
            </div>

            <div class="bg-red-500 rounded-3xl p-6 text-white shadow-lg shadow-red-500/20 relative overflow-hidden">
                <div class="absolute right-0 top-0 h-full opacity-10">
                    <svg class="h-full w-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.82v-1.91c-1.35-.14-2.58-.65-3.59-1.42l1.39-1.41c.8.56 1.73.91 2.76 1.01V13.1c-1.9-.45-3.51-1.2-3.51-3.4 0-1.79 1.4-3.15 3.51-3.41V4h2.82v1.94c1.17.16 2.21.61 3.09 1.22l-1.3 1.4c-.65-.41-1.35-.67-2.19-.74v3.19c1.9.46 3.79 1.15 3.79 3.53 0 2.05-1.63 3.33-3.79 3.55z"/></svg>
                </div>
                <div class="relative">
                    <div class="text-white/70 text-[10px] font-bold uppercase tracking-widest mb-1">Total Dana Diambil</div>
                    <div class="text-2xl font-black">Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}</div>
                    <div class="mt-2 text-[10px] bg-white/20 px-2 py-0.5 rounded inline-block font-bold uppercase">Biaya Operasional</div>
                </div>
            </div>

            <div class="bg-nu-gold-500 rounded-3xl p-6 text-white shadow-lg shadow-nu-gold-500/20 relative overflow-hidden border border-white/20">
                <div class="absolute right-0 top-0 h-full opacity-10">
                    <svg class="h-full w-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.82v-1.91c-1.35-.14-2.58-.65-3.59-1.42l1.39-1.41c.8.56 1.73.91 2.76 1.01V13.1c-1.9-.45-3.51-1.2-3.51-3.4 0-1.79 1.4-3.15 3.51-3.41V4h2.82v1.94c1.17.16 2.21.61 3.09 1.22l-1.3 1.4c-.65-.41-1.35-.67-2.19-.74v3.19c1.9.46 3.79 1.15 3.79 3.53 0 2.05-1.63 3.33-3.79 3.55z"/></svg>
                </div>
                <div class="relative">
                    <div class="text-white/70 text-[10px] font-bold uppercase tracking-widest mb-1">Saldo Tersedia</div>
                    <div class="text-2xl font-black">Rp {{ number_format($balance, 0, ',', '.') }}</div>
                    <div class="mt-2 text-[10px] bg-white/20 px-2 py-0.5 rounded inline-block font-bold uppercase">Kas Infaq</div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 p-1 bg-surface-100 rounded-2xl w-fit">
                <button @click="activeTab = 'income'" 
                    :class="activeTab === 'income' ? 'bg-white shadow-sm text-nu-green-700' : 'text-surface-500 hover:text-surface-700'"
                    class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                    Riwayat Infaq Masuk
                </button>
                <button @click="activeTab = 'withdrawal'" 
                    :class="activeTab === 'withdrawal' ? 'bg-white shadow-sm text-nu-green-700' : 'text-surface-500 hover:text-surface-700'"
                    class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                    Pencatatan Pengambilan Dana
                </button>
            </div>
            <button @click="withdrawalModal = true" class="bg-nu-green-600 text-white px-6 py-3 rounded-2xl hover:bg-nu-green-700 transition-all text-xs font-black uppercase tracking-widest shadow-lg shadow-nu-green-600/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Catat Pengambilan Dana
            </button>
        </div>

        <!-- Tab Content: Income -->
        <div x-show="activeTab === 'income'" class="bg-white rounded-3xl shadow-card border border-surface-100 overflow-hidden" x-transition>
            <div class="p-8 border-b border-surface-100">
                <form action="{{ route('admin.infaq.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Cari Wakif / Program</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600" 
                            placeholder="Masukkan nama wakif atau judul program...">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Tgl Bayar</label>
                        <div class="flex gap-2">
                            <input type="date" name="date" value="{{ request('date') }}" 
                                class="flex-1 rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                            <button type="submit" class="bg-nu-green-600 text-white px-5 py-2.5 rounded-xl hover:bg-nu-green-700 transition-colors text-xs font-bold uppercase tracking-wider">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-50 text-surface-400 text-[10px] uppercase font-bold tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Wakif</th>
                            <th class="px-8 py-4">Program</th>
                            <th class="px-8 py-4 text-right">Infaq Layanan</th>
                            <th class="px-8 py-4">Tgl Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse ($infaqList as $donation)
                            <tr class="hover:bg-surface-50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-nu-green-50 flex items-center justify-center text-nu-green-600 font-bold text-xs">
                                            {{ substr($donation->donor_display_name, 0, 1) }}
                                        </div>
                                        <div class="text-sm font-bold text-surface-900">{{ $donation->donor_display_name }}</div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm text-surface-600 max-w-[250px] truncate" title="{{ $donation->program->title }}">
                                        {{ $donation->program->title }}
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="text-sm font-black text-nu-green-600">Rp {{ number_format($donation->service_fee, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm text-surface-600">{{ $donation->paid_at->format('d M Y') }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-8 py-16 text-center text-surface-400 italic">Belum ada data infaq masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($infaqList->hasPages())
                <div class="px-8 py-6 bg-surface-50 border-t border-surface-100">
                    {{ $infaqList->appends(['withdrawal_page' => $withdrawals->currentPage()])->links() }}
                </div>
            @endif
        </div>

        <!-- Tab Content: Withdrawal -->
        <div x-show="activeTab === 'withdrawal'" class="bg-white rounded-3xl shadow-card border border-surface-100 overflow-hidden" x-transition x-cloak>
            <div class="p-8 border-b border-surface-100">
                <form action="{{ route('admin.infaq.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="hidden" name="withdrawal_active" value="1">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Cari Nama Rekening / Keperluan</label>
                        <input type="text" name="w_search" value="{{ request('w_search') }}" 
                            class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600" 
                            placeholder="Cari kata kunci...">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Tgl Pengambilan</label>
                        <div class="flex gap-2">
                            <input type="date" name="w_date" value="{{ request('w_date') }}" 
                                class="flex-1 rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                            <button type="submit" class="bg-nu-green-600 text-white px-5 py-2.5 rounded-xl hover:bg-nu-green-700 transition-colors text-xs font-bold uppercase tracking-wider">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-50 text-surface-400 text-[10px] uppercase font-bold tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Tanggal</th>
                            <th class="px-8 py-4">Penerima (Rekening)</th>
                            <th class="px-8 py-4">Keperluan</th>
                            <th class="px-8 py-4 text-right">Jumlah</th>
                            <th class="px-8 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse ($withdrawals as $w)
                            <tr class="hover:bg-surface-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="text-sm font-bold text-surface-900">{{ $w->withdrawal_date->format('d M Y') }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-bold text-surface-900">{{ $w->bank_account_name }}</div>
                                    <div class="text-[10px] text-surface-400 font-bold uppercase tracking-widest">{{ $w->bank_name }} - {{ $w->bank_account_number }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm text-surface-600 max-w-[200px] truncate">{{ $w->purpose }}</div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="text-sm font-black text-red-600">Rp {{ number_format($w->amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <button @click="openDetail({
                                        date: '{{ $w->withdrawal_date->format('d M Y') }}',
                                        amount: '{{ number_format($w->amount, 0, ',', '.') }}',
                                        bank: '{{ $w->bank_name }}',
                                        acc_no: '{{ $w->bank_account_number }}',
                                        acc_name: '{{ $w->bank_account_name }}',
                                        purpose: '{{ addslashes($w->purpose) }}',
                                        proof: '{{ Storage::url($w->proof_path) }}',
                                        admin: '{{ $w->creator->name }}'
                                    })" class="text-[10px] font-black text-nu-green-600 uppercase tracking-widest hover:underline">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-8 py-16 text-center text-surface-400 italic">Belum ada data pengambilan dana.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($withdrawals->hasPages())
                <div class="px-8 py-6 bg-surface-50 border-t border-surface-100">
                    {{ $withdrawals->appends(['income_page' => $infaqList->currentPage()])->links() }}
                </div>
            @endif
        </div>

        <!-- Modal: Catat Pengambilan Dana -->
        <div x-show="withdrawalModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 lg:p-6" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="withdrawalModal = false"></div>
            <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl overflow-hidden animate-zoom-in">
                <div class="px-8 py-6 border-b border-surface-50 bg-surface-50 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-surface-900">Catat Pengambilan Dana Infaq</h3>
                    <button @click="withdrawalModal = false" class="p-2 hover:bg-surface-200 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form action="{{ route('admin.infaq.withdraw') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Tgl Pengambilan</label>
                            <input type="date" name="withdrawal_date" required value="{{ date('Y-m-d') }}"
                                class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Jumlah (Rp)</label>
                            <div x-data="{ 
                                rawAmount: '',
                                displayAmount: '',
                                formatDisplay() {
                                    let val = this.displayAmount.replace(/\D/g, '');
                                    this.rawAmount = val;
                                    this.displayAmount = val ? parseInt(val).toLocaleString('id-ID') : '';
                                }
                            }">
                                <input type="hidden" name="amount" x-model="rawAmount">
                                <input type="text" 
                                    x-model="displayAmount" 
                                    @input="formatDisplay()"
                                    required 
                                    class="w-full rounded-xl border-surface-200 text-sm font-black focus:ring-nu-green-600 focus:border-nu-green-600" 
                                    placeholder="0">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1">
                            <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Nama Bank</label>
                            <input type="text" name="bank_name" required class="w-full rounded-xl border-surface-200 text-sm" placeholder="BSI, Mandiri, dll">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Nomor Rekening</label>
                            <input type="text" name="bank_account_number" required class="w-full rounded-xl border-surface-200 text-sm" placeholder="Nomor rekening tujuan">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Atas Nama Rekening</label>
                        <input type="text" name="bank_account_name" required class="w-full rounded-xl border-surface-200 text-sm" placeholder="Nama pemilik rekening">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Keperluan / Keterangan</label>
                        <textarea name="purpose" rows="3" required class="w-full rounded-xl border-surface-200 text-sm" placeholder="Jelaskan tujuan penggunaan dana..."></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Upload Bukti Transfer</label>
                        <input type="file" name="proof_file" required accept="image/*"
                            class="block w-full text-xs text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-surface-900 file:text-white hover:file:bg-black cursor-pointer bg-surface-50 rounded-xl border border-surface-200 p-2">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-4 bg-nu-green-600 text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-600/20">
                            Simpan & Kurangi Saldo
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal: Detail Pengambilan -->
        <div x-show="detailModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 lg:p-6" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="detailModal = false"></div>
            <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl overflow-hidden animate-zoom-in flex flex-col max-h-[90vh]">
                <div class="px-8 py-6 border-b border-surface-50 bg-surface-50 flex items-center justify-between shrink-0">
                    <h3 class="text-xl font-bold text-surface-900">Detail Pengambilan Dana</h3>
                    <button @click="detailModal = false" class="p-2 hover:bg-surface-200 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <template x-if="selectedWithdrawal">
                <div class="flex-1 overflow-y-auto p-8 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="p-4 bg-surface-50 rounded-2xl border border-surface-100">
                            <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Tanggal</div>
                            <div class="text-sm font-bold text-surface-900" x-text="selectedWithdrawal.date"></div>
                        </div>
                        <div class="p-4 bg-red-50 rounded-2xl border border-red-100">
                            <div class="text-[10px] font-bold text-red-600 uppercase tracking-widest mb-1">Jumlah Diambil</div>
                            <div class="text-sm font-black text-red-700" x-text="'Rp ' + selectedWithdrawal.amount"></div>
                        </div>
                    </div>

                    <div class="p-4 bg-surface-50 rounded-2xl border border-surface-100">
                        <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Tujuan Rekening</div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-surface-500">Bank</span>
                            <span class="text-sm font-bold text-surface-900" x-text="selectedWithdrawal.bank"></span>
                        </div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-surface-500">Nomor Rekening</span>
                            <span class="text-sm font-bold text-surface-900" x-text="selectedWithdrawal.acc_no"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-surface-500">Atas Nama</span>
                            <span class="text-sm font-bold text-surface-900" x-text="selectedWithdrawal.acc_name"></span>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Keperluan</h4>
                        <p class="text-sm text-surface-600 leading-relaxed italic" x-text="selectedWithdrawal.purpose"></p>
                    </div>

                    <div>
                        <h4 class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-3">Bukti Transfer</h4>
                        <div class="rounded-2xl overflow-hidden border border-surface-200">
                            <img :src="selectedWithdrawal.proof" class="w-full h-auto">
                        </div>
                    </div>
                </div>
                </template>
            </div>
        </div>

    </div>

</x-dashboard-layout>
