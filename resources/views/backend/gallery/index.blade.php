@extends('backend.layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Galeri</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-images mr-2"></i>Kelola Galeri
                </h5>
                <a href="{{ route('backend.gallery.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i>Tambah Foto
                </a>
            </div>

            <div class="card-body">
                <!-- Filter & Search -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari judul atau deskripsi..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-control">
                                <option value="">Semua Kategori</option>
                                <option value="kegiatan" {{ request('category') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                <option value="infrastruktur" {{ request('category') == 'infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                                <option value="wisata" {{ request('category') == 'wisata' ? 'selected' : '' }}>Wisata</option>
                                <option value="budaya" {{ request('category') == 'budaya' ? 'selected' : '' }}>Budaya</option>
                                <option value="lainnya" {{ request('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-3">
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
                    </div>
                </form>

                <!-- Gallery Grid -->
                @if($galleries->count() > 0)
                    <div class="row">
                        @foreach($galleries as $gallery)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="card">
                                    <div class="card-img-top position-relative" style="height: 200px; overflow: hidden;">
                                        @if($gallery->image_path)
                                            <img src="{{ Storage::url($gallery->image_path) }}" 
                                                 class="img-fluid w-100 h-100" 
                                                 style="object-fit: cover;"
                                                 alt="{{ $gallery->alt_text ?? $gallery->title }}">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                        
                                        @if($gallery->is_featured)
                                            <span class="badge badge-warning position-absolute" style="top: 10px; right: 10px;">
                                                <i class="fas fa-star"></i> Unggulan
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">{{ Str::limit($gallery->title, 30) }}</h6>
                                        
                                        @if($gallery->description)
                                            <p class="card-text text-muted small mb-2">
                                                {{ Str::limit($gallery->description, 60) }}
                                            </p>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center text-small">
                                            <div>
                                                @if($gallery->category)
                                                    <span class="badge badge-secondary">{{ ucfirst($gallery->category) }}</span>
                                                @endif
                                            </div>
                                            <div class="text-muted small">
                                                <i class="fas fa-eye"></i> {{ $gallery->views_count ?? 0 }}
                                                <i class="fas fa-heart ml-1"></i> {{ $gallery->likes_count ?? 0 }}
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2 pt-2 border-top">
                                            <div class="btn-group btn-group-sm w-100">
                                                <a href="{{ route('backend.gallery.show', $gallery) }}" class="btn btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('backend.gallery.edit', $gallery) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="deleteGallery({{ $gallery->id }})">
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
                        {{ $galleries->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-images text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 text-muted">Belum Ada Foto</h5>
                        <p class="text-muted">Mulai tambahkan foto untuk galeri desa.</p>
                        <a href="{{ route('backend.gallery.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>Tambah Foto Pertama
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
                Apakah Anda yakin ingin menghapus foto ini? Tindakan ini tidak dapat dibatalkan.
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
function deleteGallery(id) {
    $('#deleteForm').attr('action', `{{ url('admin/gallery') }}/${id}`);
    $('#deleteModal').modal('show');
}
</script>
@endpush