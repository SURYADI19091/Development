@extends('backend.layout.main')

@section('page_title', 'Tambah Lokasi')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.locations.index') }}">Lokasi</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Lokasi</h3>
                </div>
                
                <form action="{{ route('backend.locations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <!-- Interactive Map Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label><i class="fas fa-map"></i> Pilih Lokasi & Ukur Luas Wilayah</label>
                                    <div class="alert alert-info alert-sm mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <i class="fas fa-info-circle"></i> 
                                                <strong>Cara menggunakan peta:</strong>
                                                <ul class="mb-0 mt-1 small">
                                                    <li>Klik di peta untuk menentukan lokasi</li>
                                                    <li>Drag marker untuk memindahkan posisi</li>
                                                    <li>Koordinat akan otomatis terisi</li>
                                                    <li>Alamat akan otomatis diperbarui (jika kosong)</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <i class="fas fa-draw-polygon"></i> 
                                                <strong>Cara mengukur luas:</strong>
                                                <ul class="mb-0 mt-1 small">
                                                    <li>Pilih warna polygon yang diinginkan</li>
                                                    <li>Gunakan tool Polygon/Rectangle di peta</li>
                                                    <li>Klik beberapa titik untuk membuat polygon</li>
                                                    <li>Luas akan dihitung otomatis</li>
                                                </ul>
                                            </div>
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
                                                <button type="button" class="btn btn-light btn-sm" onclick="getCurrentLocation()" title="Lokasi Saya">
                                                    <i class="fas fa-location-arrow"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Satellite Status Indicator -->
                                        <div class="satellite-status" id="satellite-status">
                                            <i class="fas fa-map"></i> Street Map
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-lightbulb"></i> 
                                        Tips: Anda juga bisa memasukkan alamat terlebih dahulu, kemudian klik "Cari di Peta" untuk menemukan koordinat secara otomatis.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Area Measurement Controls -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-ruler-combined text-primary"></i> Pengukuran Luas Wilayah</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="area_size">Luas Wilayah (m²)</label>
                                                    <input type="number" class="form-control @error('area_size') is-invalid @enderror" 
                                                           id="area_size" name="area_size" value="{{ old('area_size') }}" 
                                                           step="0.01" min="0" readonly placeholder="0">
                                                    @error('area_size')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                    <small class="form-text text-muted">Hasil pengukuran otomatis</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="polygon_color">Warna Polygon</label>
                                                    <div class="input-group">
                                                        <input type="color" class="form-control" id="polygon_color" value="#2196F3" 
                                                               style="height: 38px; padding: 2px;" onchange="updatePolygonColor()">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetPolygonColor()" title="Reset">
                                                                <i class="fas fa-undo"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-muted">Pilih warna untuk polygon area</small>
                                                    <div class="mt-2">
                                                        <small class="text-muted">Preset:</small><br>
                                                        <button type="button" class="btn btn-sm mr-1 mt-1" style="background-color: #2196F3; width: 20px; height: 20px;" onclick="setPolygonColor('#2196F3')" title="Biru"></button>
                                                        <button type="button" class="btn btn-sm mr-1 mt-1" style="background-color: #4CAF50; width: 20px; height: 20px;" onclick="setPolygonColor('#4CAF50')" title="Hijau"></button>
                                                        <button type="button" class="btn btn-sm mr-1 mt-1" style="background-color: #FF9800; width: 20px; height: 20px;" onclick="setPolygonColor('#FF9800')" title="Orange"></button>
                                                        <button type="button" class="btn btn-sm mr-1 mt-1" style="background-color: #F44336; width: 20px; height: 20px;" onclick="setPolygonColor('#F44336')" title="Merah"></button>
                                                        <button type="button" class="btn btn-sm mr-1 mt-1" style="background-color: #9C27B0; width: 20px; height: 20px;" onclick="setPolygonColor('#9C27B0')" title="Ungu"></button>
                                                        <button type="button" class="btn btn-sm mr-1 mt-1" style="background-color: #607D8B; width: 20px; height: 20px;" onclick="setPolygonColor('#607D8B')" title="Abu-abu"></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="polygon_opacity">Opacity (%)</label>
                                                    <input type="range" class="form-control-range" id="polygon_opacity" 
                                                           min="10" max="80" value="20" onchange="updatePolygonOpacity()">
                                                    <div class="d-flex justify-content-between">
                                                        <small>10%</small>
                                                        <small id="opacity_value">20%</small>
                                                        <small>80%</small>
                                                    </div>
                                                    <small class="form-text text-muted">Transparansi polygon</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Kontrol Area</label><br>
                                                    <button type="button" class="btn btn-warning btn-sm mr-2" onclick="clearAreaMeasurement()">
                                                        <i class="fas fa-eraser"></i> Hapus
                                                    </button>
                                                    <button type="button" class="btn btn-success btn-sm" onclick="savePolygonSettings()">
                                                        <i class="fas fa-save"></i> Simpan Warna
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="area_coordinates">Koordinat Batas Wilayah (JSON)</label>
                                                    <textarea class="form-control @error('area_coordinates') is-invalid @enderror" 
                                                             id="area_coordinates" name="area_coordinates" rows="3" readonly 
                                                             placeholder="Koordinat polygon akan muncul otomatis">{{ old('area_coordinates') }}</textarea>
                                                    @error('area_coordinates')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                    <small class="form-text text-muted">Data koordinat batas wilayah dalam format JSON</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lokasi *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="type">Tipe Lokasi *</label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="office" {{ old('type') == 'office' ? 'selected' : '' }}>Pemerintahan</option>
                                        <option value="school" {{ old('type') == 'school' ? 'selected' : '' }}>Pendidikan</option>
                                        <option value="health" {{ old('type') == 'health' ? 'selected' : '' }}>Kesehatan</option>
                                        <option value="religious" {{ old('type') == 'religious' ? 'selected' : '' }}>Tempat Ibadah</option>
                                        <option value="commercial" {{ old('type') == 'commercial' ? 'selected' : '' }}>Perdagangan</option>
                                        <option value="public" {{ old('type') == 'public' ? 'selected' : '' }}>Fasilitas Umum</option>
                                        <option value="tourism" {{ old('type') == 'tourism' ? 'selected' : '' }}>Wisata</option>
                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Alamat *</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="latitude">Latitude (Opsional)</label>
                                            <input type="number" class="form-control @error('latitude') is-invalid @enderror" 
                                                   id="latitude" name="latitude" value="{{ old('latitude') }}" 
                                                   step="0.00000001" min="-90" max="90" 
                                                   placeholder="Contoh: -6.2088">
                                            @error('latitude')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small class="form-text text-muted">Koordinat lintang (-90 sampai 90)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="longitude">Longitude (Opsional)</label>
                                            <input type="number" class="form-control @error('longitude') is-invalid @enderror" 
                                                   id="longitude" name="longitude" value="{{ old('longitude') }}" 
                                                   step="0.00000001" min="-180" max="180"
                                                   placeholder="Contoh: 106.8456">
                                            @error('longitude')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small class="form-text text-muted">Koordinat bujur (-180 sampai 180)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-light">
                                    <i class="fas fa-info-circle text-info"></i> 
                                    <strong>Info Koordinat:</strong> Koordinat GPS membantu menampilkan lokasi di peta dengan akurat. 
                                    Jika tidak diisi, lokasi hanya akan ditampilkan berdasarkan alamat.
                                </div>




                            </div>

                            <!-- Contact & Additional Info -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="icon">Icon (Font Awesome class)</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', 'fas fa-map-marker-alt') }}" 
                                           placeholder="fas fa-map-marker-alt">
                                    @error('icon')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Contoh: fas fa-hospital, fas fa-school, fas fa-mosque</small>
                                </div>

                                <div class="form-group">
                                    <label for="color">Warna Marker</label>
                                    <input type="color" class="form-control @error('color') is-invalid @enderror" 
                                           id="color" name="color" value="{{ old('color', '#007bff') }}">
                                    @error('color')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="sort_order">Urutan Tampil</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="image">Foto Lokasi</label>
                                    <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Operating Hours -->
                        <div class="form-group">
                            <label>Jam Operasional</label>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Senin</label>
                                    <input type="text" class="form-control" name="operating_hours[monday]" 
                                           value="{{ old('operating_hours.monday') }}" placeholder="08:00 - 17:00">
                                </div>
                                <div class="col-md-2">
                                    <label>Selasa</label>
                                    <input type="text" class="form-control" name="operating_hours[tuesday]" 
                                           value="{{ old('operating_hours.tuesday') }}" placeholder="08:00 - 17:00">
                                </div>
                                <div class="col-md-2">
                                    <label>Rabu</label>
                                    <input type="text" class="form-control" name="operating_hours[wednesday]" 
                                           value="{{ old('operating_hours.wednesday') }}" placeholder="08:00 - 17:00">
                                </div>
                                <div class="col-md-2">
                                    <label>Kamis</label>
                                    <input type="text" class="form-control" name="operating_hours[thursday]" 
                                           value="{{ old('operating_hours.thursday') }}" placeholder="08:00 - 17:00">
                                </div>
                                <div class="col-md-2">
                                    <label>Jumat</label>
                                    <input type="text" class="form-control" name="operating_hours[friday]" 
                                           value="{{ old('operating_hours.friday') }}" placeholder="08:00 - 17:00">
                                </div>
                                <div class="col-md-2">
                                    <label>Sabtu</label>
                                    <input type="text" class="form-control" name="operating_hours[saturday]" 
                                           value="{{ old('operating_hours.saturday') }}" placeholder="08:00 - 12:00">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label>Minggu</label>
                                    <input type="text" class="form-control" name="operating_hours[sunday]" 
                                           value="{{ old('operating_hours.sunday') }}" placeholder="Tutup">
                                </div>
                            </div>
                            <small class="form-text text-muted">Kosongkan jika tidak ada jam operasional tertentu</small>
                        </div>

                        <!-- Status Options -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Status Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="show_on_map" name="show_on_map" value="1" 
                                               {{ old('show_on_map', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_on_map">
                                            Tampilkan di Peta
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('backend.locations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<!-- Leaflet Draw CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
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
@endpush

@push('scripts')
<!-- Leaflet JavaScript with fallback -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" 
        onerror="this.onerror=null; this.src='https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js';"></script>
        
<!-- Fallback Leaflet from different CDN -->
<script>
    // Check if Leaflet loaded, if not load from alternative CDN
    window.addEventListener('load', function() {
        if (typeof L === 'undefined') {
            console.log('Loading Leaflet from fallback CDN...');
            var script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js';
            script.onload = function() {
                console.log('Leaflet fallback loaded successfully');
                window.leafletReady = true;
            };
            document.head.appendChild(script);
        } else {
            window.leafletReady = true;
        }
    });
</script>

<!-- Leaflet Draw JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js" 
        onerror="console.warn('Leaflet Draw failed to load');"></script>
<!-- Turf.js for area calculation -->
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>

<script>
    let map;
    let marker;
    let drawnItems;
    let drawControl;
    let currentPolygon = null;
    let currentPolygonColor = '#2196F3';
    let currentPolygonOpacity = 0.2;
    let satelliteLayer;
    let osmLayer;
    let isFullscreen = false;
    let isSatelliteView = false;

    // Initialize Leaflet Map
    function initMap() {
        try {
            console.log('Starting map initialization...');
            
            // Check if Leaflet is available
            if (typeof L === 'undefined') {
                console.error('Leaflet library not loaded');
                showMapError();
                return;
            }

            // Check if map container exists
            const mapContainer = document.getElementById('map');
            if (!mapContainer) {
                console.error('Map container not found');
                setTimeout(initMap, 500); // Retry after 500ms
                return;
            }

            // Show loading indicator with timeout
            mapContainer.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin text-primary"></i> <br><span class="mt-2 d-block">Memuat peta...</span></div>';
            
            // Set timeout for loading
            const loadingTimeout = setTimeout(() => {
                console.warn('Map loading timeout, trying alternative approach...');
                showMapError();
            }, 10000); // 10 second timeout

            // Default location (Indonesia center)
            const defaultLocation = [-6.2088, 106.8456];
            
            // Clear any existing map
            if (map) {
                map.remove();
            }
            
            // Initialize map with OpenStreetMap
            map = L.map('map', {
                center: defaultLocation,
                zoom: 13,
                zoomControl: true,
                scrollWheelZoom: true,
                preferCanvas: false
            });
            
            // Add OpenStreetMap tile layer with fallback servers
            const osmUrls = [
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png'
            ];
            
            osmLayer = L.tileLayer(osmUrls[0], {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
                timeout: 10000,
                errorTileUrl: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjU2IiBoZWlnaHQ9IjI1NiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjBmMGYwIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9IjAuM2VtIj5Mb2FkaW5nLi4uPC90ZXh0Pjwvc3ZnPg=='
            });
            
            // Add Satellite layer with fallback options
            satelliteLayer = L.layerGroup([
                // Primary: Esri World Imagery
                L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: 'Tiles © Esri — Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
                    maxZoom: 18,
                    crossOrigin: true
                })
            ]);
            
            // Initialize satellite providers globally
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
            
            // Set current satellite provider (Google as default)
            currentSatelliteProvider = satelliteProviders.google;
            
            console.log('Satellite providers initialized:', Object.keys(satelliteProviders));
            
            osmLayer.on('tileerror', function(error) {
                console.warn('Tile loading error:', error);
            });
            
            osmLayer.on('load', function() {
                console.log('Map tiles loaded successfully');
            });
            
            // Add default OSM layer to map with success callback
            osmLayer.addTo(map);
            
            let tilesLoaded = 0;
            let tilesLoading = 0;
            
            // Track tile loading progress
            osmLayer.on('loading', function() {
                console.log('Started loading tiles...');
            });
            
            osmLayer.on('load', function() {
                console.log('All visible tiles loaded successfully');
                clearTimeout(loadingTimeout);
                
                // Remove loading indicator
                setTimeout(() => {
                    const loadingDiv = document.getElementById('map').querySelector('.text-center');
                    if (loadingDiv) {
                        loadingDiv.remove();
                    }
                }, 500);
            });
            
            osmLayer.on('tileloadstart', function() {
                tilesLoading++;
            });
            
            osmLayer.on('tileload', function() {
                tilesLoaded++;
                console.log(`Tile loaded: ${tilesLoaded}/${tilesLoading}`);
            });
            
            osmLayer.on('tileerror', function(e) {
                console.warn('Tile failed to load:', e);
                tilesLoaded++; // Count as processed
            });
            
            // Test satellite layer loading
            console.log('Testing satellite layer...'); 
            currentSatelliteProvider.on('tileload', function() {
                console.log('Satellite tile loaded successfully');
            });
            
            currentSatelliteProvider.on('tileerror', function(e) {
                console.error('Satellite tile error:', e);
            });

            // Initialize marker
            marker = L.marker(defaultLocation, {
                draggable: true,
                title: "Klik dan drag untuk memindahkan lokasi"
            }).addTo(map);

            // Add click event to map
            map.on('click', function(e) {
                placeMarker(e.latlng);
            });

            // Add drag event to marker
            marker.on('dragend', function(e) {
                const position = e.target.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });

            // Map ready event
            map.whenReady(function() {
                console.log('Map is ready and fully loaded');
                map.invalidateSize(); // Ensure proper sizing
            });
            
            // Map error event
            map.on('error', function(e) {
                console.error('Map error:', e);
                showMapError();
            });

            // Try to get user's current location (optional)
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const pos = [position.coords.latitude, position.coords.longitude];
                        map.setView(pos, 15);
                        marker.setLatLng(pos);
                        updateCoordinates(position.coords.latitude, position.coords.longitude);
                    },
                    function(error) {
                        console.log('Geolocation error (optional):', error);
                        // This is optional, so we don't show error
                    }
                );
            }

            // Listen to coordinate input changes
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            
            if (latInput) latInput.addEventListener('input', updateMapFromInputs);
            if (lngInput) lngInput.addEventListener('input', updateMapFromInputs);
            
            // Initialize drawing controls for area measurement
            setTimeout(setupDrawingControls, 500); // Delay to ensure all libraries loaded
            
            console.log('Map initialized successfully');
            
            // Clear loading timeout and hide loading indicator
            if (typeof loadingTimeout !== 'undefined') {
                clearTimeout(loadingTimeout);
            }
            
            setTimeout(() => {
                const loadingDiv = document.getElementById('map').querySelector('.text-center');
                if (loadingDiv) {
                    loadingDiv.style.display = 'none';
                }
            }, 1000);
            
        } catch (error) {
            console.error('Error initializing map:', error);
            if (typeof loadingTimeout !== 'undefined') {
                clearTimeout(loadingTimeout);
            }
            showMapError();
        }
    }
    
    // Simple fallback map initialization
    function initSimpleMap() {
        console.log('Attempting simple map initialization...');
        try {
            const mapContainer = document.getElementById('map');
            if (!mapContainer || typeof L === 'undefined') {
                showMapError();
                return;
            }
            
            mapContainer.innerHTML = '';
            
            // Basic map without fancy features
            const defaultLocation = [-6.2088, 106.8456];
            map = L.map('map').setView(defaultLocation, 13);
            
            // Simple OSM layer
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);
            
            // Simple marker
            marker = L.marker(defaultLocation, { draggable: true }).addTo(map);
            
            // Basic events
            marker.on('dragend', function(e) {
                updateCoordinates(e.target.getLatLng());
            });
            
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateCoordinates(e.latlng);
            });
            
            console.log('Simple map initialized successfully');
            
        } catch (error) {
            console.error('Simple map initialization failed:', error);
            showMapError();
        }
    }

    // Setup drawing controls for area measurement
    function setupDrawingControls() {
        try {
            // Initialize feature group for drawn items
            drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);

            // Initialize draw control with dynamic colors
            drawControl = new L.Control.Draw({
                edit: {
                    featureGroup: drawnItems,
                    remove: true
                },
                draw: {
                    polygon: {
                        allowIntersection: false,
                        drawError: {
                            color: '#e1e100',
                            message: 'Polygon tidak boleh berpotongan!'
                        },
                        shapeOptions: {
                            color: currentPolygonColor,
                            weight: 2,
                            fillOpacity: currentPolygonOpacity,
                            fillColor: currentPolygonColor
                        }
                    },
                    rectangle: {
                        shapeOptions: {
                            color: currentPolygonColor,
                            weight: 2,
                            fillOpacity: currentPolygonOpacity,
                            fillColor: currentPolygonColor
                        }
                    },
                    polyline: false,
                    circle: false,
                    marker: false,
                    circlemarker: false
                }
            });
            
            map.addControl(drawControl);

            // Event handler for drawn shapes
            map.on('draw:created', function(e) {
                const layer = e.layer;
                
                // Remove existing polygon if any
                if (currentPolygon) {
                    drawnItems.removeLayer(currentPolygon);
                }
                
                // Add new polygon
                drawnItems.addLayer(layer);
                currentPolygon = layer;
                
                // Calculate and display area
                calculateArea(layer);
            });

            // Event handler for edited shapes
            map.on('draw:edited', function(e) {
                const layers = e.layers;
                layers.eachLayer(function(layer) {
                    if (layer === currentPolygon) {
                        calculateArea(layer);
                    }
                });
            });

            // Event handler for deleted shapes
            map.on('draw:deleted', function(e) {
                currentPolygon = null;
                document.getElementById('area_size').value = '';
                document.getElementById('area_coordinates').value = '';
            });
            
        } catch (error) {
            console.error('Error setting up drawing controls:', error);
        }
    }

    // Calculate area of polygon
    function calculateArea(layer) {
        try {
            let area = 0;
            let coordinates = [];
            
            if (layer instanceof L.Polygon || layer instanceof L.Rectangle) {
                const latlngs = layer.getLatLngs()[0];
                
                // Convert to coordinates array for Turf.js
                const coords = latlngs.map(latlng => [latlng.lng, latlng.lat]);
                coords.push(coords[0]); // Close the polygon
                coordinates = coords;
                
                // Calculate area using Turf.js
                if (typeof turf !== 'undefined') {
                    const polygon = turf.polygon([coords]);
                    area = turf.area(polygon); // Returns area in square meters
                } else {
                    // Fallback calculation using Leaflet's built-in method
                    area = L.GeometryUtil ? L.GeometryUtil.geodesicArea(latlngs) : 0;
                }
            }
            
            // Update form fields
            document.getElementById('area_size').value = Math.round(area * 100) / 100;
            document.getElementById('area_coordinates').value = JSON.stringify(coordinates, null, 2);
            
            // Show area info on map
            const areaText = formatArea(area);
            layer.bindPopup(`<strong>Luas Wilayah:</strong><br>${areaText}`).openPopup();
            
        } catch (error) {
            console.error('Error calculating area:', error);
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
                        <small class="text-muted d-block mb-3">Anda masih bisa memasukkan koordinat secara manual.</small>
                        <button type="button" class="btn btn-sm btn-primary" onclick="retryMapLoad()">
                            <i class="fas fa-redo"></i> Coba Lagi
                        </button>
                    </div>
                </div>
            `;
        }
    }

    // Retry map loading
    function retryMapLoad() {
        console.log('Retrying map load...');
        const mapDiv = document.getElementById('map');
        if (mapDiv) {
            mapDiv.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin text-primary"></i><br><span class="mt-2 d-block">Mencoba memuat peta...</span></div>';
            
            setTimeout(function() {
                if (typeof L !== 'undefined') {
                    console.log('Leaflet available, trying simple map...');
                    try {
                        initSimpleMap();
                    } catch (error) {
                        console.error('Simple map retry failed:', error);
                        // Try full map as fallback
                        try {
                            initMap();
                        } catch (fullError) {
                            console.error('Full map retry also failed:', fullError);
                            showMapError();
                        }
                    }
                } else {
                    console.error('Leaflet still not available');
                    showMapError();
                }
            }, 1500);
        }
    }





    // Fallback area calculation using spherical law of cosines
    function calculatePolygonArea(latlngs) {
        const earthRadius = 6378137; // Earth's radius in meters
        let area = 0;
        
        if (latlngs.length < 3) return 0;
        
        for (let i = 0; i < latlngs.length; i++) {
            const j = (i + 1) % latlngs.length;
            const lat1 = latlngs[i].lat * Math.PI / 180;
            const lng1 = latlngs[i].lng * Math.PI / 180;
            const lat2 = latlngs[j].lat * Math.PI / 180;
            const lng2 = latlngs[j].lng * Math.PI / 180;
            
            area += (lng2 - lng1) * (2 + Math.sin(lat1) + Math.sin(lat2));
        }
        
        area = Math.abs(area * earthRadius * earthRadius / 2);
        return area;
    }



    // Clear area measurement
    function clearAreaMeasurement() {
        if (currentPolygon && drawnItems) {
            drawnItems.removeLayer(currentPolygon);
            currentPolygon = null;
            document.getElementById('area_size').value = '';
            document.getElementById('area_coordinates').value = '';
        }
    }

    // Update polygon color
    function updatePolygonColor() {
        const colorPicker = document.getElementById('polygon_color');
        currentPolygonColor = colorPicker.value;
        
        // Update existing polygon if any
        if (currentPolygon) {
            currentPolygon.setStyle({
                color: currentPolygonColor,
                fillColor: currentPolygonColor
            });
        }
        
        // Update draw control options for new polygons
        updateDrawControlOptions();
    }

    // Update polygon opacity
    function updatePolygonOpacity() {
        const opacitySlider = document.getElementById('polygon_opacity');
        const opacityValue = document.getElementById('opacity_value');
        
        currentPolygonOpacity = opacitySlider.value / 100;
        opacityValue.textContent = opacitySlider.value + '%';
        
        // Update existing polygon if any
        if (currentPolygon) {
            currentPolygon.setStyle({
                fillOpacity: currentPolygonOpacity
            });
        }
        
        // Update draw control options for new polygons
        updateDrawControlOptions();
    }

    // Reset polygon color to default
    function resetPolygonColor() {
        document.getElementById('polygon_color').value = '#2196F3';
        document.getElementById('polygon_opacity').value = '20';
        document.getElementById('opacity_value').textContent = '20%';
        
        currentPolygonColor = '#2196F3';
        currentPolygonOpacity = 0.2;
        
        // Update existing polygon if any
        if (currentPolygon) {
            currentPolygon.setStyle({
                color: currentPolygonColor,
                fillColor: currentPolygonColor,
                fillOpacity: currentPolygonOpacity
            });
        }
        
        updateDrawControlOptions();
    }

    // Update draw control options
    function updateDrawControlOptions() {
        if (drawControl && map) {
            // Remove existing control
            map.removeControl(drawControl);
            
            // Create new control with updated colors
            drawControl = new L.Control.Draw({
                edit: {
                    featureGroup: drawnItems,
                    remove: true
                },
                draw: {
                    polygon: {
                        allowIntersection: false,
                        drawError: {
                            color: '#e1e100',
                            message: 'Polygon tidak boleh berpotongan!'
                        },
                        shapeOptions: {
                            color: currentPolygonColor,
                            weight: 2,
                            fillOpacity: currentPolygonOpacity,
                            fillColor: currentPolygonColor
                        }
                    },
                    rectangle: {
                        shapeOptions: {
                            color: currentPolygonColor,
                            weight: 2,
                            fillOpacity: currentPolygonOpacity,
                            fillColor: currentPolygonColor
                        }
                    },
                    polyline: false,
                    circle: false,
                    marker: false,
                    circlemarker: false
                }
            });
            
            // Add updated control back to map
            map.addControl(drawControl);
        }
    }

    // Set polygon color from preset
    function setPolygonColor(color) {
        document.getElementById('polygon_color').value = color;
        currentPolygonColor = color;
        
        // Update existing polygon if any
        if (currentPolygon) {
            currentPolygon.setStyle({
                color: currentPolygonColor,
                fillColor: currentPolygonColor
            });
        }
        
        // Update draw control options for new polygons
        updateDrawControlOptions();
    }

    // Save polygon color settings to localStorage
    function savePolygonSettings() {
        const settings = {
            color: currentPolygonColor,
            opacity: currentPolygonOpacity
        };
        
        localStorage.setItem('polygonSettings', JSON.stringify(settings));
        
        // Show success message
        const saveBtn = event.target;
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-check"></i> Tersimpan!';
        saveBtn.classList.remove('btn-success');
        saveBtn.classList.add('btn-info');
        
        setTimeout(() => {
            saveBtn.innerHTML = originalText;
            saveBtn.classList.remove('btn-info');
            saveBtn.classList.add('btn-success');
        }, 2000);
    }

    // Load polygon color settings from localStorage
    function loadPolygonSettings() {
        try {
            const savedSettings = localStorage.getItem('polygonSettings');
            if (savedSettings) {
                const settings = JSON.parse(savedSettings);
                
                // Update color picker
                document.getElementById('polygon_color').value = settings.color || '#2196F3';
                currentPolygonColor = settings.color || '#2196F3';
                
                // Update opacity slider
                const opacityValue = Math.round((settings.opacity || 0.2) * 100);
                document.getElementById('polygon_opacity').value = opacityValue;
                document.getElementById('opacity_value').textContent = opacityValue + '%';
                currentPolygonOpacity = settings.opacity || 0.2;
            }
        } catch (error) {
            console.log('Failed to load polygon settings:', error);
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
            
            // Add ESC key listener
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
        
        // Invalidate map size after transition
        setTimeout(() => {
            if (map) {
                map.invalidateSize();
            }
        }, 100);
    }

            }
    }
    
    // Change satellite provider
    function changeSatelliteProvider(provider) {
        if (satelliteProviders[provider]) {
            console.log('Changing satellite provider to:', provider);
            
            // Remove current satellite layer if active
            if (isSatelliteView && currentSatelliteProvider && map.hasLayer(currentSatelliteProvider)) {
                map.removeLayer(currentSatelliteProvider);
            }
            
            // Set new provider
            currentSatelliteProvider = satelliteProviders[provider];
            
            // Add new provider if satellite view is active
            if (isSatelliteView) {
                currentSatelliteProvider.addTo(map);
            }
            
            // Show notification
            if (typeof toastr !== 'undefined') {
                toastr.success('Satellite provider changed to ' + provider.charAt(0).toUpperCase() + provider.slice(1));
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

    // Toggle satellite view
    function toggleSatellite() {
        const satelliteBtn = document.getElementById('satellite-btn');
        
        try {
            if (!isSatelliteView) {
                // Switch to satellite
                console.log('Switching to satellite view...');
                
                if (osmLayer && map.hasLayer(osmLayer)) {
                    map.removeLayer(osmLayer);
                }
                
                // Try to add satellite layer
                if (currentSatelliteProvider) {
                    try {
                        currentSatelliteProvider.addTo(map);
                        console.log('Satellite layer added successfully');
                    } catch (error) {
                        console.error('Error adding satellite layer:', error);
                        // Fallback to Esri if Google fails
                        if (currentSatelliteProvider !== satelliteProviders.esri) {
                            currentSatelliteProvider = satelliteProviders.esri;
                            currentSatelliteProvider.addTo(map);
                        }
                    }
                }
                
                satelliteBtn.innerHTML = '<i class="fas fa-map"></i>';
                satelliteBtn.title = 'Street Map';
                satelliteBtn.classList.remove('btn-light');
                satelliteBtn.classList.add('btn-info');
                isSatelliteView = true;
                
                // Show notification and update status
                const statusElement = document.getElementById('satellite-status');
                if (statusElement) {
                    statusElement.innerHTML = '<i class="fas fa-satellite text-info"></i> Satellite Active';
                    statusElement.style.display = 'block';
                    setTimeout(() => { statusElement.style.display = 'none'; }, 3000);
                }
                
                if (typeof toastr !== 'undefined') {
                    toastr.info('Mode Satelit Aktif');
                }
                
            } else {
                // Switch to street map
                console.log('Switching to street view...');
                
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
                
                // Show notification and update status
                const statusElement = document.getElementById('satellite-status');
                if (statusElement) {
                    statusElement.innerHTML = '<i class="fas fa-map text-success"></i> Street Map Active';
                    statusElement.style.display = 'block';
                    setTimeout(() => { statusElement.style.display = 'none'; }, 3000);
                }
                
                if (typeof toastr !== 'undefined') {
                    toastr.info('Mode Street Map Aktif');
                }
            }
            
            // Force map to refresh
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
            
        } catch (error) {
            console.error('Error in toggleSatellite:', error);
            alert('Terjadi kesalahan saat mengganti mode peta');
        }
    }

    // Place marker on map click
    function placeMarker(latlng) {
        marker.setLatLng(latlng);
        map.setView(latlng, map.getZoom());
        updateCoordinates(latlng.lat, latlng.lng);
    }

    // Update coordinate inputs
    function updateCoordinates(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
        
        // Reverse geocoding to get address using Nominatim (OpenStreetMap)
        reverseGeocode(lat, lng);
    }

    // Update map when coordinates are manually entered
    function updateMapFromInputs() {
        const lat = parseFloat(document.getElementById('latitude').value);
        const lng = parseFloat(document.getElementById('longitude').value);
        
        if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            const position = [lat, lng];
            marker.setLatLng(position);
            map.setView(position, map.getZoom());
        }
    }

    // Reverse geocoding using Nominatim API
    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.display_name) {
                const addressField = document.getElementById('address');
                if (addressField && !addressField.value) {
                    addressField.value = data.display_name;
                }
            }
        })
        .catch(error => {
            console.log('Reverse geocoding failed:', error);
        });
    }    // Get current location function
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const pos = [position.coords.latitude, position.coords.longitude];
                
                // Update form inputs
                document.getElementById('latitude').value = position.coords.latitude.toFixed(8);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(8);
                
                // Update map
                map.setView(pos, 15);
                marker.setLatLng(pos);
                
                // Get address
                reverseGeocode(position.coords.latitude, position.coords.longitude);
                
            }, function(error) {
                let errorMsg = "Error getting location: ";
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMsg += "User denied geolocation request.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMsg += "Location information unavailable.";
                        break;
                    case error.TIMEOUT:
                        errorMsg += "Location request timed out.";
                        break;
                    default:
                        errorMsg += "Unknown error occurred.";
                        break;
                }
                alert(errorMsg);
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    // Preview image function
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create or update image preview
                let preview = document.getElementById('image-preview');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.id = 'image-preview';
                    preview.className = 'img-thumbnail mt-2';
                    preview.style.maxWidth = '200px';
                    document.getElementById('image').parentNode.appendChild(preview);
                }
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Check if Leaflet map initialized successfully
    setTimeout(function() {
        if (!map) {
            console.warn('Map failed to initialize');
            showMapError();
        }
    }, 5000); // 5 second timeout
    
    // Add button to get current location
    document.addEventListener('DOMContentLoaded', function() {
        const latitudeField = document.getElementById('latitude');
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'mt-2';
        
        const currentLocationBtn = document.createElement('button');
        currentLocationBtn.type = 'button';
        currentLocationBtn.className = 'btn btn-sm btn-info mr-2';
        currentLocationBtn.innerHTML = '<i class="fas fa-location-arrow"></i> Lokasi Saat Ini';
        currentLocationBtn.onclick = getCurrentLocation;
        
        const clearLocationBtn = document.createElement('button');
        clearLocationBtn.type = 'button';
        clearLocationBtn.className = 'btn btn-sm btn-secondary';
        clearLocationBtn.innerHTML = '<i class="fas fa-times"></i> Hapus Koordinat';
        clearLocationBtn.onclick = function() {
            document.getElementById('latitude').value = '';
            document.getElementById('longitude').value = '';
            // Reset map to default location
            const defaultLocation = [-6.2088, 106.8456];
            map.setView(defaultLocation, 13);
            marker.setLatLng(defaultLocation);
        };
        
        buttonContainer.appendChild(currentLocationBtn);
        buttonContainer.appendChild(clearLocationBtn);
        latitudeField.parentNode.appendChild(buttonContainer);
    });

    // Address search functionality using Nominatim
    function searchAddress() {
        const addressField = document.getElementById('address');
        const address = addressField.value.trim();
        
        if (!address) {
            alert('Masukkan alamat terlebih dahulu');
            return;
        }

        // Show loading
        const searchBtn = document.getElementById('search-address-btn');
        const originalText = searchBtn.innerHTML;
        searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
        searchBtn.disabled = true;

        // Search using Nominatim API
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);
                
                // Update map and marker
                map.setView([lat, lng], 15);
                marker.setLatLng([lat, lng]);
                
                // Update coordinate inputs
                updateCoordinates(lat, lng);
                
                alert('Lokasi ditemukan!');
            } else {
                alert('Alamat tidak ditemukan. Coba gunakan alamat yang lebih spesifik.');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            alert('Terjadi kesalahan saat mencari alamat. Silakan coba lagi.');
        })
        .finally(() => {
            // Reset button
            searchBtn.innerHTML = originalText;
            searchBtn.disabled = false;
        });
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Add address search functionality
        const addressField = document.getElementById('address');
        if (addressField) {
            // Add search button for address
            const searchButton = document.createElement('button');
            searchButton.type = 'button';
            searchButton.id = 'search-address-btn';
            searchButton.className = 'btn btn-sm btn-success mt-2';
            searchButton.innerHTML = '<i class="fas fa-search"></i> Cari di Peta';
            searchButton.onclick = searchAddress;
            addressField.parentNode.appendChild(searchButton);
        }

        // Add area measurement controls
        const areaSizeField = document.getElementById('area_size');
        if (areaSizeField) {
            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'mt-2';
            
            const clearAreaBtn = document.createElement('button');
            clearAreaBtn.type = 'button';
            clearAreaBtn.className = 'btn btn-sm btn-warning mr-2';
            clearAreaBtn.innerHTML = '<i class="fas fa-eraser"></i> Hapus Pengukuran';
            clearAreaBtn.onclick = clearAreaMeasurement;
            
            const saveColorBtn = document.createElement('button');
            saveColorBtn.type = 'button';
            saveColorBtn.className = 'btn btn-sm btn-success';
            saveColorBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Warna';
            saveColorBtn.onclick = savePolygonSettings;
            
            buttonContainer.appendChild(clearAreaBtn);
            buttonContainer.appendChild(saveColorBtn);
            areaSizeField.parentNode.appendChild(buttonContainer);
        }

        // Load saved polygon settings
        loadPolygonSettings();

        // Simple and reliable map initialization
        console.log('Starting map initialization...');
        
        let initAttempts = 0;
        const maxInitAttempts = 10;
        
        function tryInitMap() {
            initAttempts++;
            console.log('Map init attempt:', initAttempts);
            
            if (typeof L !== 'undefined') {
                console.log('Leaflet available, initializing map...');
                try {
                    initMap();
                    console.log('Map initialized successfully!');
                } catch (error) {
                    console.error('Full map init failed:', error);
                    console.log('Trying simple map...');
                    try {
                        initSimpleMap();
                        console.log('Simple map initialized successfully!');
                    } catch (simpleError) {
                        console.error('Simple map also failed:', simpleError);
                        showMapError();
                    }
                }
            } else if (initAttempts < maxInitAttempts) {
                console.log('Leaflet not ready, waiting... (attempt ' + initAttempts + '/' + maxInitAttempts + ')');
                setTimeout(tryInitMap, 1000);
            } else {
                console.error('Leaflet failed to load after', maxInitAttempts, 'attempts');
                showMapError();
            }
        }
        
        // Start trying to initialize
        setTimeout(tryInitMap, 500);
    });
</script>
@endpush