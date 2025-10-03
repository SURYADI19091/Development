@extends('backend.layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.umkm.index') }}">UMKM</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $umkm->business_name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-store mr-2"></i>{{ $umkm->business_name }}
                    @if($umkm->is_verified)
                        <i class="fas fa-check-circle text-success ml-2" title="Terverifikasi"></i>
                    @endif
                </h5>
                <div>
                    <a href="{{ route('backend.umkm.edit', $umkm) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <a href="{{ route('backend.umkm.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Business Info -->
                        <div class="row">
                            <div class="col-md-3 text-center">
                                @if($umkm->logo_path)
                                    <img src="{{ Storage::url($umkm->logo_path) }}" 
                                         class="img-fluid rounded mb-3" 
                                         alt="{{ $umkm->business_name }}"
                                         style="max-width: 150px;">
                                @else
                                    <div class="bg-light p-4 rounded mb-3 d-inline-block">
                                        <i class="fas fa-store text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h4>{{ $umkm->business_name }}</h4>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-user mr-1"></i>Pemilik: {{ $umkm->owner_name }}
                                </p>
                                <p class="mb-2">
                                    <span class="badge badge-info">{{ ucfirst($umkm->category) }}</span>
                                    @if($umkm->is_active)
                                        <span class="badge badge-success ml-1">Aktif</span>
                                    @else
                                        <span class="badge badge-danger ml-1">Tidak Aktif</span>
                                    @endif
                                    @if($umkm->is_verified)
                                        <span class="badge badge-warning ml-1">
                                            <i class="fas fa-certificate"></i> Terverifikasi
                                        </span>
                                    @endif
                                </p>
                                @if($umkm->rating > 0)
                                    <div class="mb-2">
                                        <span class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $umkm->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </span>
                                        <span class="ml-1">{{ number_format($umkm->rating, 1) }} ({{ $umkm->total_reviews }} ulasan)</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Description -->
                        @if($umkm->description)
                            <div class="mt-4">
                                <h6>Deskripsi Usaha</h6>
                                <p class="text-muted">{{ $umkm->description }}</p>
                            </div>
                        @endif

                        <!-- Products & Services -->
                        <div class="row mt-4">
                            @if($umkm->products)
                                <div class="col-md-6">
                                    <h6>Produk</h6>
                                    <div class="text-muted">{{ $umkm->products }}</div>
                                </div>
                            @endif
                            @if($umkm->services)
                                <div class="col-md-6">
                                    <h6>Layanan</h6>
                                    <div class="text-muted">{{ $umkm->services }}</div>
                                </div>
                            @endif
                        </div>

                        <!-- Photos -->
                        @if($umkm->photos && count($umkm->photos) > 0)
                            <div class="mt-4">
                                <h6>Foto Usaha</h6>
                                <div class="row">
                                    @foreach($umkm->photos as $photo)
                                        <div class="col-md-4 mb-3">
                                            <img src="{{ Storage::url($photo) }}" class="img-fluid rounded">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <!-- Contact Info -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Informasi Kontak</h6>
                            </div>
                            <div class="card-body">
                                @if($umkm->address)
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Alamat</small>
                                        <div>{{ $umkm->address }}</div>
                                        @if($umkm->settlement)
                                            <small class="text-info">{{ $umkm->settlement->name }}</small>
                                        @endif
                                    </div>
                                @endif

                                @if($umkm->phone)
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Telepon</small>
                                        <a href="tel:{{ $umkm->phone }}">{{ $umkm->phone }}</a>
                                    </div>
                                @endif

                                @if($umkm->email)
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Email</small>
                                        <a href="mailto:{{ $umkm->email }}">{{ $umkm->email }}</a>
                                    </div>
                                @endif

                                @if($umkm->website)
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Website</small>
                                        <a href="{{ $umkm->website }}" target="_blank">{{ $umkm->website }}</a>
                                    </div>
                                @endif

                                @if($umkm->operating_hours)
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Jam Operasional</small>
                                        <div>{{ $umkm->operating_hours }}</div>
                                    </div>
                                @endif

                                @if($umkm->price_range)
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Kisaran Harga</small>
                                        <div>{{ $umkm->price_range }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Business Stats -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Statistik Usaha</h6>
                            </div>
                            <div class="card-body">
                                @if($umkm->employee_count > 0)
                                    <div class="mb-2">
                                        <small class="text-muted">Jumlah Karyawan</small>
                                        <div class="font-weight-bold">{{ $umkm->employee_count }} orang</div>
                                    </div>
                                @endif

                                @if($umkm->monthly_revenue > 0)
                                    <div class="mb-2">
                                        <small class="text-muted">Pendapatan Bulanan</small>
                                        <div class="font-weight-bold text-success">Rp {{ number_format($umkm->monthly_revenue) }}</div>
                                        <small class="text-info">Data rahasia</small>
                                    </div>
                                @endif

                                @if($umkm->registered_at)
                                    <div class="mb-2">
                                        <small class="text-muted">Tanggal Registrasi</small>
                                        <div>{{ $umkm->registered_at->format('d/m/Y') }}</div>
                                    </div>
                                @endif

                                <div class="mb-2">
                                    <small class="text-muted">Dibuat</small>
                                    <div>{{ $umkm->created_at->format('d/m/Y H:i') }}</div>
                                </div>

                                @if($umkm->updated_at != $umkm->created_at)
                                    <div class="mb-2">
                                        <small class="text-muted">Terakhir Diupdate</small>
                                        <div>{{ $umkm->updated_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-3">
                            <div class="btn-group btn-group-sm w-100">
                                <a href="{{ route('backend.umkm.edit', $umkm) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @if(method_exists($umkm, 'reviews'))
                                    <a href="{{ route('backend.umkm.reviews', $umkm) }}" class="btn btn-info">
                                        <i class="fas fa-star"></i> Ulasan
                                    </a>
                                @endif
                                <button type="button" class="btn btn-danger" onclick="deleteUmkm()">
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
                Apakah Anda yakin ingin menghapus UMKM "<strong>{{ $umkm->business_name }}</strong>"? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('backend.umkm.destroy', $umkm) }}" method="POST" style="display: inline;">
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
function deleteUmkm() {
    $('#deleteModal').modal('show');
}
</script>
@endpush