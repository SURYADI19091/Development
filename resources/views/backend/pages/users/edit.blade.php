@extends('backend.layout.main')

@section('title', 'Edit Pengguna')
@section('header', 'Edit Pengguna')
@section('description', 'Mengedit informasi pengguna')

@section('content')
<section class="content">
    <div class="container-fluid">
        <!-- Form Card -->
        <form action="{{ route('backend.users.update', $user) }}" method="POST" enctype="multipart/form-data" id="user-form">
            @csrf
            @method('PUT')
            
            <div class="card card-primary">
                <!-- Header -->
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit mr-1"></i>
                        Informasi Pengguna
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('backend.users.show', $user) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye mr-1"></i>Lihat Detail
                        </a>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Avatar Upload -->
                    <div class="form-group">
                        <label class="form-label">Foto Profil</label>
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <img id="avatar-preview" class="img-circle elevation-2" width="80" height="80"
                                     src="{{ $user->avatar_url ?? asset('dist/img/user2-160x160.jpg') }}" alt="Avatar Preview">
                            </div>
                            <div>
                                <div class="custom-file mb-2" style="width: 200px;">
                                    <input type="file" class="custom-file-input" id="avatar" name="avatar" accept="image/*">
                                    <label class="custom-file-label" for="avatar">Pilih foto baru</label>
                                </div>
                                <button type="button" onclick="removeAvatar()" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                                <small class="form-text text-muted d-block">Format: PNG, JPG, GIF. Maksimal 2MB</small>
                            </div>
                        </div>
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Basic Information -->
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                       class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                       class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan alamat email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                       class="form-control @error('phone') is-invalid @enderror" placeholder="Masukkan nomor telepon">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role" class="form-label">
                                    Role <span class="text-danger">*</span>
                                </label>
                                <select name="role" id="role" required class="form-control @error('role') is-invalid @enderror">
                                    <option value="">Pilih Role</option>
                                    @can('assign-super-admin-role')
                                    <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                    @endcan
                                    @can('assign-admin-role')
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                    @endcan
                                    @can('assign-operator-role')
                                    <option value="operator" {{ old('role', $user->role) === 'operator' ? 'selected' : '' }}>Operator</option>
                                    @endcan
                                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Terakhir Login</label>
                                <input type="text" value="{{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum pernah login' }}" 
                                       class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Password Fields (Optional for Edit) -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle mr-1"></i>Ubah Password (Opsional)</h6>
                        <p class="mb-0">Biarkan kosong jika tidak ingin mengubah password.</p>
                    </div>

                    <div class="row">
                        <!-- New Password -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password baru (opsional)">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                            <i id="password-icon" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    Minimal 8 karakter, kombinasi huruf dan angka
                                </small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control" placeholder="Konfirmasi password baru">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                            <i id="password_confirmation-icon" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea name="address" id="address" rows="3" 
                                          class="form-control @error('address') is-invalid @enderror" 
                                          placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea name="notes" id="notes" rows="3" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          placeholder="Catatan tambahan (opsional)">{{ old('notes', $user->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Notification Settings -->
                    <div class="form-group">
                        <label class="form-label">Pengaturan Notifikasi</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="email_notifications" name="email_notifications" value="1" 
                                   {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="email_notifications">Terima notifikasi email</label>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('backend.users.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times mr-1"></i>
                                Batal
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <small class="text-muted">
                                Dibuat: {{ $user->created_at->format('d M Y H:i') }}<br>
                                Diperbarui: {{ $user->updated_at->format('d M Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('styles')
<style>
    .img-circle {
        border-radius: 50%;
        object-fit: cover;
    }
    
    .custom-file-label::after {
        content: "Browse";
    }
    
    .alert-info {
        border-left: 4px solid #17a2b8;
    }
</style>
@endpush

@push('scripts')
<script>
    // Avatar preview
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
            
            // Update label
            const fileName = file.name;
            document.querySelector('.custom-file-label').textContent = fileName;
        }
    });

    // Remove avatar
    function removeAvatar() {
        document.getElementById('avatar').value = '';
        document.getElementById('avatar-preview').src = '{{ asset('dist/img/user2-160x160.jpg') }}';
        document.querySelector('.custom-file-label').textContent = 'Pilih foto baru';
    }

    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Form validation
    document.getElementById('user-form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password && password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok!');
            return false;
        }
        
        // Show loading state
        const submitBtn = document.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...';
    });
</script>
@endpush