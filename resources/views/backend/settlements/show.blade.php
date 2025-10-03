@extends('backend.layout.main')

@section('page_title', 'Detail Settlement')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.settlements.index') }}">Settlement</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('page_actions')
<div class="btn-group">
    @can('manage-village-data')
    <a href="{{ route('backend.settlements.edit', $settlement->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit
    </a>
    @endcan
    <a href="{{ route('backend.settlements.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Settlement Info -->
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <i class="fas fa-map-marked-alt fa-5x text-primary mb-3"></i>
                    </div>

                    <h3 class="profile-username text-center">{{ $settlement->name }}</h3>

                    <p class="text-muted text-center">
                        <span class="badge badge-{{ $settlement->is_active ? 'success' : 'danger' }} px-3 py-2">
                            {{ $settlement->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Kode</b> 
                            <span class="float-right">{{ $settlement->code ?? '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Tipe</b> 
                            <span class="float-right">{{ $settlement->type }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>RT/RW</b> 
                            <span class="float-right">
                                <span class="badge badge-info">RT {{ $settlement->neighborhood_number }}</span>
                                <span class="badge badge-secondary">RW {{ $settlement->community_number }}</span>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Jumlah Penduduk</b> 
                            <span class="float-right">
                                <span class="badge badge-primary">{{ number_format($settlement->population_data_count) }}</span>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Jumlah UMKM</b> 
                            <span class="float-right">
                                <span class="badge badge-success">{{ number_format($settlement->umkms_count) }}</span>
                            </span>
                        </li>

                    </ul>

                    @can('manage-village-data')
                    <div class="text-center">
                        <a href="{{ route('backend.settlements.edit', $settlement->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit mr-1"></i> Edit Data
                        </a>
                        <button type="button" class="btn btn-{{ $settlement->is_active ? 'secondary' : 'success' }} btn-sm"
                                onclick="toggleStatus({{ $settlement->id }})">
                            <i class="fas fa-{{ $settlement->is_active ? 'times' : 'check' }} mr-1"></i>
                            {{ $settlement->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </div>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#details" data-toggle="tab">
                                <i class="fas fa-info-circle mr-1"></i> Detail
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#location" data-toggle="tab">
                                <i class="fas fa-map-marker-alt mr-1"></i> Lokasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#statistics" data-toggle="tab">
                                <i class="fas fa-chart-bar mr-1"></i> Statistik
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <!-- Details Tab -->
                        <div class="active tab-pane" id="details">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Settlement</label>
                                        <p class="form-control-static">{{ $settlement->name }}</p>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Kode</label>
                                        <p class="form-control-static">{{ $settlement->code ?? '-' }}</p>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Tipe</label>
                                        <p class="form-control-static">
                                            <span class="badge badge-info px-3 py-2">{{ $settlement->type }}</span>
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <p class="form-control-static">{{ $settlement->description ?: 'Tidak ada deskripsi' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>RT/RW</label>
                                        <p class="form-control-static">
                                            <span class="badge badge-info px-3 py-2">
                                                <i class="fas fa-home mr-1"></i>
                                                RT {{ $settlement->neighborhood_number }} / RW {{ $settlement->community_number }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label>Nama RT</label>
                                        <p class="form-control-static">{{ $settlement->neighborhood_name }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label>Nama RW</label>
                                        <p class="form-control-static">{{ $settlement->community_name }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <p class="form-control-static">
                                            <span class="badge badge-{{ $settlement->is_active ? 'success' : 'danger' }} px-3 py-2">
                                                {{ $settlement->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Informasi Dusun</label>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>Nama Dusun:</strong> {{ $settlement->hamlet_name }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Kepala Dusun:</strong> {{ $settlement->hamlet_leader }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location Tab -->
                        <div class="tab-pane" id="location">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kecamatan</label>
                                        <p class="form-control-static">{{ $settlement->district }}</p>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Kabupaten</label>
                                        <p class="form-control-static">{{ $settlement->regency }}</p>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Provinsi</label>
                                        <p class="form-control-static">{{ $settlement->province }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label>Kode Pos</label>
                                        <p class="form-control-static">{{ $settlement->postal_code ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Luas Area</label>
                                        <p class="form-control-static">
                                            {{ $settlement->area_size ? number_format($settlement->area_size, 2) . ' Ha' : '-' }}
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label>Latitude</label>
                                        <p class="form-control-static">{{ $settlement->latitude ?? '-' }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label>Longitude</label>
                                        <p class="form-control-static">{{ $settlement->longitude ?? '-' }}</p>
                                    </div>

                                    @if($settlement->latitude && $settlement->longitude)
                                    <div class="form-group">
                                        <label>Koordinat</label>
                                        <p class="form-control-static">
                                            <a href="https://maps.google.com/maps?q={{ $settlement->latitude }},{{ $settlement->longitude }}" 
                                               target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                Lihat di Google Maps
                                            </a>
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Tab -->
                        <div class="tab-pane" id="statistics">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Penduduk</span>
                                            <span class="info-box-number">{{ number_format($settlement->population_data_count) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-store"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">UMKM</span>
                                            <span class="info-box-number">{{ number_format($settlement->umkms_count) }}</span>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Informasi Umum</h3>
                                        </div>
                                        <div class="card-body">
                                            <dl class="row">
                                                <dt class="col-sm-3">Jumlah Penduduk (Data):</dt>
                                                <dd class="col-sm-3">{{ number_format($settlement->population_data_count) }} orang</dd>
                                                
                                                <dt class="col-sm-3">Jumlah Penduduk (Manual):</dt>
                                                <dd class="col-sm-3">{{ number_format($settlement->population) }} orang</dd>
                                                
                                                <dt class="col-sm-3">Luas Area:</dt>
                                                <dd class="col-sm-3">{{ $settlement->area_size ? number_format($settlement->area_size, 2) . ' Ha' : '-' }}</dd>
                                                
                                                <dt class="col-sm-3">Kepadatan:</dt>
                                                <dd class="col-sm-3">
                                                    @if($settlement->area_size && $settlement->area_size > 0)
                                                        {{ number_format($settlement->population_data_count / $settlement->area_size, 2) }} orang/Ha
                                                    @else
                                                        -
                                                    @endif
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleStatus(settlementId) {
        fetch(`/admin/settlements/${settlementId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                // Show success message
                alert(data.message);
                // Reload page to update status
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status.');
        });
    }
</script>
@endpush