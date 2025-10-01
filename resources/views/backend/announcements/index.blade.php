@extends('backend.layout.main')

@section('page_title', 'Announcements Management')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item active">Announcements</li>
@endsection

@section('page_actions')
<a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Add Announcement
</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Announcements List</h3>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search announcements..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="priority" class="form-control">
                                    <option value="">All Priorities</option>
                                    @foreach($priorities as $priority)
                                        <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                                            {{ ucfirst($priority) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_from" class="form-control" 
                                       placeholder="From Date" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_to" class="form-control" 
                                       placeholder="To Date" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <!-- Bulk Actions -->
                    <form id="bulkActionForm" method="POST" action="{{ route('admin.announcements.bulk-action') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select name="action" id="bulkAction" class="form-control">
                                    <option value="">Select Action</option>
                                    <option value="activate">Activate</option>
                                    <option value="deactivate">Deactivate</option>
                                    <option value="delete">Delete</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-warning" disabled id="bulkActionBtn">
                                    Apply Action
                                </button>
                            </div>
                        </div>

                        <!-- Announcements Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="30">
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th>Title</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Views</th>
                                        <th>Author</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($announcements as $announcement)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="announcements[]" 
                                                       value="{{ $announcement->id }}" class="announcement-checkbox">
                                            </td>
                                            <td>
                                                <strong>{{ $announcement->title }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ Str::limit(strip_tags($announcement->content), 100) }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ 
                                                    $announcement->priority == 'urgent' ? 'danger' : 
                                                    ($announcement->priority == 'high' ? 'warning' : 
                                                    ($announcement->priority == 'medium' ? 'info' : 'secondary')) 
                                                }}">
                                                    {{ ucfirst($announcement->priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $announcement->is_active ? 'success' : 'secondary' }}">
                                                    {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $announcement->start_date->format('M d, Y') }}</td>
                                            <td>{{ $announcement->end_date->format('M d, Y') }}</td>
                                            <td>{{ number_format($announcement->views_count) }}</td>
                                            <td>{{ $announcement->author->name }}</td>
                                            <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.announcements.show', $announcement->id) }}" 
                                                       class="btn btn-sm btn-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.announcements.edit', $announcement->id) }}" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-{{ $announcement->is_active ? 'secondary' : 'success' }}" 
                                                            onclick="toggleStatus({{ $announcement->id }})" title="Toggle Status">
                                                        <i class="fas fa-{{ $announcement->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                    <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" 
                                                          method="POST" class="d-inline" 
                                                          onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">
                                                No announcements found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $announcements->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Select All functionality
    $('#selectAll').change(function() {
        $('.announcement-checkbox').prop('checked', $(this).prop('checked'));
        toggleBulkActionButton();
    });

    $('.announcement-checkbox').change(function() {
        toggleBulkActionButton();
    });

    $('#bulkAction').change(function() {
        toggleBulkActionButton();
    });

    function toggleBulkActionButton() {
        const checkedCount = $('.announcement-checkbox:checked').length;
        const actionSelected = $('#bulkAction').val();
        $('#bulkActionBtn').prop('disabled', checkedCount === 0 || !actionSelected);
    }

    // Bulk action form submission
    $('#bulkActionForm').submit(function(e) {
        const action = $('#bulkAction').val();
        const checkedCount = $('.announcement-checkbox:checked').length;
        
        if (checkedCount === 0) {
            e.preventDefault();
            alert('Please select at least one announcement.');
            return false;
        }

        if (action === 'delete') {
            if (!confirm(`Are you sure you want to delete ${checkedCount} announcement(s)?`)) {
                e.preventDefault();
                return false;
            }
        }
    });

    // Toggle status function
    function toggleStatus(announcementId) {
        fetch(`/admin/announcements/${announcementId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                toastr.success(data.message);
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while updating the status.');
        });
    }
</script>
@endpush