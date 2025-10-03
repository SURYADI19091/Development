@extends('backend.layout.main')

@section('page_title', 'Manajemen Lokasi')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item active">Lokasi</li>
@endsection

@section('page_actions')
@can('manage.locations')
<a href="{{ route('backend.locations.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Tambah Lokasi
</a>
<button type="button" class="btn btn-info" onclick="exportLocations()">
    <i class="fas fa-download"></i> Export
</button>
@endcan
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total Lokasi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['active'] }}</h3>
                    <p>Aktif</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['on_map'] }}</h3>
                    <p>Tampil di Peta</p>
                </div>
                <div class="icon">
                    <i class="fas fa-map"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['tourism'] }}</h3>
                    <p>Wisata</p>
                </div>
                <div class="icon">
                    <i class="fas fa-camera"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Lokasi</h3>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Cari lokasi..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="type" class="form-control">
                                    <option value="">Semua Tipe</option>
                                    <option value="office" {{ request('type') == 'office' ? 'selected' : '' }}>Pemerintahan</option>
                                    <option value="school" {{ request('type') == 'school' ? 'selected' : '' }}>Pendidikan</option>
                                    <option value="health" {{ request('type') == 'health' ? 'selected' : '' }}>Kesehatan</option>
                                    <option value="religious" {{ request('type') == 'religious' ? 'selected' : '' }}>Tempat Ibadah</option>
                                    <option value="commercial" {{ request('type') == 'commercial' ? 'selected' : '' }}>Perdagangan</option>
                                    <option value="public" {{ request('type') == 'public' ? 'selected' : '' }}>Fasilitas Umum</option>
                                    <option value="tourism" {{ request('type') == 'tourism' ? 'selected' : '' }}>Wisata</option>
                                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="is_active" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('backend.locations.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="60px">Foto</th>
                                    <th>Nama</th>
                                    <th>Tipe</th>
                                    <th>Alamat</th>
                                    <th>Kontak</th>
                                    <th>Status</th>
                                    <th width="150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($locations as $location)
                                    <tr>
                                        <td>
                                            @if($location->image_path)
                                                <img src="{{ asset($location->image_path) }}" alt="{{ $location->name }}" 
                                                     class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $location->name }}</strong>
                                            @if($location->show_on_map)
                                                <br><small class="text-success"><i class="fas fa-map"></i> Tampil di Peta</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $location->type_name }}</span>
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($location->address, 50) }}</small>
                                            @if($location->latitude && $location->longitude)
                                                <br><a href="https://maps.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}" 
                                                      target="_blank" class="text-info">
                                                    <i class="fas fa-map-marker-alt"></i> Lihat Peta
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($location->phone)
                                                <small><i class="fas fa-phone"></i> {{ $location->phone }}</small><br>
                                            @endif
                                            @if($location->email)
                                                <small><i class="fas fa-envelope"></i> {{ $location->email }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $location->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $location->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('backend.locations.show', $location) }}" 
                                                   class="btn btn-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @can('manage.locations')
                                                <a href="{{ route('backend.locations.edit', $location) }}" 
                                                   class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-{{ $location->is_active ? 'secondary' : 'success' }}" 
                                                        onclick="toggleStatus({{ $location->id }})" 
                                                        title="{{ $location->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas fa-{{ $location->is_active ? 'times' : 'check' }}"></i>
                                                </button>
                                                <button class="btn btn-danger" 
                                                        onclick="deleteLocation({{ $location->id }})" 
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <p class="text-muted">Tidak ada data lokasi.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($locations->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $locations->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function exportLocations() {
        const params = new URLSearchParams(window.location.search);
        window.open(`{{ route('backend.locations.export') }}?${params.toString()}`, '_blank');
    }

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

    function deleteLocation(id) {
        if (confirm('Apakah Anda yakin ingin menghapus lokasi ini?')) {
            fetch(`{{ url('admin/locations') }}/${id}`, {
                method: 'DELETE',
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
                alert('Terjadi kesalahan saat menghapus lokasi');
            });
        }
    }
</script>
@endpush