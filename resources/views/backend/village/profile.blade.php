@extends('backend.layout.main')

@section('page_title', 'Profil Desa')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item active">Profil Desa</li>
@endsection

@section('page_actions')
<div class="btn-group">
    @can('manage.village_profile')
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">
        <i class="fas fa-edit"></i> Edit Profil Desa
    </button>
    @endcan
</div>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i>Terdapat kesalahan dalam form:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <!-- Profil Desa Card -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-home mr-2"></i>Informasi Desa
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Nama Desa</strong></td>
                            <td>: {{ $profile->village_name ?? 'Belum diisi' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kode Desa</strong></td>
                            <td>: {{ $profile->village_code ?? 'Belum diisi' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kecamatan</strong></td>
                            <td>: {{ $profile->district ?? 'Belum diisi' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kabupaten</strong></td>
                            <td>: {{ $profile->regency ?? 'Belum diisi' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Provinsi</strong></td>
                            <td>: {{ $profile->province ?? 'Belum diisi' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kode Pos</strong></td>
                            <td>: {{ $profile->postal_code ?? 'Belum diisi' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Luas Wilayah</strong></td>
                            <td>: {{ $profile->area_size ?? 'Belum diisi' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah RW</strong></td>
                            <td>: {{ $profile->total_rw ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah RT</strong></td>
                            <td>: {{ $profile->total_rt ?? 0 }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kontak & Lokasi -->
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map-marker-alt mr-2"></i>Kontak & Lokasi
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Alamat</strong></td>
                            <td>: {{ $profile->address ?? 'Belum diisi' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Telepon</strong></td>
                            <td>: {{ $profile->phone ?? 'Belum diisi' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>: {{ $profile->email ?? 'Belum diisi' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Website</strong></td>
                            <td>: {{ $profile->website ?? 'Belum diisi' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Koordinat</strong></td>
                            <td>: 
                                @if($profile->latitude && $profile->longitude)
                                    {{ $profile->latitude }}, {{ $profile->longitude }}
                                @else
                                    Belum diisi
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if($profile->latitude && $profile->longitude)
                    <div class="mt-3">
                        <a href="https://maps.google.com/maps?q={{ $profile->latitude }},{{ $profile->longitude }}" 
                           target="_blank" class="btn btn-success btn-sm">
                            <i class="fas fa-map"></i> Lihat di Google Maps
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Visi Misi -->
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye mr-2"></i>Visi
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-justify">
                        {{ $profile->vision ?? 'Visi desa belum diisi.' }}
                    </p>
                </div>
            </div>

            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bullseye mr-2"></i>Misi
                    </h3>
                </div>
                <div class="card-body">
                    @if($profile->mission)
                        <div class="text-justify">
                            {!! nl2br(e($profile->mission)) !!}
                        </div>
                    @else
                        <p class="text-muted">Misi desa belum diisi.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Deskripsi & Sejarah -->
        <div class="col-md-6">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>Deskripsi Desa
                    </h3>
                </div>
                <div class="card-body">
                    @if($profile->description)
                        <div class="text-justify">
                            {!! nl2br(e($profile->description)) !!}
                        </div>
                    @else
                        <p class="text-muted">Deskripsi desa belum diisi.</p>
                    @endif
                </div>
            </div>

            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i>Sejarah Desa
                    </h3>
                </div>
                <div class="card-body">
                    @if($profile->history)
                        <div class="text-justify">
                            {!! nl2br(e($profile->history)) !!}
                        </div>
                    @else
                        <p class="text-muted">Sejarah desa belum diisi.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
@can('manage.village_profile')
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('backend.village.update-profile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">
                        <i class="fas fa-edit mr-2"></i>Edit Profil Desa
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Informasi Dasar -->
                        <div class="col-md-6">
                            <h6 class="text-primary">Informasi Dasar</h6>
                            
                            <div class="form-group">
                                <label for="name">Nama Desa *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $profile->village_name ?? '') }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="code">Kode Desa</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code', $profile->village_code ?? '') }}">
                                @error('code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="district">Kecamatan *</label>
                                <input type="text" class="form-control @error('district') is-invalid @enderror" 
                                       id="district" name="district" value="{{ old('district', $profile->district ?? '') }}" required>
                                @error('district')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="regency">Kabupaten *</label>
                                <input type="text" class="form-control @error('regency') is-invalid @enderror" 
                                       id="regency" name="regency" value="{{ old('regency', $profile->regency ?? '') }}" required>
                                @error('regency')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="province">Provinsi *</label>
                                <input type="text" class="form-control @error('province') is-invalid @enderror" 
                                       id="province" name="province" value="{{ old('province', $profile->province ?? '') }}" required>
                                @error('province')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="postal_code">Kode Pos</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}">
                                @error('postal_code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Kontak & Detail -->
                        <div class="col-md-6">
                            <h6 class="text-info">Kontak & Detail</h6>
                            
                            <div class="form-group">
                                <label for="address">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3">{{ old('address', $profile->address ?? '') }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $profile->phone ?? '') }}">
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $profile->email ?? '') }}">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="website">Website</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                       id="website" name="website" value="{{ old('website', $profile->website ?? '') }}">
                                @error('website')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="latitude">Latitude</label>
                                        <input type="text" class="form-control @error('latitude') is-invalid @enderror" 
                                               id="latitude" name="latitude" value="{{ old('latitude', $profile->latitude ?? '') }}" 
                                               placeholder="-6.200000">
                                        @error('latitude')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="longitude">Longitude</label>
                                        <input type="text" class="form-control @error('longitude') is-invalid @enderror" 
                                               id="longitude" name="longitude" value="{{ old('longitude', $profile->longitude ?? '') }}"
                                               placeholder="106.816666">
                                        @error('longitude')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center mb-3">
                                <button type="button" class="btn btn-info btn-sm" id="getLocationBtn">
                                    <i class="fas fa-map-marker-alt mr-1"></i>Dapatkan Koordinat Otomatis
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="area">Luas Wilayah</label>
                                <input type="text" class="form-control @error('area') is-invalid @enderror" 
                                       id="area" name="area" value="{{ old('area', $profile->area_size ?? '') }}" 
                                       placeholder="contoh: 15.5 km²">
                                @error('area')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="total_rw">Jumlah RW</label>
                                <input type="number" class="form-control @error('total_rw') is-invalid @enderror" 
                                       id="total_rw" name="total_rw" value="{{ old('total_rw', $profile->total_rw ?? 0) }}" min="0">
                                @error('total_rw')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="total_rt">Jumlah RT</label>
                                <input type="number" class="form-control @error('total_rt') is-invalid @enderror" 
                                       id="total_rt" name="total_rt" value="{{ old('total_rt', $profile->total_rt ?? 0) }}" min="0">
                                @error('total_rt')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="vision">Visi Desa</label>
                        <textarea class="form-control @error('vision') is-invalid @enderror" 
                                  id="vision" name="vision" rows="3">{{ old('vision', $profile->vision ?? '') }}</textarea>
                        @error('vision')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="mission">Misi Desa</label>
                        <textarea class="form-control @error('mission') is-invalid @enderror" 
                                  id="mission" name="mission" rows="4">{{ old('mission', $profile->mission ?? '') }}</textarea>
                        @error('mission')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi Desa</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description', $profile->description ?? '') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="history">Sejarah Desa</label>
                        <textarea class="form-control @error('history') is-invalid @enderror" 
                                  id="history" name="history" rows="4">{{ old('history', $profile->history ?? '') }}</textarea>
                        @error('history')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-populate coordinates if available
    $('#getLocationBtn').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Mendapatkan Lokasi...');
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                $('#latitude').val(position.coords.latitude.toFixed(8));
                $('#longitude').val(position.coords.longitude.toFixed(8));
                
                btn.prop('disabled', false).html('<i class="fas fa-map-marker-alt mr-1"></i>Dapatkan Koordinat Otomatis');
                
                toastr.success('Koordinat berhasil didapatkan!');
            }, function(error) {
                btn.prop('disabled', false).html('<i class="fas fa-map-marker-alt mr-1"></i>Dapatkan Koordinat Otomatis');
                
                let errorMessage = 'Gagal mendapatkan lokasi.';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Akses lokasi ditolak oleh pengguna.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Informasi lokasi tidak tersedia.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'Request timeout untuk mendapatkan lokasi.';
                        break;
                }
                
                toastr.error(errorMessage);
            });
        } else {
            btn.prop('disabled', false).html('<i class="fas fa-map-marker-alt mr-1"></i>Dapatkan Koordinat Otomatis');
            toastr.error('Browser Anda tidak mendukung fitur geolokasi.');
        }
    });

    // Form validation and submission
    $('#editProfileModal form').on('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const requiredFields = $(this).find('[required]');
        
        // Reset validation states
        $('.is-invalid').removeClass('is-invalid');
        
        // Check required fields
        requiredFields.each(function() {
            if (!$(this).val() || $(this).val().trim() === '') {
                $(this).addClass('is-invalid');
                isValid = false;
            }
        });

        if (isValid) {
            // Show loading state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');
            
            // Submit form
            this.submit();
        } else {
            toastr.error('Mohon lengkapi semua field yang wajib diisi.');
        }
    });

    // Auto close modal on success
    @if(session('success'))
        $('#editProfileModal').modal('hide');
    @endif
    
    // Clear form validation on modal show
    $('#editProfileModal').on('show.bs.modal', function() {
        $(this).find('.is-invalid').removeClass('is-invalid');
    });

    // Real-time validation
    $('input[required], textarea[required]').on('input blur', function() {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });
});
</script>
@endpush