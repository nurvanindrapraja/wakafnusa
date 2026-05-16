<x-dashboard-layout>
    @section('title', 'Tambah Anggota Tim')
    @section('page-title', 'Tambah Anggota Tim')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div class="max-w-3xl">
        <div class="bg-white rounded-3xl shadow-card border border-surface-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-surface-50 bg-surface-50 flex items-center justify-between">
                <h3 class="font-bold text-surface-900">Form Anggota Baru</h3>
                <a href="{{ route('admin.team-members.index') }}" class="text-xs font-bold text-surface-400 hover:text-surface-600 transition-colors">&larr; Kembali</a>
            </div>
            
            <form action="{{ route('admin.team-members.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-surface-400 uppercase mb-2 tracking-widest">Nama Lengkap</label>
                        <input type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: Dr. H. Ahmad Fauzi"
                            class="w-full rounded-2xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                        @error('name') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-surface-400 uppercase mb-2 tracking-widest">Jabatan / Posisi</label>
                        <input type="text" name="position" required value="{{ old('position') }}" placeholder="Contoh: Ketua Tim"
                            class="w-full rounded-2xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                        @error('position') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-surface-400 uppercase mb-2 tracking-widest">Kategori Tim</label>
                        <select name="category" required class="w-full rounded-2xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                            <option value="">Pilih Kategori</option>
                            <option value="NAZHIR" {{ old('category') === 'NAZHIR' ? 'selected' : '' }}>Tim Nazhir NU</option>
                            <option value="PENILAI" {{ old('category') === 'PENILAI' ? 'selected' : '' }}>Tim Penilai</option>
                            <option value="PENGEMBANG" {{ old('category') === 'PENGEMBANG' ? 'selected' : '' }}>Tim Pengembang</option>
                        </select>
                        @error('category') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-surface-400 uppercase mb-2 tracking-widest">Foto (Opsional)</label>
                        <div x-data="{ photoName: null, photoPreview: null }" class="flex items-center gap-6 p-4 bg-surface-50 rounded-2xl border border-dashed border-surface-200">
                            <div class="w-20 h-20 rounded-2xl bg-white border border-surface-100 flex items-center justify-center overflow-hidden shrink-0">
                                <template x-if="!photoPreview">
                                    <svg class="w-10 h-10 text-surface-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </template>
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                </template>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="photo" class="hidden" x-ref="photo"
                                    @change="
                                        const file = $refs.photo.files[0];
                                        if (file) {
                                            photoName = file.name;
                                            const reader = new FileReader();
                                            reader.onload = (e) => { photoPreview = e.target.result; };
                                            reader.readAsDataURL(file);
                                        }
                                    ">
                                <button type="button" @click="$refs.photo.click()" class="px-4 py-2 bg-white text-surface-900 text-xs font-bold rounded-lg border border-surface-200 hover:bg-surface-50 transition-all shadow-sm">
                                    Pilih Foto Anggota
                                </button>
                                <p class="mt-2 text-[10px] text-surface-400">JPG, PNG, atau JPEG. Max 2MB.</p>
                            </div>
                        </div>
                        @error('photo') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-surface-400 uppercase mb-2 tracking-widest">Urutan Tampil (Bobot)</label>
                        <input type="number" name="order_weight" value="{{ old('order_weight', 0) }}"
                            class="w-full rounded-2xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600">
                        <p class="mt-1 text-[9px] text-surface-400 font-medium italic">* Angka lebih kecil akan tampil lebih dulu.</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-surface-50">
                    <button type="submit" class="w-full py-4 bg-nu-green-600 text-white font-bold rounded-2xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-100">
                        Simpan Anggota Tim
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
