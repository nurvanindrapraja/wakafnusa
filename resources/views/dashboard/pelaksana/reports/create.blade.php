<x-dashboard-layout>
    @section('title', 'Buat Laporan Realisasi')
    @section('page-title', 'Buat Laporan Realisasi')

    @section('sidebar-nav')
        @include('dashboard.pelaksana.partials.nav')
    @endsection

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
            <div class="p-8 border-b border-surface-100 bg-surface-50">
                <h3 class="text-xl font-bold text-surface-900 mb-1">Laporan Realisasi Program</h3>
                <p class="text-sm text-surface-500">{{ $program->title }}</p>
            </div>

            <form action="{{ route('pelaksana.reports.store', $program) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-600">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-surface-700 mb-1">Periode Laporan <span class="text-red-500">*</span></label>
                        <input type="month" name="report_period" value="{{ old('report_period') }}" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                        <p class="text-[10px] text-surface-400 mt-1">Pilih bulan dan tahun pelaksanaan kegiatan</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-surface-700 mb-1">Total Dana Terealisasi (Rp) <span class="text-red-500">*</span></label>
                        <div x-data="{ 
                            display: '', 
                            raw: '{{ old('realized_amount') }}',
                            init() { if(this.raw) this.display = number_format(this.raw) }
                        }">
                            <input type="text" x-model="display" 
                                @input="raw = display.replace(/\D/g, ''); display = raw ? number_format(raw) : ''" 
                                required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Contoh: 5.000.000">
                            <input type="hidden" name="realized_amount" :value="raw">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-surface-700 mb-1">Judul Laporan <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Contoh: Progress Pembangunan Fondasi Tahap 1">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-surface-700 mb-1">Deskripsi Kegiatan <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="5" required class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600" placeholder="Jelaskan secara naratif progress kegiatan, kendala, dan hasil yang dicapai...">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-surface-700 mb-1">Dokumentasi Foto</label>
                        <input type="file" name="evidence_photos[]" multiple accept="image/*" class="w-full text-xs text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-nu-green-50 file:text-nu-green-700 hover:file:bg-nu-green-100 cursor-pointer">
                        <p class="text-[10px] text-surface-400 mt-1">Maks 2MB per foto (JPG, PNG)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-surface-700 mb-1">Dokumen Pendukung <span class="text-[10px] text-surface-400 font-normal">(kuitansi / nota / dokumentasi pelaksanaan)</span></label>
                        <input type="file" name="evidence_docs[]" multiple accept=".pdf,.zip" class="w-full text-xs text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-nu-green-50 file:text-nu-green-700 hover:file:bg-nu-green-100 cursor-pointer">
                        <p class="text-[10px] text-surface-400 mt-1">Maks 5MB per file (PDF, ZIP)</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-surface-100 flex justify-end gap-3">
                    <a href="{{ route('pelaksana.dashboard') }}" class="px-6 py-2.5 text-sm font-bold text-surface-600 hover:bg-surface-50 rounded-xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-10 py-2.5 bg-nu-green-600 text-white font-bold rounded-xl hover:bg-nu-green-700 shadow-lg shadow-nu-green-600/20 transition-all">
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>

<script>
    function number_format(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
</script>
