@extends('backend.layout.main')

@section('page_title', 'Detail Lokasi')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.locations.index') }}">Lokasi</a></li>
<li class="breadcrumb-item active">{{ $location->name }}</li>
@endsection

@section('page_actions')
@can('manage.locations')
<a href="{{ route('backend.locations.edit', $location) }}" class="btn btn-warning">
    <i class="fas fa-edit"></i> Edit
</a>
<button class="btn btn-{{ $location->is_active ? 'secondary' : 'success' }}" 
        onclick="toggleStatus({{ $location->id }})">
    <i class="fas fa-{{ $location->is_active ? 'times' : 'check' }}"></i> 
    {{ $location->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
</button>
@if($location->latitude && $location->longitude)
<a href="https://www.openstreetmap.org/?mlat={{ $location->latitude }}&mlon={{ $location->longitude }}&zoom=15" 
   target="_blank" class="btn btn-info">
    <i class="fas fa-external-link-alt"></i> OpenStreetMap
</a>
<a href="https://maps.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}" 
   target="_blank" class="btn btn-primary">
    <i class="fab fa-google"></i> Google Maps
</a>
@endif
@endcan
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Location Info -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Lokasi</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>Nama</strong></td>
                                    <td>: {{ $location->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tipe</strong></td>
                                    <td>: <span class="badge badge-primary">{{ $location->type_name }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td>: {{ $location->address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Koordinat</strong></td>
                                    <td>: 
                                        @if($location->latitude && $location->longitude)
                                            <code>{{ number_format($location->latitude, 8) }}, {{ number_format($location->longitude, 8) }}</code>
                                            <div class="mt-1">
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt"></i> Lat: {{ number_format($location->latitude, 8) }} | 
                                                    <i class="fas fa-globe"></i> Lng: {{ number_format($location->longitude, 8) }}
                                                </small>
                                            </div>
                                        @else
                                            <span class="text-muted">Tidak ada koordinat</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($location->area_size)
                                <tr>
                                    <td><strong>Luas Wilayah</strong></td>
                                    <td>: 
                                        <span class="badge badge-info">{{ number_format($location->area_size, 2) }} m²</span>
                                        @if($location->area_size >= 1000000)
                                            <small class="text-muted">({{ number_format($location->area_size / 1000000, 2) }} km²)</small>
                                        @elseif($location->area_size >= 1000)
                                            <small class="text-muted">({{ number_format($location->area_size / 10000, 2) }} Ha)</small>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Telepon</strong></td>
                                    <td>: {{ $location->phone ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>: {{ $location->email ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>Status</strong></td>
                                    <td>: 
                                        <span class="badge {{ $location->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $location->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Peta</strong></td>
                                    <td>: 
                                        <span class="badge {{ $location->show_on_map ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $location->show_on_map ? 'Tampil di Peta' : 'Tidak Tampil' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Icon</strong></td>
                                    <td>: 
                                        <i class="{{ $location->icon }}" style="color: {{ $location->color }}"></i> 
                                        <code>{{ $location->icon }}</code>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Warna</strong></td>
                                    <td>: 
                                        <span class="badge" style="background-color: {{ $location->color }}">{{ $location->color }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Urutan</strong></td>
                                    <td>: {{ $location->sort_order }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat</strong></td>
                                    <td>: {{ $location->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($location->description)
                    <div class="mt-3">
                        <h5>Deskripsi</h5>
                        <p>{{ $location->description }}</p>
                    </div>
                    @endif

                    @if($location->operating_hours)
                    <div class="mt-3">
                        <h5>Jam Operasional</h5>
                        <div class="row">
                            @foreach(['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'] as $day => $dayName)
                                @if(!empty($location->operating_hours[$day]))
                                    <div class="col-md-3 mb-2">
                                        <strong>{{ $dayName }}:</strong> {{ $location->operating_hours[$day] }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($location->latitude && $location->longitude)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map"></i> Peta Lokasi & Area Wilayah
                    </h3>
                    <div class="card-tools">
                        @if($location->area_coordinates)
                        <span class="badge badge-success">
                            <i class="fas fa-draw-polygon"></i> Area Tersedia
                        </span>
                        @endif
                        <span class="badge badge-info">
                            <i class="fas fa-map-marker-alt"></i> {{ number_format($location->latitude, 6) }}, {{ number_format($location->longitude, 6) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info alert-sm mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Fitur peta:</strong>
                                <ul class="mb-0 mt-1 small">
                                    <li>Klik fullscreen untuk tampilan penuh</li>
                                    <li>Toggle satellite/street view</li>
                                    <li>Zoom dengan scroll atau tombol +/-</li>
                                </ul>
                            </div>
                            @if($location->area_coordinates)
                            <div class="col-md-6">
                                <i class="fas fa-ruler-combined"></i> 
                                <strong>Area wilayah:</strong>
                                <ul class="mb-0 mt-1 small">
                                    <li>Luas: <strong>{{ number_format($location->area_size ?? 0, 2) }} m²</strong></li>
                                    <li>Polygon batas wilayah ditampilkan</li>
                                    <li>Klik area untuk info detail</li>
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div id="map" style="height: 500px; width: 100%; border: 1px solid #ddd; border-radius: 5px; position: relative;">
                        <div class="d-flex align-items-center justify-content-center" style="height: 100%;">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                                <p class="text-muted mt-2">Memuat peta...</p>
                            </div>
                        </div>
                        <!-- Map Controls -->
                        <div class="map-controls" style="position: absolute; top: 10px; right: 50px; z-index: 1000;">
                            <div class="btn-group-vertical" role="group">
                                <button type="button" class="btn btn-light btn-sm" id="fullscreen-btn" onclick="toggleFullscreen()" title="Fullscreen">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <button type="button" class="btn btn-light btn-sm" id="satellite-btn" onclick="toggleSatellite()" title="Satelit">
                                    <i class="fas fa-satellite"></i>
                                </button>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown" title="Pilih Provider Satelit">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" onclick="changeSatelliteProvider('google')"><i class="fab fa-google text-danger"></i> Google Satellite</a>
                                        <a class="dropdown-item" href="#" onclick="changeSatelliteProvider('esri')"><i class="fas fa-globe text-primary"></i> Esri World Imagery</a>
                                        <a class="dropdown-item" href="#" onclick="changeSatelliteProvider('bing')"><i class="fab fa-microsoft text-info"></i> Bing Aerial</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Satellite Status Indicator -->
                        <div class="satellite-status" id="satellite-status">
                            <i class="fas fa-map"></i> Street Map
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Image and Additional Info -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Foto Lokasi</h3>
                </div>
                <div class="card-body text-center">
                    @if($location->image_path)
                        <img src="{{ asset($location->image_path) }}" alt="{{ $location->name }}" 
                             class="img-fluid rounded" style="max-height: 300px;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                             style="height: 200px;">
                            <div class="text-center">
                                <i class="fas fa-image text-muted fa-3x"></i>
                                <p class="text-muted mt-2">Tidak ada foto</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Informasi Sistem</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td>Dibuat oleh</td>
                            <td>: {{ $location->creator->name ?? 'System' }}</td>
                        </tr>
                        <tr>
                            <td>Dibuat pada</td>
                            <td>: {{ $location->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td>Terakhir diubah</td>
                            <td>: {{ $location->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                        @if($location->area_coordinates)
                        <tr>
                            <td>Koordinat Area</td>
                            <td>: 
                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i> {{ is_array($location->area_coordinates) ? count($location->area_coordinates) : 'Data' }} titik
                                </span>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            
            @if($location->area_coordinates)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-code"></i> Data Koordinat Area
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Koordinat Batas Wilayah (JSON)</label>
                        <textarea class="form-control" rows="8" readonly style="font-family: monospace; font-size: 12px;">{{ is_array($location->area_coordinates) ? json_encode($location->area_coordinates, JSON_PRETTY_PRINT) : $location->area_coordinates }}</textarea>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Data koordinat dalam format JSON untuk batas wilayah area.
                        </small>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
@if($location->latitude && $location->longitude)
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<style>
    .map-controls .btn {
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        margin-bottom: 2px;
    }
    .map-controls .btn:hover {
        transform: scale(1.05);
    }
    .map-controls .btn-group {
        margin-left: 5px;
    }
    .map-controls .dropdown-menu {
        min-width: 160px;
        font-size: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    .map-controls .dropdown-item {
        padding: 8px 12px;
        font-size: 12px;
    }
    .map-controls .dropdown-item:hover {
        background-color: #007bff;
        color: white;
    }
    #map.fullscreen {
        transition: all 0.3s ease;
    }
    .satellite-status {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 11px;
        z-index: 1000;
        display: none;
    }
</style>
@endif
@endpush

@push('scripts')
@if($location->latitude && $location->longitude)
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    let map, marker, polygon;
    let osmLayer, satelliteProviders = {}, currentSatelliteProvider;
    let isFullscreen = false, isSatelliteView = false;
    
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
    });
    
    function initMap() {
        try {
            console.log('Initializing show map...');
            
            const mapContainer = document.getElementById('map');
            if (!mapContainer) {
                console.error('Map container not found');
                return;
            }
            
            // Remove loading indicator
            const loadingDiv = mapContainer.querySelector('.text-center');
            if (loadingDiv) {
                loadingDiv.remove();
            }
            
            // Initialize map
            const location = [{{ $location->latitude }}, {{ $location->longitude }}];
            map = L.map('map', {
                center: location,
                zoom: 15,
                zoomControl: true,
                scrollWheelZoom: true
            });
            
            // Add OpenStreetMap layer
            osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);
            
            // Initialize satellite providers
            satelliteProviders = {
                esri: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: 'Tiles © Esri',
                    maxZoom: 18,
                    crossOrigin: true
                }),
                google: L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                    attribution: 'Imagery © Google',
                    maxZoom: 20,
                    crossOrigin: true
                }),
                bing: L.tileLayer('https://ecn.t3.tiles.virtualearth.net/tiles/a{q}.jpeg?g=587&mkt=en-gb&n=z', {
                    attribution: 'Imagery © Microsoft Bing Maps',
                    maxZoom: 19,
                    subdomains: ['t0', 't1', 't2', 't3'],
                    crossOrigin: true
                })
            };
            
            currentSatelliteProvider = satelliteProviders.google;
            
            // Add marker with custom icon and popup
            const customIcon = L.divIcon({
                html: '<i class="{{ $location->icon ?? "fas fa-map-marker-alt" }}" style="color: {{ $location->color ?? "#007bff" }}; font-size: 24px;"></i>',
                iconSize: [30, 30],
                className: 'custom-div-icon'
            });
            
            marker = L.marker(location, { icon: customIcon })
                .addTo(map)
                .bindPopup(`
                    <div style="min-width: 200px;">
                        <h6><strong>{{ $location->name }}</strong></h6>
                        <p class="mb-1"><i class="fas fa-map-marker-alt"></i> {{ $location->address }}</p>
                        @if($location->phone)
                        <p class="mb-1"><i class="fas fa-phone"></i> {{ $location->phone }}</p>
                        @endif
                        @if($location->email)
                        <p class="mb-1"><i class="fas fa-envelope"></i> {{ $location->email }}</p>
                        @endif
                        <p class="mb-0"><small class="text-muted">{{ $location->type_name }}</small></p>
                    </div>
                `);
            
            // Add area polygon if coordinates exist
            @if($location->area_coordinates)
            const areaCoordinates = @json($location->area_coordinates);
            console.log('Area coordinates:', areaCoordinates);
            
            if (areaCoordinates && Array.isArray(areaCoordinates) && areaCoordinates.length > 2) {
                // Handle different coordinate formats
                let latlngs;
                
                if (areaCoordinates[0] && areaCoordinates[0].length === 2 && typeof areaCoordinates[0][0] === 'number') {
                    // Convert [lng, lat] to [lat, lng] for Leaflet
                    latlngs = areaCoordinates.map(coord => [coord[1], coord[0]]);
                } else {
                    // Assume already in [lat, lng] format
                    latlngs = areaCoordinates;
                }
                
                // Remove duplicate closing coordinate if exists
                if (latlngs.length > 3 && 
                    latlngs[0][0] === latlngs[latlngs.length - 1][0] && 
                    latlngs[0][1] === latlngs[latlngs.length - 1][1]) {
                    latlngs = latlngs.slice(0, -1);
                }
                
                if (latlngs.length >= 3) {
                    polygon = L.polygon(latlngs, {
                        color: '{{ $location->color ?? "#007bff" }}',
                        fillColor: '{{ $location->color ?? "#007bff" }}',
                        fillOpacity: 0.3,
                        weight: 2
                    }).addTo(map);
                    
                    // Add area info to polygon popup
                    @if($location->area_size)
                    const areaSize = {{ $location->area_size }};
                    const areaText = formatArea(areaSize);
                    polygon.bindPopup(`
                        <div>
                            <h6><strong>Area Wilayah</strong></h6>
                            <p><i class="fas fa-ruler-combined"></i> <strong>${areaText}</strong></p>
                            <p class="mb-0"><small class="text-muted">{{ $location->name }}</small></p>
                        </div>
                    `);
                    @endif
                    
                    // Fit map to show both marker and polygon
                    const group = new L.featureGroup([marker, polygon]);
                    map.fitBounds(group.getBounds().pad(0.1));
                    
                    console.log('Area polygon added successfully');
                } else {
                    console.warn('Not enough coordinates for polygon');
                }
            } else {
                console.log('No valid area coordinates found');
            }
            @else
            console.log('No area coordinates available');
            @endif
            
            map.whenReady(function() {
                map.invalidateSize();
                console.log('Show map initialized successfully');
            });
            
        } catch (error) {
            console.error('Error initializing show map:', error);
            showMapError();
        }
    }
    
    // Format area for display
    function formatArea(area) {
        if (area < 1000) {
            return Math.round(area * 100) / 100 + ' m²';
        } else if (area < 1000000) {
            return Math.round(area / 10) / 100 + ' Ha';
        } else {
            return Math.round(area / 10000) / 100 + ' km²';
        }
    }
    
    // Show error message if map fails to load
    function showMapError() {
        const mapDiv = document.getElementById('map');
        if (mapDiv) {
            mapDiv.innerHTML = `
                <div class="d-flex align-items-center justify-content-center" style="height: 400px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 0.375rem;">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                        <p class="text-muted mt-2 mb-1">Peta tidak dapat dimuat.</p>
                        <small class="text-muted d-block">Silakan refresh halaman untuk mencoba lagi.</small>
                    </div>
                </div>
            `;
        }
    }
    
    // Toggle fullscreen map
    function toggleFullscreen() {
        const mapContainer = document.getElementById('map');
        const fullscreenBtn = document.getElementById('fullscreen-btn');
        
        if (!isFullscreen) {
            // Enter fullscreen
            mapContainer.style.position = 'fixed';
            mapContainer.style.top = '0';
            mapContainer.style.left = '0';
            mapContainer.style.width = '100vw';
            mapContainer.style.height = '100vh';
            mapContainer.style.zIndex = '9999';
            mapContainer.style.backgroundColor = 'white';
            
            fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
            fullscreenBtn.title = 'Exit Fullscreen';
            isFullscreen = true;
            
            document.addEventListener('keydown', handleEscapeKey);
        } else {
            // Exit fullscreen
            mapContainer.style.position = 'relative';
            mapContainer.style.top = 'auto';
            mapContainer.style.left = 'auto';
            mapContainer.style.width = '100%';
            mapContainer.style.height = '500px';
            mapContainer.style.zIndex = 'auto';
            
            fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
            fullscreenBtn.title = 'Fullscreen';
            isFullscreen = false;
            
            document.removeEventListener('keydown', handleEscapeKey);
        }
        
        setTimeout(() => {
            if (map) {
                map.invalidateSize();
            }
        }, 100);
    }
    
    // Toggle satellite view
    function toggleSatellite() {
        const satelliteBtn = document.getElementById('satellite-btn');
        const statusElement = document.getElementById('satellite-status');
        
        try {
            if (!isSatelliteView) {
                // Switch to satellite
                if (osmLayer && map.hasLayer(osmLayer)) {
                    map.removeLayer(osmLayer);
                }
                
                if (currentSatelliteProvider) {
                    currentSatelliteProvider.addTo(map);
                }
                
                satelliteBtn.innerHTML = '<i class="fas fa-map"></i>';
                satelliteBtn.title = 'Street Map';
                satelliteBtn.classList.remove('btn-light');
                satelliteBtn.classList.add('btn-info');
                isSatelliteView = true;
                
                if (statusElement) {
                    statusElement.innerHTML = '<i class="fas fa-satellite text-info"></i> Satellite Active';
                    statusElement.style.display = 'block';
                    setTimeout(() => { statusElement.style.display = 'none'; }, 3000);
                }
            } else {
                // Switch to street map
                if (currentSatelliteProvider && map.hasLayer(currentSatelliteProvider)) {
                    map.removeLayer(currentSatelliteProvider);
                }
                
                if (osmLayer) {
                    osmLayer.addTo(map);
                }
                
                satelliteBtn.innerHTML = '<i class="fas fa-satellite"></i>';
                satelliteBtn.title = 'Satelit';
                satelliteBtn.classList.remove('btn-info');
                satelliteBtn.classList.add('btn-light');
                isSatelliteView = false;
                
                if (statusElement) {
                    statusElement.innerHTML = '<i class="fas fa-map text-success"></i> Street Map Active';
                    statusElement.style.display = 'block';
                    setTimeout(() => { statusElement.style.display = 'none'; }, 3000);
                }
            }
            
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
            
        } catch (error) {
            console.error('Error in toggleSatellite:', error);
        }
    }
    
    // Change satellite provider
    function changeSatelliteProvider(provider) {
        if (satelliteProviders[provider]) {
            if (isSatelliteView && currentSatelliteProvider && map.hasLayer(currentSatelliteProvider)) {
                map.removeLayer(currentSatelliteProvider);
            }
            
            currentSatelliteProvider = satelliteProviders[provider];
            
            if (isSatelliteView) {
                currentSatelliteProvider.addTo(map);
            }
            
            map.invalidateSize();
        }
    }
    
    // Handle ESC key for fullscreen exit
    function handleEscapeKey(event) {
        if (event.key === 'Escape' && isFullscreen) {
            toggleFullscreen();
        }
    }
</script>
@endif

<script>
    function toggleStatus(locationId) {
        fetch(`{{ url('admin/locations') }}/${locationId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status');
        });
    }
</script>
@endpush