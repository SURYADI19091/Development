@extends('backend.layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.tourism.index') }}">Objek Wisata</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $tourism->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-map-marker-alt mr-2"></i>{{ $tourism->name }}
                    @if($tourism->is_featured)
                        <span class="badge badge-warning ml-2">
                            <i class="fas fa-star"></i> Unggulan
                        </span>
                    @endif
                </h5>
                <div>
                    <a href="{{ route('backend.tourism.edit', $tourism) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <a href="{{ route('backend.tourism.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Image Gallery -->
                        @if($tourism->images && count($tourism->images) > 0)
                            <div class="mb-4">
                                <div id="tourismCarousel" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner rounded">
                                        @foreach($tourism->images as $index => $image)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <img src="{{ Storage::url($image) }}" 
                                                     class="d-block w-100" 
                                                     style="height: 400px; object-fit: cover;"
                                                     alt="{{ $tourism->name }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(count($tourism->images) > 1)
                                        <a class="carousel-control-prev" href="#tourismCarousel" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#tourismCarousel" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                        <ol class="carousel-indicators">
                                            @foreach($tourism->images as $index => $image)
                                                <li data-target="#tourismCarousel" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
                                            @endforeach
                                        </ol>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Description -->
                        <div class="mb-4">
                            <h6>Deskripsi</h6>
                            <p class="text-muted">{{ $tourism->description }}</p>
                        </div>

                        <!-- Facilities -->
                        @if($tourism->facilities)
                            <div class="mb-4">
                                <h6>Fasilitas</h6>
                                <div class="text-muted">
                                    {{ $tourism->facilities }}
                                </div>
                            </div>
                        @endif



                        <!-- Map -->
                        @if($tourism->latitude && $tourism->longitude)
                            <div class="mb-4">
                                <h6>Lokasi GPS</h6>
                                <div class="card">
                                    <div class="card-body text-center">
                                        <p class="mb-2">
                                            <strong>Koordinat:</strong> {{ $tourism->latitude }}, {{ $tourism->longitude }}
                                        </p>
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $tourism->latitude }},{{ $tourism->longitude }}" 
                                           target="_blank" class="btn btn-success btn-sm">
                                            <i class="fas fa-map-marked-alt mr-1"></i>Buka di Google Maps
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <!-- Basic Info -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Informasi Dasar</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>Kategori:</strong></td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($tourism->category) }}</span>
                                        </td>
                                    </tr>
                                    @if($tourism->settlement)
                                    <tr>
                                        <td><strong>Dusun:</strong></td>
                                        <td>{{ $tourism->settlement->name }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @if($tourism->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($tourism->entry_fee > 0)
                                    <tr>
                                        <td><strong>Tiket Masuk:</strong></td>
                                        <td class="text-success font-weight-bold">Rp {{ number_format($tourism->entry_fee) }}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td><strong>Tiket Masuk:</strong></td>
                                        <td class="text-success font-weight-bold">Gratis</td>
                                    </tr>
                                    @endif
                                    @if($tourism->operating_hours)
                                    <tr>
                                        <td><strong>Jam Buka:</strong></td>
                                        <td>{{ $tourism->operating_hours }}</td>
                                    </tr>
                                    @endif
                                    @if($tourism->rating > 0)
                                    <tr>
                                        <td><strong>Rating:</strong></td>
                                        <td>
                                            <span class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $tourism->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </span>
                                            <small class="text-muted">({{ $tourism->total_reviews }} ulasan)</small>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <!-- Address & Contact -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Alamat & Kontak</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Alamat</small>
                                    <div>{{ $tourism->address }}</div>
                                </div>

                                @if($tourism->contact_info)
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Kontak</small>
                                        <div>{{ $tourism->contact_info }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Informasi Sistem</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <small class="text-muted">Dibuat</small>
                                    <div>{{ $tourism->created_at->format('d/m/Y H:i') }}</div>
                                </div>

                                @if($tourism->updated_at != $tourism->created_at)
                                    <div class="mb-2">
                                        <small class="text-muted">Terakhir Diupdate</small>
                                        <div>{{ $tourism->updated_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="btn-group btn-group-sm w-100">
                            <a href="{{ route('backend.tourism.edit', $tourism) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" class="btn btn-danger" onclick="deleteTourism()">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
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
                Apakah Anda yakin ingin menghapus objek wisata "<strong>{{ $tourism->name }}</strong>"? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('backend.tourism.destroy', $tourism) }}" method="POST" style="display: inline;">
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
function deleteTourism() {
    $('#deleteModal').modal('show');
}
</script>
@endpush