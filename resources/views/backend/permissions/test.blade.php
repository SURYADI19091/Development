@extends('backend.layout.main')

@section('page_title', 'Test Permission')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.permissions.index') }}">Permission</a></li>
<li class="breadcrumb-item active">Test Permission</li>
@endsection

@section('page_actions')
<div class="btn-group">
    <a href="{{ route('backend.permissions.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i>Kembali
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- User Info -->
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user mr-2"></i>Informasi User
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Nama:</strong></td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Role:</strong></td>
                            <td>
                                <span class="badge badge-primary">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Permission Test Results -->
        <div class="col-md-8">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-vial mr-2"></i>Hasil Test Permission
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($allPermissions->groupBy(function($item) {
                            return explode('.', $item->name)[0];
                        }) as $group => $groupPermissions)
                        <div class="col-md-6 mb-4">
                            <h5 class="text-info border-bottom pb-2">
                                <i class="fas fa-folder mr-2"></i>{{ ucfirst(str_replace('_', ' ', $group)) }}
                            </h5>
                            @foreach($groupPermissions as $permission)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-sm">{{ $permission->display_name }}</span>
                                @if($userPermissions[$permission->name])
                                    <span class="badge badge-success">
                                        <i class="fas fa-check mr-1"></i>GRANTED
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times mr-1"></i>DENIED
                                    </span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row">
        <div class="col-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-2"></i>Ringkasan Permission
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Permission Granted</span>
                                    <span class="info-box-number">
                                        {{ collect($userPermissions)->filter()->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-times"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Permission Denied</span>
                                    <span class="info-box-number">
                                        {{ collect($userPermissions)->reject()->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Permission</span>
                                    <span class="info-box-number">{{ $allPermissions->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Coverage</span>
                                    <span class="info-box-number">
                                        {{ $allPermissions->count() > 0 ? round((collect($userPermissions)->filter()->count() / $allPermissions->count()) * 100) : 0 }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Commands -->
    <div class="row">
        <div class="col-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-terminal mr-2"></i>Test Commands
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Contoh penggunaan permission di Blade templates:</p>
                    <div class="row">
                        @foreach($allPermissions->take(6) as $permission)
                        <div class="col-md-6 mb-2">
                            <code class="d-block bg-light p-2 rounded">
                                @verbatim @can('{{ $permission->name }}') ... @endcan @endverbatim
                            </code>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection