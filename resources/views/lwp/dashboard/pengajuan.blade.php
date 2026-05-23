@extends('layouts.lwp')

@section('title', 'Daftar Pengajuan Wakif')
@section('page_title', 'Daftar Pengajuan Wakif')
@section('page_subtitle', 'Kelola pendaftaran aset wakaf baru yang diajukan oleh publik.')

@section('content')
    <div class="mb-8 flex flex-col lg:flex-row items-center justify-between gap-6">
        <!-- Filter Toggle & Search -->
        <div class="flex flex-wrap items-center gap-4 w-full lg:w-auto">
            <button id="toggleFilter" class="flex items-center gap-3 px-6 py-3.5 bg-white border border-slate-100 rounded-2xl font-black text-[11px] uppercase tracking-widest text-slate-600 hover:border-nu-green-200 hover:text-nu-green-600 transition-all shadow-sm group">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-nu-green-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                Filter Data
            </button>
        </div>
    </div>

    <!-- Filter Form (Hidden by Default) -->
    <div id="filterForm" class="hidden mb-10 bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm transition-all duration-300">
        <form id="pengajuanSearchForm" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Cari Kata Kunci</label>
                <input type="text" name="search" placeholder="Nomor pengajuan, wakif, aset..." class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Status Pengajuan</label>
                <select name="status" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-nu-green-500/20 focus:border-nu-green-500 outline-none transition-all">
                    <option value="">Semua Status</option>
                    <option value="PENDING">PENDING</option>
                    <option value="VERIFIKASI_LAPANGAN">VERIFIKASI LAPANGAN</option>
                    <option value="SELESAI">SELESAI</option>
                    <option value="DIBATALKAN">DIBATALKAN</option>
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="reset" class="px-6 py-3.5 rounded-xl font-black text-[11px] uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-all">Reset</button>
                <button type="submit" class="flex-1 px-8 py-3.5 bg-nu-green-600 text-white rounded-xl font-black text-[11px] uppercase tracking-widest shadow-lg shadow-nu-green-100 hover:bg-nu-green-700 transition-all">Terapkan Filter</button>
            </div>
        </form>
    </div>

    <!-- Progress Bar -->
    <div id="progressBar" class="fixed top-0 left-0 w-full h-1 z-[60] overflow-hidden hidden">
        <div class="h-full bg-nu-green-600 animate-[progress_2s_ease-in-out_infinite]" style="width: 30%"></div>
    </div>
    <style>
        @keyframes progress {
            0% { transform: translateX(-100%); width: 30%; }
            50% { width: 60%; }
            100% { transform: translateX(400%); width: 30%; }
        }
    </style>

    <!-- Content Container -->
    <div id="pengajuanDataContainer" class="transition-opacity duration-300">
        @include('lwp.dashboard.partials._pengajuan_list')
    </div>

    <!-- LWP Generic Modal -->
    <div id="lwpModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeLwpModal()"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div id="modalContent" class="relative w-full max-w-3xl bg-white rounded-[3rem] shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300">
                <!-- Content injected here -->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter form toggle
            const toggleFilter = document.getElementById('toggleFilter');
            const filterForm = document.getElementById('filterForm');
            if (toggleFilter && filterForm) {
                toggleFilter.addEventListener('click', function() {
                    filterForm.classList.toggle('hidden');
                });
            }

            // Search form AJAX submission
            const searchForm = document.getElementById('pengajuanSearchForm');
            if (searchForm) {
                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const params = new URLSearchParams(new FormData(searchForm));
                    loadData(`{{ route('lwp.pengajuan.index') }}?${params.toString()}`);
                });
                searchForm.addEventListener('reset', function() {
                    setTimeout(() => loadData(`{{ route('lwp.pengajuan.index') }}`), 50);
                });
            }
        });

        // Global loadData function for Ajax Pagination and Search
        var currentPengajuanPage = 1;
        function loadData(url) {
            const container = document.getElementById('pengajuanDataContainer');
            const progressBar = document.getElementById('progressBar');
            
            progressBar.classList.remove('hidden');
            container.style.opacity = '0.5';

            try {
                const urlObj = new URL(url, window.location.origin);
                currentPengajuanPage = urlObj.searchParams.get('page') || 1;
            } catch(e) {}

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                container.style.opacity = '1';
                progressBar.classList.add('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .catch(err => {
                console.error(err);
                progressBar.classList.add('hidden');
                container.style.opacity = '1';
            });
        }

        function refreshCurrentList() {
            const form = document.getElementById('pengajuanSearchForm');
            const params = new URLSearchParams(new FormData(form));
            params.set('page', currentPengajuanPage);
            loadData(`{{ route('lwp.pengajuan.index') }}?${params.toString()}`);
        }

        // Show Details Modal
        function showDetail(id) {
            const progressBar = document.getElementById('progressBar');
            if(progressBar) progressBar.classList.remove('hidden');
            fetch(`/lwp/pengajuan/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => showLwpModal(html))
                .finally(() => {
                    if(progressBar) progressBar.classList.add('hidden');
                });
        }

        function showLwpModal(html) {
            const modal = document.getElementById('lwpModal');
            const content = document.getElementById('modalContent');
            content.innerHTML = html;
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
            }, 10);
        }

        function closeLwpModal() {
            const modal = document.getElementById('lwpModal');
            const content = document.getElementById('modalContent');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Action: Verifikasi Lapangan (Field Verification)
        function showVerifyLapangan(id) {
            Swal.fire({
                title: 'Verifikasi Lapangan',
                html: `
                    <div class="text-left space-y-2">
                        <label class="block text-xs font-black uppercase text-slate-400">Pilih Tanggal Verifikasi Lapangan</label>
                        <input type="date" id="tanggal_verifikasi" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm" value="${new Date().toISOString().split('T')[0]}">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const tanggal = document.getElementById('tanggal_verifikasi').value;
                    if (!tanggal) {
                        Swal.showValidationMessage('Tanggal verifikasi wajib diisi!');
                    }
                    return { tanggal_verifikasi: tanggal };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    executePost(`/lwp/pengajuan/${id}/verify-lapangan`, result.value);
                }
            });
        }

        // Action: Batalkan Pengajuan (Cancel Application)
        function showCancelPengajuan(id) {
            Swal.fire({
                title: 'Batalkan Pengajuan',
                html: `
                    <div class="text-left space-y-2">
                        <label class="block text-xs font-black uppercase text-slate-400">Alasan Pembatalan Pengajuan</label>
                        <textarea id="alasan_batal" rows="3" placeholder="Masukkan alasan dibatalkan..." class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm"></textarea>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'Batalkan Pengajuan',
                cancelButtonText: 'Kembali',
                preConfirm: () => {
                    const alasan = document.getElementById('alasan_batal').value.trim();
                    if (!alasan) {
                        Swal.showValidationMessage('Alasan pembatalan wajib diisi!');
                    }
                    return { alasan_batal: alasan };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    executePost(`/lwp/pengajuan/${id}/cancel`, result.value);
                }
            });
        }

        // Action: Proses Selesai (Complete Wizard & Export to wk_wakaf)
        function showCompletePengajuan(id, defaultIkrar, defaultSertifikat, defaultWakif, defaultNazhir) {
            Swal.fire({
                title: 'Proses Selesai',
                html: `
                    <div class="text-left space-y-4 max-h-[60vh] overflow-y-auto p-1">
                        <div class="space-y-1">
                            <label class="block text-[10px] font-black uppercase text-slate-400">Nomor Akta Ikrar Wakaf (AIW)</label>
                            <input type="text" id="nomor_ikrar" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold" value="${defaultIkrar || ''}">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] font-black uppercase text-slate-400">Tanggal Ikrar Wakaf</label>
                            <input type="date" id="tanggal_ikrar" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] font-black uppercase text-slate-400">Nomor Sertifikat / NIB</label>
                            <input type="text" id="nomor_sertifikat" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold" value="${defaultSertifikat || ''}">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] font-black uppercase text-slate-400">Tanggal Sertifikat</label>
                            <input type="date" id="tanggal_sertifikat" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] font-black uppercase text-slate-400">Atas Nama Sertifikat</label>
                            <input type="text" id="atas_nama_sertifikat" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold" value="${defaultNazhir || 'NAHDLATUL ULAMA'}">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] font-black uppercase text-slate-400">Jenis Sertifikat</label>
                            <select id="jenis_sertifikat" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold">
                                <option value="WAKAF">Sertifikat Wakaf</option>
                                <option value="SHM">Sertifikat Hak Milik (SHM)</option>
                                <option value="SHGB">Sertifikat Hak Guna Bangunan (SHGB)</option>
                            </select>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'Selesai & Ekspor',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const noIkrar = document.getElementById('nomor_ikrar').value.trim();
                    const tglIkrar = document.getElementById('tanggal_ikrar').value;
                    const noSert = document.getElementById('nomor_sertifikat').value.trim();
                    const tglSert = document.getElementById('tanggal_sertifikat').value;
                    const atasNama = document.getElementById('atas_nama_sertifikat').value.trim();
                    const jenis = document.getElementById('jenis_sertifikat').value;

                    if (!noIkrar || !tglIkrar || !noSert || !tglSert || !atasNama || !jenis) {
                        Swal.showValidationMessage('Seluruh kolom legalitas wajib diisi untuk memproses selesai!');
                    }

                    return {
                        nomor_ikrar: noIkrar,
                        tanggal_ikrar: tglIkrar,
                        nomor_sertifikat: noSert,
                        tanggal_sertifikat: tglSert,
                        atas_nama_sertifikat: atasNama,
                        jenis_sertifikat: jenis
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    executePost(`/lwp/pengajuan/${id}/complete`, result.value);
                }
            });
        }

        // Action: Manual Export to SIWAKNU (for ASET_NU)
        function exportToSiwaknu(id) {
            Swal.fire({
                title: 'Ekspor ke SIWAKNU?',
                text: "Apakah Anda yakin ingin mengekspor data pengajuan ini ke SIWAKNU sebagai aset tanah Nahdlatul Ulama?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'Ya, Ekspor!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    executePost(`/lwp/pengajuan/${id}/export`, {});
                }
            });
        }

        // Helper fetch executor
        function executePost(url, payload) {
            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    closeLwpModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#059669'
                    }).then(() => refreshCurrentList());
                } else {
                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Gagal!', 'Koneksi ke server terputus.', 'error');
            });
        }
    </script>
@endsection
