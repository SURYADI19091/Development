@extends('backend.layout.main')

@section('page_title', 'Edit Announcement')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.announcements.index') }}">Announcements</a></li>
<li class="breadcrumb-item"><a href="{{ route('backend.announcements.show', $announcement) }}">{{ $announcement->title }}</a></li>
<li class="breadcrumb-item active">Edit</li>
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
                        <i class="fas fa-edit mr-2"></i>Edit Announcement
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('backend.announcements.show', $announcement) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                        <a href="{{ route('backend.announcements.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Back
                        </a>
                    </div>
                </div>
                <form action="{{ route('backend.announcements.update', $announcement) }}" method="POST" id="announcement-form">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $announcement->title) }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="content">Content *</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" name="content" rows="10" required>{{ old('content', $announcement->content) }}</textarea>
                                    @error('content')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="excerpt">Excerpt</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                              id="excerpt" name="excerpt" rows="3" 
                                              placeholder="Short summary of the announcement (optional)">{{ old('excerpt', $announcement->excerpt) }}</textarea>
                                    @error('excerpt')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="priority">Priority</label>
                                    <select class="form-control @error('priority') is-invalid @enderror" 
                                            id="priority" name="priority">
                                        <option value="low" {{ old('priority', $announcement->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority', $announcement->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority', $announcement->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority', $announcement->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                    @error('priority')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                           id="category" name="category" 
                                           value="{{ old('category', $announcement->category) }}" 
                                           placeholder="Announcement category (optional)">
                                    @error('category')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="valid_from">Valid From</label>
                                            <input type="date" class="form-control @error('valid_from') is-invalid @enderror" 
                                                   id="valid_from" name="valid_from" 
                                                   value="{{ old('valid_from', $announcement->valid_from ? $announcement->valid_from->format('Y-m-d') : '') }}">
                                            @error('valid_from')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="valid_until">Valid Until</label>
                                            <input type="date" class="form-control @error('valid_until') is-invalid @enderror" 
                                                   id="valid_until" name="valid_until" 
                                                   value="{{ old('valid_until', $announcement->valid_until ? $announcement->valid_until->format('Y-m-d') : '') }}">
                                            @error('valid_until')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('backend.announcements.show', $announcement) }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>Update Announcement
                            </button>
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
    // Hide the original textarea and create a div for Quill
    $('#content').hide();
    $('#content').after('<div id="quill-editor" style="min-height: 200px;"></div>');
    
    // Initialize Quill editor on the new div
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Write your announcement content here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['blockquote', 'code-block'],
                ['link'],
                ['clean']
            ]
        }
    });

    // Set initial content from the announcement
    var existingContent = `{!! addslashes($announcement->content) !!}`;
    if (existingContent) {
        quill.root.innerHTML = existingContent;
    }

    // Handle form submission
    $('#announcement-form').on('submit', function(e) {
        // Get Quill content and set it to the hidden textarea
        const content = quill.root.innerHTML;
        
        // Validate content is not empty
        if (quill.getText().trim().length === 0) {
            e.preventDefault();
            alert('Content is required!');
            return false;
        }
        
        // Set content to the hidden textarea
        $('#content').val(content);

        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Updating...');
        
        // Allow form to submit normally
        return true;
    });

    // Form validation
    $('input[required], textarea[required]').on('input blur', function() {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>
@endpush