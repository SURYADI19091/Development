@extends('backend.layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.news.index') }}">Berita</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Berita</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-edit mr-2"></i>Edit Berita
                </h5>
                <div>
                    <a href="{{ route('backend.news.show', $news) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye mr-1"></i>Preview
                    </a>
                    <a href="{{ route('backend.news.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('backend.news.update', $news) }}" method="POST" enctype="multipart/form-data" id="news-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title">Judul Berita <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $news->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug URL</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" name="slug" value="{{ old('slug', $news->slug) }}">
                                <small class="form-text text-muted">Biarkan kosong untuk generate otomatis dari judul</small>
                                @error('slug')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="excerpt">Ringkasan/Excerpt</label>
                                <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                          id="excerpt" name="excerpt" rows="3" 
                                          placeholder="Ringkasan singkat berita (opsional)">{{ old('excerpt', $news->excerpt) }}</textarea>
                                @error('excerpt')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="content">Konten Berita <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                          id="content" name="content" rows="10" required>{{ old('content', $news->content) }}</textarea>
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
                                            <option value="{{ $key }}" {{ (old('category', $news->category) == $key) ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    @else
                                        <option value="kegiatan" {{ (old('category', $news->category) == 'kegiatan') ? 'selected' : '' }}>Kegiatan</option>
                                        <option value="kesehatan" {{ (old('category', $news->category) == 'kesehatan') ? 'selected' : '' }}>Kesehatan</option>
                                        <option value="ekonomi" {{ (old('category', $news->category) == 'ekonomi') ? 'selected' : '' }}>Ekonomi</option>
                                        <option value="infrastruktur" {{ (old('category', $news->category) == 'infrastruktur') ? 'selected' : '' }}>Infrastruktur</option>
                                        <option value="pendidikan" {{ (old('category', $news->category) == 'pendidikan') ? 'selected' : '' }}>Pendidikan</option>
                                        <option value="olahraga" {{ (old('category', $news->category) == 'olahraga') ? 'selected' : '' }}>Olahraga</option>
                                        <option value="lainnya" {{ (old('category', $news->category) == 'lainnya') ? 'selected' : '' }}>Lainnya</option>
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
                                <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                @error('featured_image')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                
                                @if($news->featured_image)
                                    <div class="mt-2">
                                        <label class="small text-muted">Gambar saat ini:</label>
                                        <div id="current-image">
                                            <img src="{{ Storage::url($news->featured_image) }}" class="img-thumbnail" style="max-width: 200px;">
                                            <small class="d-block text-muted">{{ basename($news->featured_image) }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" 
                                           {{ (old('is_featured', $news->is_featured)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Berita Unggulan
                                    </label>
                                </div>
                                <small class="form-text text-muted">Centang untuk menampilkan sebagai berita unggulan</small>
                            </div>

                            <div class="form-group">
                                <label>Status Publikasi</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_draft" value="draft" 
                                               {{ (old('status', $news->is_published ? 'published' : 'draft') == 'draft') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_draft">
                                            <i class="fas fa-edit text-warning"></i> Draft
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_published" value="published" 
                                               {{ (old('status', $news->is_published ? 'published' : 'draft') == 'published') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_published">
                                            <i class="fas fa-globe text-success"></i> Terbitkan
                                        </label>
                                    </div>
                                </div>
                                @if($news->published_at)
                                    <small class="form-text text-muted">
                                        <i class="fas fa-clock"></i> Diterbitkan: {{ $news->published_at->format('d/m/Y H:i') }}
                                    </small>
                                @endif
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
                                    <i class="fas fa-paper-plane mr-1"></i>Update & Publikasikan
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
    console.log('News edit form initialized');
    
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

    // Load existing content
    var existingContent = $('#content').val();
    if (existingContent && existingContent.trim() !== '' && existingContent !== '<p><br></p>') {
        quill.root.innerHTML = existingContent;
    }

    // Auto-generate slug from title (only if current slug is empty or matches current title)
    $('#title').on('input', function() {
        const title = $(this).val();
        const currentSlug = $('#slug').val();
        
        // Only auto-generate if slug is empty or if we're editing and slug matches title pattern
        if (!currentSlug.trim()) {
            const slug = title
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            $('#slug').val(slug);
        }
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
                    preview = $('<div id="image-preview" class="mt-2"><label class="small text-muted">Preview gambar baru:</label><br><img class="img-thumbnail" style="max-width: 200px;"></div>');
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
            $('#content').val('');
        }
        
        // Update status radio button
        if (status === 'draft') {
            $('#status_draft').prop('checked', true);
        } else {
            $('#status_published').prop('checked', true);
        }
        
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
