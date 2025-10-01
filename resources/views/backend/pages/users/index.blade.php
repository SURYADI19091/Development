@extends('backend.layout.main')

@section('title', 'Manajemen Pengguna')
@section('header', 'Manajemen Pengguna')
@section('description', 'Kelola data pengguna sistem')

@push('styles')
<style>
    .bulk-actions-panel {
        transition: all 0.3s ease-in-out;
        backdrop-filter: blur(4px);
        min-height: 80px;
        padding: 1.5rem 0;
    }
    
    .bulk-actions-panel.translate-y-full {
        transform: translateY(100%);
    }
    
    .bulk-actions-panel.hidden {
        display: none;
    }
    
    /* Custom badge colors */
    .badge-purple {
        color: #fff;
        background-color: #6f42c1;
    }
    
    /* User panel spacing */
    .user-panel .info {
        line-height: 1.2;
    }
    
    /* Table row hover */
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.05);
    }
    
    .bulk-action-btn {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .bulk-action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .bulk-action-btn:active {
        transform: translateY(0);
    }
    
    .bulk-action-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none !important;
    }
    
    .bulk-action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.3s, height 0.3s;
    }
    
    .bulk-action-btn:active::before {
        width: 300px;
        height: 300px;
    }
    
    .progress-bar {
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .toast-enter {
        animation: toastSlideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .toast-exit {
        animation: toastSlideOut 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    @keyframes toastSlideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes toastSlideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .selection-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .status-indicator {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }
    
    .card {
        transition: box-shadow 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,0.05);
        transition: background-color 0.2s ease;
    }
    
    .checkbox-wrapper {
        position: relative;
        display: inline-block;
    }
    
    .checkbox-wrapper input[type="checkbox"] {
        transition: all 0.2s ease;
    }
    
    .checkbox-wrapper input[type="checkbox"]:checked {
        transform: scale(1.1);
    }
    
    .spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Responsive spacing */
    @media (max-width: 768px) {
        .bulk-actions-panel {
            padding: 1rem 0;
        }
        
        .bulk-actions-panel .px-8 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .bulk-actions-panel .py-6 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        
        .bulk-actions-panel .space-x-4 > * + * {
            margin-left: 0.75rem;
        }
        
        .bulk-actions-panel .space-x-6 > * + * {
            margin-left: 1rem;
        }
        
        .bulk-action-btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .selection-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manajemen Pengguna</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Manajemen Pengguna</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @include('backend.partials.search-filter', [
            'searchPlaceholder' => 'Cari nama, email, atau ID pegawai...',
            'filters' => [
                [
                    'name' => 'role',
                    'label' => 'Semua Role',
                    'options' => [
                        'super_admin' => 'Super Admin',
                        'admin' => 'Admin', 
                        'operator' => 'Operator',
                        'user' => 'User'
                    ]
                ],
                [
                    'name' => 'status',
                    'label' => 'Semua Status',
                    'options' => [
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif'
                    ]
                ]
            ],
            'sortOptions' => [
                'name' => 'Nama',
                'email' => 'Email',
                'created_at' => 'Tanggal Dibuat',
                'updated_at' => 'Terakhir Diubah'
            ],
            'showStats' => true,
            'stats' => $stats,
            'actionButtons' => [
                [
                    'label' => 'Export',
                    'url' => '#',
                    'class' => 'outline-secondary',
                    'icon' => 'download',
                    'onclick' => 'exportUsers()',
                    'permission' => 'export-users'
                ],
                [
                    'label' => 'Tambah Pengguna',
                    'url' => route('backend.users.create'),
                    'class' => 'primary',
                    'icon' => 'plus',
                    'permission' => 'create-user'
                ]
            ]
        ])

        <!-- Users Table Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-1"></i>
                    Daftar Pengguna
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="select-all">
                                    <label for="select-all"></label>
                                </div>
                            </th>
                            <th>Pengguna</th>
                            <th>Role & Status</th>
                            <th>Aktivitas</th>
                            <th>Bergabung</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="users-table">
                    @forelse($users ?? [] as $user)
                    <tr data-user-id="{{ $user->id }}">
                        <td>
                            <div class="icheck-primary">
                                <input type="checkbox" class="user-checkbox" value="{{ $user->id }}" id="check{{ $user->id }}">
                                <label for="check{{ $user->id }}"></label>
                            </div>
                        </td>
                        <td>
                            <div class="user-panel d-flex">
                                <div class="image">
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" class="img-circle elevation-2" alt="User Image" style="width: 34px; height: 34px;">
                                    @else
                                        <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image" style="width: 34px; height: 34px;">
                                    @endif
                                </div>
                                <div class="info ml-3">
                                    <strong class="d-block">{{ $user->name }}</strong>
                                    <small class="text-muted d-block">{{ $user->email }}</small>
                                    @if($user->phone)
                                    <small class="text-muted d-block"><i class="fas fa-phone fa-xs"></i> {{ $user->phone }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="badge 
                                    @if($user->role === 'super_admin') badge-danger
                                    @elseif($user->role === 'admin') badge-primary
                                    @elseif($user->role === 'operator') badge-success
                                    @else badge-secondary @endif">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                                <br>
                                <span class="badge mt-1
                                    @if($user->status === 'active') badge-success
                                    @elseif($user->status === 'inactive') badge-secondary
                                    @else badge-warning @endif">
                                    <i class="fas fa-circle fa-xs mr-1"></i>
                                    {{ ucfirst($user->status) }}
                                </span>
                            </div>
                        </td>
                        <td>
                            @if($user->last_login_at)
                                <small class="d-block">{{ $user->last_login_at->diffForHumans() }}</small>
                                <small class="text-muted d-block">{{ $user->last_login_at->format('d M Y H:i') }}</small>
                            @else
                                <small class="text-muted">Belum pernah login</small>
                            @endif
                        </td>
                        <td>
                            <small>{{ $user->created_at->format('d M Y') }}</small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                @can('view-user', $user)
                                <a href="{{ route('backend.users.show', $user) }}" 
                                   class="btn btn-info btn-xs" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
                                
                                @can('edit-user', $user)
                                <a href="{{ route('backend.users.edit', $user) }}" 
                                   class="btn btn-warning btn-xs" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                
                                @can('manage-user-status', $user)
                                @if($user->status === 'active')
                                    <button type="button" onclick="toggleUserStatus({{ $user->id }}, 'inactive')" 
                                            class="btn btn-secondary btn-xs" title="Nonaktifkan">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                @else
                                    <button type="button" onclick="toggleUserStatus({{ $user->id }}, 'active')" 
                                            class="btn btn-success btn-xs" title="Aktifkan">
                                        <i class="fas fa-play"></i>
                                    </button>
                                @endif
                                @endcan
                                
                                @can('delete-user', $user)
                                @if($user->id !== Auth::id())
                                <button type="button" onclick="deleteUser({{ $user->id }})" 
                                        class="btn btn-danger btn-xs" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-users fa-3x mb-3 d-block"></i>
                                <h5>Tidak ada pengguna</h5>
                                <p>Belum ada pengguna yang terdaftar dalam sistem</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @include('backend.partials.pagination', [
                'paginator' => $users,
                'perPageOptions' => $paginationInfo['per_page_options'] ?? [10, 15, 25, 50]
            ])
        </div>
        
        <!-- Bulk Actions Panel -->
        <div id="bulk-actions" class="alert alert-info alert-dismissible d-none mt-3">
            <button type="button" class="close" onclick="clearSelection()">
                <span>&times;</span>
            </button>
            <h5><i class="icon fas fa-check-circle"></i> Bulk Actions</h5>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <strong><span id="selected-count">0</span> pengguna dipilih</strong>
                    <small class="d-block text-muted" id="selection-preview">Pilih aksi untuk diterapkan</small>
                </div>
                <div class="col-md-6 text-right">
                    <div class="btn-group btn-group-sm">
                        @can('manage-user-status')
                        <button type="button" onclick="bulkAction('activate')" id="bulk-activate" class="btn btn-success">
                            <i class="fas fa-check"></i> Aktifkan (<span id="activate-count">0</span>)
                        </button>
                        <button type="button" onclick="bulkAction('deactivate')" id="bulk-deactivate" class="btn btn-warning">
                            <i class="fas fa-pause"></i> Nonaktifkan (<span id="deactivate-count">0</span>)
                        </button>
                        @endcan
                        @can('bulk-delete-users')
                        <button type="button" onclick="bulkAction('delete')" id="bulk-delete" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus (<span id="delete-count">0</span>)
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
            <!-- Progress Bar -->
            <div id="bulk-progress" class="progress mt-2 d-none">
                <div id="bulk-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-info" style="width: 0%"></div>
            </div>
        </div>
    </div>
</section>

    <!-- Old Bulk Actions Panel - REMOVED -->
    <div id="bulk-actions" class="hidden fixed bottom-0 left-0 right-0 z-50 transform translate-y-full transition-transform duration-300 ease-in-out">
        <!-- Background overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-20 backdrop-blur-sm"></div>
        
        <!-- Main panel -->
        <div class="relative bg-white border-t-2 border-blue-500 shadow-2xl">
            <!-- Top indicator line -->
            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1 w-12 h-1 bg-blue-500 rounded-full"></div>
            
            <div class="px-8 py-6">
                <div class="flex items-center justify-between max-w-7xl mx-auto">
                    <!-- Left side - Selection info with avatar preview -->
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-4">
                            <!-- Selection indicator -->
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full shadow-sm">
                                <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                            </div>
                            
                            <!-- Selection count and info -->
                            <div class="flex flex-col">
                                <div class="flex items-center space-x-3 mb-1">
                                    <span class="text-xl font-semibold text-gray-900">
                                        <span id="selected-count">0</span> pengguna dipilih
                                    </span>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                        dari <span id="total-visible">0</span>
                                    </span>
                                </div>
                                <span class="text-sm text-gray-500" id="selection-preview">
                                    Pilih aksi untuk diterapkan ke pengguna yang dipilih
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right side - Action buttons -->
                    <div class="flex items-center space-x-3">
                        @can('manage-user-status')
                        <!-- Activate button -->
                        <button type="button" onclick="bulkAction('activate')" 
                                class="group inline-flex items-center px-5 py-3 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100 hover:border-green-400 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-sm">
                            <div class="flex items-center justify-center w-5 h-5 mr-2 bg-green-200 rounded-full group-hover:bg-green-300 transition-colors">
                                <i class="fas fa-check text-xs text-green-700"></i>
                            </div>
                            Aktifkan
                            <span class="ml-2 px-2 py-0.5 bg-green-200 text-green-800 text-xs rounded-full">
                                <span id="activate-count">0</span>
                            </span>
                        </button>

                        <!-- Deactivate button -->
                        <button type="button" onclick="bulkAction('deactivate')" 
                                class="group inline-flex items-center px-5 py-3 border border-orange-300 text-sm font-medium rounded-lg text-orange-700 bg-orange-50 hover:bg-orange-100 hover:border-orange-400 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 shadow-sm">
                            <div class="flex items-center justify-center w-5 h-5 mr-2 bg-orange-200 rounded-full group-hover:bg-orange-300 transition-colors">
                                <i class="fas fa-pause text-xs text-orange-700"></i>
                            </div>
                            Nonaktifkan
                            <span class="ml-2 px-2 py-0.5 bg-orange-200 text-orange-800 text-xs rounded-full">
                                <span id="deactivate-count">0</span>
                            </span>
                        </button>
                        @endcan
                        
                        @can('bulk-delete-users')
                        <!-- Delete button -->
                        <button type="button" onclick="bulkAction('delete')" 
                                class="group inline-flex items-center px-5 py-3 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 hover:border-red-400 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-sm">
                            <div class="flex items-center justify-center w-5 h-5 mr-2 bg-red-200 rounded-full group-hover:bg-red-300 transition-colors">
                                <i class="fas fa-trash text-xs text-red-700"></i>
                            </div>
                            Hapus
                            <span class="ml-2 px-2 py-0.5 bg-red-200 text-red-800 text-xs rounded-full">
                                <span id="delete-count">0</span>
                            </span>
                        </button>
                        @endcan
                        
                        <!-- Divider -->
                        <div class="w-px h-10 bg-gray-300 mx-2"></div>
                        
                        <!-- Export selected -->
                        <button type="button" onclick="exportSelected()" 
                                class="group inline-flex items-center px-5 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-sm">
                            <i class="fas fa-download mr-2 text-gray-600 group-hover:text-gray-700"></i>
                            Export
                        </button>
                        
                        <!-- Clear selection -->
                        <button type="button" onclick="clearSelection()" 
                                class="group flex items-center justify-center w-12 h-12 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 ml-2"
                                title="Batalkan pilihan">
                            <i class="fas fa-times text-lg group-hover:rotate-90 transition-transform duration-200"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Quick stats bar -->
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <div class="flex items-center space-x-4">
                            <span class="flex items-center">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                <span id="active-selected">0</span> aktif
                            </span>
                            <span class="flex items-center">
                                <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                                <span id="inactive-selected">0</span> tidak aktif
                            </span>
                            <span class="flex items-center">
                                <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                <span id="suspended-selected">0</span> suspended
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="font-medium">Aksi massal tersedia</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-500 mb-4" id="delete-message">
                Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-center space-x-4">
                <button type="button" onclick="hideDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Batal
                </button>
                <button type="button" id="confirm-delete" 
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedUsers = [];
    let deleteUserId = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('search-input');
        const roleFilter = document.getElementById('role-filter');
        const statusFilter = document.getElementById('status-filter');

        // Debounce search
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterUsers();
            }, 300);
        });

        roleFilter.addEventListener('change', filterUsers);
        statusFilter.addEventListener('change', filterUsers);

        // Checkbox functionality
        const selectAll = document.getElementById('select-all');
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
                if (this.checked) {
                    addToSelection(cb.value);
                } else {
                    removeFromSelection(cb.value);
                }
            });
            updateBulkActions();
        });

        // Individual checkboxes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('user-checkbox')) {
                if (e.target.checked) {
                    addToSelection(e.target.value);
                } else {
                    removeFromSelection(e.target.value);
                }
                updateBulkActions();
                updateSelectAll();
            }
        });
        
        // Add event listeners for bulk action buttons
        const bulkActivateBtn = document.getElementById('bulk-activate');
        const bulkDeactivateBtn = document.getElementById('bulk-deactivate');
        const bulkDeleteBtn = document.getElementById('bulk-delete');
        
        if (bulkActivateBtn) {
            bulkActivateBtn.addEventListener('click', () => bulkAction('activate'));
        }
        if (bulkDeactivateBtn) {
            bulkDeactivateBtn.addEventListener('click', () => bulkAction('deactivate'));
        }
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', () => bulkAction('delete'));
        }
    });

    function addToSelection(userId) {
        if (!selectedUsers.includes(userId)) {
            selectedUsers.push(userId);
        }
    }

    function removeFromSelection(userId) {
        selectedUsers = selectedUsers.filter(id => id !== userId);
    }

    function updateBulkActions() {
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');
        
        if (selectedUsers.length > 0) {
            // Show panel
            bulkActions.classList.remove('d-none');
            
            // Update counts
            selectedCount.textContent = selectedUsers.length;
            
            // Update action button counts
            if (document.getElementById('activate-count')) {
                document.getElementById('activate-count').textContent = selectedUsers.length;
            }
            if (document.getElementById('deactivate-count')) {
                document.getElementById('deactivate-count').textContent = selectedUsers.length;
            }
            if (document.getElementById('delete-count')) {
                document.getElementById('delete-count').textContent = selectedUsers.length;
            }
            
            // Update preview text
            updateSelectionPreview();
        } else {
            // Hide panel
            bulkActions.classList.add('d-none');
        }
    }

    function updateStatusCounts() {
        let activeCount = 0, inactiveCount = 0, suspendedCount = 0;
        
        selectedUsers.forEach(userId => {
            const row = document.querySelector(`tr[data-user-id="${userId}"]`);
            if (row) {
                const statusElement = row.querySelector('[class*="bg-green-100"]');
                if (statusElement && statusElement.textContent.toLowerCase().includes('aktif')) {
                    activeCount++;
                } else if (statusElement && statusElement.textContent.toLowerCase().includes('tidak aktif')) {
                    inactiveCount++;
                } else if (statusElement && statusElement.textContent.toLowerCase().includes('suspended')) {
                    suspendedCount++;
                }
            }
        });
        
        document.getElementById('active-selected').textContent = activeCount;
        document.getElementById('inactive-selected').textContent = inactiveCount;
        document.getElementById('suspended-selected').textContent = suspendedCount;
    }

    function updateSelectionPreview() {
        const previewElement = document.getElementById('selection-preview');
        const count = selectedUsers.length;
        
        if (count === 1) {
            previewElement.textContent = 'Pilih aksi untuk diterapkan ke 1 pengguna';
        } else if (count <= 5) {
            previewElement.textContent = `Pilih aksi untuk diterapkan ke ${count} pengguna`;
        } else {
            previewElement.textContent = `Pilih aksi untuk diterapkan ke ${count} pengguna sekaligus`;
        }
    }

    function updateSelectAll() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.user-checkbox');
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
        
        if (checkedBoxes.length === 0) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        } else if (checkedBoxes.length === checkboxes.length) {
            selectAll.checked = true;
            selectAll.indeterminate = false;
        } else {
            selectAll.checked = false;
            selectAll.indeterminate = true;
        }
    }

    function clearSelection() {
        selectedUsers = [];
        document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = false);
        const selectAll = document.getElementById('select-all');
        if (selectAll) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        }
        updateBulkActions();
    }

    function filterUsers() {
        const search = document.getElementById('search-input').value;
        const role = document.getElementById('role-filter').value;
        const status = document.getElementById('status-filter').value;

        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (role) params.append('role', role);
        if (status) params.append('status', status);

        // Reload table with filters
        fetch(`{{ route('backend.users.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Update table content
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.querySelector('#users-table');
            const newTotalCount = doc.querySelector('#total-count');
            
            if (newTable) {
                document.getElementById('users-table').innerHTML = newTable.innerHTML;
            }
            if (newTotalCount) {
                document.getElementById('total-count').textContent = newTotalCount.textContent;
            }
            
            clearSelection();
        })
        .catch(error => {
            console.error('Filter error:', error);
            showNotification('Terjadi kesalahan saat memfilter data', 'error');
        });
    }

    function toggleUserStatus(userId, status) {
        fetch(`{{ route('backend.users.index') }}/${userId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                // Refresh the current view
                filterUsers();
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error('Status update error:', error);
            showNotification('Terjadi kesalahan saat mengubah status', 'error');
        });
    }

    function deleteUser(userId) {
        deleteUserId = userId;
        document.getElementById('delete-message').textContent = 'Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.';
        const modal = document.getElementById('delete-modal');
        if (modal) {
            modal.classList.remove('d-none');
        }
        
        document.getElementById('confirm-delete').onclick = function() {
            performDelete(userId);
        };
    }

    function performDelete(userId) {
        fetch(`{{ route('backend.users.index') }}/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            hideDeleteModal();
            if (data.success) {
                showNotification(data.message, 'success');
                filterUsers();
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            hideDeleteModal();
            console.error('Delete error:', error);
            showNotification('Terjadi kesalahan saat menghapus pengguna', 'error');
        });
    }

    function hideDeleteModal() {
        const modal = document.getElementById('delete-modal');
        if (modal) {
            modal.classList.add('d-none');
        }
        deleteUserId = null;
    }

    function bulkAction(action) {
        if (selectedUsers.length === 0) {
            showToast('Tidak ada pengguna yang dipilih', 'warning');
            return;
        }

        const button = document.getElementById(`bulk-${action}`);
        const originalText = button.innerHTML;
        
        const messages = {
            activate: {
                confirm: `Apakah Anda yakin ingin mengaktifkan ${selectedUsers.length} pengguna?`,
                processing: '<i class="fas fa-spinner fa-spin mr-2"></i>Mengaktifkan...',
                success: `${selectedUsers.length} pengguna berhasil diaktifkan`,
                error: 'Gagal mengaktifkan pengguna'
            },
            deactivate: {
                confirm: `Apakah Anda yakin ingin menonaktifkan ${selectedUsers.length} pengguna?`,
                processing: '<i class="fas fa-spinner fa-spin mr-2"></i>Menonaktifkan...',
                success: `${selectedUsers.length} pengguna berhasil dinonaktifkan`,
                error: 'Gagal menonaktifkan pengguna'
            },
            delete: {
                confirm: `Apakah Anda yakin ingin menghapus ${selectedUsers.length} pengguna?\n\nTindakan ini tidak dapat dibatalkan!`,
                processing: '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...',
                success: `${selectedUsers.length} pengguna berhasil dihapus`,
                error: 'Gagal menghapus pengguna'
            }
        };

        if (confirm(messages[action].confirm)) {
            // Show loading state
            button.innerHTML = messages[action].processing;
            button.disabled = true;
            
            // Show progress bar
            showProgressBar();

            fetch(`{{ route('backend.users.bulk-action') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    action: action,
                    user_ids: selectedUsers
                })
            })
            .then(response => response.json())
            .then(data => {
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
                
                // Hide progress bar
                hideProgressBar();
                
                if (data.success) {
                    showToast(data.message || messages[action].success, 'success');
                    clearSelection();
                    filterUsers();
                } else {
                    showToast(data.message || messages[action].error, 'error');
                }
            })
            .catch(error => {
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
                
                // Hide progress bar
                hideProgressBar();
                
                console.error('Bulk action error:', error);
                showToast('Terjadi kesalahan saat melakukan bulk action', 'error');
            });
        }
    }

    function showProgressBar() {
        const progressContainer = document.getElementById('bulk-progress');
        const progressBar = document.getElementById('bulk-progress-bar');
        
        if (progressContainer && progressBar) {
            progressContainer.classList.remove('d-none');
            progressBar.style.width = '0%';
            
            // Animate progress
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 30;
                if (progress >= 90) progress = 90;
                progressBar.style.width = progress + '%';
                
                if (progress >= 90) {
                    clearInterval(interval);
                }
            }, 200);
        }
    }

    function hideProgressBar() {
        const progressContainer = document.getElementById('bulk-progress');
        const progressBar = document.getElementById('bulk-progress-bar');
        
        if (progressContainer && progressBar) {
            // Complete animation
            progressBar.style.width = '100%';
            
            setTimeout(() => {
                progressContainer.classList.add('d-none');
                progressBar.style.width = '0%';
            }, 500);
        }
    }

    function showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
        
        // Set colors based on type
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-white',
            info: 'bg-blue-500 text-white'
        };
        
        toast.className += ` ${colors[type] || colors.info}`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    function exportUsers() {
        const params = new URLSearchParams();
        const search = document.getElementById('search-input').value;
        const role = document.getElementById('role-filter').value;
        const status = document.getElementById('status-filter').value;
        
        if (search) params.append('search', search);
        if (role) params.append('role', role);
        if (status) params.append('status', status);

        window.open(`{{ route('backend.users.export') }}?${params.toString()}`, '_blank');
    }
</script>
@endpush