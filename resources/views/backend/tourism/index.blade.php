@extends('backend.layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Objek Wisata</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-map-marker-alt mr-2"></i>Kelola Objek Wisata
                </h5>
                <a href="{{ route('backend.tourism.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i>Tambah Objek Wisata
                </a>
            </div>

            <div class="card-body">
                <!-- Filter & Search -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari nama objek wisata..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-control">
                                <option value="">Semua Kategori</option>
                                <option value="alam" {{ request('category') == 'alam' ? 'selected' : '' }}>Wisata Alam</option>
                                <option value="budaya" {{ request('category') == 'budaya' ? 'selected' : '' }}>Wisata Budaya</option>
                                <option value="sejarah" {{ request('category') == 'sejarah' ? 'selected' : '' }}>Wisata Sejarah</option>
                                <option value="religi" {{ request('category') == 'religi' ? 'selected' : '' }}>Wisata Religi</option>
                                <option value="kuliner" {{ request('category') == 'kuliner' ? 'selected' : '' }}>Wisata Kuliner</option>
                                <option value="adventure" {{ request('category') == 'adventure' ? 'selected' : '' }}>Wisata Petualangan</option>
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
                            <button type="submit" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('backend.tourism.index') }}" class="btn btn-outline-secondary btn-block">
                                <i class="fas fa-refresh"></i>
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Tourism Objects Grid -->
                @if($tourism->count() > 0)
                    <div class="row">
                        @foreach($tourism as $object)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-img-top position-relative" style="height: 200px; overflow: hidden;">
                                        @if($object->images && is_array($object->images) && count($object->images) > 0)
                                            <img src="{{ Storage::url($object->images[0]) }}" 
                                                 class="img-fluid w-100 h-100" 
                                                 style="object-fit: cover;"
                                                 alt="{{ $object->name }}">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                <i class="fas fa-mountain text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                        
                                        @if($object->is_featured)
                                            <span class="badge badge-warning position-absolute" style="top: 10px; right: 10px;">
                                                <i class="fas fa-star"></i> Unggulan
                                            </span>
                                        @endif

                                        @if(!$object->is_active)
                                            <span class="badge badge-danger position-absolute" style="top: 10px; left: 10px;">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title">{{ Str::limit($object->name, 30) }}</h6>
                                        
                                        @if($object->description)
                                            <p class="card-text text-muted small mb-2 flex-grow-1">
                                                {{ Str::limit($object->description, 80) }}
                                            </p>
                                        @endif
                                        
                                        <div class="mb-2">
                                            <span class="badge badge-info">{{ ucfirst($object->category) }}</span>
                                            @if($object->settlement)
                                                <span class="badge badge-secondary ml-1">{{ $object->settlement->name }}</span>
                                            @endif
                                        </div>

                                        @if($object->entry_fee)
                                            <div class="text-success font-weight-bold mb-2">
                                                <i class="fas fa-ticket-alt mr-1"></i>Rp {{ number_format($object->entry_fee) }}
                                            </div>
                                        @else
                                            <div class="text-success font-weight-bold mb-2">
                                                <i class="fas fa-gift mr-1"></i>Gratis
                                            </div>
                                        @endif
                                        
                                        @if($object->rating > 0)
                                            <div class="mb-2">
                                                <span class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $object->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </span>
                                                <small class="ml-1 text-muted">({{ $object->total_reviews }})</small>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-auto">
                                            <div class="btn-group btn-group-sm w-100">
                                                <a href="{{ route('backend.tourism.show', $object) }}" class="btn btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('backend.tourism.edit', $object) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="deleteTourism({{ $object->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $tourism->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-map-marker-alt text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 text-muted">Belum Ada Objek Wisata</h5>
                        <p class="text-muted">Mulai tambahkan objek wisata untuk mempromosikan desa Anda.</p>
                        <a href="{{ route('backend.tourism.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>Tambah Objek Wisata Pertama
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
                Apakah Anda yakin ingin menghapus objek wisata ini? Tindakan ini tidak dapat dibatalkan.
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
function deleteTourism(id) {
    $('#deleteForm').attr('action', `{{ url('admin/tourism') }}/${id}`);
    $('#deleteModal').modal('show');
}
</script>
@endpush