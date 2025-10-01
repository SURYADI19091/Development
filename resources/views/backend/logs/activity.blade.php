@extends('backend.layout.main')

@section('page_title', 'Activity Logs')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item active">Activity Logs</li>
@endsection

@section('page_actions')
<button type="button" class="btn btn-primary" onclick="refreshLogs()">
    <i class="fas fa-sync-alt"></i> Refresh
</button>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Activity Logs</h3>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search activities..." value="{{ $search }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="user" class="form-control" 
                                       placeholder="Filter by user" value="{{ $user_filter }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="action" class="form-control" 
                                       placeholder="Filter by action" value="{{ $action_filter }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.activity-logs') }}" class="btn btn-secondary">Clear Filters</a>
                            </div>
                        </div>
                    </form>

                    <!-- Activity Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th>IP Address</th>
                                    <th>DateTime</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                    <tr>
                                        <td>{{ $activity['id'] }}</td>
                                        <td>
                                            <strong>{{ $activity['user'] }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ 
                                                str_contains($activity['action'], 'Login') ? 'success' : 
                                                (str_contains($activity['action'], 'Delete') ? 'danger' : 
                                                (str_contains($activity['action'], 'Update') ? 'warning' : 'info')) 
                                            }}">
                                                {{ $activity['action'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div style="max-width: 400px; word-break: break-word;">
                                                {{ $activity['description'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $activity['ip_address'] }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $activity['created_at']->format('M d, Y H:i:s') }}</small>
                                            <br>
                                            <small class="text-muted">{{ $activity['created_at']->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No activity logs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        Activity logs help track user actions for security and audit purposes.
                        <br>
                        <strong>Note:</strong> This is a basic implementation. In production, you would implement a proper activity logging system.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Login Activities</h5>
                            <h3>{{ $activities->where('action', 'User Login')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-sign-in-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Create Actions</h5>
                            <h3>{{ $activities->filter(function($item) { return str_contains($item['action'], 'Created'); })->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-plus fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Update Actions</h5>
                            <h3>{{ $activities->filter(function($item) { return str_contains($item['action'], 'Updated'); })->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-edit fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Delete Actions</h5>
                            <h3>{{ $activities->filter(function($item) { return str_contains($item['action'], 'Deleted'); })->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-trash fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function refreshLogs() {
        window.location.reload();
    }

    // Auto-refresh every 60 seconds
    setInterval(function() {
        if (!document.hidden) {
            refreshLogs();
        }
    }, 60000);
</script>
@endpush