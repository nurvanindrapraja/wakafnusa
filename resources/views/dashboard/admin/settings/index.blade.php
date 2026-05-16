<x-dashboard-layout>
    @section('title', 'Pengaturan Sertifikat')
    @section('page-title', 'Konfigurasi e-AIW & Sertifikat')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div class="max-w-4xl">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="bg-white rounded-[32px] shadow-card border border-surface-100 overflow-hidden">
                <div class="p-8 border-b border-surface-100 bg-surface-50/50">
                    <h3 class="text-lg font-black text-surface-900">Pejabat Penandatangan</h3>
                    <p class="text-xs text-surface-500 mt-1 uppercase font-bold tracking-widest">Data ini akan muncul pada dokumen Akta Ikrar Wakaf (e-AIW)</p>
                </div>

                <div class="p-8 space-y-8">
                    <!-- Chairman Name -->
                    <div>
                        <label class="block text-xs font-black text-surface-900 uppercase tracking-widest mb-2">Nama Ketua LWP PBNU</label>
                        <input type="text" name="chairman_name" value="{{ old('chairman_name', $settings['chairman_name'] ?? '') }}" 
                            class="w-full rounded-2xl border-surface-200 focus:ring-nu-green-600 focus:border-nu-green-600 text-sm py-3 px-4 shadow-sm"
                            placeholder="Contoh: Drs. H. Ahmad Muzakki, M.Ag">
                        @error('chairman_name') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Chairman Title -->
                    <div>
                        <label class="block text-xs font-black text-surface-900 uppercase tracking-widest mb-2">Jabatan</label>
                        <input type="text" name="chairman_title" value="{{ old('chairman_title', $settings['chairman_title'] ?? 'Ketua LWP PBNU') }}" 
                            class="w-full rounded-2xl border-surface-200 focus:ring-nu-green-600 focus:border-nu-green-600 text-sm py-3 px-4 shadow-sm">
                        @error('chairman_title') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Chairman Signature -->
                    <div>
                        <label class="block text-xs font-black text-surface-900 uppercase tracking-widest mb-2">Gambar Tanda Tangan (PNG Transparan)</label>
                        <div class="flex items-start gap-6">
                            <div class="flex-1">
                                <div class="relative group">
                                    <input type="file" name="chairman_signature" id="signature_input" class="hidden" accept="image/*" onchange="previewImage(this)">
                                    <label for="signature_input" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-surface-200 rounded-3xl cursor-pointer bg-surface-50 hover:bg-surface-100 hover:border-nu-green-300 transition-all overflow-hidden group">
                                        <div id="upload_placeholder" class="{{ isset($settings['chairman_signature']) ? 'hidden' : '' }} flex flex-col items-center">
                                            <svg class="w-10 h-10 text-surface-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            <p class="text-[11px] font-black text-surface-400 uppercase tracking-widest">Klik untuk unggah</p>
                                        </div>
                                        <img id="signature_preview" src="{{ isset($settings['chairman_signature']) ? Storage::url($settings['chairman_signature']) : '#' }}" 
                                            class="{{ isset($settings['chairman_signature']) ? '' : 'hidden' }} max-h-full max-w-full object-contain p-4 group-hover:scale-105 transition-transform">
                                    </label>
                                </div>
                                <p class="text-[10px] text-surface-400 mt-3 italic font-medium">* Gunakan file PNG dengan latar belakang transparan untuk hasil terbaik (Maks 1MB)</p>
                            </div>
                        </div>
                        @error('chairman_signature') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="px-8 py-6 bg-surface-50 border-t border-surface-100 flex justify-end">
                    <button type="submit" class="bg-nu-green-600 text-white px-8 py-3 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-nu-green-700 shadow-lg shadow-nu-green-600/20 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function previewImage(input) {
            const preview = document.getElementById('signature_preview');
            const placeholder = document.getElementById('upload_placeholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                    
                    // Add a subtle bounce effect to indicate change
                    preview.classList.add('scale-105');
                    setTimeout(() => preview.classList.remove('scale-105'), 200);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
</x-dashboard-layout>
