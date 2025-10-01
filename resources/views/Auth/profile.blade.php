@extends('backend.layout.main')

@section('page_title', 'My Profile')
@section('breadcrumb')
<li class="breadcrumb-item">Profile</li>
<li class="breadcrumb-item active">My Profile</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Profile Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Profile Information</h3>
                </div>
                <div class="card-body text-center">
                    <div class="profile-avatar mb-3">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" 
                                 class="rounded-circle" width="100" height="100" alt="Avatar">
                        @else
                            <div class="bg-primary rounded-circle d-inline-flex justify-content-center align-items-center" 
                                 style="width: 100px; height: 100px;">
                                <span class="text-white font-weight-bold" style="font-size: 2rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="badge badge-{{ $user->role == 'super_admin' ? 'danger' : ($user->role == 'admin' ? 'warning' : 'primary') }}">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </span>
                    <br>
                    <span class="badge badge-{{ $user->status == 'active' ? 'success' : 'secondary' }} mt-2">
                        {{ ucfirst($user->status) }}
                    </span>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            Member since {{ $user->created_at->format('M Y') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Quick Stats</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</h4>
                            <small class="text-muted">Last Login</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $user->login_count ?? 0 }}</h4>
                            <small class="text-muted">Total Logins</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Forms -->
        <div class="col-md-8">
            <!-- Update Profile Form -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Update Profile</h3>
                </div>
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" 
                                           value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <input type="date" 
                                           class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" 
                                           value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="avatar">Profile Picture</label>
                            <input type="file" 
                                   class="form-control-file @error('avatar') is-invalid @enderror" 
                                   id="avatar" name="avatar" 
                                   accept="image/*">
                            <small class="form-text text-muted">Maximum file size: 2MB. Formats: JPG, PNG, GIF</small>
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Change Password</h3>
                </div>
                <form action="{{ route('user.password.change') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="current_password">Current Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" 
                                   required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">New Password <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" 
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm New Password <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Password must be at least 8 characters long and contain a mix of letters, numbers, and symbols.
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Show password strength indicator
    $('#password').on('input', function() {
        const password = $(this).val();
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        const strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        const strengthClass = ['danger', 'warning', 'info', 'primary', 'success'];
        
        if (password.length > 0) {
            if (!$('.password-strength').length) {
                $(this).after('<div class="password-strength mt-1"><small class="strength-text"></small></div>');
            }
            $('.strength-text').html(`Password Strength: <span class="text-${strengthClass[strength-1] || 'danger'}">${strengthText[strength-1] || 'Very Weak'}</span>`);
        } else {
            $('.password-strength').remove();
        }
    });

    // Preview avatar
    $('#avatar').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.profile-avatar img, .profile-avatar div').replaceWith(
                    `<img src="${e.target.result}" class="rounded-circle" width="100" height="100" alt="Avatar Preview">`
                );
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush