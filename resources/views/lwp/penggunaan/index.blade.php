@extends('layouts.lwp')

@section('title', 'Master Penggunaan Wakaf')
@section('page_title', 'Master Penggunaan')
@section('page_subtitle', 'Kelola data referensi klasifikasi penggunaan tanah wakaf (tabel wk_penggunaan)')

@section('content')
<div class="space-y-8">
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Penggunaan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-nu-green-50 text-nu-green-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block">Total Penggunaan</span>
                <span class="text-2xl font-black text-slate-900 leading-tight">{{ $stats['total'] }}</span>
            </div>
        </div>

        <!-- Card 2: Kategori Utama -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block">Kategori Utama</span>
                <span class="text-2xl font-black text-slate-900 leading-tight">{{ $stats['parents'] }}</span>
            </div>
        </div>

        <!-- Card 3: Sub Kategori -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block">Sub Kategori</span>
                <span class="text-2xl font-black text-slate-900 leading-tight">{{ $stats['subs'] }}</span>
            </div>
        </div>

        <!-- Card 4: Aktif / Sampah -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block">Aktif / Di Sampah</span>
                <span class="text-2xl font-black text-slate-900 leading-tight">
                    {{ $stats['active'] }} <span class="text-xs font-bold text-red-500">({{ $stats['trash'] }} sampah)</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden relative">
        <!-- Horizontal Loading Progress Bar -->
        <div id="usageProgressBar" class="absolute top-0 left-0 right-0 h-1 bg-nu-green-500 transition-all duration-300 transform scale-x-0 origin-left z-20"></div>

        <!-- Toolbar Filters -->
        <div class="p-4 sm:px-8 sm:py-6 border-b border-slate-50 space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <!-- Left Filters Group -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center flex-1 gap-3">
                    <!-- Search Input -->
                    <div class="relative min-w-[260px] sm:max-w-xs">
                        <input type="text" id="searchUsage" value="{{ $search }}" placeholder="Cari kode atau nama penggunaan..." class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all placeholder:text-slate-400">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </span>
                        <button id="clearUsageSearch" onclick="clearSearch()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-0.5 {{ $search ? '' : 'hidden' }}">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Filter Kategori Utama -->
                    <div class="min-w-[220px]">
                        <select id="filterKategori" onchange="applyFilters(1)" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                            <option value="">Semua Kategori Utama</option>
                            @foreach($parentCategories as $cat)
                                <option value="{{ $cat->KODE_PENG }}" {{ $kategori == $cat->KODE_PENG ? 'selected' : '' }}>
                                    {{ $cat->KODE_PENG }} - {{ $cat->PENGGUNAAN }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Status -->
                    <div class="min-w-[150px]">
                        <select id="filterStatus" onchange="applyFilters(1)" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                            <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Hanya Aktif</option>
                            <option value="trash" {{ $status == 'trash' ? 'selected' : '' }}>Data Sampah ({{ $stats['trash'] }})</option>
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Data</option>
                        </select>
                    </div>
                </div>

                <!-- Create Action Button -->
                <div class="shrink-0 flex items-center">
                    <button onclick="openCreateModal()" class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3.5 bg-nu-green-600 hover:bg-nu-green-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-md shadow-nu-green-600/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                        Tambah Penggunaan
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div id="usageTableContainer">
            @include('lwp.penggunaan.partials._table')
        </div>
    </div>
</div>

<!-- Modal Create & Edit Components -->
@include('lwp.penggunaan.partials._modal_create')
@include('lwp.penggunaan.partials._modal_edit')

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-del-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" onclick="closeDeleteConfirmModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100">
            <div class="p-8">
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </div>
                <h3 class="text-base font-black text-slate-900 mb-1" id="modal-del-title">Hapus Master Penggunaan?</h3>
                <p class="text-xs text-slate-500 font-semibold mb-4 leading-relaxed">
                    Penggunaan <span id="delUsageName" class="font-bold text-slate-900 font-mono"></span> akan dipindahkan ke folder sampah.
                </p>
                <div id="delWakafWarning" class="hidden px-4 py-3 bg-amber-50 border border-amber-200 rounded-2xl mb-4 text-xs font-bold text-amber-800">
                    <svg class="w-4 h-4 text-amber-600 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    Catatan: Terdapat <span id="delWakafCount">0</span> data tanah wakaf aktif yang terhubung dengan kode penggunaan ini.
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeDeleteConfirmModal()" class="px-5 py-2.5 text-slate-500 hover:text-slate-700 text-xs font-bold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="button" id="executeDeleteBtn" onclick="executeDeleteUsage()" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-red-600/20">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Force Delete Confirmation Modal -->
<div id="forceDeleteModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" onclick="closeForceDeleteModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-red-200">
            <div class="p-8">
                <div class="w-12 h-12 bg-red-100 text-red-700 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h3 class="text-base font-black text-red-950 mb-1">Hapus Permanen?</h3>
                <p class="text-xs text-slate-600 font-semibold mb-4 leading-relaxed">
                    Data penggunaan <span id="forceDelUsageName" class="font-bold text-slate-900 font-mono"></span> akan dihapus selamanya dari basis data dan tidak dapat dipulihkan.
                </p>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeForceDeleteModal()" class="px-5 py-2.5 text-slate-500 hover:text-slate-700 text-xs font-bold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="button" id="executeForceDeleteBtn" onclick="executeForceDeleteUsage()" class="px-6 py-2.5 bg-red-700 hover:bg-red-800 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-red-700/20">
                        Hapus Permanen
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Toast -->
<div id="usageToast" class="fixed bottom-6 right-6 z-50 hidden transition-all duration-300 transform translate-y-4">
    <div class="flex items-center gap-3 px-5 py-3.5 bg-slate-900 text-white rounded-2xl shadow-xl text-xs font-bold">
        <span id="toastIcon"></span>
        <span id="toastMessage"></span>
    </div>
</div>

<script>
    let searchDebounceTimer = null;
    let pendingDeleteCode = null;
    let pendingForceDeleteCode = null;

    // Toast Helper
    function showToast(message, type = 'success') {
        const toast = document.getElementById('usageToast');
        const icon = document.getElementById('toastIcon');
        const msg = document.getElementById('toastMessage');

        if (type === 'success') {
            icon.innerHTML = `<svg class="w-4 h-4 text-nu-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>`;
        } else {
            icon.innerHTML = `<svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>`;
        }

        msg.textContent = message;
        toast.classList.remove('hidden', 'translate-y-4');
        toast.classList.add('translate-y-0');

        setTimeout(() => {
            toast.classList.add('translate-y-4');
            setTimeout(() => toast.classList.add('hidden'), 300);
        }, 3500);
    }

    // Progress Bar Helper
    function setProgressBar(active) {
        const bar = document.getElementById('usageProgressBar');
        if (!bar) return;
        if (active) {
            bar.classList.remove('scale-x-0');
            bar.classList.add('scale-x-100');
        } else {
            bar.classList.remove('scale-x-100');
            bar.classList.add('scale-x-0');
        }
    }

    // Filter & Search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchUsage');
        if (searchInput) {
            searchInput.addEventListener('input', function (e) {
                const val = e.target.value;
                document.getElementById('clearUsageSearch').classList.toggle('hidden', !val);

                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(() => {
                    applyFilters(1);
                }, 350);
            });
        }
    });

    function clearSearch() {
        const input = document.getElementById('searchUsage');
        input.value = '';
        document.getElementById('clearUsageSearch').classList.add('hidden');
        applyFilters(1);
    }

    let currentUsagePage = {{ $usages->currentPage() }};

    function applyFilters(page = null) {
        const search = document.getElementById('searchUsage').value;
        const kategori = document.getElementById('filterKategori').value;
        const status = document.getElementById('filterStatus').value;

        setProgressBar(true);

        if (page !== null && page !== undefined) {
            currentUsagePage = parseInt(page);
        } else {
            page = currentUsagePage;
        }

        const params = new URLSearchParams({
            search: search,
            kategori: kategori,
            status: status,
            page: page
        });

        // Update URL query string without full page reload
        window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);

        fetch(`${window.location.pathname}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('usageTableContainer').innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            showToast('Gagal memuat data penggunaan.', 'error');
        })
        .finally(() => {
            setProgressBar(false);
        });
    }

    // Intercept pagination clicks for AJAX navigation
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.penggunaan-pagination-wrapper a');
        if (link) {
            e.preventDefault();
            const url = new URL(link.href);
            const page = url.searchParams.get('page') || 1;
            applyFilters(page);
        }
    });

    function copyToClipboard(text) {
        if (!navigator.clipboard) {
            const temp = document.createElement('textarea');
            temp.value = text;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            showToast(`Kode '${text}' berhasil disalin ke clipboard!`, 'info');
            return;
        }
        navigator.clipboard.writeText(text).then(() => {
            showToast(`Kode '${text}' berhasil disalin ke clipboard!`, 'info');
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }

    // Modal Create Handlers
    function openCreateModal() {
        document.getElementById('createErrorAlert').classList.add('hidden');
        document.getElementById('createUsageForm').reset();
        document.querySelector('input[name="tipe_penggunaan"][value="sub"]').checked = true;
        toggleCategoryType();
        document.getElementById('createUsageModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createUsageModal').classList.add('hidden');
    }

    function toggleCategoryType() {
        const type = document.querySelector('input[name="tipe_penggunaan"]:checked').value;
        const parentGroup = document.getElementById('parentCategoryGroup');
        const kodeInput = document.getElementById('createKodePeng');

        if (type === 'parent') {
            parentGroup.classList.add('hidden');
            kodeInput.placeholder = 'Misal: 19';
        } else {
            parentGroup.classList.remove('hidden');
            kodeInput.placeholder = 'Misal: 11.04';
        }
    }

    function onParentCategoryChange() {
        const parent = document.getElementById('selectParentCategory').value;
        const kodeInput = document.getElementById('createKodePeng');
        if (parent) {
            kodeInput.value = parent + '.';
            kodeInput.focus();
        }
    }

    function submitCreateForm(e) {
        e.preventDefault();
        const form = document.getElementById('createUsageForm');
        const btn = document.getElementById('createSubmitBtn');
        const errorAlert = document.getElementById('createErrorAlert');
        const errorMsg = document.getElementById('createErrorMessage');

        errorAlert.classList.add('hidden');
        btn.disabled = true;
        btn.innerHTML = `<span class="inline-block animate-spin mr-2">⏳</span> Menyimpan...`;

        const formData = new FormData(form);

        fetch("{{ route('lwp.penggunaan.store') }}", {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.message || 'Terjadi kesalahan validasi.');
            }
            return data;
        })
        .then(data => {
            closeCreateModal();
            showToast(data.message || 'Data penggunaan berhasil disimpan.');
            applyFilters();
        })
        .catch(err => {
            errorMsg.textContent = err.message;
            errorAlert.classList.remove('hidden');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Simpan Data`;
        });
    }

    // Modal Edit Handlers
    function openEditModal(kodePeng) {
        document.getElementById('editErrorAlert').classList.add('hidden');
        setProgressBar(true);

        fetch(`{{ url('/lwp/master-penggunaan') }}/${encodeURIComponent(kodePeng)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                const item = res.data;
                document.getElementById('editOriginalKodePeng').value = item.KODE_PENG;
                document.getElementById('editOriginCode').textContent = item.KODE_PENG;
                document.getElementById('editKodePeng').value = item.KODE_PENG;
                document.getElementById('editKodePengOld').value = item.KODE_PENG_OLD || '';
                document.getElementById('editPenggunaan').value = item.PENGGUNAAN;
                document.getElementById('editIconSelect').value = item.ICON || '';

                const notice = document.getElementById('editWakafNotice');
                const badge = document.getElementById('editWakafCountBadge');
                if (res.wakaf_count > 0) {
                    badge.textContent = res.wakaf_count + ' tanah';
                    notice.classList.remove('hidden');
                } else {
                    notice.classList.add('hidden');
                }

                document.getElementById('editUsageModal').classList.remove('hidden');
            } else {
                showToast(res.message || 'Gagal mengambil data.', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Terjadi kesalahan saat memuat data.', 'error');
        })
        .finally(() => {
            setProgressBar(false);
        });
    }

    function closeEditModal() {
        document.getElementById('editUsageModal').classList.add('hidden');
    }

    function submitEditForm(e) {
        e.preventDefault();
        const originKode = document.getElementById('editOriginalKodePeng').value;
        const form = document.getElementById('editUsageForm');
        const btn = document.getElementById('editSubmitBtn');
        const errorAlert = document.getElementById('editErrorAlert');
        const errorMsg = document.getElementById('editErrorMessage');

        errorAlert.classList.add('hidden');
        btn.disabled = true;
        btn.innerHTML = `<span class="inline-block animate-spin mr-2">⏳</span> Menyimpan...`;

        const formData = new FormData(form);

        fetch(`{{ url('/lwp/master-penggunaan') }}/${encodeURIComponent(originKode)}/update`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.message || 'Terjadi kesalahan.');
            }
            return data;
        })
        .then(data => {
            closeEditModal();
            showToast(data.message || 'Data penggunaan berhasil diperbarui.');
            applyFilters(currentUsagePage);
        })
        .catch(err => {
            errorMsg.textContent = err.message;
            errorAlert.classList.remove('hidden');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Simpan Perubahan`;
        });
    }

    // Delete Handlers
    function confirmDeleteUsage(kodePeng, nama, wakafCount) {
        pendingDeleteCode = kodePeng;
        document.getElementById('delUsageName').textContent = `${kodePeng} - ${nama}`;
        const warning = document.getElementById('delWakafWarning');
        const countSpan = document.getElementById('delWakafCount');

        if (wakafCount > 0) {
            countSpan.textContent = wakafCount;
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }

        document.getElementById('deleteConfirmModal').classList.remove('hidden');
    }

    function closeDeleteConfirmModal() {
        document.getElementById('deleteConfirmModal').classList.add('hidden');
        pendingDeleteCode = null;
    }

    function executeDeleteUsage() {
        if (!pendingDeleteCode) return;
        const btn = document.getElementById('executeDeleteBtn');
        btn.disabled = true;
        btn.textContent = 'Menghapus...';

        fetch(`{{ url('/lwp/master-penggunaan') }}/${encodeURIComponent(pendingDeleteCode)}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            closeDeleteConfirmModal();
            showToast(data.message || 'Data penggunaan dipindahkan ke sampah.');
            applyFilters(currentUsagePage);
        })
        .catch(err => {
            console.error(err);
            showToast('Gagal menghapus data.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Ya, Hapus';
        });
    }

    // Restore Handlers
    function restoreUsage(kodePeng, nama) {
        if (!confirm(`Pulihkan penggunaan "${kodePeng} - ${nama}" dari status sampah?`)) {
            return;
        }

        setProgressBar(true);

        fetch(`{{ url('/lwp/master-penggunaan') }}/${encodeURIComponent(kodePeng)}/restore`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            showToast(data.message || 'Data penggunaan berhasil dipulihkan.');
            applyFilters(currentUsagePage);
        })
        .catch(err => {
            console.error(err);
            showToast('Gagal memulihkan data.', 'error');
        })
        .finally(() => {
            setProgressBar(false);
        });
    }

    // Force Delete Handlers
    function confirmForceDeleteUsage(kodePeng, nama, wakafCount) {
        if (wakafCount > 0) {
            alert(`Tidak dapat menghapus permanen! Penggunaan ${kodePeng} masih digunakan oleh ${wakafCount} data tanah wakaf.`);
            return;
        }
        pendingForceDeleteCode = kodePeng;
        document.getElementById('forceDelUsageName').textContent = `${kodePeng} - ${nama}`;
        document.getElementById('forceDeleteModal').classList.remove('hidden');
    }

    function closeForceDeleteModal() {
        document.getElementById('forceDeleteModal').classList.add('hidden');
        pendingForceDeleteCode = null;
    }

    function executeForceDeleteUsage() {
        if (!pendingForceDeleteCode) return;
        const btn = document.getElementById('executeForceDeleteBtn');
        btn.disabled = true;
        btn.textContent = 'Menghapus...';

        fetch(`{{ url('/lwp/master-penggunaan') }}/${encodeURIComponent(pendingForceDeleteCode)}?force=1`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.message || 'Gagal menghapus.');
            }
            return data;
        })
        .then(data => {
            closeForceDeleteModal();
            showToast(data.message || 'Data penggunaan telah dihapus permanen.');
            applyFilters(currentUsagePage);
        })
        .catch(err => {
            alert(err.message);
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Hapus Permanen';
        });
    }

    // Keyboard ESC listener for modals
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDeleteConfirmModal();
            closeForceDeleteModal();
        }
    });
</script>
@endsection
