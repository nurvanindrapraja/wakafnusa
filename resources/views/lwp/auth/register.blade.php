<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengurus LWP - WAKAF NUSA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nu-green': {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#006400', // Deep NU Green
                            700: '#004d00',
                        },
                        'nu-gold': {
                            50: '#fffbeb',
                            500: '#D4AF37', // Gold
                            600: '#B8860B',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .bg-nu-pattern {
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23006400' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-nu-pattern min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-[2.5rem] shadow-2xl border border-surface-100 overflow-hidden">
            <!-- Header -->
            <div class="p-8 text-center bg-gradient-to-b from-nu-green-50/50 to-white border-b border-slate-50">
                <img src="{{ asset('images/logo-nu.svg') }}" 
                    alt="Logo NU" class="w-24 mx-auto mb-4 drop-shadow-lg">
                <h1 class="text-2xl font-black text-slate-900 leading-tight">Daftar Akun Pengurus LWP</h1>
                <p class="text-slate-400 text-xs mt-1 font-medium tracking-wide">Pendaftaran Portal Lembaga Wakaf dan Pertanahan NU</p>
            </div>

            <!-- Form Body -->
            <div class="p-8">
                <form action="{{ route('lwp.register.post') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    @if($errors->any())
                        <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-sm font-bold flex items-center gap-3 border border-red-100 animate-shake">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column: Personal Info -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-black text-nu-green-600 uppercase tracking-widest border-b pb-2">Informasi Pribadi</h3>
                            
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" required value="{{ old('nama') }}"
                                    class="w-full px-4 py-3 bg-slate-50 border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-nu-green-600 rounded-xl text-slate-900 font-bold text-sm transition-all"
                                    placeholder="Nama lengkap Anda">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Username <span class="text-red-500">*</span></label>
                                <input type="text" name="username" required value="{{ old('username') }}"
                                    class="w-full px-4 py-3 bg-slate-50 border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-nu-green-600 rounded-xl text-slate-900 font-bold text-sm transition-all"
                                    placeholder="Username akun">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" required value="{{ old('email') }}"
                                    class="w-full px-4 py-3 bg-slate-50 border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-nu-green-600 rounded-xl text-slate-900 font-bold text-sm transition-all"
                                    placeholder="alamat@email.com">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kata Sandi <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required
                                    class="w-full px-4 py-3 bg-slate-50 border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-nu-green-600 rounded-xl text-slate-900 font-bold text-sm transition-all"
                                    placeholder="Min. 6 karakter">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Konfirmasi Kata Sandi <span class="text-red-500">*</span></label>
                                <input type="password" name="password_confirmation" required
                                    class="w-full px-4 py-3 bg-slate-50 border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-nu-green-600 rounded-xl text-slate-900 font-bold text-sm transition-all"
                                    placeholder="Ulangi kata sandi">
                            </div>
                        </div>

                        <!-- Right Column: Location -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-black text-nu-green-600 uppercase tracking-widest border-b pb-2">Wilayah Penugasan</h3>
                            
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Provinsi <span class="text-red-500">*</span></label>
                                <select id="regProvinsi" name="prov" required
                                    class="w-full px-4 py-3 bg-white border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-nu-green-600 rounded-xl text-slate-900 font-bold text-sm transition-all">
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinces as $prov)
                                        <option value="{{ $prov->kode_lokasi }}" {{ old('prov') == $prov->kode_lokasi ? 'selected' : '' }}>{{ $prov->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kabupaten / Kota</label>
                                <select id="regKabupaten" name="kab"
                                    class="w-full px-4 py-3 bg-white border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-nu-green-600 rounded-xl text-slate-900 font-bold text-sm transition-all disabled:opacity-60 disabled:bg-slate-100" disabled>
                                    <option value="">Pilih Kabupaten / Kota</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kecamatan</label>
                                <select id="regKecamatan" name="kec"
                                    class="w-full px-4 py-3 bg-white border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-nu-green-600 rounded-xl text-slate-900 font-bold text-sm transition-all disabled:opacity-60 disabled:bg-slate-100" disabled>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kelurahan</label>
                                <select id="regKelurahan" name="kel"
                                    class="w-full px-4 py-3 bg-white border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-nu-green-600 rounded-xl text-slate-900 font-bold text-sm transition-all disabled:opacity-60 disabled:bg-slate-100" disabled>
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full py-4 mt-4 bg-nu-green-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-nu-green-700 transition-all shadow-xl shadow-nu-green-100 group">
                        Daftar Akun
                        <svg class="w-4 h-4 inline-block ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </form>

                <div class="mt-8 text-center border-t pt-6 flex justify-between items-center text-xs">
                    <a href="{{ route('lwp.login') }}" class="font-bold text-slate-400 hover:text-nu-green-600 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        Sudah Punya Akun? Login
                    </a>
                    
                    <a href="{{ route('land-waqf.catalog') }}" class="font-bold text-slate-400 hover:text-nu-green-600 transition-colors flex items-center gap-2">
                        Katalog Wakaf
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>
            </div>
        </div>
        
        <p class="mt-8 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
            &copy; 2026 WAKAF NUSA • LWPNU
        </p>
    </div>

    <!-- Script Cascading Dropdowns -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const provSelect = document.getElementById('regProvinsi');
        const kabSelect = document.getElementById('regKabupaten');
        const kecSelect = document.getElementById('regKecamatan');
        const kelSelect = document.getElementById('regKelurahan');

        function fetchChildren(parentCode, targetLevel, selectEl, defaultText) {
            if (!parentCode) {
                selectEl.innerHTML = `<option value="">${defaultText}</option>`;
                selectEl.disabled = true;
                return Promise.resolve();
            }

            selectEl.disabled = true;
            selectEl.innerHTML = `<option value="">Memuat...</option>`;

            return fetch(`/lwp/lokasi-helper?parent=${parentCode}&level=${targetLevel}`)
                .then(res => res.json())
                .then(data => {
                    let html = `<option value="">${defaultText}</option>`;
                    data.forEach(item => {
                        html += `<option value="${item.kode_lokasi}">${item.nama_lokasi}</option>`;
                    });
                    selectEl.innerHTML = html;
                    selectEl.disabled = false;
                })
                .catch(err => {
                    console.error(err);
                    selectEl.innerHTML = `<option value="">Gagal memuat</option>`;
                });
        }

        provSelect.addEventListener('change', function() {
            kabSelect.innerHTML = '<option value="">Pilih Kabupaten / Kota</option>';
            kabSelect.disabled = true;
            kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            kecSelect.disabled = true;
            kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            kelSelect.disabled = true;

            fetchChildren(this.value, 'kab', kabSelect, 'Pilih Kabupaten / Kota');
        });

        kabSelect.addEventListener('change', function() {
            kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            kecSelect.disabled = true;
            kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            kelSelect.disabled = true;

            fetchChildren(this.value, 'kec', kecSelect, 'Pilih Kecamatan');
        });

        kecSelect.addEventListener('change', function() {
            kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            kelSelect.disabled = true;

            fetchChildren(this.value, 'kel', kelSelect, 'Pilih Kelurahan');
        });

        // Trigger cascade on page load if old value exists
        @if(old('prov'))
            setTimeout(() => {
                provSelect.dispatchEvent(new Event('change'));
            }, 100);
        @endif
    });
    </script>
</body>
</html>
