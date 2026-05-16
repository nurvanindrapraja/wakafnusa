<x-dashboard-layout>
    @section('title', 'Usulkan Program Baru')
    @section('page-title', 'Usulkan Program Baru')

    @section('sidebar-nav')
        @include('dashboard.pelaksana.partials.nav')
    @endsection

    <div class="max-w-4xl mx-auto" x-data="{ step: 1 }">
        <!-- Step Indicators -->
        <div class="flex items-center justify-between mb-8 relative">
            <div class="absolute top-1/2 left-0 w-full h-0.5 bg-surface-200 -translate-y-1/2 -z-10"></div>
            <div class="absolute top-1/2 left-0 h-0.5 bg-nu-green-600 -translate-y-1/2 -z-10 transition-all duration-300" :style="'width: ' + ((step - 1) * 33.33) + '%'"></div>

            <template x-for="i in 4">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-colors duration-300"
                         :class="step >= i ? 'bg-nu-green-600 text-white' : 'bg-white border-2 border-surface-200 text-surface-400'">
                        <span x-text="i"></span>
                    </div>
                    <span class="text-[10px] uppercase font-bold tracking-wider"
                          :class="step >= i ? 'text-nu-green-600' : 'text-surface-400'"
                          x-text="['Umum', 'Target', 'RAB', 'Dokumen'][i-1]"></span>
                </div>
            </template>
        </div>

        <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
            <form action="{{ route('pelaksana.programs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Step 1: Info Umum -->
                <div x-show="step === 1" x-transition class="p-8">
                    <h3 class="text-xl font-bold text-surface-900 mb-6">Informasi Umum</h3>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Judul Program <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Contoh: Pemberdayaan Ekonomi Santri">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select name="category" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                                <option value="">Pilih Kategori</option>
                                <option value="EKONOMI">Ekonomi</option>
                                <option value="PENDIDIKAN">Pendidikan</option>
                                <option value="KESEHATAN">Kesehatan</option>
                                <option value="SOSIAL">Sosial</option>
                                <option value="DAKWAH">Dakwah</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="6" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Jelaskan detail program, tujuan, dan manfaatnya..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Foto Sampul</label>
                            <input type="file" name="cover_image" accept="image/*" class="w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-nu-green-50 file:text-nu-green-700 hover:file:bg-nu-green-100 cursor-pointer">
                            <p class="text-[10px] text-surface-400 mt-1">Maksimal 2MB (JPG, PNG, WEBP)</p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Target & Lokasi -->
                <div x-show="step === 2" x-transition x-cloak class="p-8">
                    <h3 class="text-xl font-bold text-surface-900 mb-6">Target & Lokasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Target Dana (Rp) <span class="text-red-500">*</span></label>
                            <div x-data="{ display: '', raw: '' }">
                                <input type="text" x-model="display" 
                                    @input="raw = display.replace(/\D/g, ''); display = raw ? number_format(raw) : ''" 
                                    required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Contoh: 100.000.000">
                                <input type="hidden" name="target_amount" :value="raw">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Tanggal Berakhir <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                            <input type="text" name="location_province" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Contoh: Jawa Timur">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Kota/Kabupaten <span class="text-red-500">*</span></label>
                            <input type="text" name="location_city" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Contoh: Kudus">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Target Penerima Manfaat (Orang) <span class="text-red-500">*</span></label>
                            <input type="number" name="beneficiary_count" required min="1" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Contoh: 100">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Alamat Detail</label>
                            <textarea name="location_detail" rows="3" class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Contoh: Jl. Kyai Haji Wahid Hasyim No. 12..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 3: RAB -->
                <div x-show="step === 3" x-transition x-cloak class="p-8">
                    <h3 class="text-xl font-bold text-surface-900 mb-6">Rencana Anggaran Biaya (RAB)</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Total RAB Keseluruhan (Rp) <span class="text-red-500">*</span></label>
                            <div x-data="{ display: '', raw: '' }">
                                <input type="text" x-model="display" 
                                    @input="raw = display.replace(/\D/g, ''); display = raw ? number_format(raw) : ''" 
                                    required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Masukkan total anggaran">
                                <input type="hidden" name="rab_total" :value="raw">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Dokumen RAB Detail</label>
                            <input type="file" name="rab_document" accept=".pdf,.xlsx,.xls" class="w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-nu-green-50 file:text-nu-green-700 hover:file:bg-nu-green-100 cursor-pointer">
                            <p class="text-[10px] text-surface-400 mt-1">Format PDF, Excel (Maks 5MB)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 mb-1">Rencana Tahapan Pelaksanaan (PDF)</label>
                            <input type="file" name="implementation_plan_doc" accept="application/pdf" class="w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-nu-green-50 file:text-nu-green-700 hover:file:bg-nu-green-100 cursor-pointer">
                            <p class="text-[10px] text-surface-400 mt-1">Wajib format PDF (Maks 5MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Dokumen Pendukung -->
                <div x-show="step === 4" x-transition x-cloak class="p-8">
                    <h3 class="text-xl font-bold text-surface-900 mb-6">Dokumen Pendukung</h3>
                    <div class="space-y-5">
                        <div class="bg-surface-50 p-6 rounded-2xl border-2 border-dashed border-surface-200 text-center">
                            <svg class="w-12 h-12 text-surface-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <p class="text-sm text-surface-600 mb-4">Upload dokumen pendukung seperti Profil Lembaga, SK Pengurus, Sertifikat LSP-BWI, dll.</p>
                            <input type="file" name="supporting_docs[]" multiple class="text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-nu-green-50 file:text-nu-green-700 hover:file:bg-nu-green-100 cursor-pointer">
                        </div>
                        <div class="bg-nu-green-50 border border-nu-green-200 rounded-xl p-4">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" required class="mt-1 rounded text-nu-green-600 focus:ring-nu-green-500">
                                <span class="text-xs text-surface-700">Saya menyatakan bahwa data yang diisi adalah benar dan dapat dipertanggungjawabkan sesuai dengan amanah wakif dan regulasi yang berlaku di lingkungan Nahdlatul Ulama.</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Footer Navigation -->
                <div class="px-8 py-6 bg-surface-50 border-t border-surface-100 flex items-center justify-between">
                    <button type="button" x-show="step > 1" @click="step--" class="px-6 py-2.5 text-sm font-bold text-surface-600 hover:bg-surface-100 rounded-xl transition-colors">
                        Sebelumnya
                    </button>
                    <div class="flex-1"></div>
                    <button type="button" x-show="step < 4" @click="step++" class="px-8 py-2.5 bg-nu-green-600 text-white font-bold rounded-xl hover:bg-nu-green-700 transition-colors">
                        Lanjut
                    </button>
                    <button type="submit" x-show="step === 4" class="px-10 py-2.5 bg-nu-green-600 text-white font-bold rounded-xl hover:bg-nu-green-700 shadow-lg shadow-nu-green-600/20 transition-all">
                        Usulkan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function number_format(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }
    </script>
</x-dashboard-layout>
