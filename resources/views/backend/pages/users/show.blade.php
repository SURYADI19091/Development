@extends('backend.layout.main')

@section('title', 'Detail Pengguna')
@section('header', 'Detail Pengguna')
@section('description', 'Informasi lengkap pengguna')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- User Profile -->
            <div class="col-md-4">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                 src="{{ $user->avatar_url ?? asset('dist/img/user2-160x160.jpg') }}"
                                 alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">{{ $user->name }}</h3>

                        <p class="text-muted text-center">
                            <span class="badge badge-{{ $user->status === 'active' ? 'success' : ($user->status === 'inactive' ? 'secondary' : 'warning') }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Role</b> 
                                <a class="float-right">
                                    <span class="badge badge-primary">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span>
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Telepon</b> <a class="float-right">{{ $user->phone ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Bergabung</b> <a class="float-right">{{ $user->created_at->format('d M Y') }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Terakhir Login</b> 
                                <a class="float-right">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum pernah' }}
                                </a>
                            </li>
                        </ul>

                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('backend.users.edit', $user) }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </div>
                            <div class="col-6">
                                @if($user->status === 'active')
                                    <button class="btn btn-warning btn-block" onclick="changeStatus('{{ $user->id }}', 'inactive')">
                                        <i class="fas fa-pause mr-1"></i>Nonaktifkan
                                    </button>
                                @else
                                    <button class="btn btn-success btn-block" onclick="changeStatus('{{ $user->id }}', 'active')">
                                        <i class="fas fa-play mr-1"></i>Aktifkan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Kontak</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>
                        <p class="text-muted">
                            {{ $user->address ?? 'Alamat tidak tersedia' }}
                        </p>

                        <hr>

                        <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                        <p class="text-muted">{{ $user->email }}</p>

                        <hr>

                        <strong><i class="fas fa-phone mr-1"></i> Telepon</strong>
                        <p class="text-muted">{{ $user->phone ?? 'Tidak tersedia' }}</p>

                        <hr>

                        <strong><i class="fas fa-bell mr-1"></i> Notifikasi Email</strong>
                        <p class="text-muted">
                            <span class="badge badge-{{ $user->email_notifications ? 'success' : 'secondary' }}">
                                {{ $user->email_notifications ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- User Details -->
            <div class="col-md-8">
                <!-- Account Details -->
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab">Detail Akun</a></li>
                            <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Aktivitas</a></li>
                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Pengaturan</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Account Details Tab -->
                            <div class="active tab-pane" id="details">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ID Pengguna</label>
                                            <input type="text" class="form-control" value="{{ $user->id }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" class="form-control" value="{{ $user->username ?? $user->email }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Lengkap</label>
                                            <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Role</label>
                                            <input type="text" class="form-control" value="{{ ucwords(str_replace('_', ' ', $user->role)) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <input type="text" class="form-control" value="{{ ucfirst($user->status) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal Bergabung</label>
                                            <input type="text" class="form-control" value="{{ $user->created_at->format('d M Y H:i') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Terakhir Diperbarui</label>
                                            <input type="text" class="form-control" value="{{ $user->updated_at->format('d M Y H:i') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Catatan</label>
                                            <textarea class="form-control" rows="3" readonly>{{ $user->notes ?? 'Tidak ada catatan' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Activity Tab -->
                            <div class="tab-pane" id="activity">
                                <div class="timeline timeline-inverse">
                                    @forelse($activities as $activity)
                                    <div class="time-label">
                                        <span class="bg-success">
                                            {{ $activity['date'] ?? 'Hari ini' }}
                                        </span>
                                    </div>
                                    <div>
                                        <i class="fas fa-user bg-info"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i> {{ $activity['time'] ?? 'Tidak diketahui' }}</span>
                                            <h3 class="timeline-header">{{ $activity['title'] ?? 'Aktivitas' }}</h3>
                                            <div class="timeline-body">
                                                {{ $activity['description'] ?? 'Tidak ada deskripsi' }}
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Belum ada aktivitas yang tercatat untuk pengguna ini.
                                    </div>
                                    @endforelse
                                    
                                    <!-- Default Activities -->
                                    <div class="time-label">
                                        <span class="bg-primary">{{ $user->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-user-plus bg-success"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i> {{ $user->created_at->format('H:i') }}</span>
                                            <h3 class="timeline-header">Akun Dibuat</h3>
                                            <div class="timeline-body">
                                                Pengguna {{ $user->name }} berhasil mendaftar dengan role {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <i class="far fa-clock bg-gray"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Settings Tab -->
                            <div class="tab-pane" id="settings">
                                <form class="form-horizontal" id="settings-form">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="change_status" class="col-sm-3 col-form-label">Status Akun</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="change_status" name="status">
                                                <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                                <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                                <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email_notifications" class="col-sm-3 col-form-label">Notifikasi Email</label>
                                        <div class="col-sm-9">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="email_notifications" 
                                                       name="email_notifications" {{ $user->email_notifications ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="email_notifications">Aktifkan notifikasi email</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-3 col-sm-9">
                                            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                                            @can('delete-users')
                                            <button type="button" class="btn btn-danger ml-2" onclick="deleteUser('{{ $user->id }}')">
                                                Hapus Pengguna
                                            </button>
                                            @endcan
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <a href="{{ route('backend.users.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali ke Daftar
                        </a>
                        <a href="{{ route('backend.users.edit', $user) }}" class="btn btn-primary ml-2">
                            <i class="fas fa-edit mr-1"></i>Edit Pengguna
                        </a>
                        @if($user->id !== auth()->id())
                        <button class="btn btn-warning ml-2" onclick="resetPassword('{{ $user->id }}')">
                            <i class="fas fa-key mr-1"></i>Reset Password
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .profile-user-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
    
    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #ddd;
        left: 31px;
        margin: 0;
        border-radius: 2px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Change user status
    function changeStatus(userId, status) {
        if (confirm('Apakah Anda yakin ingin mengubah status pengguna ini?')) {
            fetch(`/admin/users/${userId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengubah status');
            });
        }
    }

    // Delete user
    function deleteUser(userId) {
        if (confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')) {
            fetch(`/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '{{ route("backend.users.index") }}';
                } else {
                    alert('Terjadi kesalahan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus pengguna');
            });
        }
    }

    // Reset password
    function resetPassword(userId) {
        if (confirm('Apakah Anda yakin ingin reset password pengguna ini?')) {
            // You can implement password reset functionality here
            alert('Fitur reset password akan segera tersedia');
        }
    }

    // Settings form
    document.getElementById('settings-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(`/admin/users/{{ $user->id }}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pengaturan berhasil disimpan');
                location.reload();
            } else {
                alert('Terjadi kesalahan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan pengaturan');
        });
    });
</script>
@endpush