@extends('backend.layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.gallery.index') }}">Galeri</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Foto</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-image mr-2"></i>Detail Foto: {{ $gallery->title }}
                </h5>
                <div>
                    <a href="{{ route('backend.gallery.edit', $gallery) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <a href="{{ route('backend.gallery.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Main Image -->
                        <div class="text-center mb-4">
                            @if($gallery->image_path)
                                <img src="{{ Storage::url($gallery->image_path) }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $gallery->alt_text ?? $gallery->title }}"
                                     style="max-height: 500px;">
                            @else
                                <div class="bg-light p-5 rounded">
                                    <i class="fas fa-image text-muted" style="font-size: 5rem;"></i>
                                    <p class="mt-3 text-muted">Tidak ada gambar</p>
                                </div>
                            @endif
                        </div>

                        <!-- Description -->
                        @if($gallery->description)
                            <div class="mb-4">
                                <h6>Deskripsi:</h6>
                                <p class="text-muted">{{ $gallery->description }}</p>
                            </div>
                        @endif

                        <!-- Tags -->
                        @if($gallery->tags && count($gallery->tags) > 0)
                            <div class="mb-4">
                                <h6>Tag:</h6>
                                @foreach($gallery->tags as $tag)
                                    <span class="badge badge-secondary mr-1">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Informasi Foto</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Judul:</strong></td>
                                        <td>{{ $gallery->title }}</td>
                                    </tr>
                                    @if($gallery->category)
                                    <tr>
                                        <td><strong>Kategori:</strong></td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($gallery->category) }}</span>
                                        </td>
                                    </tr>
                                    @endif
                                    @if($gallery->photographer)
                                    <tr>
                                        <td><strong>Fotografer:</strong></td>
                                        <td>{{ $gallery->photographer }}</td>
                                    </tr>
                                    @endif
                                    @if($gallery->location)
                                    <tr>
                                        <td><strong>Lokasi:</strong></td>
                                        <td>{{ $gallery->location }}</td>
                                    </tr>
                                    @endif
                                    @if($gallery->taken_at)
                                    <tr>
                                        <td><strong>Tanggal Foto:</strong></td>
                                        <td>{{ $gallery->taken_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endif
                                    @if($gallery->event_date)
                                    <tr>
                                        <td><strong>Tanggal Acara:</strong></td>
                                        <td>{{ $gallery->event_date->format('d/m/Y') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @if($gallery->is_featured)
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-star"></i> Unggulan
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">Normal</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Views:</strong></td>
                                        <td>{{ number_format($gallery->views_count ?? 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Likes:</strong></td>
                                        <td>{{ number_format($gallery->likes_count ?? 0) }}</td>
                                    </tr>
                                    @if($gallery->uploader)
                                    <tr>
                                        <td><strong>Diupload oleh:</strong></td>
                                        <td>{{ $gallery->uploader->name }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Tanggal Upload:</strong></td>
                                        <td>{{ $gallery->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @if($gallery->updated_at != $gallery->created_at)
                                    <tr>
                                        <td><strong>Terakhir Diupdate:</strong></td>
                                        <td>{{ $gallery->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-3">
                            <div class="btn-group btn-group-sm w-100">
                                <a href="{{ route('backend.gallery.edit', $gallery) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-danger" onclick="deleteGallery()">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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
                Apakah Anda yakin ingin menghapus foto "<strong>{{ $gallery->title }}</strong>"? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('backend.gallery.destroy', $gallery) }}" method="POST" style="display: inline;">
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
function deleteGallery() {
    $('#deleteModal').modal('show');
}
</script>
@endpush