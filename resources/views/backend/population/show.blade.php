@extends('backend.layout.main')

@section('title', 'Detail Data Penduduk')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Data Penduduk</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('backend.population.index') }}">Data Penduduk</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Profile Card -->
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <div class="profile-user-img-container mb-3">
                                <div class="profile-user-img bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 128px; height: 128px; margin: 0 auto; font-size: 48px; color: white;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <h3 class="profile-username text-center">{{ $population->name }}</h3>
                            <p class="text-muted text-center">
                                NIK: {{ $population->identity_card_number }}
                            </p>
                        </div>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Jenis Kelamin</b>
                                <span class="float-right">
                                    @if($population->gender == 'M')
                                        <span class="badge badge-primary">Laki-laki</span>
                                    @else
                                        <span class="badge badge-pink">Perempuan</span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>Umur</b> <span class="float-right">{{ $population->age }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b> 
                                <span class="float-right">
                                    @switch($population->marital_status)
                                        @case('Single')
                                            <span class="badge badge-info">Belum Menikah</span>
                                            @break
                                        @case('Married')
                                            <span class="badge badge-success">Menikah</span>
                                            @break
                                        @case('Divorced')
                                            <span class="badge badge-warning">Cerai Hidup</span>
                                            @break
                                        @case('Widowed')
                                            <span class="badge badge-secondary">Cerai Mati</span>
                                            @break
                                    @endswitch
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>RT/RW</b> 
                                <span class="float-right">{{ $population->settlement->name ?? 'Tidak ada' }}</span>
                            </li>
                        </ul>

                        @can('edit-population')
                        <div class="text-center">
                            <a href="{{ route('backend.population.edit', $population->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit mr-1"></i> Edit Data
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Details Card -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active" href="#personal" data-toggle="tab">
                                    <i class="fas fa-user mr-1"></i> Data Pribadi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#family" data-toggle="tab">
                                    <i class="fas fa-users mr-1"></i> Data Keluarga
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#address" data-toggle="tab">
                                    <i class="fas fa-map-marker-alt mr-1"></i> Data Alamat
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Personal Data Tab -->
                            <div class="active tab-pane" id="personal">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">No. Urut</label>
                                            <p class="form-control-static">{{ $population->serial_number }}</p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">NIK</label>
                                            <p class="form-control-static">{{ $population->identity_card_number }}</p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Nama Lengkap</label>
                                            <p class="form-control-static">{{ $population->name }}</p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Tempat, Tanggal Lahir</label>
                                            <p class="form-control-static">
                                                {{ $population->birth_place }}, 
                                                {{ \Carbon\Carbon::parse($population->birth_date)->format('d F Y') }}
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Umur</label>
                                            <p class="form-control-static">{{ $population->age }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Jenis Kelamin</label>
                                            <p class="form-control-static">
                                                @if($population->gender == 'M')
                                                    <span class="badge badge-primary px-3 py-2">
                                                        <i class="fas fa-male mr-1"></i> Laki-laki
                                                    </span>
                                                @else
                                                    <span class="badge badge-pink px-3 py-2">
                                                        <i class="fas fa-female mr-1"></i> Perempuan
                                                    </span>
                                                @endif
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Status Perkawinan</label>
                                            <p class="form-control-static">
                                                @switch($population->marital_status)
                                                    @case('Single')
                                                        <span class="badge badge-info px-3 py-2">Belum Menikah</span>
                                                        @break
                                                    @case('Married')
                                                        <span class="badge badge-success px-3 py-2">Menikah</span>
                                                        @break
                                                    @case('Divorced')
                                                        <span class="badge badge-warning px-3 py-2">Cerai Hidup</span>
                                                        @break
                                                    @case('Widowed')
                                                        <span class="badge badge-secondary px-3 py-2">Cerai Mati</span>
                                                        @break
                                                @endswitch
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Agama</label>
                                            <p class="form-control-static">
                                                <span class="badge badge-outline-primary px-3 py-2">
                                                    {{ $population->religion }}
                                                </span>
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Pekerjaan</label>
                                            <p class="form-control-static">{{ $population->occupation }}</p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Jenis Tinggal</label>
                                            <p class="form-control-static">
                                                <span class="badge badge-outline-secondary px-3 py-2">
                                                    {{ $population->residence_type }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Family Data Tab -->
                            <div class="tab-pane" id="family">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">No. KK</label>
                                            <p class="form-control-static">{{ $population->family_card_number }}</p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Hubungan dengan Kepala Keluarga</label>
                                            <p class="form-control-static">
                                                <span class="badge badge-primary px-3 py-2">
                                                    {{ $population->family_relationship }}
                                                </span>
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Nama Kepala Keluarga</label>
                                            <p class="form-control-static">{{ $population->head_of_family }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Kepala Keluarga Mandiri</label>
                                            <p class="form-control-static">{{ $population->independent_family_head }}</p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">RT/RW</label>
                                            <p class="form-control-static">
                                                @if($population->settlement)
                                                    <span class="badge badge-success px-3 py-2">
                                                        <i class="fas fa-home mr-1"></i>
                                                        {{ $population->settlement->name }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary px-3 py-2">Tidak ada</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Data Tab -->
                            <div class="tab-pane" id="address">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Alamat Lengkap</label>
                                            <div class="border p-3 rounded bg-light">
                                                {{ $population->address }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Kecamatan</label>
                                            <p class="form-control-static">{{ $population->district }}</p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Kabupaten</label>
                                            <p class="form-control-static">{{ $population->regency }}</p>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">Provinsi</label>
                                            <p class="form-control-static">{{ $population->province }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Map placeholder -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card bg-light">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-map-marked-alt mr-2"></i>Lokasi
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-center py-5">
                                                    <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">Peta Lokasi</h5>
                                                    <p class="text-muted mb-0">
                                                        {{ $population->address }}<br>
                                                        {{ $population->district }}, {{ $population->regency }}, {{ $population->province }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Dibuat: {{ $population->created_at ? $population->created_at->format('d F Y H:i') : 'Tidak ada' }}
                                </small><br>
                                <small class="text-muted">
                                    <i class="fas fa-edit mr-1"></i>
                                    Diperbarui: {{ $population->updated_at ? $population->updated_at->format('d F Y H:i') : 'Tidak ada' }}
                                </small>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('backend.population.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                                
                                @can('edit-population')
                                <a href="{{ route('backend.population.edit', $population->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit mr-2"></i>Edit Data
                                </a>
                                @endcan

                                @can('delete-population')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $population->id }})">
                                    <i class="fas fa-trash mr-2"></i>Hapus
                                </button>
                                @endcan

                                <button type="button" class="btn btn-info" onclick="window.print()">
                                    <i class="fas fa-print mr-2"></i>Cetak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@can('delete-population')
<!-- Delete Form (hidden) -->
<form id="delete-form-{{ $population->id }}" action="{{ route('backend.population.destroy', $population->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endcan
@endsection

@push('styles')
<style>
    .badge-pink {
        background-color: #e91e63;
        color: white;
    }
    
    .badge-outline-primary {
        border: 1px solid #007bff;
        color: #007bff;
        background: transparent;
    }
    
    .badge-outline-secondary {
        border: 1px solid #6c757d;
        color: #6c757d;
        background: transparent;
    }
    
    .form-control-static {
        min-height: 38px;
        padding: 8px 12px;
        margin-bottom: 0;
        font-size: 14px;
        line-height: 1.42857143;
    }
    
    .profile-user-img {
        border: 3px solid #adb5bd;
        margin: 0 auto;
    }
    
    @media print {
        .btn, .nav-pills, .breadcrumb, .content-header {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .tab-content > .tab-pane {
            display: block !important;
            opacity: 1 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
@can('delete-population')
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data penduduk ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
@endcan
</script>
@endpush