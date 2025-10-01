@extends('backend.layout.main')

@section('page_title', 'Kelola Permission')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item active">Permission</li>
@endsection

@section('page_actions')
<div class="btn-group">
    <a href="{{ route('backend.permissions.test') }}" class="btn btn-info">
        <i class="fas fa-vial mr-1"></i>Test Permission
    </a>
</div>
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

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <!-- Role Permissions -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-tag mr-2"></i>Permission Role
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('backend.permissions.update-role') }}">
                        @csrf
                        <div class="form-group">
                            <label for="role_id">Pilih Role:</label>
                            <select id="role_id" name="role_id" class="form-control" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="role-permissions" class="d-none">
                            <label class="font-weight-bold">Permission:</label>
                            <div class="row">
                                @foreach($permissions->groupBy(function($item) {
                                    return explode('.', $item->name)[0];
                                }) as $group => $groupPermissions)
                                <div class="col-md-6">
                                    <h6 class="text-primary">{{ ucfirst(str_replace('_', ' ', $group)) }}</h6>
                                    @foreach($groupPermissions as $permission)
                                    <div class="form-check">
                                        <input class="form-check-input role-permission" 
                                               type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}" 
                                               id="role_perm_{{ $permission->id }}">
                                        <label class="form-check-label" for="role_perm_{{ $permission->id }}">
                                            {{ $permission->display_name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3" id="update-role-btn" disabled>
                            <i class="fas fa-save mr-1"></i>Update Permission Role
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Permissions -->
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-cog mr-2"></i>Permission User
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('backend.permissions.update-user') }}">
                        @csrf
                        <div class="form-group">
                            <label for="user_id">Pilih User:</label>
                            <select id="user_id" name="user_id" class="form-control" required>
                                <option value="">-- Pilih User --</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="user-permissions" class="d-none">
                            <label class="font-weight-bold">Permission:</label>
                            <div class="row">
                                @foreach($permissions->groupBy(function($item) {
                                    return explode('.', $item->name)[0];
                                }) as $group => $groupPermissions)
                                <div class="col-md-6">
                                    <h6 class="text-info">{{ ucfirst(str_replace('_', ' ', $group)) }}</h6>
                                    @foreach($groupPermissions as $permission)
                                    <div class="form-check">
                                        <input class="form-check-input user-permission" 
                                               type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}" 
                                               id="user_perm_{{ $permission->id }}">
                                        <label class="form-check-label" for="user_perm_{{ $permission->id }}">
                                            {{ $permission->display_name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-info mt-3" id="update-user-btn" disabled>
                            <i class="fas fa-save mr-1"></i>Update Permission User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Overview -->
    <div class="row">
        <div class="col-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-2"></i>Daftar Permission
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Permission</th>
                                    <th>Display Name</th>
                                    <th>Deskripsi</th>
                                    <th>Roles yang Memiliki</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $permission)
                                <tr>
                                    <td><code>{{ $permission->name }}</code></td>
                                    <td>{{ $permission->display_name }}</td>
                                    <td>{{ $permission->description }}</td>
                                    <td>
                                        @foreach($roles as $role)
                                            @if($role->permissions->contains('id', $permission->id))
                                                <span class="badge badge-primary mr-1">{{ $role->display_name }}</span>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Role selection handler
    $('#role_id').on('change', function() {
        const roleId = $(this).val();
        
        if (roleId) {
            $('#role-permissions').removeClass('d-none');
            $('#update-role-btn').prop('disabled', false);
            
            // Get role permissions via AJAX or use data from server
            $.get(`{{ route('backend.permissions.index') }}?role_id=${roleId}`, function(data) {
                // Handle response if needed
            });
            
            // Load existing permissions for selected role
            loadRolePermissions(roleId);
        } else {
            $('#role-permissions').addClass('d-none');
            $('#update-role-btn').prop('disabled', true);
            $('.role-permission').prop('checked', false);
        }
    });

    // User selection handler
    $('#user_id').on('change', function() {
        const userId = $(this).val();
        
        if (userId) {
            $('#user-permissions').removeClass('d-none');
            $('#update-user-btn').prop('disabled', false);
            
            // Load existing permissions for selected user
            loadUserPermissions(userId);
        } else {
            $('#user-permissions').addClass('d-none');
            $('#update-user-btn').prop('disabled', true);
            $('.user-permission').prop('checked', false);
        }
    });

    function loadRolePermissions(roleId) {
        // Get role data from server-side rendered data
        @foreach($roles as $role)
            if ({{ $role->id }} == roleId) {
                $('.role-permission').prop('checked', false);
                @foreach($role->permissions as $permission)
                    $('#role_perm_{{ $permission->id }}').prop('checked', true);
                @endforeach
            }
        @endforeach
    }

    function loadUserPermissions(userId) {
        // Get user data from server-side rendered data
        @foreach($users as $user)
            if ({{ $user->id }} == userId) {
                $('.user-permission').prop('checked', false);
                @foreach($user->permissions as $permission)
                    $('#user_perm_{{ $permission->id }}').prop('checked', true);
                @endforeach
            }
        @endforeach
    }

    // Form submission with loading state
    $('form').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');
    });
});
</script>
@endpush