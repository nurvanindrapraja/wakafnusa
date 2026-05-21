@extends('layouts.lwp')

@section('title', 'Peta Sebaran Wakaf')
@section('page_title', 'Peta Sebaran Wakaf')
@section('page_subtitle', 'Analisis spasial persebaran aset wakaf Nahdlatul Ulama secara berjenjang.')

@section('content')
<div class="space-y-6">
    <!-- Map Control Header & Breadcrumbs -->
    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3 overflow-x-auto py-1 w-full md:w-auto">
            <span class="text-slate-400 text-xs font-bold uppercase tracking-wider shrink-0">Wilayah:</span>
            <div id="breadcrumb-container" class="flex items-center gap-2 text-sm font-bold text-slate-700">
                <!-- Breadcrumbs rendered dynamically -->
                <button onclick="goToBreadcrumb('provinsi', '', 'Nasional')" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-nu-green-50 hover:text-nu-green-700 transition-all text-xs uppercase tracking-wider font-black">
                    Nasional
                </button>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <div class="text-right hidden sm:block">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Level Peta</div>
                <div id="current-level-badge" class="text-xs font-black text-nu-green-700 uppercase tracking-wider mt-0.5">Nasional (Provinsi)</div>
            </div>
            <div class="w-2 h-8 bg-nu-green-600 rounded-full"></div>
        </div>
    </div>

    <!-- Map Container -->
    <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100 relative overflow-hidden">
        <div id="sebaran-map" class="w-full rounded-[2rem] border border-slate-100 z-10" style="height: 600px;"></div>
        
        <!-- Map Statistics Overlay -->
        <div class="absolute bottom-10 left-10 z-20 bg-white/95 backdrop-blur-md px-6 py-5 rounded-[2rem] shadow-xl border border-slate-100/50 max-w-xs transition-all">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Ringkasan Wilayah</h4>
            <div class="space-y-3">
                <div>
                    <div class="text-[10px] text-slate-400 font-bold uppercase">Nama Wilayah</div>
                    <div id="summary-region-name" class="text-base font-black text-slate-800">Nasional</div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Total Aset</div>
                        <div id="summary-total-wakaf" class="text-lg font-black text-nu-green-700">-</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Total Luas</div>
                        <div id="summary-total-luas" class="text-lg font-black text-slate-800">- m²</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    /* Custom Leaflet Marker Styling */
    .custom-map-marker {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #006400;
        border: 3px solid #ffffff;
        border-radius: 50%;
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        font-weight: 900;
        font-size: 13px;
        box-shadow: 0 10px 15px -3px rgba(0, 100, 0, 0.3), 0 4px 6px -4px rgba(0, 100, 0, 0.3);
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .custom-map-marker:hover {
        transform: scale(1.15);
        background: #004d00;
        z-index: 1000 !important;
    }
    .custom-map-marker span {
        text-align: center;
        line-height: 1;
    }
</style>

<script>
    let map;
    let markersLayer;
    let currentLevel = 'provinsi';
    let currentParentCode = '';
    let breadcrumbs = [{ name: 'Nasional', code: '', level: 'provinsi' }];
    let isProgrammaticZoom = false;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Map
        map = L.map('sebaran-map').setView([-2.5, 118], 5);

        // Tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Markers layer
        markersLayer = L.layerGroup().addTo(map);

        // Load Initial Data
        loadMapData();

        // Bind zoom end to check if we should auto-adjust levels based on zoom
        map.on('zoomend', function() {
            if (isProgrammaticZoom) {
                isProgrammaticZoom = false;
                return;
            }
            const zoom = map.getZoom();
            let newLevel = 'provinsi';
            
            if (zoom >= 13) {
                newLevel = 'kelurahan';
            } else if (zoom >= 10) {
                newLevel = 'kecamatan';
            } else if (zoom >= 7) {
                newLevel = 'kabupaten';
            }

            // Only switch levels automatically if we are zooming *out* and the zoom level changes the category
            // When zooming *in*, we prefer users clicking the marker to get targeted parent code updates
            if (newLevel !== currentLevel) {
                // If zooming out, we need to adapt the parent code accordingly
                if (zoom < 7 && currentLevel !== 'provinsi') {
                    goToBreadcrumb('provinsi', '', 'Nasional');
                } else if (zoom < 10 && zoom >= 7 && currentLevel === 'kecamatan') {
                    // Zoom out to kabupaten, chop parent code to province
                    const provCode = currentParentCode.substring(0, 2);
                    const provName = breadcrumbs.find(b => b.code === provCode)?.name || 'Provinsi';
                    goToBreadcrumb('kabupaten', provCode, provName);
                } else if (zoom < 12 && zoom >= 10 && currentLevel === 'kelurahan') {
                    // Zoom out to kecamatan
                    const kabCode = currentParentCode.substring(0, 5);
                    const kabName = breadcrumbs.find(b => b.code === kabCode)?.name || 'Kabupaten';
                    goToBreadcrumb('kecamatan', kabCode, kabName);
                }
            }
        });
    });

    function loadMapData() {
        // Show loading state
        const summaryRegion = document.getElementById('summary-region-name');
        const summaryTotal = document.getElementById('summary-total-wakaf');
        const summaryLuas = document.getElementById('summary-total-luas');
        
        summaryTotal.textContent = 'Memuat...';
        summaryLuas.textContent = 'Memuat...';

        // Clear markers
        markersLayer.clearLayers();

        // Update Level Badge
        const badge = document.getElementById('current-level-badge');
        badge.textContent = `${currentLevel.toUpperCase()}`;

        // Fetch
        const url = `/lwp/peta-sebaran/data?level=${currentLevel}&parent_code=${currentParentCode}`;
        fetch(url)
            .then(res => res.json())
            .then(data => {
                let overallTotal = 0;
                let overallLuas = 0;

                if (data.length === 0) {
                    summaryTotal.textContent = '0';
                    summaryLuas.textContent = '0 m²';
                    return;
                }

                const bounds = [];

                data.forEach(item => {
                    overallTotal += item.total_wakaf;
                    overallLuas += item.total_luas;

                    // Create beautiful custom circle DivIcon
                    // Calculate size based on asset count (min 35px, max 60px)
                    const size = Math.min(60, Math.max(35, 30 + Math.log10(item.total_wakaf) * 12));
                    
                    const icon = L.divIcon({
                        className: 'custom-map-marker',
                        html: `<span>${item.total_wakaf}</span>`,
                        iconSize: [size, size],
                        iconAnchor: [size / 2, size / 2]
                    });

                    const marker = L.marker([item.lat, item.lng], { icon: icon });

                    // Popup Content
                    const popupContent = `
                        <div class="p-3 font-sans min-w-[200px]">
                            <div class="text-[9px] uppercase tracking-wider font-bold text-slate-400 mb-1">Nama Wilayah</div>
                            <h4 class="text-sm font-black text-slate-800 uppercase mb-3">${item.name}</h4>
                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div>
                                    <div class="text-[9px] uppercase tracking-wider font-bold text-slate-400">Total Aset</div>
                                    <div class="text-sm font-black text-nu-green-700">${item.total_wakaf}</div>
                                </div>
                                <div>
                                    <div class="text-[9px] uppercase tracking-wider font-bold text-slate-400">Total Luas</div>
                                    <div class="text-sm font-black text-slate-800">${formatNumber(item.total_luas)} m²</div>
                                </div>
                            </div>
                            ${currentLevel !== 'kelurahan' ? `
                                <button onclick="drillDown('${item.code}', '${item.name.replace(/'/g, "\\'")}')" class="w-full py-2 px-3 bg-nu-green-600 hover:bg-nu-green-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all text-center flex items-center justify-center gap-1">
                                    Buka Wilayah <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                </button>
                            ` : ''}
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                    markersLayer.addLayer(marker);
                    bounds.push([item.lat, item.lng]);
                });

                // Update ringkasan panel
                summaryTotal.textContent = overallTotal.toLocaleString('id-ID');
                summaryLuas.textContent = formatNumber(overallLuas) + ' m²';

                // Automatically center map on loaded markers if drilling down
                if (bounds.length > 0 && currentLevel !== 'provinsi') {
                    isProgrammaticZoom = true;
                    map.fitBounds(bounds, { padding: [50, 50], maxZoom: currentLevel === 'kelurahan' ? 14 : 12, animate: false });
                }
            })
            .catch(err => {
                console.error(err);
                summaryTotal.textContent = 'Error';
                summaryLuas.textContent = 'Error';
            });
    }

    function drillDown(code, name) {
        let nextLevel = 'provinsi';
        if (currentLevel === 'provinsi') {
            nextLevel = 'kabupaten';
        } else if (currentLevel === 'kabupaten') {
            nextLevel = 'kecamatan';
        } else if (currentLevel === 'kecamatan') {
            nextLevel = 'kelurahan';
        }

        // Add to breadcrumbs
        breadcrumbs.push({ name: name, code: code, level: nextLevel });
        renderBreadcrumbs();

        currentLevel = nextLevel;
        currentParentCode = code;

        // Update ringkasan name immediately
        document.getElementById('summary-region-name').textContent = name;

        loadMapData();
        map.closePopup();
    }

    function goToBreadcrumb(level, code, name) {
        // Find index of breadcrumb
        const index = breadcrumbs.findIndex(b => b.code === code && b.level === level);
        if (index !== -1) {
            breadcrumbs = breadcrumbs.slice(0, index + 1);
        } else {
            breadcrumbs = [{ name: 'Nasional', code: '', level: 'provinsi' }];
        }

        renderBreadcrumbs();

        currentLevel = level;
        currentParentCode = code;

        document.getElementById('summary-region-name').textContent = name;

        // Reset map view to national if going back to provinsi
        if (level === 'provinsi') {
            isProgrammaticZoom = true;
            map.setView([-2.5, 118], 5, { animate: false });
        }

        loadMapData();
    }

    function renderBreadcrumbs() {
        const container = document.getElementById('breadcrumb-container');
        container.innerHTML = '';

        breadcrumbs.forEach((crumb, idx) => {
            if (idx > 0) {
                container.innerHTML += `
                    <span class="text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                    </span>
                `;
            }
            container.innerHTML += `
                <button onclick="goToBreadcrumb('${crumb.level}', '${crumb.code}', '${crumb.name.replace(/'/g, "\\'")}')" class="px-3 py-1.5 rounded-xl hover:bg-nu-green-50 hover:text-nu-green-700 transition-all text-xs uppercase tracking-wider font-black ${idx === breadcrumbs.length - 1 ? 'bg-nu-green-50 text-nu-green-700' : 'bg-slate-50 text-slate-600'}">
                    ${crumb.name}
                </button>
            `;
        });
    }

    function formatNumber(num) {
        if (num >= 1000000) {
            return (num / 1000000).toFixed(1) + 'M';
        }
        return num.toLocaleString('id-ID');
    }
</script>
@endsection
