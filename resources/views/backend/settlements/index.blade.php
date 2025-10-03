@extends('backend.layout.main')

@section('page_title', 'Manajemen Data Settlement (RT/RW)')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item active">Settlement</li>
@endsection

@section('page_actions')
<div class="btn-group">
    @can('manage-village-data')
    <a href="{{ route('backend.settlements.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Settlement
    </a>
    @endcan
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-map-marked-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Settlement</span>
                    <span class="info-box-number">{{ number_format($stats['total'] ?? 0) }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Aktif</span>
                    <span class="info-box-number">{{ number_format($stats['active'] ?? 0) }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Penduduk</span>
                    <span class="info-box-number">{{ number_format($stats['total_population'] ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Settlement</h3>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Cari nama, kode, atau dusun..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="type" class="form-control">
                                    <option value="">Semua Tipe</option>
                                    <option value="Village" {{ request('type') == 'Village' ? 'selected' : '' }}>Village</option>
                                    <option value="Urban" {{ request('type') == 'Urban' ? 'selected' : '' }}>Urban</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <!-- Settlement Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Settlement</th>
                                    <th>RT/RW</th>
                                    <th>Dusun</th>
                                    <th>Kepala Dusun</th>
                                    <th>Jumlah Penduduk</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settlements as $settlement)
                                    <tr>
                                        <td>{{ $settlement->code ?? '-' }}</td>
                                        <td>
                                            <strong>{{ $settlement->name }}</strong>
                                            @if($settlement->description)
                                                <br><small class="text-muted">{{ Str::limit($settlement->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">RT {{ $settlement->neighborhood_number ?? '-' }}</span>
                                            <span class="badge badge-secondary">RW {{ $settlement->community_number ?? '-' }}</span>
                                        </td>
                                        <td>{{ $settlement->hamlet_name ?? '-' }}</td>
                                        <td>{{ $settlement->hamlet_leader ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ number_format($settlement->population_data_count ?? 0) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $settlement->is_active ? 'success' : 'danger' }}">
                                                {{ $settlement->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('backend.settlements.show', $settlement->id) }}" 
                                                   class="btn btn-sm btn-info" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @can('manage-village-data')
                                                <a href="{{ route('backend.settlements.edit', $settlement->id) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-{{ $settlement->is_active ? 'secondary' : 'success' }}" 
                                                        onclick="toggleStatus({{ $settlement->id }})" 
                                                        title="{{ $settlement->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas fa-{{ $settlement->is_active ? 'times' : 'check' }}"></i>
                                                </button>
                                                <form action="{{ route('backend.settlements.destroy', $settlement->id) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus settlement ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            Data settlement tidak ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($settlements) && $settlements->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $settlements->appends(request()->query())->links() }}
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