@extends('layouts.lwp')

@section('title', 'Data Sampah')
@section('page_title', 'Data Sampah')
@section('page_subtitle', 'Kelola data yang telah dihapus.')

@section('content')
    <!-- Progress Bar -->
    <div id="progressBar" class="fixed top-0 left-0 right-0 z-[60] hidden">
        <div class="h-1 bg-nu-green-600 animate-pulse rounded-full"></div>
    </div>

    <!-- Detail Modal -->
    <div id="trashModal" class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeTrashModal()"></div>
        <div class="absolute inset-4 md:inset-8 lg:inset-y-8 lg:inset-x-[15%] bg-white rounded-3xl shadow-2xl flex flex-col overflow-hidden transform transition-all duration-300" id="trashModalContent">
            <div id="trashModalBody" class="flex-1 overflow-y-auto"></div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 bg-white p-1.5 rounded-2xl border border-slate-100 shadow-sm">
            <button id="tabWakaf" class="trash-tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $tab === 'wakaf' ? 'bg-nu-green-600 text-white shadow-lg shadow-nu-green-100' : 'text-slate-400 hover:text-nu-green-600 hover:bg-nu-green-50' }}" data-tab="wakaf">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    Aset Wakaf ({{ $deletedWakaf->total() }})
                </span>
            </button>
            <button id="tabUsers" class="trash-tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $tab === 'users' ? 'bg-nu-green-600 text-white shadow-lg shadow-nu-green-100' : 'text-slate-400 hover:text-nu-green-600 hover:bg-nu-green-50' }}" data-tab="users">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Pengguna ({{ $deletedUsers->total() }})
                </span>
            </button>
        </div>
    </div>

    <!-- Tab Content: Wakaf -->
    <div id="trashTabWakaf" class="{{ $tab === 'wakaf' ? '' : 'hidden' }}">
        <div class="mb-6 bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
            <form id="searchWakafForm" class="flex gap-3">
                <input type="text" name="search_wakaf" placeholder="Cari penggunaan, wakif, no AIW, no sertifikat..." class="flex-1 px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-nu-green-500 focus:border-transparent outline-none" value="{{ request('search_wakaf') }}">
                <button type="submit" class="px-6 py-3 bg-nu-green-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-nu-green-700 transition-all">Cari</button>
            </form>
        </div>
        <div id="trashWakafContainer">
            @include('lwp.dashboard.partials._trash_wakaf', ['deletedWakaf' => $deletedWakaf])
        </div>
    </div>

    <!-- Tab Content: Users -->
    <div id="trashTabUsers" class="{{ $tab === 'users' ? '' : 'hidden' }}">
        <div class="mb-6 bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
            <form id="searchUserForm" class="flex gap-3">
                <input type="text" name="search_user" placeholder="Cari nama, email, atau no HP..." class="flex-1 px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-nu-green-500 focus:border-transparent outline-none" value="{{ request('search_user') }}">
                <button type="submit" class="px-6 py-3 bg-nu-green-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-nu-green-700 transition-all">Cari</button>
            </form>
        </div>
        <div id="trashUserContainer">
            @include('lwp.dashboard.partials._trash_users', ['deletedUsers' => $deletedUsers])
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabWakafBtn = document.getElementById('tabWakaf');
        const tabUsersBtn = document.getElementById('tabUsers');
        const tabWakafContent = document.getElementById('trashTabWakaf');
        const tabUsersContent = document.getElementById('trashTabUsers');

        function switchTab(tab) {
            if (tab === 'wakaf') {
                tabWakafBtn.className = 'trash-tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-nu-green-600 text-white shadow-lg shadow-nu-green-100';
                tabUsersBtn.className = 'trash-tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-slate-400 hover:text-nu-green-600 hover:bg-nu-green-50';
                tabWakafContent.classList.remove('hidden');
                tabUsersContent.classList.add('hidden');
            } else {
                tabUsersBtn.className = 'trash-tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-nu-green-600 text-white shadow-lg shadow-nu-green-100';
                tabWakafBtn.className = 'trash-tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-slate-400 hover:text-nu-green-600 hover:bg-nu-green-50';
                tabUsersContent.classList.remove('hidden');
                tabWakafContent.classList.add('hidden');
            }
        }

        tabWakafBtn.addEventListener('click', () => switchTab('wakaf'));
        tabUsersBtn.addEventListener('click', () => switchTab('users'));

        document.getElementById('searchWakafForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const params = new URLSearchParams(new FormData(this));
            loadTrashData(`{{ route('lwp.trash') }}?tab=wakaf&${params.toString()}`, 'trashWakafContainer');
        });

        document.getElementById('searchUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const params = new URLSearchParams(new FormData(this));
            loadTrashData(`{{ route('lwp.trash') }}?tab=users&${params.toString()}`, 'trashUserContainer');
        });

        document.addEventListener('click', function(e) {
            const link = e.target.closest('.trash-ajax-pagination a');
            if (link) {
                e.preventDefault();
                const url = link.href;
                const container = link.closest('.trash-ajax-pagination').dataset.container || 'trashWakafContainer';
                loadTrashData(url, container);
            }
        });
    });

    var currentWakafPage = 1;
    var currentUserPage = 1;

    function loadTrashData(url, containerId) {
        const container = document.getElementById(containerId);
        const progressBar = document.getElementById('progressBar');
        progressBar.classList.remove('hidden');
        container.style.opacity = '0.5';

        // Track current page from URL
        const urlObj = new URL(url, window.location.origin);
        if (containerId === 'trashWakafContainer') {
            currentWakafPage = urlObj.searchParams.get('wakaf_page') || 1;
        } else if (containerId === 'trashUserContainer') {
            currentUserPage = urlObj.searchParams.get('user_page') || 1;
        }

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text())
            .then(html => {
                container.innerHTML = html;
                container.style.opacity = '1';
                progressBar.classList.add('hidden');
            })
            .catch(err => {
                console.error(err);
                container.style.opacity = '1';
                progressBar.classList.add('hidden');
            });
    }

    function refreshWakafTable() {
        const form = document.getElementById('searchWakafForm');
        const params = new URLSearchParams(new FormData(form));
        params.set('wakaf_page', currentWakafPage);
        loadTrashData(`{{ route('lwp.trash') }}?tab=wakaf&${params.toString()}`, 'trashWakafContainer');
    }

    function refreshUserTable() {
        const form = document.getElementById('searchUserForm');
        const params = new URLSearchParams(new FormData(form));
        params.set('user_page', currentUserPage);
        loadTrashData(`{{ route('lwp.trash') }}?tab=users&${params.toString()}`, 'trashUserContainer');
    }

    // === Modal ===
    function showTrashDetail(id) {
        const progressBar = document.getElementById('progressBar');
        progressBar.classList.remove('hidden');

        fetch(`/lwp/wakaf/${id}?readonly=1`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text())
            .then(html => {
                document.getElementById('trashModalBody').innerHTML = html;
                document.getElementById('trashModal').classList.remove('hidden');
            })
            .finally(() => progressBar.classList.add('hidden'));
    }

    function closeTrashModal() {
        const modal = document.getElementById('trashModal');
        modal.classList.add('hidden');
        document.getElementById('trashModalBody').innerHTML = '';
        if(window.lwpDetailMap) {
            window.lwpDetailMap.remove();
            window.lwpDetailMap = null;
        }
    }

    window.closeLwpModal = closeTrashModal;

    // Tab switching for detail modal
    window.switchLwpTab = function(tabId) {
        document.querySelectorAll('.lwp-tab-btn').forEach(btn => {
            if(btn.dataset.tab === tabId) {
                btn.className = 'lwp-tab-btn pb-3 text-xs font-black uppercase tracking-[0.2em] transition-all border-b-2 border-nu-green-600 text-nu-green-700';
            } else {
                btn.className = 'lwp-tab-btn pb-3 text-xs font-black uppercase tracking-[0.2em] transition-all border-b-2 border-transparent text-slate-400 hover:text-slate-600';
            }
        });

        document.querySelectorAll('.lwp-tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        const activeTab = document.getElementById('lwp-tab-' + tabId);
        if(activeTab) activeTab.classList.remove('hidden');

        if(tabId === 'lokasi') {
            setTimeout(() => {
                const mapEl = document.getElementById('lwpMap');
                if(mapEl) {
                    const lat = parseFloat(mapEl.dataset.lat);
                    const lng = parseFloat(mapEl.dataset.lng);
                    const usage = mapEl.dataset.usage;

                    if(!window.lwpDetailMap) {
                        window.lwpDetailMap = L.map('lwpMap').setView([lat, lng], 16);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap'
                        }).addTo(window.lwpDetailMap);
                        L.marker([lat, lng]).addTo(window.lwpDetailMap)
                            .bindPopup(usage).openPopup();
                    } else {
                        window.lwpDetailMap.setView([lat, lng], 16);
                        window.lwpDetailMap.invalidateSize();
                    }
                }
            }, 400);
        }
    }

    // Preview file (used by detail modal)
    window.previewFile = function(url, name) {
        const ext = name.split('.').pop().toLowerCase();
        const isPdf = ext === 'pdf';
        let content = '';
        if (isPdf) {
            content = `<iframe src="${url}" class="w-full h-full" frameborder="0"></iframe>`;
        } else {
            content = `<img src="${url}" class="max-w-full max-h-full object-contain mx-auto">`;
        }
        const previewHtml = `
            <div class="fixed inset-0 z-[80] flex items-center justify-center bg-black/80" onclick="this.remove()">
                <div class="relative w-[90vw] h-[90vh] flex items-center justify-center" onclick="event.stopPropagation()">
                    <button onclick="this.closest('.fixed').remove()" class="absolute top-4 right-4 z-10 p-2 bg-white/20 hover:bg-white/40 rounded-full text-white transition-all">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    ${content}
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', previewHtml);
    }

    // === Wakaf Actions ===
    function restoreWakaf(id) {
        Swal.fire({
            title: 'Pulihkan Data?',
            text: 'Data aset wakaf ini akan dipulihkan kembali.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#d1d5db',
            confirmButtonText: 'Ya, Pulihkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/lwp/data-sampah/wakaf/${id}/restore`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data aset berhasil dipulihkan.', confirmButtonColor: '#059669', timer: 1500, showConfirmButton: false });
                        refreshWakafTable();
                    }
                })
                .catch(err => Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error'));
            }
        });
    }

    function permanentDeleteWakaf(id) {
        Swal.fire({
            title: 'Hapus Permanen?',
            text: 'Data ini akan dihapus secara permanen dan TIDAK DAPAT dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#d1d5db',
            confirmButtonText: 'Ya, Hapus Permanen!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/lwp/data-sampah/wakaf/${id}/permanent`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: 'Terhapus!', text: 'Data aset telah dihapus permanen.', confirmButtonColor: '#059669', timer: 1500, showConfirmButton: false });
                        refreshWakafTable();
                    }
                })
                .catch(err => Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error'));
            }
        });
    }

    // === User Actions ===
    function restoreUser(id) {
        Swal.fire({
            title: 'Pulihkan Pengguna?',
            text: 'Pengguna ini akan dipulihkan kembali.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#d1d5db',
            confirmButtonText: 'Ya, Pulihkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/lwp/data-sampah/user/${id}/restore`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Pengguna berhasil dipulihkan.', confirmButtonColor: '#059669', timer: 1500, showConfirmButton: false });
                        refreshUserTable();
                    }
                })
                .catch(err => Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error'));
            }
        });
    }

    function permanentDeleteUser(id) {
        Swal.fire({
            title: 'Hapus Permanen?',
            text: 'Pengguna ini akan dihapus secara permanen dan TIDAK DAPAT dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#d1d5db',
            confirmButtonText: 'Ya, Hapus Permanen!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/lwp/data-sampah/user/${id}/permanent`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: 'Terhapus!', text: 'Pengguna telah dihapus permanen.', confirmButtonColor: '#059669', timer: 1500, showConfirmButton: false });
                        refreshUserTable();
                    }
                })
                .catch(err => Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error'));
            }
        });
    }
</script>
@endsection
