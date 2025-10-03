@extends('backend.layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.umkm.index') }}">UMKM</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit {{ $umkm->business_name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-edit mr-2"></i>Edit UMKM: {{ $umkm->business_name }}
                </h5>
                <div>
                    <a href="{{ route('backend.umkm.show', $umkm) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye mr-1"></i>Lihat
                    </a>
                    <a href="{{ route('backend.umkm.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('backend.umkm.update', $umkm) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="business_name">Nama Usaha <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('business_name') is-invalid @enderror" 
                                       id="business_name" name="business_name" value="{{ old('business_name', $umkm->business_name) }}" required>
                                @error('business_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug URL</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" name="slug" value="{{ old('slug', $umkm->slug) }}">
                                <small class="form-text text-muted">Biarkan kosong untuk generate otomatis dari nama usaha</small>
                                @error('slug')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="owner_name">Nama Pemilik <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('owner_name') is-invalid @enderror" 
                                       id="owner_name" name="owner_name" value="{{ old('owner_name', $umkm->owner_name) }}" required>
                                @error('owner_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi Usaha</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Deskripsi singkat tentang usaha">{{ old('description', $umkm->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="products">Produk/Layanan</label>
                                <textarea class="form-control @error('products') is-invalid @enderror" 
                                          id="products" name="products" rows="3" 
                                          placeholder="Daftar produk atau layanan yang ditawarkan">{{ old('products', $umkm->products) }}</textarea>
                                @error('products')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="services">Layanan Tambahan</label>
                                <textarea class="form-control @error('services') is-invalid @enderror" 
                                          id="services" name="services" rows="3" 
                                          placeholder="Layanan tambahan yang disediakan">{{ old('services', $umkm->services) }}</textarea>
                                @error('services')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="logo_path">Logo Usaha Baru</label>
                                <input type="file" class="form-control-file @error('logo_path') is-invalid @enderror" 
                                       id="logo_path" name="logo_path" accept="image/*">
                                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti logo. Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                @error('logo_path')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                
                                <!-- Current Logo -->
                                @if($umkm->logo_path)
                                    <div class="mt-3">
                                        <label class="small text-muted">Logo saat ini:</label>
                                        <div id="current-logo">
                                            <img src="{{ Storage::url($umkm->logo_path) }}" class="img-thumbnail" style="max-width: 150px;">
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- New Logo Preview -->
                                <div id="logo-preview" class="mt-2" style="display: none;">
                                    <label class="small text-muted">Preview logo baru:</label><br>
                                    <img class="img-thumbnail" style="max-width: 150px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="category">Kategori <span class="text-danger">*</span></label>
                                <select class="form-control @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="kuliner" {{ (old('category', $umkm->category) == 'kuliner') ? 'selected' : '' }}>Kuliner</option>
                                    <option value="kerajinan" {{ (old('category', $umkm->category) == 'kerajinan') ? 'selected' : '' }}>Kerajinan</option>
                                    <option value="pertanian" {{ (old('category', $umkm->category) == 'pertanian') ? 'selected' : '' }}>Pertanian</option>
                                    <option value="jasa" {{ (old('category', $umkm->category) == 'jasa') ? 'selected' : '' }}>Jasa</option>
                                    <option value="perdagangan" {{ (old('category', $umkm->category) == 'perdagangan') ? 'selected' : '' }}>Perdagangan</option>
                                    <option value="lainnya" {{ (old('category', $umkm->category) == 'lainnya') ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('category')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="settlement_id">Dusun/Wilayah</label>
                                <select class="form-control @error('settlement_id') is-invalid @enderror" 
                                        id="settlement_id" name="settlement_id">
                                    <option value="">-- Pilih Dusun --</option>
                                    @if(isset($settlements))
                                        @foreach($settlements as $settlement)
                                            <option value="{{ $settlement->id }}" {{ (old('settlement_id', $umkm->settlement_id) == $settlement->id) ? 'selected' : '' }}>
                                                {{ $settlement->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('settlement_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address">Alamat Lengkap</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" 
                                          placeholder="Alamat lengkap usaha">{{ old('address', $umkm->address) }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">Nomor Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $umkm->phone) }}" 
                                       placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $umkm->email) }}" 
                                       placeholder="email@contoh.com">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="website">Website</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                       id="website" name="website" value="{{ old('website', $umkm->website) }}" 
                                       placeholder="https://website.com">
                                @error('website')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="operating_hours">Jam Operasional</label>
                                <input type="text" class="form-control @error('operating_hours') is-invalid @enderror" 
                                       id="operating_hours" name="operating_hours" value="{{ old('operating_hours', $umkm->operating_hours) }}" 
                                       placeholder="Senin-Jumat 08:00-17:00">
                                @error('operating_hours')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="price_range">Kisaran Harga</label>
                                <input type="text" class="form-control @error('price_range') is-invalid @enderror" 
                                       id="price_range" name="price_range" value="{{ old('price_range', $umkm->price_range) }}" 
                                       placeholder="Rp 10.000 - Rp 50.000">
                                @error('price_range')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="employee_count">Jumlah Karyawan</label>
                                <input type="number" class="form-control @error('employee_count') is-invalid @enderror" 
                                       id="employee_count" name="employee_count" value="{{ old('employee_count', $umkm->employee_count) }}" 
                                       min="0" placeholder="0">
                                @error('employee_count')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="monthly_revenue">Pendapatan Bulanan (Rp)</label>
                                <input type="number" class="form-control @error('monthly_revenue') is-invalid @enderror" 
                                       id="monthly_revenue" name="monthly_revenue" value="{{ old('monthly_revenue', $umkm->monthly_revenue) }}" 
                                       step="0.01" min="0" placeholder="0">
                                <small class="form-text text-muted">Data ini akan dijaga kerahasiaannya</small>
                                @error('monthly_revenue')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="registered_at">Tanggal Registrasi</label>
                                <input type="date" class="form-control @error('registered_at') is-invalid @enderror" 
                                       id="registered_at" name="registered_at" 
                                       value="{{ old('registered_at', $umkm->registered_at ? $umkm->registered_at->format('Y-m-d') : '') }}">
                                @error('registered_at')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', $umkm->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Status Aktif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_verified" name="is_verified" value="1" 
                                           {{ old('is_verified', $umkm->is_verified) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_verified">
                                        Terverifikasi
                                    </label>
                                </div>
                            </div>

                            <!-- Statistics (read-only) -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Statistik</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <h4 class="text-warning">{{ number_format($umkm->rating, 1) }}</h4>
                                            <small class="text-muted">Rating</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-info">{{ number_format($umkm->total_reviews) }}</h4>
                                            <small class="text-muted">Ulasan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('backend.umkm.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>Update UMKM
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Logo preview
    $('#logo_path').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#logo-preview img').attr('src', e.target.result);
                $('#logo-preview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#logo-preview').hide();
        }
    });

    // Format phone number
    $('#phone').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.startsWith('0')) {
            $(this).val(value);
        } else if (value.startsWith('62')) {
            $(this).val('0' + value.substring(2));
        }
    });

    // Format currency for monthly revenue
    $('#monthly_revenue').on('input', function() {
        let value = $(this).val().replace(/[^0-9.]/g, '');
        $(this).val(value);
    });
});
</script>
@endpush