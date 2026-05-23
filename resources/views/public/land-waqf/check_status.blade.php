<x-public-layout>
    @section('title', 'Cek Status Pendaftaran Aset')

    <div class="py-12 pt-36 bg-surface-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Progress Bar -->
            <div id="statusProgressBar" class="fixed top-0 left-0 w-full h-1 bg-nu-green-600 transform -translate-x-full transition-transform duration-300 z-[100] pointer-events-none"></div>

            <!-- Header Section -->
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-black text-surface-900 tracking-tight uppercase">Cek Status Pendaftaran Aset</h1>
                <p class="text-surface-500 mt-2 font-semibold uppercase tracking-wider text-xs">
                    Pantau proses verifikasi aset wakaf Anda atas nama <span class="text-nu-green-700">Nazhir Nahdlatul Ulama</span>
                </p>
            </div>

            <!-- Search Form Card -->
            <div class="bg-white rounded-[2rem] shadow-card border border-surface-100 p-8 md:p-10 mb-8 relative overflow-hidden">
                <form id="checkStatusForm" method="GET" action="{{ route('land-waqf.check-status.form') }}" class="space-y-6">
                    <div class="space-y-2">
                        <label for="nomor_pengajuan" class="block text-[10px] font-black text-surface-400 uppercase tracking-widest ml-1">Masukkan Nomor Registrasi / Pengajuan</label>
                        <div class="relative group">
                            <input type="text" id="nomor_pengajuan" name="nomor_pengajuan" value="{{ $nomorPengajuan ?? '' }}" required
                                class="w-full pl-12 pr-4 py-4 rounded-2xl border-none ring-1 ring-surface-200 focus:ring-2 focus:ring-nu-green-600 shadow-sm text-base font-bold transition-all uppercase placeholder:normal-case placeholder:font-medium placeholder:text-surface-300"
                                placeholder="Contoh: REG/20260523/00003">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400 group-focus-within:text-nu-green-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-4 pt-2">
                        <a href="{{ route('land-waqf.catalog') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-surface-100 text-surface-600 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-surface-200 transition-all">
                            Kembali ke Katalog
                        </a>
                        <button type="submit" id="btnSubmitStatus" class="inline-flex items-center gap-2 px-8 py-3.5 bg-nu-green-600 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-nu-green-100 hover:bg-nu-green-700 transition-all">
                            Cari Pendaftaran
                        </button>
                    </div>
                </form>
            </div>

            <!-- Search Result Container (Updated via AJAX) -->
            <div id="statusResultContainer" class="transition-opacity duration-300">
                @if($searched)
                    @include('public.land-waqf.partials._check_status_result')
                @endif
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('checkStatusForm');
            const container = document.getElementById('statusResultContainer');
            const progressBar = document.getElementById('statusProgressBar');
            const btnSubmit = document.getElementById('btnSubmitStatus');
            const inputField = document.getElementById('nomor_pengajuan');

            if (!form || !container || !progressBar) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const nomorValue = inputField.value.trim();
                if (!nomorValue) return;

                // 1. Show progress bar at 70% and lower opacity of container
                progressBar.style.transition = 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                progressBar.style.transform = 'translateX(-30%)';
                container.style.opacity = '0.5';
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = 'Sedang mencari...';

                // 2. Formulate target URL with query params
                const params = new URLSearchParams({ nomor_pengajuan: nomorValue });
                const requestUrl = `${form.getAttribute('action')}?${params.toString()}`;

                // 3. Fetch via AJAX
                fetch(requestUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response error');
                    return response.text();
                })
                .then(html => {
                    // Update URL and browser history without reloading
                    window.history.pushState({ path: requestUrl }, '', requestUrl);

                    // 4. Animate progress bar to 100%
                    progressBar.style.transition = 'transform 0.2s ease-out';
                    progressBar.style.transform = 'translateX(0%)';

                    setTimeout(() => {
                        // Inject result HTML and restore opacity
                        container.innerHTML = html;
                        container.style.opacity = '1';
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = 'Cari Pendaftaran';

                        // Fade out the progress bar
                        setTimeout(() => {
                            progressBar.style.transition = 'none';
                            progressBar.style.transform = 'translateX(-100%)';
                        }, 200);

                        // Smooth scroll to the result
                        window.scrollTo({
                            top: container.offsetTop - 120,
                            behavior: 'smooth'
                        });
                    }, 150);
                })
                .catch(error => {
                    console.error('Error checking status:', error);
                    container.style.opacity = '1';
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = 'Cari Pendaftaran';
                    
                    // Reset progress bar on error
                    progressBar.style.transform = 'translateX(-100%)';
                });
            });

            // Re-render check if browser back/forward is pressed
            window.addEventListener('popstate', function() {
                const urlParams = new URLSearchParams(window.location.search);
                const queryVal = urlParams.get('nomor_pengajuan');
                if (queryVal) {
                    inputField.value = queryVal;
                    // Trigger submit programmatically
                    form.dispatchEvent(new Event('submit', { cancelable: true }));
                } else {
                    inputField.value = '';
                    container.innerHTML = '';
                }
            });
        });
    </script>
</x-public-layout>
