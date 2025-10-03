@extends('backend.layout.main')

@section('page_title', $announcement->title)
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.announcements.index') }}">Announcements</a></li>
<li class="breadcrumb-item active">{{ $announcement->title }}</li>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bullhorn mr-2"></i>{{ $announcement->title }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('backend.announcements.edit', $announcement) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <a href="{{ route('backend.announcements.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($announcement->excerpt)
                    <div class="alert alert-info">
                        <strong>Summary:</strong> {{ $announcement->excerpt }}
                    </div>
                    @endif

                    <div class="content">
                        {!! $announcement->content !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>Announcement Details
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge {{ $announcement->is_active ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Priority:</strong></td>
                            <td>
                                <span class="badge 
                                    @if($announcement->priority == 'urgent') badge-danger
                                    @elseif($announcement->priority == 'high') badge-warning
                                    @elseif($announcement->priority == 'medium') badge-info
                                    @else badge-secondary
                                    @endif">
                                    {{ ucfirst($announcement->priority) }}
                                </span>
                            </td>
                        </tr>
                        @if($announcement->category)
                        <tr>
                            <td><strong>Category:</strong></td>
                            <td>{{ $announcement->category }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td><strong>Author:</strong></td>
                            <td>{{ $announcement->author->name ?? 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td>{{ $announcement->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Updated:</strong></td>
                            <td>{{ $announcement->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                        @if($announcement->valid_from)
                        <tr>
                            <td><strong>Valid From:</strong></td>
                            <td>{{ $announcement->valid_from->format('d M Y') }}</td>
                        </tr>
                        @endif
                        @if($announcement->valid_until)
                        <tr>
                            <td><strong>Valid Until:</strong></td>
                            <td>{{ $announcement->valid_until->format('d M Y') }}</td>
                        </tr>
                        @endif

                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog mr-2"></i>Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('announcements.show', $announcement->id) }}" target="_blank" class="btn btn-info btn-block">
                            <i class="fas fa-eye mr-1"></i>View on Website
                        </a>

                        @if($announcement->is_active)
                        <button type="button" class="btn btn-warning btn-block" onclick="toggleStatus(false)">
                            <i class="fas fa-pause mr-1"></i>Deactivate
                        </button>
                        @else
                        <button type="button" class="btn btn-success btn-block" onclick="toggleStatus(true)">
                            <i class="fas fa-play mr-1"></i>Activate
                        </button>
                        @endif

                        <button type="button" class="btn btn-danger btn-block" onclick="deleteAnnouncement()">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(status) {
    const action = status ? 'activate' : 'deactivate';
    if (confirm(`Are you sure you want to ${action} this announcement?`)) {
        fetch(`{{ route('backend.announcements.toggle-status', $announcement) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ is_active: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error occurred while updating status');
        });
    }
}

function deleteAnnouncement() {
    if (confirm('Are you sure you want to delete this announcement? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('backend.announcements.destroy', $announcement) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush