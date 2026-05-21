<x-public-layout>
    @section('title', 'Detail Aset')

    <!-- Leaflet Map CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        /* Ensure map controls don't overlap with sticky navbar */
        #map { z-index: 10 !important; }
        .leaflet-top, .leaflet-bottom { z-index: 9 !important; }
    </style>

    <div class="py-12 pt-24 bg-surface-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('landing') }}" class="text-sm font-medium text-surface-500 hover:text-nu-green-600 transition-colors">Beranda</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-surface-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <a href="{{ route('land-waqf.catalog') }}" class="ml-1 text-sm font-medium text-surface-500 hover:text-nu-green-600 md:ml-2 transition-colors">Katalog Wakaf Tanah</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-surface-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-sm font-bold text-nu-green-600 md:ml-2">Detail #{{ $item->ID_WAKAF }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white rounded-[2.5rem] shadow-card overflow-hidden border border-surface-100">
                <div class="p-8 md:p-12">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                        <div>
                            <h1 class="text-4xl md:text-5xl font-black text-surface-900 tracking-tight uppercase leading-none">{{ $item->PENGGUNAAN }}</h1>
                            <p class="text-surface-500 mt-3 font-medium">
                                ID Wakaf: <span class="text-surface-900 font-bold ml-1">{{ $item->ID_WAKAF }}</span>
                                @if($item->nia)
                                    <span class="mx-2 text-surface-300">|</span>
                                    NIA: <span class="text-surface-900 font-bold ml-1">{{ $item->nia }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <span class="px-6 py-3 rounded-2xl bg-nu-green-50 text-nu-green-700 text-xs font-black tracking-widest border border-nu-green-100 uppercase">
                                Terverifikasi
                            </span>
                        </div>
                    </div>

                    <!-- Top Grid: Location & Stakeholders -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        <!-- Location Box -->
                        <div class="p-8 rounded-[2rem] bg-surface-50 border border-surface-100">
                            <h3 class="text-[10px] font-black text-surface-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                                <svg class="w-4 h-4 text-nu-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Informasi Lokasi
                            </h3>
                            <div class="space-y-8">
                                <div>
                                    <div class="text-[9px] text-surface-400 font-black uppercase tracking-widest mb-1">Alamat Lengkap</div>
                                    <p class="text-lg text-surface-900 font-bold leading-relaxed">{{ $item->ALAMAT }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-y-8 gap-x-6">
                                    <div>
                                        <div class="text-[9px] text-surface-400 font-black uppercase tracking-widest mb-1">Kelurahan</div>
                                        <p class="text-sm text-surface-900 font-black">{{ $item->KEL }}</p>
                                    </div>
                                    <div>
                                        <div class="text-[9px] text-surface-400 font-black uppercase tracking-widest mb-1">Kecamatan</div>
                                        <p class="text-sm text-surface-900 font-black">{{ $item->KEC }}</p>
                                    </div>
                                    <div>
                                        <div class="text-[9px] text-surface-400 font-black uppercase tracking-widest mb-1">Kabupaten/Kota</div>
                                        <p class="text-sm text-surface-900 font-black">{{ $item->KAB }}</p>
                                    </div>
                                    <div>
                                        <div class="text-[9px] text-surface-400 font-black uppercase tracking-widest mb-1">Provinsi</div>
                                        <p class="text-sm text-surface-900 font-black">{{ $item->PROV }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stakeholders Box -->
                        <div class="p-8 rounded-[2rem] bg-surface-50 border border-surface-100 flex flex-col justify-center">
                            <h3 class="text-[10px] font-black text-surface-400 uppercase tracking-[0.2em] mb-8">Pihak Terkait</h3>
                            <div class="space-y-8">
                                <div>
                                    <div class="text-[9px] text-surface-400 font-black uppercase tracking-widest mb-1">Nama Wakif</div>
                                    <p class="text-xl text-surface-900 font-black leading-tight">{{ $item->WAKIF }}</p>
                                </div>
                                <div>
                                    <div class="text-[9px] text-surface-400 font-black uppercase tracking-widest mb-1">Nama Nazhir</div>
                                    <p class="text-xl text-surface-900 font-black leading-relaxed">{{ $item->NADZIR }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Middle Grid: Usage Detail (Left) & Legal (Right) -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        <!-- Technical Details Box (Left) -->
                        <div class="p-8 md:p-10 rounded-[2.5rem] bg-nu-green-700 text-white shadow-xl shadow-nu-green-100/30 flex flex-col justify-center">
                            <h3 class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em] mb-8">Detail Penggunaan</h3>
                            <div class="space-y-8">
                                <div>
                                    <div class="text-[9px] text-white/50 font-black uppercase tracking-widest mb-1">Jenis Penggunaan</div>
                                    <p class="text-3xl font-black uppercase tracking-tight">{{ $item->PENGGUNAAN }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-8">
                                    <div>
                                        <div class="text-[9px] text-white/50 font-black uppercase tracking-widest mb-1">Luas Tanah</div>
                                        <p class="text-3xl font-black">{{ number_format($item->LUAS, 0, ',', '.') }} m<sup>2</sup></p>
                                    </div>
                                    <div>
                                        <div class="text-[9px] text-white/50 font-black uppercase tracking-widest mb-1">Nilai Data</div>
                                        <div class="flex flex-col gap-2">
                                            <p class="text-3xl font-black leading-none">{{ number_format($item->NILAI, 0, ',', '.') }}</p>
                                            
                                            <!-- Star Rating -->
                                            @php
                                                $stars = 1;
                                                if ($item->NILAI > 11) $stars = 5;
                                                elseif ($item->NILAI > 8) $stars = 4;
                                                elseif ($item->NILAI > 5) $stars = 3;
                                                elseif ($item->NILAI > 3) $stars = 2;
                                            @endphp
                                            <div class="flex gap-0.5">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= $stars ? 'text-nu-gold-400' : 'text-white/20' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Legal Box (Right) -->
                        <div class="p-8 md:p-10 rounded-[2.5rem] bg-white border-2 border-surface-100 shadow-sm relative overflow-hidden flex flex-col justify-center">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-nu-gold-500/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                            <h3 class="text-[10px] font-black text-surface-400 uppercase tracking-[0.2em] mb-10 text-center">Legalitas Wakaf</h3>
                            <div class="space-y-10">
                                <div class="text-center pb-8 border-b border-surface-100">
                                    <div class="text-[9px] text-surface-400 font-black uppercase tracking-widest mb-2">Nomor AIW</div>
                                    <p class="text-xl md:text-2xl font-black text-nu-green-600 leading-tight break-all">{{ $item->NO_AIW ?: '-' }}</p>
                                    <div class="text-[10px] text-surface-500 font-bold mt-2">Tanggal: {{ $item->TGL_AIW ? $item->TGL_AIW->format('d M Y') : '-' }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-[9px] text-surface-400 font-black uppercase tracking-widest mb-2">Nomor Sertifikat</div>
                                    <p class="text-xl md:text-2xl font-black text-nu-gold-600 leading-tight break-all">{{ $item->NO_SERT ?: '-' }}</p>
                                    <div class="text-[10px] text-surface-500 font-bold mt-2">Tanggal: {{ $item->TGL_SERT ? $item->TGL_SERT->format('d M Y') : '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($item->KETERANGAN)
                    <div class="mb-10 p-8 rounded-[2rem] bg-amber-50 border border-amber-100">
                        <h3 class="text-[10px] font-black text-amber-600 uppercase tracking-[0.2em] mb-3">Keterangan Tambahan</h3>
                        <p class="text-sm text-surface-700 leading-relaxed font-medium">{{ $item->KETERANGAN }}</p>
                    </div>
                    @endif

                    <!-- Map Section at the bottom -->
                    @if($item->LAT && $item->LNG)
                    <div class="mb-10 rounded-[2.5rem] overflow-hidden border border-surface-100 shadow-sm relative">
                        <div id="map" class="w-full h-96 bg-surface-100"></div>
                    </div>
                    @endif

                    <div class="flex justify-center">
                        <a href="{{ route('land-waqf.catalog') }}" class="text-sm font-black text-surface-400 hover:text-nu-green-600 flex items-center gap-2 transition-all group">
                            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet Map Scripts -->
    @if($item->LAT && $item->LNG)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = {{ $item->LAT }};
            const lng = {{ $item->LNG }};
            
            const map = L.map('map').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup('{{ $item->PENGGUNAAN }}')
                .openPopup();
        });
    </script>
    @endif
</x-public-layout>
