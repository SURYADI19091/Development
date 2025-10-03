@extends('backend.layout.main')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .leaflet-control-attribution {
        font-size: 10px;
    }
    .search-results {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-top: none;
        background: white;
        z-index: 1000;
        position: absolute;
        width: 100%;
    }
    .search-result-item {
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }
    .search-result-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.tourism.index') }}">Objek Wisata</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit {{ $tourism->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-edit mr-2"></i>Edit Objek Wisata
                </h5>
                <a href="{{ route('backend.tourism.show', $tourism) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('backend.tourism.update', $tourism) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name">Nama Objek Wisata <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $tourism->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="5" 
                                          placeholder="Deskripsi lengkap objek wisata">{{ old('description', $tourism->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" 
                                          placeholder="Alamat lengkap objek wisata" required>{{ old('address', $tourism->address) }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="facilities">Fasilitas</label>
                                <textarea class="form-control @error('facilities') is-invalid @enderror" 
                                          id="facilities" name="facilities" rows="3" 
                                          placeholder="Daftar fasilitas yang tersedia (pisahkan dengan koma)">{{ old('facilities', $tourism->facilities) }}</textarea>
                                <small class="form-text text-muted">Contoh: Toilet, Parkir, Musholla, Warung Makan</small>
                                @error('facilities')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Current Images -->
                            @if($tourism->images && count($tourism->images) > 0)
                                <div class="form-group">
                                    <label>Gambar Saat Ini</label>
                                    <div class="row">
                                        @foreach($tourism->images as $index => $image)
                                            <div class="col-md-6 mb-2">
                                                <div class="position-relative">
                                                    <img src="{{ Storage::url($image) }}" 
                                                         class="img-thumbnail w-100" 
                                                         style="height: 150px; object-fit: cover;">
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm position-absolute" 
                                                            style="top: 5px; right: 5px;"
                                                            onclick="removeImage({{ $index }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <input type="hidden" name="existing_images[]" value="{{ $image }}" id="existing_image_{{ $index }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="images">{{ $tourism->images && count($tourism->images) > 0 ? 'Tambah Gambar Baru' : 'Upload Foto' }}</label>
                                <input type="file" class="form-control-file @error('images') is-invalid @enderror" 
                                       id="images" name="images[]" accept="image/*" multiple>
                                <small class="form-text text-muted">Bisa pilih beberapa foto sekaligus. Format: JPG, PNG, GIF. Maksimal 2MB per foto</small>
                                @error('images')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <div id="images-preview" class="mt-2 row"></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="category">Kategori <span class="text-danger">*</span></label>
                                <select class="form-control @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="alam" {{ old('category', $tourism->category) == 'alam' ? 'selected' : '' }}>Wisata Alam</option>
                                    <option value="budaya" {{ old('category', $tourism->category) == 'budaya' ? 'selected' : '' }}>Wisata Budaya</option>
                                    <option value="sejarah" {{ old('category', $tourism->category) == 'sejarah' ? 'selected' : '' }}>Wisata Sejarah</option>
                                    <option value="religi" {{ old('category', $tourism->category) == 'religi' ? 'selected' : '' }}>Wisata Religi</option>
                                    <option value="kuliner" {{ old('category', $tourism->category) == 'kuliner' ? 'selected' : '' }}>Wisata Kuliner</option>
                                    <option value="edukasi" {{ old('category', $tourism->category) == 'edukasi' ? 'selected' : '' }}>Wisata Edukasi</option>
                                </select>
                                @error('category')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_person">Nama Kontak</label>
                                        <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                                               id="contact_person" name="contact_person" value="{{ old('contact_person', $tourism->contact_person) }}" 
                                               placeholder="Nama penanggung jawab">
                                        @error('contact_person')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_phone">Nomor Telepon</label>
                                        <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                               id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $tourism->contact_phone) }}" 
                                               placeholder="08123456789">
                                        @error('contact_phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="operating_hours">Jam Operasional</label>
                                <input type="text" class="form-control @error('operating_hours') is-invalid @enderror" 
                                       id="operating_hours" name="operating_hours" value="{{ old('operating_hours', $tourism->operating_hours) }}" 
                                       placeholder="Senin-Minggu 08:00-17:00">
                                @error('operating_hours')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="ticket_price">Harga Tiket Masuk (Rp)</label>
                                <input type="number" class="form-control @error('ticket_price') is-invalid @enderror" 
                                       id="ticket_price" name="ticket_price" value="{{ old('ticket_price', $tourism->ticket_price) }}" 
                                       step="500" min="0" placeholder="0">
                                <small class="form-text text-muted">Kosongkan atau isi 0 jika gratis</small>
                                @error('ticket_price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Lokasi GPS</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Search Location -->
                                    <div class="form-group">
                                        <label for="location_search">Cari Lokasi</label>
                                        <div class="input-group position-relative">
                                            <input type="text" class="form-control" id="location_search" 
                                                   placeholder="Ketik nama lokasi atau alamat...">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="search_btn">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <button class="btn btn-outline-info" type="button" id="current_location_btn">
                                                    <i class="fas fa-crosshairs"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-muted">Cari lokasi untuk mengisi koordinat otomatis</small>
                                    </div>

                                    <!-- Map Container -->
                                    <div class="form-group">
                                        <div id="map" style="height: 250px; width: 100%; border: 1px solid #ddd; border-radius: 4px;"></div>
                                    </div>

                                    <!-- Coordinates -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="latitude">Latitude</label>
                                                <input type="number" class="form-control @error('latitude') is-invalid @enderror" 
                                                       id="latitude" name="latitude" value="{{ old('latitude', $tourism->latitude) }}" 
                                                       step="0.0000001" placeholder="-6.123456" readonly>
                                                @error('latitude')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="longitude">Longitude</label>
                                                <input type="number" class="form-control @error('longitude') is-invalid @enderror" 
                                                       id="longitude" name="longitude" value="{{ old('longitude', $tourism->longitude) }}" 
                                                       step="0.0000001" placeholder="110.123456" readonly>
                                                @error('longitude')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <small class="text-muted">
                                        Klik pada peta atau gunakan pencarian untuk menentukan lokasi
                                    </small>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', $tourism->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Status Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('backend.tourism.show', $tourism) }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>Update Objek Wisata
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
let map, marker;
let searchTimeout;

$(document).ready(function() {
    // Initialize map with existing coordinates or default
    const lat = parseFloat($('#latitude').val()) || -6.2088;
    const lng = parseFloat($('#longitude').val()) || 106.8456;
    const zoom = ($('#latitude').val() && $('#longitude').val()) ? 15 : 10;
    
    initializeMap(lat, lng, zoom);
    
    // If existing coordinates, add marker
    if ($('#latitude').val() && $('#longitude').val()) {
        updateMapPosition(lat, lng);
    }
    
    // Multiple images preview
    $('#images').on('change', function() {
        const files = Array.from(this.files);
        const previewContainer = $('#images-preview');
        previewContainer.empty();
        
        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = $(`
                        <div class="col-md-6 mb-2">
                            <img src="${e.target.result}" class="img-thumbnail" style="max-height: 150px; width: 100%; object-fit: cover;">
                            <small class="d-block text-muted text-center mt-1">${file.name}</small>
                        </div>
                    `);
                    previewContainer.append(col);
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Location search functionality
    $('#location_search').on('input', function() {
        const query = $(this).val().trim();
        
        // Clear existing timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        
        // Remove existing search results
        $('.search-results').remove();
        
        if (query.length >= 3) {
            searchTimeout = setTimeout(() => {
                searchLocation(query);
            }, 500);
        }
    });

    // Search button click
    $('#search_btn').on('click', function() {
        const query = $('#location_search').val().trim();
        if (query) {
            searchLocation(query);
        }
    });

    // Current location button
    $('#current_location_btn').on('click', function() {
        getCurrentLocation();
    });

    // Format currency for ticket price
    $('#ticket_price').on('input', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(value);
    });

    // Allow manual editing of coordinates
    $('#latitude, #longitude').on('focus', function() {
        $(this).prop('readonly', false);
    });

    $('#latitude, #longitude').on('blur', function() {
        const lat = parseFloat($('#latitude').val());
        const lng = parseFloat($('#longitude').val());
        
        if (lat && lng && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            updateMapPosition(lat, lng);
        }
    });
});

function initializeMap(lat = -6.2088, lng = 106.8456, zoom = 10) {
    // Initialize map
    map = L.map('map').setView([lat, lng], zoom);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Add click event to map
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        updateMapPosition(lat, lng);
        updateCoordinateInputs(lat, lng);
        
        // Get address for the clicked location
        reverseGeocode(lat, lng);
    });
}

function updateMapPosition(lat, lng) {
    // Remove existing marker
    if (marker) {
        map.removeLayer(marker);
    }
    
    // Add new marker
    marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(map);
    
    // Center map on location
    map.setView([lat, lng], 15);
    
    // Add drag event to marker
    marker.on('dragend', function(e) {
        const position = e.target.getLatLng();
        updateCoordinateInputs(position.lat, position.lng);
        reverseGeocode(position.lat, position.lng);
    });
}

function updateCoordinateInputs(lat, lng) {
    $('#latitude').val(lat.toFixed(7));
    $('#longitude').val(lng.toFixed(7));
}

function searchLocation(query) {
    // Show loading
    $('#search_btn').html('<i class="fas fa-spinner fa-spin"></i>');
    
    // Use Nominatim API for geocoding
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&countrycodes=ID`)
        .then(response => response.json())
        .then(data => {
            $('#search_btn').html('<i class="fas fa-search"></i>');
            
            if (data && data.length > 0) {
                showSearchResults(data);
            } else {
                showNoResults();
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            $('#search_btn').html('<i class="fas fa-search"></i>');
            showNoResults();
        });
}

function showSearchResults(results) {
    // Remove existing results
    $('.search-results').remove();
    
    const resultsContainer = $('<div class="search-results"></div>');
    
    results.forEach(result => {
        const item = $(`
            <div class="search-result-item" data-lat="${result.lat}" data-lon="${result.lon}">
                <strong>${result.display_name.split(',')[0]}</strong><br>
                <small class="text-muted">${result.display_name}</small>
            </div>
        `);
        
        item.on('click', function() {
            const lat = parseFloat($(this).data('lat'));
            const lng = parseFloat($(this).data('lon'));
            
            updateMapPosition(lat, lng);
            updateCoordinateInputs(lat, lng);
            
            $('#location_search').val(result.display_name.split(',')[0]);
            $('.search-results').remove();
        });
        
        resultsContainer.append(item);
    });
    
    $('#location_search').closest('.position-relative').append(resultsContainer);
}

function showNoResults() {
    $('.search-results').remove();
    const noResults = $(`
        <div class="search-results">
            <div class="search-result-item text-center text-muted">
                Tidak ada hasil ditemukan
            </div>
        </div>
    `);
    $('#location_search').closest('.position-relative').append(noResults);
    
    setTimeout(() => {
        $('.search-results').fadeOut(300, function() {
            $(this).remove();
        });
    }, 2000);
}

function reverseGeocode(lat, lng) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.display_name) {
                $('#location_search').val(data.display_name.split(',')[0]);
            }
        })
        .catch(error => {
            console.error('Reverse geocoding error:', error);
        });
}

function getCurrentLocation() {
    if (navigator.geolocation) {
        $('#current_location_btn').html('<i class="fas fa-spinner fa-spin"></i>');
        
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            updateMapPosition(lat, lng);
            updateCoordinateInputs(lat, lng);
            reverseGeocode(lat, lng);
            
            $('#current_location_btn').html('<i class="fas fa-crosshairs"></i>');
        }, function(error) {
            $('#current_location_btn').html('<i class="fas fa-crosshairs"></i>');
            alert('Tidak dapat mengakses lokasi. Pastikan izin lokasi telah diberikan.');
        });
    } else {
        alert('Browser tidak mendukung geolokasi.');
    }
}

function removeImage(index) {
    if (confirm('Hapus gambar ini?')) {
        document.getElementById('existing_image_' + index).remove();
        event.target.closest('.col-md-6').remove();
    }
}

// Hide search results when clicking outside
$(document).on('click', function(e) {
    if (!$(e.target).closest('#location_search, .search-results').length) {
        $('.search-results').fadeOut(200, function() {
            $(this).remove();
        });
    }
});
</script>
@endpush