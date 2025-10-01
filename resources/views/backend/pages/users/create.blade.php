@extends('backend.layout.main')

@section('title', 'Tambah Pengguna')
@section('header', 'Tambah Pengguna')
@section('description', 'Membuat akun pengguna baru')

@section('content')
<section class="content">
    <div class="container-fluid">
        <!-- Form Card -->
        <form action="{{ route('backend.users.store') }}" method="POST" enctype="multipart/form-data" id="user-form">
            @csrf
            
            <div class="card card-primary">
                <!-- Header -->
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus mr-1"></i>
                        Informasi Pengguna
                    </h3>
                    <div class="card-tools">
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
                                     src="{{ asset('dist/img/user2-160x160.jpg') }}" alt="Avatar Preview">
                            </div>
                            <div>
                                <div class="custom-file mb-2" style="width: 200px;">
                                    <input type="file" class="custom-file-input" id="avatar" name="avatar" accept="image/*">
                                    <label class="custom-file-label" for="avatar">Pilih foto</label>
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
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
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
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
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
                                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                    @endcan
                                    @can('assign-admin-role')
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    @endcan
                                    @can('assign-operator-role')
                                    <option value="operator" {{ old('role') === 'operator' ? 'selected' : '' }}>Operator</option>
                                    @endcan
                                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Password Fields -->
                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" required
                                           class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password">
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

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">
                                    Konfirmasi Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                           class="form-control" placeholder="Konfirmasi password">
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
                    <h5 class="mt-4 mb-3">Informasi Tambahan</h5>
                    
                    <div class="row">
                        <!-- Address -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea name="address" id="address" rows="3"
                                          class="form-control @error('address') is-invalid @enderror" placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                                       class="form-control @error('date_of_birth') is-invalid @enderror">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Account Settings -->
                    <h5 class="mt-4 mb-3">Pengaturan Akun</h5>
                    
                    <div class="row">
                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label">Status Akun</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Email Verification -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="email_verified" id="email_verified" value="1" 
                                           {{ old('email_verified') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="email_verified">
                                        Email sudah terverifikasi
                                    </label>
                                </div>
                            </div>

                            <!-- Send Welcome Email -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="send_welcome_email" id="send_welcome_email" value="1" checked>
                                    <label class="custom-control-label" for="send_welcome_email">
                                        Kirim email selamat datang
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

                </div>
                <!-- Actions -->
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <button type="button" onclick="resetForm()" class="btn btn-outline-secondary">
                            <i class="fas fa-undo mr-1"></i>Reset Form
                        </button>
                        
                        <div>
                            <a href="{{ route('backend.users.index') }}" class="btn btn-default mr-2">
                                <i class="fas fa-times mr-1"></i>Batal
                            </a>
                            <button type="submit" id="submit-btn" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                <span id="submit-text">Simpan Pengguna</span>
                                <i id="submit-loading" class="fas fa-spinner fa-spin ml-1 d-none"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    </form>

        <!-- Role Permissions Preview -->
        <div id="role-permissions" class="card card-secondary collapsed-card d-none">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-key mr-1"></i>
                    Hak Akses Role
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Preview hak akses yang akan dimiliki pengguna berdasarkan role yang dipilih.</p>
                <div id="permissions-list" class="row">
                    <!-- Permissions will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Avatar preview
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatar-preview');

        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Role change handler
        const roleSelect = document.getElementById('role');
        roleSelect.addEventListener('change', function() {
            if (this.value) {
                showRolePermissions(this.value);
            } else {
                hideRolePermissions();
            }
        });

        // Initialize custom file input
        if (typeof bsCustomFileInput !== 'undefined') {
            bsCustomFileInput.init();
        }

        // Handle custom file input change
        $('#avatar').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass('selected').html(fileName);
        });

        // Form validation
        const form = document.getElementById('user-form');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateForm()) {
                submitForm();
            }
        });

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });

        // Real-time password confirmation
        const passwordConfirmInput = document.getElementById('password_confirmation');
        passwordConfirmInput.addEventListener('input', function() {
            checkPasswordMatch();
        });
    });

    function removeAvatar() {
        document.getElementById('avatar').value = '';
        document.getElementById('avatar-preview').src = '{{ asset('dist/img/user2-160x160.jpg') }}';
        $('.custom-file-label').text('Pilih foto');
    }

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

    function showRolePermissions(role) {
        const rolePermissions = document.getElementById('role-permissions');
        const permissionsList = document.getElementById('permissions-list');
        
        // Show loading
        permissionsList.innerHTML = '<div class="col-12 text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat hak akses...</div>';
        rolePermissions.classList.remove('d-none');

        // Fetch permissions for role (mock data for now)
        setTimeout(() => {
            let permissions = [];
            if (role === 'super_admin') {
                permissions = [
                    {name: 'Kelola Semua Data', description: 'Akses penuh ke semua fitur'},
                    {name: 'Kelola Pengguna', description: 'Tambah, edit, hapus pengguna'},
                    {name: 'Kelola Sistem', description: 'Konfigurasi sistem'}
                ];
            } else if (role === 'admin') {
                permissions = [
                    {name: 'Kelola Data Desa', description: 'Kelola informasi desa'},
                    {name: 'Kelola Berita', description: 'Kelola berita dan pengumuman'},
                    {name: 'Kelola UMKM', description: 'Kelola data UMKM'}
                ];
            } else if (role === 'operator') {
                permissions = [
                    {name: 'Input Data', description: 'Menginput data dasar'},
                    {name: 'Lihat Laporan', description: 'Melihat laporan sistem'}
                ];
            } else {
                permissions = [
                    {name: 'Lihat Data Publik', description: 'Melihat informasi publik'}
                ];
            }

            if (permissions.length > 0) {
                let html = '';
                permissions.forEach(permission => {
                    html += `
                        <div class="col-md-4 mb-3">
                            <div class="card card-outline card-success">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                        <div>
                                            <h6 class="mb-0">${permission.name}</h6>
                                            <small class="text-muted">${permission.description}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                permissionsList.innerHTML = html;
            } else {
                permissionsList.innerHTML = '<div class="col-12 text-center py-4 text-muted">Tidak ada hak akses khusus untuk role ini</div>';
            }
        }, 500);
    }

    function hideRolePermissions() {
        document.getElementById('role-permissions').classList.add('d-none');
    }

    function checkPasswordStrength(password) {
        // Add password strength indicator logic here
        // This is a basic example - you can enhance it
        const strengthIndicator = document.getElementById('password-strength');
        if (!strengthIndicator) return;

        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        // Update strength indicator display
    }

    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;
        const confirmField = document.getElementById('password_confirmation');

        if (confirmation && password !== confirmation) {
            confirmField.classList.add('is-invalid');
        } else {
            confirmField.classList.remove('is-invalid');
        }
    }

    function validateForm() {
        let isValid = true;
        const requiredFields = ['name', 'email', 'password', 'password_confirmation', 'role'];
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Check password match
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;
        if (password !== confirmation) {
            document.getElementById('password_confirmation').classList.add('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            toastr.error('Mohon lengkapi semua field yang wajib diisi');
        }

        return isValid;
    }

    function submitForm() {
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const submitLoading = document.getElementById('submit-loading');

        // Disable submit button
        submitBtn.disabled = true;
        submitText.textContent = 'Menyimpan...';
        submitLoading.classList.remove('d-none');

        // Submit form normally (let Laravel handle the response)
        document.getElementById('user-form').submit();
    }

    function resetForm() {
        if (confirm('Apakah Anda yakin ingin mereset form? Semua data yang telah diisi akan hilang.')) {
            document.getElementById('user-form').reset();
            removeAvatar();
            hideRolePermissions();
            
            // Remove validation errors
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            
            // Reset custom file input
            $('.custom-file-label').text('Pilih foto');
        }
    }
</script>
@endpush