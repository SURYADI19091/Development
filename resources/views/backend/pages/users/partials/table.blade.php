@if($users->count() > 0)
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="40">
                    <input type="checkbox" id="select-all">
                </th>
                <th width="60">Foto</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Terakhir Login</th>
                <th width="120">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <input type="checkbox" class="user-checkbox" value="{{ $user->id }}">
                </td>
                <td class="text-center">
                    <img src="{{ $user->avatar_url ?? asset('dist/img/user2-160x160.jpg') }}" 
                         alt="Avatar" class="img-circle elevation-2" width="40" height="40">
                </td>
                <td>
                    <div>
                        <strong>{{ $user->name }}</strong>
                        @if($user->phone)
                        <br><small class="text-muted">{{ $user->phone }}</small>
                        @endif
                    </div>
                </td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge badge-primary">
                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                    </span>
                </td>
                <td>
                    @if($user->status === 'active')
                        <span class="badge badge-success">Aktif</span>
                    @elseif($user->status === 'inactive')
                        <span class="badge badge-secondary">Tidak Aktif</span>
                    @elseif($user->status === 'suspended')
                        <span class="badge badge-warning">Suspended</span>
                    @else
                        <span class="badge badge-dark">{{ ucfirst($user->status) }}</span>
                    @endif
                </td>
                <td>
                    @if($user->last_login_at)
                        <small>{{ $user->last_login_at->format('d/m/Y H:i') }}</small>
                    @else
                        <small class="text-muted">Belum pernah</small>
                    @endif
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('backend.users.show', $user) }}" 
                           class="btn btn-info btn-sm" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('backend.users.edit', $user) }}" 
                           class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <button class="btn btn-danger btn-sm" 
                                onclick="deleteUser('{{ $user->id }}', '{{ $user->name }}')" 
                                title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($users->hasPages())
<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        <small class="text-muted">
            Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} dari {{ $users->total() }} hasil
        </small>
    </div>
    <div>
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
</div>
@endif

@else
<!-- Empty State -->
<div class="text-center py-5">
    <div class="mb-3">
        <i class="fas fa-users text-muted" style="font-size: 4rem;"></i>
    </div>
    <h5 class="text-muted">Tidak ada pengguna ditemukan</h5>
    <p class="text-muted">Belum ada pengguna yang sesuai dengan kriteria pencarian Anda.</p>
    <a href="{{ route('backend.users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i>Tambah Pengguna Pertama
    </a>
</div>
@endif

<style>
.img-circle {
    border-radius: 50%;
    object-fit: cover;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75em;
}

.table-responsive {
    border-radius: 0.375rem;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.05);
}
</style>

<script>
// Select all checkbox functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActions();
});

// Individual checkbox change
document.querySelectorAll('.user-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const allCheckboxes = document.querySelectorAll('.user-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
        const selectAllCheckbox = document.getElementById('select-all');
        
        selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
        
        updateBulkActions();
    });
});

// Update bulk actions visibility
function updateBulkActions() {
    const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    
    if (bulkActions) {
        if (checkedCheckboxes.length > 0) {
            bulkActions.style.display = 'block';
            document.getElementById('selected-count').textContent = checkedCheckboxes.length;
        } else {
            bulkActions.style.display = 'none';
        }
    }
}

// Delete user function
function deleteUser(userId, userName) {
    if (confirm(`Apakah Anda yakin ingin menghapus pengguna "${userName}"? Tindakan ini tidak dapat dibatalkan.`)) {
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
                // Reload the table or remove the row
                location.reload();
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
</script>