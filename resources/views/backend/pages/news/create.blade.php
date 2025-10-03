@extends('backend.layout.main')

@section('page_title', 'Buat Berita')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.news.index') }}">Berita</a></li>
<li class="breadcrumb-item active">Buat Berita</li>
@endsection

@section('content')
<div class="container-fluid">
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i>Terdapat kesalahan dalam form:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-newspaper mr-2"></i>Buat Berita Baru
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('backend.news.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('backend.news.store') }}" method="POST" enctype="multipart/form-data" id="news-form">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Judul Berita *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="slug">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug') }}"
                                           placeholder="Otomatis dihasilkan dari judul">
                                    @error('slug')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="excerpt">Ringkasan</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                              id="excerpt" name="excerpt" rows="3" 
                                              placeholder="Ringkasan singkat berita (opsional)">{{ old('excerpt') }}</textarea>
                                    @error('excerpt')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="content">Konten Berita *</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" name="content" rows="15" required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category">Kategori</label>
                                    <select class="form-control @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @if(isset($categories))
                                            @foreach($categories as $key => $label)
                                                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        @else
                                            <option value="kegiatan" {{ old('category') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                            <option value="kesehatan" {{ old('category') == 'kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                            <option value="ekonomi" {{ old('category') == 'ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                                            <option value="infrastruktur" {{ old('category') == 'infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                                            <option value="pendidikan" {{ old('category') == 'pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                            <option value="olahraga" {{ old('category') == 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                                            <option value="lainnya" {{ old('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        @endif
                                    </select>
                                    @error('category')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="featured_image">Gambar Utama</label>
                                    <input type="file" class="form-control-file @error('featured_image') is-invalid @enderror" 
                                           id="featured_image" name="featured_image" accept="image/*">
                                    <small class="form-text text-muted">Maksimal 2MB, format: JPG, PNG, GIF</small>
                                    @error('featured_image')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="tags">Tags</label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                           id="tags" name="tags" value="{{ old('tags') }}"
                                           placeholder="Pisahkan dengan koma">
                                    @error('tags')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="published_at">Tanggal Publikasi</label>
                                    <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                                           id="published_at" name="published_at" 
                                           value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                                    @error('published_at')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_published" name="is_published" value="1" 
                                               {{ old('is_published') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_published">
                                            Publikasikan Sekarang
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_featured" name="is_featured" value="1" 
                                               {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_featured">
                                            Berita Unggulan
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('backend.news.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i>Batal
                            </a>
                            <div>
                                <button type="button" onclick="submitNews('draft')" class="btn btn-outline-primary">
                                    <i class="fas fa-save mr-1"></i>Simpan Draft
                                </button>
                                <button type="button" onclick="submitNews('published')" class="btn btn-primary">
                                    <i class="fas fa-paper-plane mr-1"></i>Publikasikan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
#quill-editor .ql-editor {
    min-height: 200px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
$(document).ready(function() {
    console.log('News create form initialized');
    
    // Hide original textarea and create Quill container
    $('#content').hide();
    $('#content').after('<div id="quill-editor" style="min-height: 200px;"></div>');
    
    // Initialize Quill editor on the new div
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Tulis konten berita di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Load existing content if any (for edit mode)
    var existingContent = $('#content').val();
    if (existingContent && existingContent.trim() !== '' && existingContent !== '<p><br></p>') {
        quill.root.innerHTML = existingContent;
    }

    // Auto-generate slug from title
    $('#title').on('input', function() {
        const title = $(this).val();
        const slug = title
            .toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        $('#slug').val(slug);
    });

    // Make quill accessible globally
    window.quill = quill;

    // Simple form submit handler for logging
    $('#news-form').on('submit', function(e) {
        console.log('Form submit event fired');
        return true;
    });

    // Form validation
    $('input[required], textarea[required]').on('input blur', function() {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
        }
    });

    // Image preview
    $('#featured_image').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create preview if doesn't exist
                let preview = $('#image-preview');
                if (preview.length === 0) {
                    preview = $('<div id="image-preview" class="mt-2"><img class="img-thumbnail" style="max-width: 200px;"></div>');
                    $('#featured_image').after(preview);
                }
                preview.find('img').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            $('#image-preview').remove();
        }
    });
});

// Global functions (outside document.ready)
function submitNews(status) {
    console.log('submitNews called with status:', status);
    
    try {
        // Basic validation
        const title = $('#title').val().trim();
        const category = $('#category').val();
        
        console.log('Title:', title);
        console.log('Category:', category);
        
        if (!title) {
            alert('Judul berita harus diisi!');
            return;
        }
        
        if (!category) {
            alert('Kategori harus dipilih!');
            return;
        }
        
        // Generate slug if empty
        if (!$('#slug').val().trim()) {
            const slug = title
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            $('#slug').val(slug);
            console.log('Generated slug:', slug);
        }
        
        // Get Quill content
        const quillText = window.quill.getText().trim();
        const quillHtml = window.quill.root.innerHTML;
        
        console.log('Quill text length:', quillText.length);
        console.log('Quill HTML:', quillHtml);
        
        // Set content
        if (quillText.length > 0) {
            $('#content').val(quillHtml);
        } else {
            $('#content').val('Test content - no empty content');
        }
        
        // Add status to form
        $('#news-form').append('<input type="hidden" name="status" value="' + status + '">');
        
        console.log('About to submit form');
        
        // Submit form
        document.getElementById('news-form').submit();
        
    } catch (error) {
        console.error('Error in submitNews:', error);
        alert('Error: ' + error.message);
    }
}
</script>
@endpush