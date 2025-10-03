@extends('backend.layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.umkm.index') }}">UMKM</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.umkm.show', $umkm) }}">{{ $umkm->business_name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ulasan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-star mr-2"></i>Ulasan - {{ $umkm->business_name }}
                </h5>
                <div>
                    <a href="{{ route('backend.umkm.show', $umkm) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye mr-1"></i>Lihat UMKM
                    </a>
                    <a href="{{ route('backend.umkm.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Rating Summary -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h2 class="text-warning">{{ number_format($umkm->rating, 1) }}</h2>
                                <div class="text-warning mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $umkm->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-muted mb-0">{{ $umkm->total_reviews }} ulasan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="mb-3">Distribusi Rating</h6>
                                @for($i = 5; $i >= 1; $i--)
                                    @php
                                        $count = $reviews->where('rating', $i)->count();
                                        $percentage = $umkm->total_reviews > 0 ? ($count / $umkm->total_reviews) * 100 : 0;
                                    @endphp
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="mr-2">{{ $i }} ★</span>
                                        <div class="progress flex-grow-1 mr-2" style="height: 20px;">
                                            <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="small">{{ $count }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                @if($reviews->count() > 0)
                    <div class="row">
                        @foreach($reviews as $review)
                            <div class="col-lg-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1">{{ $review->reviewer_name ?? 'Anonymous' }}</h6>
                                                <div class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                {{ $review->created_at->format('d/m/Y') }}
                                            </small>
                                        </div>
                                        
                                        @if($review->comment)
                                            <p class="text-muted mb-2">{{ $review->comment }}</p>
                                        @endif
                                        
                                        @if($review->photos && count($review->photos) > 0)
                                            <div class="row">
                                                @foreach($review->photos as $photo)
                                                    <div class="col-4 mb-2">
                                                        <img src="{{ Storage::url($photo) }}" class="img-fluid rounded" style="height: 80px; object-fit: cover;">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="deleteReview({{ $review->id }})">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $reviews->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-star text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 text-muted">Belum Ada Ulasan</h5>
                        <p class="text-muted">UMKM ini belum memiliki ulasan dari pelanggan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Review Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus Ulasan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus ulasan ini? Tindakan ini tidak dapat dibatalkan.
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
function deleteReview(id) {
    $('#deleteForm').attr('action', `{{ url('admin/umkm/'.$umkm->id.'/reviews') }}/${id}`);
    $('#deleteModal').modal('show');
}
</script>
@endpush