@extends('backend.layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.umkm.index') }}">UMKM</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah UMKM</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-plus mr-2"></i>Tambah UMKM Baru
                </h5>
                <a href="{{ route('backend.umkm.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('backend.umkm.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="business_name">Nama Usaha <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('business_name') is-invalid @enderror" 
                                       id="business_name" name="business_name" value="{{ old('business_name') }}" required>
                                @error('business_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug URL</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" name="slug" value="{{ old('slug') }}">
                                <small class="form-text text-muted">Biarkan kosong untuk generate otomatis dari nama usaha</small>
                                @error('slug')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="owner_name">Nama Pemilik <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('owner_name') is-invalid @enderror" 
                                       id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required>
                                @error('owner_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi Usaha</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Deskripsi singkat tentang usaha">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="products">Produk/Layanan</label>
                                <textarea class="form-control @error('products') is-invalid @enderror" 
                                          id="products" name="products" rows="3" 
                                          placeholder="Daftar produk atau layanan yang ditawarkan">{{ old('products') }}</textarea>
                                @error('products')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="services">Layanan Tambahan</label>
                                <textarea class="form-control @error('services') is-invalid @enderror" 
                                          id="services" name="services" rows="3" 
                                          placeholder="Layanan tambahan yang disediakan">{{ old('services') }}</textarea>
                                @error('services')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="logo_path">Logo Usaha</label>
                                <input type="file" class="form-control-file @error('logo_path') is-invalid @enderror" 
                                       id="logo_path" name="logo_path" accept="image/*">
                                <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                @error('logo_path')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <div id="logo-preview" class="mt-2" style="display: none;">
                                    <img class="img-thumbnail" style="max-width: 150px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="category">Kategori <span class="text-danger">*</span></label>
                                <select class="form-control @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="kuliner" {{ old('category') == 'kuliner' ? 'selected' : '' }}>Kuliner</option>
                                    <option value="kerajinan" {{ old('category') == 'kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                    <option value="pertanian" {{ old('category') == 'pertanian' ? 'selected' : '' }}>Pertanian</option>
                                    <option value="jasa" {{ old('category') == 'jasa' ? 'selected' : '' }}>Jasa</option>
                                    <option value="perdagangan" {{ old('category') == 'perdagangan' ? 'selected' : '' }}>Perdagangan</option>
                                    <option value="lainnya" {{ old('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                            <option value="{{ $settlement->id }}" {{ old('settlement_id') == $settlement->id ? 'selected' : '' }}>
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
                                          placeholder="Alamat lengkap usaha">{{ old('address') }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">Nomor Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="email@contoh.com">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="website">Website</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                       id="website" name="website" value="{{ old('website') }}" 
                                       placeholder="https://website.com">
                                @error('website')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="operating_hours">Jam Operasional</label>
                                <input type="text" class="form-control @error('operating_hours') is-invalid @enderror" 
                                       id="operating_hours" name="operating_hours" value="{{ old('operating_hours') }}" 
                                       placeholder="Senin-Jumat 08:00-17:00">
                                @error('operating_hours')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="price_range">Kisaran Harga</label>
                                <input type="text" class="form-control @error('price_range') is-invalid @enderror" 
                                       id="price_range" name="price_range" value="{{ old('price_range') }}" 
                                       placeholder="Rp 10.000 - Rp 50.000">
                                @error('price_range')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="employee_count">Jumlah Karyawan</label>
                                <input type="number" class="form-control @error('employee_count') is-invalid @enderror" 
                                       id="employee_count" name="employee_count" value="{{ old('employee_count') }}" 
                                       min="0" placeholder="0">
                                @error('employee_count')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="monthly_revenue">Pendapatan Bulanan (Rp)</label>
                                <input type="number" class="form-control @error('monthly_revenue') is-invalid @enderror" 
                                       id="monthly_revenue" name="monthly_revenue" value="{{ old('monthly_revenue') }}" 
                                       step="0.01" min="0" placeholder="0">
                                <small class="form-text text-muted">Data ini akan dijaga kerahasiaannya</small>
                                @error('monthly_revenue')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="registered_at">Tanggal Registrasi</label>
                                <input type="date" class="form-control @error('registered_at') is-invalid @enderror" 
                                       id="registered_at" name="registered_at" value="{{ old('registered_at', date('Y-m-d')) }}">
                                @error('registered_at')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Status Aktif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_verified" name="is_verified" value="1" 
                                           {{ old('is_verified') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_verified">
                                        Terverifikasi
                                    </label>
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
                                <i class="fas fa-save mr-1"></i>Simpan UMKM
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

    // Auto-generate slug from business name
    $('#business_name').on('input', function() {
        if (!$('#slug').val()) {
            const slug = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            $('#slug').val(slug);
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