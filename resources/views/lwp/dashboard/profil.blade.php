@extends('layouts.lwp')

@section('title', 'Profil Saya')
@section('page_title', 'Detail Profil')
@section('page_subtitle', 'Informasi keanggotaan dan perbaruan profil Pengurus Lembaga Wakaf dan Pertanahan.')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Current Profile Card -->
        <div class="lg:col-span-5 bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-10">
                <!-- User Header -->
                <div class="flex flex-col items-center text-center mb-8 pb-8 border-b border-slate-50">
                    <div id="currentAvatar" class="w-24 h-24 rounded-3xl bg-nu-green-100 flex items-center justify-center text-nu-green-600 font-black text-3xl uppercase shrink-0 shadow-inner mb-4">
                        {{ substr($user->NAMA_USER, 0, 1) }}
                    </div>
                    <h3 id="currentNama" class="text-xl font-black text-slate-900 truncate w-full">{{ $user->NAMA_USER }}</h3>
                    <div class="mt-2 flex items-center justify-center gap-2">
                        <span class="px-3 py-1 bg-nu-gold-50 text-nu-gold-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-nu-gold-100">
                            {{ $detail->NAMA_LEVEL ?? 'Pengurus' }}
                        </span>
                        <span class="px-3 py-1 bg-nu-green-50 text-nu-green-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-nu-green-100">
                            Aktif
                        </span>
                    </div>
                </div>

                <!-- Compact Info Grid -->
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 text-left">Username</label>
                            <div class="text-xs font-bold text-slate-900 bg-slate-50 px-4 py-2.5 rounded-xl text-left">{{ $user->USERNAME_USER }}</div>
                        </div>
                        <div>
                            <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 text-left">Kepengurusan</label>
                            <div class="text-xs font-bold text-nu-green-700 bg-nu-green-50 px-4 py-2.5 rounded-xl text-left truncate">{{ $detail->NAMA_CABANG ?? '-' }}</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 text-left">Wilayah Tugas</label>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5 text-left">Provinsi</div>
                                <div class="text-[10px] font-black text-slate-900 text-left truncate">{{ $detail->PROV ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5 text-left">Kab/Kota</div>
                                <div class="text-[10px] font-black text-slate-900 text-left truncate">{{ $detail->KAB ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5 text-left">Kecamatan</div>
                                <div class="text-[10px] font-black text-slate-900 text-left truncate">{{ $detail->KEC ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5 text-left">Kelurahan</div>
                                <div class="text-[10px] font-black text-slate-900 text-left truncate">{{ $detail->KEL ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-50 space-y-3">
                        <div>
                            <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5 text-left">Kontak & Email</label>
                            <div id="currentTelp" class="text-xs font-bold text-slate-600 text-left">{{ $user->TELP_USER ?: '-' }}</div>
                            <div class="text-xs font-bold text-nu-green-600 text-left mt-0.5">{{ $user->EMAIL_USER ?: '-' }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5 text-left">Nomor Kartanu</label>
                                <div id="currentKartanu" class="text-xs font-bold text-slate-600 text-left">{{ $user->NO_KARTANU ?: '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5 text-left">ID Pengurus</label>
                                <div class="text-xs font-bold text-slate-600 text-left">#{{ str_pad($user->ID_USER, 5, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Update Profile Form -->
        <div class="lg:col-span-7 bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <form id="profileForm" action="{{ route('lwp.profil.update') }}" method="POST" class="p-10 space-y-8">
                @csrf
                
                <div>
                    <h3 class="text-lg font-black text-slate-900 text-left">Perbarui Profil Saya</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5 tracking-wider text-left">Kelola informasi pribadi dan kata sandi Anda</p>
                </div>

                <!-- Messages container -->
                <div id="alertContainer" class="hidden"></div>

                <!-- Progress Bar Container -->
                <div id="progressBarContainer" class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden hidden">
                    <div id="progressBar" class="bg-nu-green-600 h-full w-0 transition-all duration-300"></div>
                </div>

                <!-- Section 1: Data Diri -->
                <div class="space-y-6">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest pb-2 border-b border-slate-50 text-left">Data Diri</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 text-left">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_user" required value="{{ old('nama_user', $user->NAMA_USER) }}" placeholder="Nama Lengkap Anda" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 text-left">Nomor Telepon / WA</label>
                            <input type="text" name="telp_user" value="{{ old('telp_user', $user->TELP_USER) }}" placeholder="081xxx" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 text-left">Nomor Kartanu</label>
                        <input type="text" name="no_kartanu" value="{{ old('no_kartanu', $user->NO_KARTANU) }}" placeholder="KartaNU Anda" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                    </div>
                </div>

                <!-- Section 2: Ganti Password -->
                <div class="space-y-6 pt-4">
                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest pb-1 text-left">Ubah Kata Sandi</div>
                        <p class="text-[9px] text-slate-400 font-medium text-left mt-0.5">Biarkan kosong jika Anda tidak ingin mengubah kata sandi.</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 text-left">Password Lama</label>
                            <input type="password" name="old_password" placeholder="Masukkan password saat ini" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 text-left">Password Baru</label>
                                <input type="password" name="new_password" placeholder="Min. 6 karakter" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 text-left">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" placeholder="Ulangi password baru" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Submit -->
                <div class="pt-6 border-t border-slate-50 flex items-center justify-end">
                    <button type="submit" id="submitBtn" class="px-8 py-4 bg-nu-green-600 hover:bg-nu-green-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-lg shadow-nu-green-600/10">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = document.getElementById('submitBtn');
    const alertContainer = document.getElementById('alertContainer');
    const progressContainer = document.getElementById('progressBarContainer');
    const progressBar = document.getElementById('progressBar');
    
    // Reset alerts
    alertContainer.innerHTML = '';
    alertContainer.className = 'hidden';
    
    // Show progress bar
    progressContainer.classList.remove('hidden');
    progressBar.style.width = '0%';
    
    // Disable button
    submitBtn.disabled = true;
    const originalText = submitBtn.innerText;
    submitBtn.innerText = 'Menyimpan...';
    
    // Simulate progress bar increments
    let progress = 0;
    const progressInterval = setInterval(() => {
        if (progress < 90) {
            progress += Math.floor(Math.random() * 15) + 5;
            if (progress > 90) progress = 90;
            progressBar.style.width = `${progress}%`;
        }
    }, 100);
    
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => {
        clearInterval(progressInterval);
        progressBar.style.width = '100%';
        return res.json().then(data => ({ status: res.status, data }));
    })
    .then(({ status, data }) => {
        setTimeout(() => {
            progressContainer.classList.add('hidden');
            progressBar.style.width = '0%';
            submitBtn.disabled = false;
            submitBtn.innerText = originalText;
            
            if (status === 200 && data.status === 'success') {
                // Show success alert
                alertContainer.className = 'px-6 py-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-xs font-semibold text-left block mb-6';
                alertContainer.innerText = data.message;
                
                // Update left column details dynamically
                document.getElementById('currentNama').innerText = data.user.nama_user;
                document.getElementById('currentAvatar').innerText = data.user.nama_user.substring(0, 1).toUpperCase();
                document.getElementById('currentTelp').innerText = data.user.telp_user;
                document.getElementById('currentKartanu').innerText = data.user.no_kartanu;
                
                // Clear password inputs
                form.querySelector('input[name="old_password"]').value = '';
                form.querySelector('input[name="new_password"]').value = '';
                form.querySelector('input[name="new_password_confirmation"]').value = '';
            } else {
                // Validation errors or other failures
                alertContainer.className = 'px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl text-xs font-semibold text-left block mb-6';
                if (data.errors) {
                    let errList = '<div class="space-y-1">';
                    for (let key in data.errors) {
                        errList += `<div>${data.errors[key][0]}</div>`;
                    }
                    errList += '</div>';
                    alertContainer.innerHTML = errList;
                } else {
                    alertContainer.innerText = data.message || 'Terjadi kesalahan saat memperbarui profil.';
                }
            }
        }, 300);
    })
    .catch(err => {
        clearInterval(progressInterval);
        setTimeout(() => {
            progressContainer.classList.add('hidden');
            progressBar.style.width = '0%';
            submitBtn.disabled = false;
            submitBtn.innerText = originalText;
            
            alertContainer.className = 'px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl text-xs font-semibold text-left block mb-6';
            alertContainer.innerText = 'Terjadi kesalahan sistem.';
        }, 300);
    });
});
</script>
@endsection
