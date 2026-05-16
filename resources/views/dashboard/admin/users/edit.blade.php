<x-dashboard-layout>
    @section('title', 'Edit Pengguna — ' . $user->name)
    @section('page-title', 'Edit Data Pengguna')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
            <div class="p-8 border-b border-surface-50">
                <h3 class="text-xl font-bold text-surface-900">Perbarui Data Pengguna</h3>
                <p class="text-sm text-surface-500 mt-1">Gunakan formulir ini untuk mengubah detail akun atau mengatur ulang kata sandi.</p>
            </div>
            
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PATCH')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-surface-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-surface-700 mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-surface-700 mb-2">Peran (Role)</label>
                        <select name="role" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                            <option value="ADMIN" {{ old('role', $user->role) === 'ADMIN' ? 'selected' : '' }}>Administrator</option>
                            <option value="PELAKSANA" {{ old('role', $user->role) === 'PELAKSANA' ? 'selected' : '' }}>Pelaksana Nazhir</option>
                            <option value="PENILAI" {{ old('role', $user->role) === 'PENILAI' ? 'selected' : '' }}>Tim Penilai</option>
                            <option value="WAKIF" {{ old('role', $user->role) === 'WAKIF' ? 'selected' : '' }}>Wakif</option>
                        </select>
                        @error('role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-surface-700 mb-2">Nomor HP / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="08xxxxxxxxxx">
                        @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-surface-700 mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Masukkan alamat lengkap sesuai KTP">{{ old('address', $user->address) }}</textarea>
                        @error('address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-surface-500 uppercase mb-2">Provinsi</label>
                            <input type="text" name="province" value="{{ old('province', $user->province) }}" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Provinsi">
                            @error('province') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-surface-500 uppercase mb-2">Kota/Kabupaten</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Kota/Kab">
                            @error('city') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-surface-500 uppercase mb-2">Kecamatan</label>
                            <input type="text" name="district" value="{{ old('district', $user->district) }}" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Kecamatan">
                            @error('district') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="flex items-center gap-3 p-4 bg-surface-50 rounded-xl border border-surface-100 cursor-pointer hover:bg-surface-100 transition-colors">
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-surface-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-nu-green-600"></div>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-surface-900">Status Akun Aktif</div>
                                <div class="text-[10px] text-surface-500 uppercase tracking-wider">Berikan akses login untuk pengguna ini</div>
                            </div>
                        </label>
                    </div>

                    <div class="md:col-span-2 p-4 bg-surface-50 rounded-xl border border-surface-100">
                        <div class="text-xs font-bold text-surface-500 uppercase mb-4">Ubah Kata Sandi (Opsional)</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-surface-700 mb-2">Sandi Baru</label>
                                <input type="password" name="password" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="••••••••">
                                @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-surface-700 mb-2">Konfirmasi Sandi Baru</label>
                                <input type="password" name="password_confirmation" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="••••••••">
                            </div>
                        </div>
                        <p class="text-[10px] text-surface-400 mt-3 italic">Biarkan kosong jika tidak ingin mengubah kata sandi.</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-surface-50 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 text-sm font-bold text-surface-600 hover:bg-surface-50 rounded-xl transition-all">Batal</a>
                    <button type="submit" class="px-10 py-2.5 bg-surface-900 text-white font-bold rounded-xl hover:bg-black transition-all">
                        Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
