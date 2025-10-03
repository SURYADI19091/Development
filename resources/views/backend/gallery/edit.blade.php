@extends('backend.layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.gallery.index') }}">Galeri</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Foto</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-edit mr-2"></i>Edit Foto: {{ $gallery->title }}
                </h5>
                <div>
                    <a href="{{ route('backend.gallery.show', $gallery) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye mr-1"></i>Lihat
                    </a>
                    <a href="{{ route('backend.gallery.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('backend.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title">Judul Foto <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $gallery->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Deskripsi foto (opsional)">{{ old('description', $gallery->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image_path">Upload Foto Baru</label>
                                <input type="file" class="form-control-file @error('image_path') is-invalid @enderror" 
                                       id="image_path" name="image_path" accept="image/*">
                                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti foto. Format: JPG, PNG, GIF. Maksimal 5MB</small>
                                @error('image_path')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                
                                <!-- Current Image -->
                                @if($gallery->image_path)
                                    <div class="mt-3">
                                        <label class="small text-muted">Foto saat ini:</label>
                                        <div id="current-image">
                                            <img src="{{ Storage::url($gallery->image_path) }}" class="img-thumbnail" style="max-width: 300px;">
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- New Image Preview -->
                                <div id="image-preview" class="mt-2" style="display: none;">
                                    <label class="small text-muted">Preview foto baru:</label><br>
                                    <img class="img-thumbnail" style="max-width: 300px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="alt_text">Alt Text</label>
                                <input type="text" class="form-control @error('alt_text') is-invalid @enderror" 
                                       id="alt_text" name="alt_text" value="{{ old('alt_text', $gallery->alt_text) }}"
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
                                    <option value="kegiatan" {{ (old('category', $gallery->category) == 'kegiatan') ? 'selected' : '' }}>Kegiatan</option>
                                    <option value="infrastruktur" {{ (old('category', $gallery->category) == 'infrastruktur') ? 'selected' : '' }}>Infrastruktur</option>
                                    <option value="wisata" {{ (old('category', $gallery->category) == 'wisata') ? 'selected' : '' }}>Wisata</option>
                                    <option value="budaya" {{ (old('category', $gallery->category) == 'budaya') ? 'selected' : '' }}>Budaya</option>
                                    <option value="lainnya" {{ (old('category', $gallery->category) == 'lainnya') ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('category')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="photographer">Fotografer</label>
                                <input type="text" class="form-control @error('photographer') is-invalid @enderror" 
                                       id="photographer" name="photographer" value="{{ old('photographer', $gallery->photographer) }}"
                                       placeholder="Nama fotografer (opsional)">
                                @error('photographer')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="location">Lokasi</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location', $gallery->location) }}"
                                       placeholder="Lokasi pengambilan foto">
                                @error('location')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="taken_at">Tanggal Foto</label>
                                <input type="date" class="form-control @error('taken_at') is-invalid @enderror" 
                                       id="taken_at" name="taken_at" 
                                       value="{{ old('taken_at', $gallery->taken_at ? $gallery->taken_at->format('Y-m-d') : '') }}">
                                @error('taken_at')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="event_date">Tanggal Acara</label>
                                <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                       id="event_date" name="event_date" 
                                       value="{{ old('event_date', $gallery->event_date ? $gallery->event_date->format('Y-m-d') : '') }}">
                                @error('event_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tags">Tag</label>
                                <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                       id="tags" name="tags" 
                                       value="{{ old('tags', is_array($gallery->tags) ? implode(', ', $gallery->tags) : $gallery->tags) }}"
                                       placeholder="Tag dipisahkan koma">
                                <small class="form-text text-muted">Pisahkan dengan koma. Contoh: desa, kegiatan, gotong royong</small>
                                @error('tags')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" 
                                           {{ (old('is_featured', $gallery->is_featured)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Foto Unggulan
                                    </label>
                                </div>
                                <small class="form-text text-muted">Centang untuk menampilkan sebagai foto unggulan</small>
                            </div>

                            <!-- Statistics (read-only) -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Statistik</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <h4 class="text-info">{{ number_format($gallery->views_count ?? 0) }}</h4>
                                            <small class="text-muted">Views</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-danger">{{ number_format($gallery->likes_count ?? 0) }}</h4>
                                            <small class="text-muted">Likes</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('backend.gallery.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>Update Foto
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
});
</script>
@endpush