@extends('backend.layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.gallery.index') }}">Galeri</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Foto</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-plus mr-2"></i>Tambah Foto Baru
                </h5>
                <a href="{{ route('backend.gallery.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('backend.gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title">Judul Foto <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Deskripsi foto (opsional)">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image_path">Upload Foto <span class="text-danger">*</span></label>
                                <input type="file" class="form-control-file @error('image_path') is-invalid @enderror" 
                                       id="image_path" name="image_path" accept="image/*" required>
                                <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 5MB</small>
                                @error('image_path')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <div id="image-preview" class="mt-2" style="display: none;">
                                    <img class="img-thumbnail" style="max-width: 300px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="alt_text">Alt Text</label>
                                <input type="text" class="form-control @error('alt_text') is-invalid @enderror" 
                                       id="alt_text" name="alt_text" value="{{ old('alt_text') }}"
                                       placeholder="Deskripsi gambar untuk aksesibilitas">
                                @error('alt_text')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="category">Kategori</label>
                                <select class="form-control @error('category') is-invalid @enderror" 
                                        id="category" name="category">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="kegiatan" {{ old('category') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                    <option value="infrastruktur" {{ old('category') == 'infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                                    <option value="wisata" {{ old('category') == 'wisata' ? 'selected' : '' }}>Wisata</option>
                                    <option value="budaya" {{ old('category') == 'budaya' ? 'selected' : '' }}>Budaya</option>
                                    <option value="lainnya" {{ old('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('category')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="photographer">Fotografer</label>
                                <input type="text" class="form-control @error('photographer') is-invalid @enderror" 
                                       id="photographer" name="photographer" value="{{ old('photographer') }}"
                                       placeholder="Nama fotografer (opsional)">
                                @error('photographer')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="location">Lokasi</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location') }}"
                                       placeholder="Lokasi pengambilan foto">
                                @error('location')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="taken_at">Tanggal Foto</label>
                                <input type="date" class="form-control @error('taken_at') is-invalid @enderror" 
                                       id="taken_at" name="taken_at" value="{{ old('taken_at') }}">
                                @error('taken_at')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="event_date">Tanggal Acara</label>
                                <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                       id="event_date" name="event_date" value="{{ old('event_date') }}">
                                @error('event_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tags">Tag</label>
                                <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                       id="tags" name="tags" value="{{ old('tags') }}"
                                       placeholder="Tag dipisahkan koma">
                                <small class="form-text text-muted">Pisahkan dengan koma. Contoh: desa, kegiatan, gotong royong</small>
                                @error('tags')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" 
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Foto Unggulan
                                    </label>
                                </div>
                                <small class="form-text text-muted">Centang untuk menampilkan sebagai foto unggulan</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('backend.gallery.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>Simpan Foto
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
    // Image preview
    $('#image_path').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview img').attr('src', e.target.result);
                $('#image-preview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#image-preview').hide();
        }
    });

    // Auto generate alt text from title
    $('#title').on('input', function() {
        if (!$('#alt_text').val()) {
            $('#alt_text').val($(this).val());
        }
    });
});
</script>
@endpush