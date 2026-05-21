@extends('layouts.lwp')

@section('title', 'Manajemen Data Pengguna')
@section('page_title', 'Data Pengguna')
@section('page_subtitle', 'Kelola data akun pengurus LWP dan hak akses wilayah')

@section('content')
<div class="space-y-8">
    <!-- Action and Search Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden relative">
        <!-- Premium Horizontal Progress Bar -->
        <div id="userProgressBar" class="absolute top-0 left-0 right-0 h-1 bg-nu-green-500 transition-all duration-300 transform scale-x-0 origin-left z-20"></div>

        <!-- Toolbar -->
        <div class="px-8 py-6 border-b border-slate-50 space-y-4">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                <!-- Left Filters Group -->
                <div class="flex flex-col md:flex-row items-stretch md:items-center flex-1 gap-4">
                    <!-- Search -->
                    <div class="relative min-w-[280px] md:max-w-xs">
                        <input type="text" id="searchUser" value="{{ $search }}" placeholder="Cari berdasarkan nama, username..." class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all placeholder:text-slate-400">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </span>
                        <button id="clearUserSearch" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-0.5 {{ $search ? '' : 'hidden' }}">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Cascading Location Filters based on role -->
                    @if(in_array((int)$currentUser->LEVEL, [9, 91]))
                        <!-- Admin App & PBNU: Provinsi, Kab, Kec -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
                            <select id="filterProv" class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                                <option value="">Semua Provinsi</option>
                                @foreach($filterProvinces as $prov)
                                    <option value="{{ $prov->kode_lokasi }}">{{ $prov->nama_lokasi }}</option>
                                @endforeach
                            </select>
                            <select id="filterKab" disabled class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all disabled:opacity-50">
                                <option value="">Semua Kab/Kota</option>
                            </select>
                            <select id="filterKec" disabled class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all disabled:opacity-50">
                                <option value="">Semua Kecamatan</option>
                            </select>
                        </div>
                    @elseif((int)$currentUser->LEVEL === 31)
                        <!-- Moderator PWNU: Kab, Kec -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1 md:max-w-md">
                            <select id="filterKab" class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                                <option value="">Semua Kab/Kota</option>
                                @foreach($filterKabupatens as $kab)
                                    <option value="{{ $kab->kode_lokasi }}">{{ $kab->nama_lokasi }}</option>
                                @endforeach
                            </select>
                            <select id="filterKec" disabled class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all disabled:opacity-50">
                                <option value="">Semua Kecamatan</option>
                            </select>
                        </div>
                    @elseif((int)$currentUser->LEVEL === 3)
                        <!-- Moderator PCNU: Kec -->
                        <div class="flex-1 md:max-w-xs">
                            <select id="filterKec" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                                <option value="">Semua Kecamatan</option>
                                @foreach($filterKecamatans as $kec)
                                    <option value="{{ $kec->kode_lokasi }}">{{ $kec->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <!-- Create Button -->
                <div class="shrink-0 flex items-center">
                    <button onclick="openCreateModal()" class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3.5 bg-nu-green-600 hover:bg-nu-green-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                        Tambah Pengguna
                    </button>
                </div>
            </div>
        </div>

        <!-- Ajax Table Area -->
        <div id="userTableContainer">
            @include('lwp.user.partials._user_table')
        </div>
    </div>
</div>

<!-- Confirmation Delete Modal -->
<div id="deleteConfirmModel" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50" onclick="closeDeleteConfirmModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <div class="p-8">
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Hapus Data Pengguna?</h3>
                <p class="text-slate-500 text-xs font-semibold leading-relaxed mb-6">Apakah Anda yakin ingin menghapus akun pengguna <strong id="deleteUserDisplay" class="text-slate-800"></strong>? Tindakan ini akan menonaktifkan login pengguna tersebut.</p>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeDeleteConfirmModal()" class="px-5 py-3 bg-white hover:bg-slate-100 text-slate-700 border border-slate-100 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                        Batal
                    </button>
                    <button type="button" id="deleteConfirmBtn" class="px-5 py-3 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Partial Includes -->
@include('lwp.user.partials._user_create_modal')
@include('lwp.user.partials._user_edit_modal')
@include('lwp.user.partials._user_detail_modal')
@include('lwp.user.partials._user_reset_password_modal')

<!-- Interactive CRUD and Cascades JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchUser');
    const clearSearch = document.getElementById('clearUserSearch');
    const progressBar = document.getElementById('userProgressBar');

    let searchTimeout;

    // Helper: Progress Bar Loader
    function startLoading() {
        progressBar.classList.remove('transition-none');
        progressBar.style.transform = 'scaleX(0)';
        setTimeout(() => {
            progressBar.style.transform = 'scaleX(0.4)';
        }, 50);
        setTimeout(() => {
            progressBar.style.transform = 'scaleX(0.75)';
        }, 350);
    }

    function stopLoading() {
        progressBar.style.transform = 'scaleX(1)';
        setTimeout(() => {
            progressBar.style.transform = 'scaleX(0)';
        }, 250);
    }

    // Main: Load paginated users via AJAX
    var currentUserPage = 1;
    window.loadUsersData = function(page) {
        if (typeof page !== 'undefined') currentUserPage = page;
        page = currentUserPage;
        const search = searchInput.value;
        const provFilter = document.getElementById('filterProv');
        const kabFilter = document.getElementById('filterKab');
        const kecFilter = document.getElementById('filterKec');

        let loc = '';
        if (kecFilter && kecFilter.value) {
            loc = kecFilter.value;
        } else if (kabFilter && kabFilter.value) {
            loc = kabFilter.value;
        } else if (provFilter && provFilter.value) {
            loc = provFilter.value;
        }

        startLoading();

        fetch(`/lwp/users?search=${search}&kode_lokasi=${loc}&page=${page}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                document.getElementById('userTableContainer').innerHTML = res.html;
                
                if (search) {
                    clearSearch.classList.remove('hidden');
                } else {
                    clearSearch.classList.add('hidden');
                }
                bindPagination();
            }
            stopLoading();
        })
        .catch(err => {
            console.error(err);
            stopLoading();
        });
    }

    function bindPagination() {
        const paginationLinks = document.querySelectorAll('.user-pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page') || 1;
                loadUsersData(page);
            });
        });
    }

    // Realtime Search Input debounce
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadUsersData(1);
        }, 400);
    });

    // Clear Search click
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        loadUsersData(1);
    });

    // Cascading Location Filters on index page
    const filterProv = document.getElementById('filterProv');
    const filterKab = document.getElementById('filterKab');
    const filterKec = document.getElementById('filterKec');

    if (filterProv) {
        filterProv.addEventListener('change', function() {
            if (filterKab) {
                filterKab.innerHTML = '<option value="">Semua Kab/Kota</option>';
                filterKab.disabled = true;
            }
            if (filterKec) {
                filterKec.innerHTML = '<option value="">Semua Kecamatan</option>';
                filterKec.disabled = true;
            }
            if (this.value) {
                fetch(`/lwp/lokasi-helper?parent=${this.value}&level=kab`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '<option value="">Semua Kab/Kota</option>';
                        data.forEach(item => {
                            html += `<option value="${item.kode_lokasi}">${item.nama_lokasi}</option>`;
                        });
                        filterKab.innerHTML = html;
                        filterKab.disabled = false;
                    });
            }
            loadUsersData(1);
        });
    }

    if (filterKab) {
        filterKab.addEventListener('change', function() {
            if (filterKec) {
                filterKec.innerHTML = '<option value="">Semua Kecamatan</option>';
                filterKec.disabled = true;
            }
            if (this.value) {
                fetch(`/lwp/lokasi-helper?parent=${this.value}&level=kec`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '<option value="">Semua Kecamatan</option>';
                        data.forEach(item => {
                            html += `<option value="${item.kode_lokasi}">${item.nama_lokasi}</option>`;
                        });
                        filterKec.innerHTML = html;
                        filterKec.disabled = false;
                    });
            }
            loadUsersData(1);
        });
    }

    if (filterKec) {
        filterKec.addEventListener('change', function() {
            loadUsersData(1);
        });
    }

    bindPagination();
});

// Helper: Password generator
function makeRandomPassword() {
    const length = 10;
    const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%*";
    let password = "";
    for (let i = 0; i < length; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return password;
}

function generateCreatePasswordRecommendation() {
    const pwd = makeRandomPassword();
    document.getElementById('createPasswordInput').value = pwd;
}

function generateResetPasswordRecommendation() {
    const pwd = makeRandomPassword();
    document.getElementById('resetPasswordInput').value = pwd;
}

// Modal open/close controls
function openCreateModal() {
    document.getElementById('createErrorAlert').classList.add('hidden');
    document.getElementById('createForm').reset();
    document.getElementById('createModel').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModel').classList.add('hidden');
}

function closeEditModal() {
    document.getElementById('editModel').classList.add('hidden');
}

function showUserDetail(id) {
    const initialEl = document.getElementById('detailInitial');
    const namaEl = document.getElementById('detailNama');
    const lvlEl = document.getElementById('detailLevel');
    const usernameEl = document.getElementById('detailUsername');
    const emailEl = document.getElementById('detailEmail');
    const telpEl = document.getElementById('detailTelp');
    const cabangEl = document.getElementById('detailCabang');
    const kartanuEl = document.getElementById('detailKartanu');
    const wilayahEl = document.getElementById('detailWilayah');
    const alamatEl = document.getElementById('detailAlamat');

    fetch(`/lwp/users/${id}`)
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                const u = res.data;
                initialEl.innerText = u.NAMA_USER ? u.NAMA_USER.substring(0, 1).toUpperCase() : 'U';
                namaEl.innerText = u.NAMA_USER;
                lvlEl.innerText = u.NAMA_LEVEL;
                usernameEl.innerText = u.USERNAME_USER;
                emailEl.innerText = u.EMAIL_USER;
                telpEl.innerText = u.TELP_USER || '-';
                cabangEl.innerText = u.NAMA_CABANG || '-';
                kartanuEl.innerText = u.NO_KARTANU || '-';
                
                let regionStr = u.KODE_LOKASI || '-';
                if (u.PROV) {
                    regionStr += ' (' + u.PROV;
                    if (u.KAB) regionStr += ' > ' + u.KAB;
                    if (u.KEC) regionStr += ' > ' + u.KEC;
                    if (u.KEL) regionStr += ' > ' + u.KEL;
                    regionStr += ')';
                }
                wilayahEl.innerText = regionStr;
                alamatEl.innerText = u.ALAMAT_USER || '-';

                document.getElementById('detailModel').classList.remove('hidden');
            }
        });
}

function closeDetailModal() {
    document.getElementById('detailModel').classList.add('hidden');
}

// Reset Password Modal Functions
function openResetPassword(id, name) {
    document.getElementById('resetPasswordErrorAlert').classList.add('hidden');
    document.getElementById('resetPasswordUserId').value = id;
    document.getElementById('resetPasswordTitle').innerText = name;
    document.getElementById('resetPasswordInput').value = '';
    document.getElementById('resetPasswordModel').classList.remove('hidden');
}

function closeResetPasswordModal() {
    document.getElementById('resetPasswordModel').classList.add('hidden');
}

function submitResetPasswordForm(e) {
    e.preventDefault();
    const id = document.getElementById('resetPasswordUserId').value;
    const submitBtn = document.getElementById('resetPasswordSubmitBtn');
    const errorAlert = document.getElementById('resetPasswordErrorAlert');
    const pwd = document.getElementById('resetPasswordInput').value;

    submitBtn.disabled = true;
    submitBtn.innerText = 'Memproses...';

    fetch(`/lwp/users/${id}/reset-password`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({ password: pwd })
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            closeResetPasswordModal();
            window.loadUsersData();
            // Show custom alert if necessary, or let AJAX refresh
        } else {
            errorAlert.innerText = res.message;
            errorAlert.classList.remove('hidden');
        }
        submitBtn.disabled = false;
        submitBtn.innerText = 'Update Password';
    })
    .catch(err => {
        errorAlert.innerText = 'Terjadi kesalahan sistem.';
        errorAlert.classList.remove('hidden');
        submitBtn.disabled = false;
        submitBtn.innerText = 'Update Password';
    });
}

// Delete Confirmation
let targetDeleteUserId = null;
function confirmDeleteUser(id, name) {
    targetDeleteUserId = id;
    document.getElementById('deleteUserDisplay').innerText = name;
    document.getElementById('deleteConfirmModel').classList.remove('hidden');
}

function closeDeleteConfirmModal() {
    document.getElementById('deleteConfirmModel').classList.add('hidden');
    targetDeleteUserId = null;
}

document.getElementById('deleteConfirmBtn').addEventListener('click', function() {
    if (!targetDeleteUserId) return;
    
    this.disabled = true;
    this.innerText = 'Menghapus...';

    fetch(`/lwp/users/${targetDeleteUserId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            closeDeleteConfirmModal();
            window.loadUsersData();
        }
        this.disabled = false;
        this.innerText = 'Ya, Hapus';
    })
    .catch(err => {
        console.error(err);
        this.disabled = false;
        this.innerText = 'Ya, Hapus';
    });
});

// Cascading Locations Logic for CREATE & EDIT modals
function bindCascades(prefix) {
    const provSelect = document.getElementById(`${prefix}Prov`);
    const kabSelect = document.getElementById(`${prefix}Kab`);
    const kecSelect = document.getElementById(`${prefix}Kec`);
    const kelSelect = document.getElementById(`${prefix}Kel`);
    const cabangSelect = document.getElementById(`${prefix}Cabang`);

    function fetchRegionOptions(parentCode, targetLevel, targetSelectElement, defaultText) {
        if (!parentCode) {
            targetSelectElement.innerHTML = `<option value="">${defaultText}</option>`;
            targetSelectElement.disabled = true;
            return Promise.resolve();
        }

        return fetch(`/lwp/lokasi-helper?parent=${parentCode}&level=${targetLevel}`)
            .then(res => res.json())
            .then(data => {
                let options = `<option value="">${defaultText}</option>`;
                data.forEach(item => {
                    options += `<option value="${item.kode_lokasi}">${item.nama_lokasi}</option>`;
                });
                targetSelectElement.innerHTML = options;
                targetSelectElement.disabled = false;
            })
            .catch(err => {
                console.error(err);
            });
    }

    provSelect.addEventListener('change', function() {
        kabSelect.innerHTML = '<option value="">Pilih Kabupaten</option>';
        kabSelect.disabled = true;
        kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        kecSelect.disabled = true;
        kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
        kelSelect.disabled = true;

        fetchRegionOptions(this.value, 'kab', kabSelect, 'Pilih Kabupaten');

        // Fetch Cabang list matching province prefix
        if (cabangSelect) {
            cabangSelect.innerHTML = '<option value="">Pilih Cabang (Loading...)</option>';
            cabangSelect.disabled = true;
            if (this.value) {
                fetch(`/lwp/cabang-helper?parent=${this.value}`)
                    .then(res => res.json())
                    .then(cabangs => {
                        let options = '<option value="">Pilih Cabang (Opsional)</option>';
                        cabangs.forEach(cb => {
                            options += `<option value="${cb.ID_CABANG}" data-kode="${cb.KODE_CABANG}">${cb.NAMA_CABANG} (${cb.NAMA_INDEX})</option>`;
                        });
                        cabangSelect.innerHTML = options;
                        cabangSelect.disabled = false;
                    });
            } else {
                cabangSelect.innerHTML = '<option value="">Pilih Cabang (Pilih Provinsi Terlebih Dahulu)</option>';
                cabangSelect.disabled = true;
            }
        }
    });

    kabSelect.addEventListener('change', function() {
        kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        kecSelect.disabled = true;
        kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
        kelSelect.disabled = true;

        fetchRegionOptions(this.value, 'kec', kecSelect, 'Pilih Kecamatan');

        // Auto select Cabang based on Kabupaten code
        if (cabangSelect) {
            const kabCode = this.value;
            if (kabCode) {
                let matched = false;
                for (let i = 0; i < cabangSelect.options.length; i++) {
                    const opt = cabangSelect.options[i];
                    if (opt.getAttribute('data-kode') === kabCode) {
                        cabangSelect.value = opt.value;
                        matched = true;
                        break;
                    }
                }
                if (!matched) {
                    cabangSelect.value = "";
                }
            } else {
                cabangSelect.value = "";
            }
        }
    });

    kecSelect.addEventListener('change', function() {
        kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
        kelSelect.disabled = true;

        fetchRegionOptions(this.value, 'kel', kelSelect, 'Pilih Kelurahan');
    });
}

bindCascades('create');
bindCascades('edit');

// Submit Create Form
function submitCreateForm(e) {
    e.preventDefault();
    const form = document.getElementById('createForm');
    const submitBtn = document.getElementById('createSubmitBtn');
    const errorAlert = document.getElementById('createErrorAlert');
    
    submitBtn.disabled = true;
    submitBtn.innerText = 'Menyimpan...';

    const formData = new FormData(form);

    fetch('/lwp/users', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            closeCreateModal();
            window.loadUsersData();
        } else {
            errorAlert.innerText = res.message;
            errorAlert.classList.remove('hidden');
        }
        submitBtn.disabled = false;
        submitBtn.innerText = 'Simpan Pengguna';
    })
    .catch(err => {
        errorAlert.innerText = 'Terjadi kesalahan sistem.';
        errorAlert.classList.remove('hidden');
        submitBtn.disabled = false;
        submitBtn.innerText = 'Simpan Pengguna';
    });
}

// Edit User (Prefill and show)
function editUser(id) {
    document.getElementById('editErrorAlert').classList.add('hidden');
    
    // Helper to chain prefill selects
    function populateRegionChain(provCode, kabCode, kecCode, kelCode) {
        const provSelect = document.getElementById('editProv');
        const kabSelect = document.getElementById('editKab');
        const kecSelect = document.getElementById('editKec');
        const kelSelect = document.getElementById('editKel');

        provSelect.value = provCode;

        function fetchAndSelect(parentCode, targetLevel, targetSelect, selectValue, nextStepCallback) {
            if (!parentCode) {
                targetSelect.innerHTML = '<option value="">Pilih Option</option>';
                targetSelect.disabled = true;
                if (nextStepCallback) nextStepCallback();
                return;
            }
            fetch(`/lwp/lokasi-helper?parent=${parentCode}&level=${targetLevel}`)
                .then(res => res.json())
                .then(data => {
                    let options = `<option value="">Pilih Option</option>`;
                    data.forEach(item => {
                        options += `<option value="${item.kode_lokasi}">${item.nama_lokasi}</option>`;
                    });
                    targetSelect.innerHTML = options;
                    targetSelect.value = selectValue || '';
                    targetSelect.disabled = false;
                    if (nextStepCallback) nextStepCallback();
                });
        }

        // Chain load kabupaten -> kecamatan -> kelurahan
        fetchAndSelect(provCode, 'kab', kabSelect, kabCode, () => {
            fetchAndSelect(kabCode, 'kec', kecSelect, kecCode, () => {
                fetchAndSelect(kecCode, 'kel', kelSelect, kelCode);
            });
        });
    }

    fetch(`/lwp/users/${id}`)
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                const u = res.data;
                const locs = res.location_codes;

                document.getElementById('editUserId').value = u.ID_USER;
                document.getElementById('editNama').value = u.NAMA_USER;
                document.getElementById('editUsername').value = u.USERNAME_USER;
                document.getElementById('editEmail').value = u.EMAIL_USER;
                document.getElementById('editTelp').value = u.TELP_USER || '';
                document.getElementById('editLevel').value = u.LEVEL || '';
                document.getElementById('editKartanu').value = u.NO_KARTANU || '';
                document.getElementById('editAlamat').value = u.ALAMAT_USER || '';

                // Load Cabangs and select active cabang
                const editCabangSelect = document.getElementById('editCabang');
                if (locs.province) {
                    editCabangSelect.innerHTML = '<option value="">Pilih Cabang (Loading...)</option>';
                    editCabangSelect.disabled = true;
                    fetch(`/lwp/cabang-helper?parent=${locs.province}`)
                        .then(res => res.json())
                        .then(cabangs => {
                            let options = '<option value="">Pilih Cabang (Opsional)</option>';
                            cabangs.forEach(cb => {
                                options += `<option value="${cb.ID_CABANG}" data-kode="${cb.KODE_CABANG}">${cb.NAMA_CABANG} (${cb.NAMA_INDEX})</option>`;
                            });
                            editCabangSelect.innerHTML = options;
                            editCabangSelect.disabled = false;
                            editCabangSelect.value = u.ID_CABANG || '';
                        });
                } else {
                    editCabangSelect.innerHTML = '<option value="">Pilih Cabang (Pilih Provinsi Terlebih Dahulu)</option>';
                    editCabangSelect.disabled = true;
                }

                // Prefill location cascade
                populateRegionChain(locs.province, locs.kabupaten, locs.kecamatan, locs.kelurahan);

                document.getElementById('editModel').classList.remove('hidden');
            }
        });
}

// Submit Edit Form
function submitEditForm(e) {
    e.preventDefault();
    const id = document.getElementById('editUserId').value;
    const form = document.getElementById('editForm');
    const submitBtn = document.getElementById('editSubmitBtn');
    const errorAlert = document.getElementById('editErrorAlert');

    submitBtn.disabled = true;
    submitBtn.innerText = 'Menyimpan...';

    const formData = new FormData(form);

    fetch(`/lwp/users/${id}/update`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            closeEditModal();
            window.loadUsersData();
        } else {
            errorAlert.innerText = res.message;
            errorAlert.classList.remove('hidden');
        }
        submitBtn.disabled = false;
        submitBtn.innerText = 'Simpan Perubahan';
    })
    .catch(err => {
        errorAlert.innerText = 'Terjadi kesalahan sistem.';
        errorAlert.classList.remove('hidden');
        submitBtn.disabled = false;
        submitBtn.innerText = 'Simpan Perubahan';
    });
}
</script>
@endsection
