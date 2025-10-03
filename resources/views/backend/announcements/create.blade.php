@extends('backend.layout.main')

@section('page_title', 'Create Announcement')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.announcements.index') }}">Announcements</a></li>
<li class="breadcrumb-item active">Create</li>
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
                        <i class="fas fa-bullhorn mr-2"></i>Create New Announcement
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('backend.announcements.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Back
                        </a>
                    </div>
                </div>
                <form action="{{ route('backend.announcements.store') }}" method="POST" id="announcement-form">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="content">Content *</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="excerpt">Excerpt</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                              id="excerpt" name="excerpt" rows="3" 
                                              placeholder="Short summary of the announcement (optional)">{{ old('excerpt') }}</textarea>
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
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                    @error('priority')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                           id="category" name="category" value="{{ old('category') }}" 
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
                                                   value="{{ old('valid_from') }}">
                                            @error('valid_from')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="valid_until">Valid Until</label>
                                            <input type="date" class="form-control @error('valid_until') is-invalid @enderror" 
                                                   id="valid_until" name="valid_until" value="{{ old('valid_until') }}">
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
                                               {{ old('is_active', true) ? 'checked' : '' }}>
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
                            <a href="{{ route('backend.announcements.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>Create Announcement
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
<style>
#content {
    min-height: 200px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Document ready, form initialized');
    
    // Simple form submission test
    $('#announcement-form').on('submit', function(e) {
        console.log('Form submit clicked');
        
        // Basic validation - check if title is filled
        const title = $('#title').val().trim();
        if (!title) {
            e.preventDefault();
            alert('Title is required!');
            return false;
        }
        
        // Set a default content if empty
        const content = $('#content').val().trim();
        if (!content) {
            $('#content').val('<p>Default content</p>');
        }
        
        console.log('Form validation passed, submitting...');
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Creating...');
        
        return true;
    });

    // Auto-set valid_until when valid_from changes
    $('#valid_from').on('change', function() {
        const startDate = new Date($(this).val());
        if (startDate && !$('#valid_until').val()) {
            // Set end date to 30 days after start date
            const endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + 30);
            $('#valid_until').val(endDate.toISOString().slice(0, 10));
        }
    });
});
</script>
@endpush