<x-dashboard-layout>
    @section('title', 'Pengajuan Pelaksana Nazhir')
    @section('page-title', 'Pengajuan Pelaksana Nazhir')

    @section('sidebar-nav')
        @include('dashboard.wakif.partials.nav')
    @endsection

    <div x-data="{ 
        docModal: false,
        docUrl: '',
        docType: '',
        previewDoc(url) {
            this.docUrl = url;
            this.docType = url.toLowerCase().endsWith('.pdf') ? 'pdf' : 'image';
            this.docModal = true;
        }
    }" class="max-w-4xl">
        <!-- Application Status Header -->
        @if($application)
            <div class="mb-8 p-6 rounded-3xl border {{ $application->status === 'APPROVED' ? 'bg-nu-green-50 border-nu-green-200' : ($application->status === 'REJECTED' ? 'bg-red-50 border-red-200' : 'bg-nu-gold-50 border-nu-gold-200') }}">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $application->status === 'APPROVED' ? 'bg-nu-green-600 text-white' : ($application->status === 'REJECTED' ? 'bg-red-600 text-white' : 'bg-nu-gold-500 text-white') }}">
                        @if($application->status === 'APPROVED')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        @elseif($application->status === 'REJECTED')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @endif
                    </div>
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-widest {{ $application->status === 'APPROVED' ? 'text-nu-green-600' : ($application->status === 'REJECTED' ? 'text-red-600' : 'text-nu-gold-600') }}">
                            Status Pengajuan: {{ $application->status }}
                            <span class="ml-2 px-2 py-0.5 bg-white/50 rounded-full text-[9px] font-black">
                                {{ $application->status === 'PENDING' ? 'Dikirim: ' : ($application->status === 'APPROVED' ? 'Disetujui: ' : 'Ditolak: ') }}
                                {{ $application->updated_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        <div class="text-lg font-black text-surface-900 mt-1">
                            @if($application->status === 'APPROVED')
                                Pengajuan Anda telah disetujui! Akun Anda kini menjadi Pelaksana Nazhir.
                            @elseif($application->status === 'REJECTED')
                                Pengajuan Anda ditolak. Silakan perbaiki data dan ajukan kembali.
                            @else
                                Pengajuan Anda sedang dalam proses peninjauan oleh Admin.
                            @endif
                        </div>
                        @if($application->status === 'REJECTED' && $application->rejection_reason)
                            <div class="mt-4 p-4 bg-white/50 rounded-2xl border border-red-100">
                                <div class="text-[10px] font-bold text-red-600 uppercase tracking-widest mb-1">Alasan Penolakan:</div>
                                <div class="text-sm text-surface-700 italic">"{{ $application->rejection_reason }}"</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if(!$application || $application->status === 'REJECTED')
            <div class="bg-white rounded-3xl shadow-card border border-surface-100 overflow-hidden">
                <div class="p-8 border-b border-surface-50 bg-surface-50/50">
                    <h3 class="text-xl font-bold text-surface-900">Formulir Pengajuan Pelaksana Nazhir</h3>
                    <p class="text-sm text-surface-500 mt-1">Lengkapi data lembaga Anda untuk menjadi Pelaksana Nazhir resmi.</p>
                </div>
                <form action="{{ route('wakif.nazhir-application.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    @csrf
                    
                    <!-- Section 1: Identitas -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 pb-2 border-b border-surface-100">
                            <div class="w-8 h-8 rounded-lg bg-nu-green-50 flex items-center justify-center text-nu-green-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            </div>
                            <h4 class="text-xs font-black uppercase tracking-widest text-surface-900">Identitas Pelaksana Nazhir</h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Nama Lembaga <span class="text-red-500">*</span></label>
                                <input type="text" name="institution_name" value="{{ old('institution_name', $application?->institution_name) }}" required class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600" placeholder="Masukkan nama lembaga resmi">
                                @error('institution_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone', $application?->phone) }}" required class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600" placeholder="Contoh: 08123456789">
                                @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Profil Singkat Lembaga <span class="text-red-500">*</span></label>
                            <textarea name="institution_short_profile" rows="3" required class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600" placeholder="Jelaskan secara singkat visi, misi, atau sejarah lembaga...">{{ old('institution_short_profile', $application?->institution_short_profile) }}</textarea>
                            @error('institution_short_profile') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Alamat Lengkap Lembaga <span class="text-red-500">*</span></label>
                            <textarea name="address" rows="3" required class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600" placeholder="Masukkan alamat lengkap lembaga...">{{ old('address', $application?->address) }}</textarea>
                            @error('address') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Section 2: Administrasi Rekening -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 pb-2 border-b border-surface-100">
                            <div class="w-8 h-8 rounded-lg bg-nu-green-50 flex items-center justify-center text-nu-green-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            </div>
                            <h4 class="text-xs font-black uppercase tracking-widest text-surface-900">Administrasi Rekening</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Nama Bank <span class="text-red-500">*</span></label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $application?->bank_name) }}" required class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600" placeholder="Contoh: BSI, Bank Mandiri, dll">
                                @error('bank_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Atas Nama Rekening <span class="text-red-500">*</span></label>
                                <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $application?->bank_account_name) }}" required class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600" placeholder="Nama sesuai buku tabungan">
                                @error('bank_account_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Nomor Rekening Lembaga <span class="text-red-500">*</span></label>
                            <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $application?->bank_account_number) }}" required class="w-full rounded-xl border-surface-200 text-sm focus:ring-nu-green-600 focus:border-nu-green-600" placeholder="Nomor rekening lembaga">
                            @error('bank_account_number') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Section 3: Dokumen Pendukung -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 pb-2 border-b border-surface-100">
                            <div class="w-8 h-8 rounded-lg bg-nu-green-50 flex items-center justify-center text-nu-green-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <h4 class="text-xs font-black uppercase tracking-widest text-surface-900">Dokumen Pendukung</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Profil Lembaga / SK Pengurus <span class="text-red-500">*</span></label>
                                <input type="file" name="institution_profile" required accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-surface-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-surface-900 file:text-white hover:file:bg-black cursor-pointer bg-surface-50 rounded-xl border border-surface-200 p-2">
                                <p class="text-[10px] text-surface-400 mt-2 italic">Format: PDF, JPG, PNG (Maks. 10MB)</p>
                                @error('institution_profile') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2">Sertifikat LSP-BWI <span class="text-red-500">*</span></label>
                                <input type="file" name="lsp_certificate" required accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-surface-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-surface-900 file:text-white hover:file:bg-black cursor-pointer bg-surface-50 rounded-xl border border-surface-200 p-2">
                                <p class="text-[10px] text-surface-400 mt-2 italic">Format: PDF, JPG, PNG (Maks. 10MB)</p>
                                @error('lsp_certificate') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-4 bg-nu-green-600 text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-600/20">
                            Kirim Pengajuan Sekarang
                        </button>
                    </div>
                </form>
            </div>
        @else
            <!-- Display submitted data if PENDING or APPROVED -->
            <div class="bg-white rounded-3xl shadow-card border border-surface-100 overflow-hidden">
                <div class="p-8 border-b border-surface-50 bg-surface-50/50">
                    <h3 class="text-xl font-bold text-surface-900">Data Pengajuan Anda</h3>
                </div>
                <div class="p-8 space-y-8">
                    <!-- Identity Info -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-nu-green-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            <span class="text-xs font-black uppercase tracking-widest">Identitas Pelaksana Nazhir</span>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Nama Lembaga</div>
                                <div class="text-sm font-bold text-surface-900">{{ $application->institution_name }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Nomor HP</div>
                                <div class="text-sm text-surface-900">{{ $application->phone }}</div>
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Profil Singkat</div>
                            <div class="text-sm text-surface-600 leading-relaxed">{{ $application->institution_short_profile }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Alamat</div>
                            <div class="text-sm text-surface-900">{{ $application->address }}</div>
                        </div>
                    </div>

                    <!-- Bank Info -->
                    <div class="space-y-4 pt-4 border-t border-surface-100">
                        <div class="flex items-center gap-2 text-nu-green-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            <span class="text-xs font-black uppercase tracking-widest">Administrasi Rekening</span>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Nama Bank</div>
                                <div class="text-sm text-surface-900">{{ $application->bank_name }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Atas Nama Rekening</div>
                                <div class="text-sm text-surface-900">{{ $application->bank_account_name }}</div>
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-1">Nomor Rekening</div>
                            <div class="text-sm text-surface-900 font-black">{{ $application->bank_account_number }}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <button @click="previewDoc('{{ Storage::url($application->institution_profile_path) }}')" class="w-full text-left p-4 bg-surface-50 rounded-2xl border border-surface-100 flex items-center gap-3 hover:bg-surface-100 transition-colors group">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-nu-green-600 border border-surface-100">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Profil Lembaga / SK</div>
                                <div class="text-[10px] font-black text-nu-green-600 uppercase tracking-widest mt-1 group-hover:underline">Lihat Dokumen →</div>
                            </div>
                        </button>
                        <button @click="previewDoc('{{ Storage::url($application->lsp_certificate_path) }}')" class="w-full text-left p-4 bg-surface-50 rounded-2xl border border-surface-100 flex items-center gap-3 hover:bg-surface-100 transition-colors group">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-nu-green-600 border border-surface-100">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Sertifikat LSP-BWI</div>
                                <div class="text-[10px] font-black text-nu-green-600 uppercase tracking-widest mt-1 group-hover:underline">Lihat Dokumen →</div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal: Preview Dokumen -->
        <div x-show="docModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 lg:p-12" x-cloak>
            <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm" @click="docModal = false"></div>
            <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-5xl h-full overflow-hidden animate-zoom-in flex flex-col">
                <div class="px-8 py-4 border-b border-surface-50 bg-surface-50 flex items-center justify-between shrink-0">
                    <h3 class="text-lg font-bold text-surface-900">Preview Dokumen</h3>
                    <div class="flex items-center gap-2">
                        <a :href="docUrl" target="_blank" class="px-4 py-2 bg-nu-green-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-nu-green-700 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            Buka Tab Baru
                        </a>
                        <button @click="docModal = false" class="p-2 hover:bg-surface-200 rounded-full transition-colors">
                            <svg class="w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
                <div class="flex-1 bg-surface-100 overflow-hidden relative">
                    <template x-if="docType === 'pdf'">
                        <embed :src="docUrl" type="application/pdf" class="w-full h-full">
                    </template>
                    <template x-if="docType === 'image'">
                        <div class="w-full h-full flex items-center justify-center p-8 overflow-auto">
                            <img :src="docUrl" class="max-w-full max-h-full shadow-2xl rounded-lg">
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
