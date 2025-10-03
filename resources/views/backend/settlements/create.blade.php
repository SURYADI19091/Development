@extends('backend.layout.main')

@section('page_title', 'Tambah Settlement')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.settlements.index') }}">Settlement</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Settlement</h3>
                </div>
                
                <form action="{{ route('backend.settlements.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Settlement *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="code">Kode Settlement</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code') }}" placeholder="Contoh: ST001">
                                    @error('code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="type">Tipe Settlement *</label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="Village" {{ old('type') == 'Village' ? 'selected' : '' }}>Village</option>
                                        <option value="Urban" {{ old('type') == 'Urban' ? 'selected' : '' }}>Urban</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" placeholder="Deskripsi settlement...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- RT/RW Information -->
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="neighborhood_number">Nomor RT *</label>
                                            <input type="text" class="form-control @error('neighborhood_number') is-invalid @enderror" 
                                                   id="neighborhood_number" name="neighborhood_number" value="{{ old('neighborhood_number') }}" 
                                                   placeholder="001" maxlength="10" required>
                                            @error('neighborhood_number')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="community_number">Nomor RW *</label>
                                            <input type="text" class="form-control @error('community_number') is-invalid @enderror" 
                                                   id="community_number" name="community_number" value="{{ old('community_number') }}" 
                                                   placeholder="001" maxlength="10" required>
                                            @error('community_number')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="neighborhood_name">Nama RT *</label>
                                    <input type="text" class="form-control @error('neighborhood_name') is-invalid @enderror" 
                                           id="neighborhood_name" name="neighborhood_name" value="{{ old('neighborhood_name') }}" 
                                           placeholder="Contoh: RT 001" required>
                                    @error('neighborhood_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="community_name">Nama RW *</label>
                                    <input type="text" class="form-control @error('community_name') is-invalid @enderror" 
                                           id="community_name" name="community_name" value="{{ old('community_name') }}" 
                                           placeholder="Contoh: RW 001" required>
                                    @error('community_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Hamlet Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hamlet_name">Nama Dusun *</label>
                                    <input type="text" class="form-control @error('hamlet_name') is-invalid @enderror" 
                                           id="hamlet_name" name="hamlet_name" value="{{ old('hamlet_name') }}" required>
                                    @error('hamlet_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="hamlet_leader">Kepala Dusun *</label>
                                    <input type="text" class="form-control @error('hamlet_leader') is-invalid @enderror" 
                                           id="hamlet_leader" name="hamlet_leader" value="{{ old('hamlet_leader') }}" required>
                                    @error('hamlet_leader')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="district">Kecamatan *</label>
                                    <input type="text" class="form-control @error('district') is-invalid @enderror" 
                                           id="district" name="district" value="{{ old('district', 'Krandegan') }}" required>
                                    @error('district')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="regency">Kabupaten *</label>
                                    <input type="text" class="form-control @error('regency') is-invalid @enderror" 
                                           id="regency" name="regency" value="{{ old('regency', 'Banyumas') }}" required>
                                    @error('regency')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="province">Provinsi *</label>
                                    <input type="text" class="form-control @error('province') is-invalid @enderror" 
                                           id="province" name="province" value="{{ old('province', 'Jawa Tengah') }}" required>
                                    @error('province')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Additional Information -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="area_size">Luas Area (Ha)</label>
                                    <input type="number" class="form-control @error('area_size') is-invalid @enderror" 
                                           id="area_size" name="area_size" value="{{ old('area_size') }}" 
                                           step="0.01" min="0">
                                    @error('area_size')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="population">Jumlah Penduduk</label>
                                    <input type="number" class="form-control @error('population') is-invalid @enderror" 
                                           id="population" name="population" value="{{ old('population', 0) }}" min="0">
                                    @error('population')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="postal_code">Kode Pos</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                           id="postal_code" name="postal_code" value="{{ old('postal_code') }}" 
                                           maxlength="10" placeholder="53194">
                                    @error('postal_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="is_active">Status</label>
                                    <select class="form-control @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('is_active')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Coordinates -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude">Latitude</label>
                                    <input type="number" class="form-control @error('latitude') is-invalid @enderror" 
                                           id="latitude" name="latitude" value="{{ old('latitude') }}" 
                                           step="0.00000001" min="-90" max="90" placeholder="-7.123456">
                                    @error('latitude')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude">Longitude</label>
                                    <input type="number" class="form-control @error('longitude') is-invalid @enderror" 
                                           id="longitude" name="longitude" value="{{ old('longitude') }}" 
                                           step="0.00000001" min="-180" max="180" placeholder="109.234567">
                                    @error('longitude')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('backend.settlements.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection