@extends('backend.layout.main')

@section('page_title', 'Edit Lokasi')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.locations.index') }}">Lokasi</a></li>
<li class="breadcrumb-item active">Edit - {{ $location->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Lokasi</h3>
                </div>
                
                <form action="{{ route('backend.locations.update', $location) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lokasi *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $location->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="type">Tipe Lokasi *</label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="office" {{ old('type', $location->type) == 'office' ? 'selected' : '' }}>Pemerintahan</option>
                                        <option value="school" {{ old('type', $location->type) == 'school' ? 'selected' : '' }}>Pendidikan</option>
                                        <option value="health" {{ old('type', $location->type) == 'health' ? 'selected' : '' }}>Kesehatan</option>
                                        <option value="religious" {{ old('type', $location->type) == 'religious' ? 'selected' : '' }}>Tempat Ibadah</option>
                                        <option value="commercial" {{ old('type', $location->type) == 'commercial' ? 'selected' : '' }}>Perdagangan</option>
                                        <option value="public" {{ old('type', $location->type) == 'public' ? 'selected' : '' }}>Fasilitas Umum</option>
                                        <option value="tourism" {{ old('type', $location->type) == 'tourism' ? 'selected' : '' }}>Wisata</option>
                                        <option value="other" {{ old('type', $location->type) == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Alamat *</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3" required>{{ old('address', $location->address) }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="latitude">Latitude</label>
                                            <input type="number" class="form-control @error('latitude') is-invalid @enderror" 
                                                   id="latitude" name="latitude" value="{{ old('latitude', $location->latitude) }}" 
                                                   step="0.00000001" min="-90" max="90">
                                            @error('latitude')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="longitude">Longitude</label>
                                            <input type="number" class="form-control @error('longitude') is-invalid @enderror" 
                                                   id="longitude" name="longitude" value="{{ old('longitude', $location->longitude) }}" 
                                                   step="0.00000001" min="-180" max="180">
                                            @error('longitude')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact & Additional Info -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $location->phone) }}">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $location->email) }}">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="icon">Icon (Font Awesome class)</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', $location->icon) }}" 
                                           placeholder="fas fa-map-marker-alt">
                                    @error('icon')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Contoh: fas fa-hospital, fas fa-school, fas fa-mosque</small>
                                </div>

                                <div class="form-group">
                                    <label for="color">Warna Marker</label>
                                    <input type="color" class="form-control @error('color') is-invalid @enderror" 
                                           id="color" name="color" value="{{ old('color', $location->color) }}">
                                    @error('color')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="sort_order">Urutan Tampil</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $location->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="image">Foto Lokasi</label>
                                    @if($location->image_path)
                                        <div class="mb-2">
                                            <img src="{{ asset($location->image_path) }}" alt="Current image" 
                                                 class="img-thumbnail" style="max-width: 150px;">
                                            <small class="d-block text-muted">Foto saat ini</small>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Format: JPG, JPEG, PNG. Max: 2MB. Kosongkan jika tidak ingin mengubah foto.</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $location->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Operating Hours -->
                        <div class="form-group">
                            <label>Jam Operasional</label>
                            <div class="row">
                                @php
                                    $operatingHours = old('operating_hours', $location->operating_hours ?? []);
                                    $days = [
                                        'monday' => 'Senin',
                                        'tuesday' => 'Selasa', 
                                        'wednesday' => 'Rabu',
                                        'thursday' => 'Kamis',
                                        'friday' => 'Jumat',
                                        'saturday' => 'Sabtu',
                                        'sunday' => 'Minggu'
                                    ];
                                @endphp
                                @foreach($days as $day => $dayName)
                                    <div class="col-md-{{ $day == 'sunday' ? '2' : ($loop->index < 6 ? '2' : '2') }} {{ $day == 'sunday' ? 'mt-2' : '' }}">
                                        <label>{{ $dayName }}</label>
                                        <input type="text" class="form-control" name="operating_hours[{{ $day }}]" 
                                               value="{{ $operatingHours[$day] ?? '' }}" 
                                               placeholder="{{ $day == 'sunday' ? 'Tutup' : '08:00 - 17:00' }}">
                                    </div>
                                @endforeach
                            </div>
                            <small class="form-text text-muted">Kosongkan jika tidak ada jam operasional tertentu</small>
                        </div>

                        <!-- Status Options -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $location->is_active) ? 'checked' : '' }}>
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
                                               {{ old('show_on_map', $location->show_on_map) ? 'checked' : '' }}>
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
                            <i class="fas fa-save"></i> Update Lokasi
                        </button>
                        <a href="{{ route('backend.locations.show', $location) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
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

@push('scripts')
<script>
    // Preview image
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

    // Get current location
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    // Add button to get current location
    document.addEventListener('DOMContentLoaded', function() {
        const latitudeField = document.getElementById('latitude');
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'btn btn-sm btn-info mt-1';
        button.innerHTML = '<i class="fas fa-location-arrow"></i> Gunakan Lokasi Saat Ini';
        button.onclick = getCurrentLocation;
        latitudeField.parentNode.appendChild(button);
    });
</script>
@endpush