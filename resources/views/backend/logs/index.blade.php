@extends('backend.layout.main')

@section('page_title', 'System Logs')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item active">Logs</li>
@endsection

@section('page_actions')
@can('clear-logs')
<button type="button" class="btn btn-danger" onclick="clearLogs()">
    <i class="fas fa-trash"></i> Clear Logs
</button>
@endcan
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
                    <h3 class="card-title">System Logs</h3>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search logs..." value="{{ $search }}">
                            </div>
                            <div class="col-md-2">
                                <select name="level" class="form-control">
                                    <option value="">All Levels</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level }}" {{ $currentLevel == $level ? 'selected' : '' }}>
                                            {{ ucfirst($level) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date" class="form-control" 
                                       placeholder="Filter by date" value="{{ $currentDate }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.logs') }}" class="btn btn-secondary">Clear Filters</a>
                            </div>
                        </div>
                    </form>

                    <!-- Logs Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>DateTime</th>
                                    <th>Level</th>
                                    <th>Message</th>
                                    <th>Context</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr class="{{ $log['level'] == 'error' || $log['level'] == 'critical' ? 'table-danger' : 
                                                ($log['level'] == 'warning' ? 'table-warning' : '') }}">
                                        <td>
                                            <small>{{ $log['datetime'] }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ 
                                                $log['level'] == 'error' || $log['level'] == 'critical' || $log['level'] == 'emergency' || $log['level'] == 'alert' ? 'danger' : 
                                                ($log['level'] == 'warning' ? 'warning' : 
                                                ($log['level'] == 'info' || $log['level'] == 'notice' ? 'info' : 'secondary')) 
                                            }}">
                                                {{ strtoupper($log['level']) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div style="max-width: 500px; word-break: break-word;">
                                                {{ $log['message'] }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($log['context'])
                                                <small class="text-muted">{{ $log['context'] }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            No log entries found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($logs->count() >= 50)
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            Showing the latest 50 log entries. Use filters to narrow down results.
                        </div>
                    @endif
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

    function clearLogs() {
        if (confirm('Are you sure you want to clear all logs? This action cannot be undone.')) {
            fetch('/admin/logs/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success('Logs cleared successfully!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    toastr.error(data.message || 'An error occurred while clearing logs.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred while clearing logs.');
            });
        }
    }

    // Auto-refresh every 30 seconds for error/critical logs
    @if($currentLevel == 'error' || $currentLevel == 'critical')
    setInterval(function() {
        if (!document.hidden) {
            refreshLogs();
        }
    }, 30000);
    @endif
</script>
@endpush