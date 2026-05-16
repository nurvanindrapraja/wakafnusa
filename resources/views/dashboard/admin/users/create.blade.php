<x-dashboard-layout>
    @section('title', 'Tambah Pengguna')
    @section('page-title', 'Tambah Pengguna Baru')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
            <div class="p-8 border-b border-surface-50">
                <h3 class="text-xl font-bold text-surface-900">Formulir Pengguna Baru</h3>
                <p class="text-sm text-surface-500 mt-1">Pastikan email yang digunakan belum terdaftar di sistem.</p>
            </div>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-surface-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Masukkan nama lengkap">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-surface-700 mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="email@contoh.com">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-surface-700 mb-2">Peran (Role)</label>
                        <select name="role" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                            <option value="">Pilih Peran</option>
                            <option value="ADMIN" {{ old('role') === 'ADMIN' ? 'selected' : '' }}>Administrator</option>
                            <option value="PELAKSANA" {{ old('role') === 'PELAKSANA' ? 'selected' : '' }}>Pelaksana Nazhir</option>
                            <option value="PENILAI" {{ old('role') === 'PENILAI' ? 'selected' : '' }}>Tim Penilai</option>
                            <option value="WAKIF" {{ old('role') === 'WAKIF' ? 'selected' : '' }}>Wakif</option>
                        </select>
                        @error('role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-surface-700 mb-2">Nomor HP / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="08xxxxxxxxxx">
                        @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-surface-700 mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Masukkan alamat lengkap sesuai KTP">{{ old('address') }}</textarea>
                        @error('address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-surface-500 uppercase mb-2">Provinsi</label>
                            <input type="text" name="province" value="{{ old('province') }}" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Provinsi">
                            @error('province') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-surface-500 uppercase mb-2">Kota/Kabupaten</label>
                            <input type="text" name="city" value="{{ old('city') }}" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Kota/Kab">
                            @error('city') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-surface-500 uppercase mb-2">Kecamatan</label>
                            <input type="text" name="district" value="{{ old('district') }}" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Kecamatan">
                            @error('district') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-surface-700 mb-2">Kata Sandi</label>
                        <input type="password" name="password" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="••••••••">
                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-surface-700 mb-2">Konfirmasi Sandi</label>
                        <input type="password" name="password_confirmation" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-6 border-t border-surface-50 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 text-sm font-bold text-surface-600 hover:bg-surface-50 rounded-xl transition-all">Batal</a>
                    <button type="submit" class="px-10 py-2.5 bg-nu-green-600 text-white font-bold rounded-xl hover:bg-nu-green-700 shadow-lg shadow-nu-green-600/20 transition-all">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
