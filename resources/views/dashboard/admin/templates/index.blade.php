<x-dashboard-layout>
    @section('title', 'Manajemen Template Laporan')
    @section('page-title', 'Manajemen Template Laporan')

    @section('sidebar-nav')
        @include('dashboard.admin.partials.nav')
    @endsection

    <div class="space-y-6">
        <!-- Upload Form -->
        <div class="bg-white p-8 rounded-2xl shadow-card border border-surface-100">
            <h3 class="text-lg font-black text-surface-900 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                Unggah Template Baru
            </h3>
            <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @csrf
                <div class="md:col-span-1">
                    <label class="block text-xs font-black text-surface-500 uppercase tracking-widest mb-2">Nama Template</label>
                    <input type="text" name="name" required placeholder="Contoh: Template RAB" 
                           class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-black text-surface-500 uppercase tracking-widest mb-2">Pilih File</label>
                    <input type="file" name="file" required 
                           class="w-full text-xs text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-nu-green-50 file:text-nu-green-700 hover:file:bg-nu-green-100 cursor-pointer">
                </div>
                <div class="md:col-span-1 flex items-end">
                    <button type="submit" class="w-full bg-nu-green-600 text-white px-6 py-2.5 rounded-xl text-sm font-black hover:bg-nu-green-700 transition-all shadow-lg shadow-nu-green-600/20">
                        Simpan Template
                    </button>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-black text-surface-500 uppercase tracking-widest mb-2">Keterangan (Opsional)</label>
                    <input type="text" name="description" placeholder="Contoh: Digunakan untuk pengusulan RAB program sosial" 
                           class="w-full rounded-xl border-surface-200 text-sm focus:border-nu-green-600 focus:ring-nu-green-600">
                </div>
            </form>
        </div>

        <!-- Template List -->
        <div class="bg-white rounded-2xl shadow-card border border-surface-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-50 text-surface-500 text-[10px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Nama Template</th>
                            <th class="px-8 py-4">Keterangan</th>
                            <th class="px-8 py-4">Tanggal Unggah</th>
                            <th class="px-8 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse($templates as $template)
                        <tr class="hover:bg-surface-50 transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-surface-100 flex items-center justify-center text-surface-400">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    </div>
                                    <span class="font-bold text-surface-900 text-sm">{{ $template->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-sm text-surface-500 italic">{{ $template->description ?? '-' }}</td>
                            <td class="px-8 py-4 text-sm text-surface-600">{{ $template->created_at->format('d M Y') }}</td>
                            <td class="px-8 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ Storage::url($template->file_path) }}" target="_blank" 
                                       class="p-2 text-surface-400 hover:text-nu-green-600 transition-colors" title="Lihat/Download">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    </a>
                                    <form action="{{ route('admin.templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Hapus template ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-surface-400 hover:text-red-600 transition-colors" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-surface-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <p class="text-surface-400 italic text-sm">Belum ada template yang diunggah.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard-layout>
