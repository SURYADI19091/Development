@extends('backend.layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">UMKM</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Statistics Cards -->
@if(isset($stats))
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['total'] ?? 0 }}</h4>
                        <p class="mb-0">Total UMKM</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-store fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['active'] ?? 0 }}</h4>
                        <p class="mb-0">Aktif</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['verified'] ?? 0 }}</h4>
                        <p class="mb-0">Terverifikasi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-certificate fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['categories'] ?? 0 }}</h4>
                        <p class="mb-0">Kategori</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-tags fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-store mr-2"></i>Kelola UMKM
                </h5>
                <a href="{{ route('backend.umkm.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i>Tambah UMKM
                </a>
            </div>

            <div class="card-body">
                <!-- Filter & Search -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari nama bisnis atau pemilik..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-control">
                                <option value="">Semua Kategori</option>
                                <option value="kuliner" {{ request('category') == 'kuliner' ? 'selected' : '' }}>Kuliner</option>
                                <option value="kerajinan" {{ request('category') == 'kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                <option value="pertanian" {{ request('category') == 'pertanian' ? 'selected' : '' }}>Pertanian</option>
                                <option value="jasa" {{ request('category') == 'jasa' ? 'selected' : '' }}>Jasa</option>
                                <option value="perdagangan" {{ request('category') == 'perdagangan' ? 'selected' : '' }}>Perdagangan</option>
                                <option value="lainnya" {{ request('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                            <select name="verified" class="form-control">
                                <option value="">Semua Verifikasi</option>
                                <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Terverifikasi</option>
                                <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Belum Verifikasi</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('backend.umkm.index') }}" class="btn btn-outline-secondary btn-block">
                                <i class="fas fa-refresh"></i>
                            </a>
                        </div>
                    </div>
                </form>

                <!-- UMKM Table -->
                @if($umkms->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Nama Bisnis</th>
                                    <th>Pemilik</th>
                                    <th>Kategori</th>
                                    <th>Lokasi</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($umkms as $umkm)
                                <tr>
                                    <td>
                                        @if($umkm->logo_path)
                                            <img src="{{ Storage::url($umkm->logo_path) }}" 
                                                 class="rounded" width="40" height="40" 
                                                 style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-store text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $umkm->business_name }}</strong>
                                        @if($umkm->is_verified)
                                            <i class="fas fa-check-circle text-success ml-1" title="Terverifikasi"></i>
                                        @endif
                                    </td>
                                    <td>{{ $umkm->owner_name }}</td>
                                    <td>
                                        <span class="badge badge-secondary">{{ ucfirst($umkm->category) }}</span>
                                    </td>
                                    <td>
                                        @if($umkm->settlement)
                                            {{ $umkm->settlement->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($umkm->rating > 0)
                                            <div class="d-flex align-items-center">
                                                <span class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $umkm->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </span>
                                                <small class="ml-1 text-muted">({{ $umkm->total_reviews }})</small>
                                            </div>
                                        @else
                                            <span class="text-muted">Belum ada rating</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($umkm->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('backend.umkm.show', $umkm) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('backend.umkm.edit', $umkm) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="deleteUmkm({{ $umkm->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $umkms->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-store text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 text-muted">Belum Ada UMKM</h5>
                        <p class="text-muted">Mulai tambahkan data UMKM di desa Anda.</p>
                        <a href="{{ route('backend.umkm.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>Tambah UMKM Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus UMKM ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteUmkm(id) {
    $('#deleteForm').attr('action', `{{ url('admin/umkm') }}/${id}`);
    $('#deleteModal').modal('show');
}
</script>
@endpush