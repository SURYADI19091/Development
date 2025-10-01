@extends('backend.layout.main')

@section('page_title', 'Perangkat Desa')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item active">Perangkat Desa</li>
@endsection

@section('page_actions')
<div class="btn-group">
    @can('manage.village_officials')
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addOfficialModal">
        <i class="fas fa-plus"></i> Tambah Perangkat Desa
    </button>
    @endcan
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

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i>Terdapat kesalahan dalam form:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>Daftar Perangkat Desa
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Debug Info -->
                    <div class="alert alert-info">
                        <strong>Debug:</strong> Total Officials: {{ $officials->count() }}
                    </div>

                    @if($officials->count() > 0)
                    <div class="row">
                        @foreach($officials as $official)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-widget widget-user">
                                <!-- Add the bg color to the header -->
                                <div class="widget-user-header {{ $official->is_active ? 'bg-primary' : 'bg-secondary' }}">
                                    <h3 class="widget-user-username">{{ $official->name }}</h3>
                                    <h5 class="widget-user-desc">{{ $official->position }}</h5>
                                    
                                    @can('manage.village_officials')
                                    <div class="widget-user-tools">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-light btn-edit-official" 
                                                    data-id="{{ $official->id }}"
                                                    data-name="{{ $official->name }}"
                                                    data-position="{{ $official->position }}"
                                                    data-nip="{{ $official->nip }}"
                                                    data-phone="{{ $official->phone }}"
                                                    data-email="{{ $official->email }}"
                                                    data-address="{{ $official->address }}"
                                                    data-start_date="{{ $official->start_date }}"
                                                    data-end_date="{{ $official->end_date }}"
                                                    data-order="{{ $official->order }}"
                                                    data-is_active="{{ $official->is_active ? 1 : 0 }}"
                                                    title="Edit"
                                                    style="background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.3);">
                                                <i class="fas fa-edit text-white"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-official" 
                                                    data-id="{{ $official->id }}" 
                                                    data-name="{{ $official->name }}"
                                                    title="Hapus"
                                                    style="background: rgba(220,53,69,0.8); border-color: rgba(220,53,69,0.5);">
                                                <i class="fas fa-trash text-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endcan
                                </div>
                                
                                <div class="widget-user-image">
                                    @if($official->photo_path)
                                        <img class="img-circle elevation-2" src="{{ asset('storage/' . $official->photo_path) }}" alt="{{ $official->name }}">
                                    @else
                                        <div class="img-circle elevation-2 bg-light d-flex align-items-center justify-content-center" style="width: 90px; height: 90px;">
                                            <i class="fas fa-user fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="description-block">
                                                @if($official->nip)
                                                <span class="description-text"><strong>NIP:</strong> {{ $official->nip }}</span><br>
                                                @endif
                                                @if($official->phone)
                                                <span class="description-text"><strong>Telepon:</strong> {{ $official->phone }}</span><br>
                                                @endif
                                                @if($official->email)
                                                <span class="description-text"><strong>Email:</strong> {{ $official->email }}</span><br>
                                                @endif
                                                @if($official->start_date)
                                                <span class="description-text">
                                                    <strong>Mulai Jabatan:</strong> {{ \Carbon\Carbon::parse($official->start_date)->format('d M Y') }}
                                                </span><br>
                                                @endif
                                                @if($official->end_date)
                                                <span class="description-text">
                                                    <strong>Berakhir:</strong> {{ \Carbon\Carbon::parse($official->end_date)->format('d M Y') }}
                                                </span><br>
                                                @endif
                                                <span class="description-text">
                                                    <strong>Status:</strong> 
                                                    <span class="badge {{ $official->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                        {{ $official->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer text-center bg-light">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-edit-official" 
                                                data-id="{{ $official->id }}"
                                                data-name="{{ $official->name }}"
                                                data-position="{{ $official->position }}"
                                                data-nip="{{ $official->nip }}"
                                                data-phone="{{ $official->phone }}"
                                                data-email="{{ $official->email }}"
                                                data-address="{{ $official->address }}"
                                                data-start_date="{{ $official->start_date }}"
                                                data-end_date="{{ $official->end_date }}"
                                                data-order="{{ $official->order }}"
                                                data-is_active="{{ $official->is_active ? 1 : 0 }}">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-delete-official" 
                                                data-id="{{ $official->id }}" 
                                                data-name="{{ $official->name }}">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-5x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Data Perangkat Desa</h5>
                        <p class="text-muted">Klik tombol "Tambah Perangkat Desa" untuk menambahkan data perangkat desa.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Official Modal -->
@can('manage.village_officials')
<div class="modal fade" id="addOfficialModal" tabindex="-1" role="dialog" aria-labelledby="addOfficialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="addOfficialForm" action="{{ route('backend.village.store-official') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addOfficialModalLabel">
                        <i class="fas fa-user-plus mr-2"></i>Tambah Perangkat Desa
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_name">Nama Lengkap *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="add_name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="add_position">Jabatan *</label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                       id="add_position" name="position" value="{{ old('position') }}" 
                                       placeholder="Contoh: Kepala Desa, Sekretaris Desa" required>
                                @error('position')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="add_nip">NIP</label>
                                <input type="text" class="form-control @error('nip') is-invalid @enderror" 
                                       id="add_nip" name="nip" value="{{ old('nip') }}">
                                @error('nip')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="add_phone">Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="add_phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="add_email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="add_email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_address">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="add_address" name="address" rows="3">{{ old('address') }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="add_start_date">Mulai Jabatan</label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                               id="add_start_date" name="start_date" value="{{ old('start_date') }}">
                                        @error('start_date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="add_end_date">Berakhir Jabatan</label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                               id="add_end_date" name="end_date" value="{{ old('end_date') }}">
                                        @error('end_date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="add_photo">Foto</label>
                                <input type="file" class="form-control-file @error('photo') is-invalid @enderror" 
                                       id="add_photo" name="photo" accept="image/*">
                                <small class="form-text text-muted">Maksimal 2MB, format: JPG, PNG, GIF</small>
                                @error('photo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="add_order">Urutan Tampil</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                       id="add_order" name="order" value="{{ old('order', $officials->max('order') + 1) }}" min="1">
                                @error('order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="add_is_active" name="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="add_is_active">
                                        Status Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Official Modal -->
<div class="modal fade" id="editOfficialModal" tabindex="-1" role="dialog" aria-labelledby="editOfficialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="editOfficialForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOfficialModalLabel">
                        <i class="fas fa-user-edit mr-2"></i>Edit Perangkat Desa
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_name">Nama Lengkap *</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>

                            <div class="form-group">
                                <label for="edit_position">Jabatan *</label>
                                <input type="text" class="form-control" id="edit_position" name="position" required>
                            </div>

                            <div class="form-group">
                                <label for="edit_nip">NIP</label>
                                <input type="text" class="form-control" id="edit_nip" name="nip">
                            </div>

                            <div class="form-group">
                                <label for="edit_phone">Telepon</label>
                                <input type="text" class="form-control" id="edit_phone" name="phone">
                            </div>

                            <div class="form-group">
                                <label for="edit_email">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_address">Alamat</label>
                                <textarea class="form-control" id="edit_address" name="address" rows="3"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_start_date">Mulai Jabatan</label>
                                        <input type="date" class="form-control" id="edit_start_date" name="start_date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_end_date">Berakhir Jabatan</label>
                                        <input type="date" class="form-control" id="edit_end_date" name="end_date">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="edit_photo">Foto Baru</label>
                                <input type="file" class="form-control-file" id="edit_photo" name="photo" accept="image/*">
                                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                            </div>

                            <div class="form-group">
                                <label for="edit_order">Urutan Tampil</label>
                                <input type="number" class="form-control" id="edit_order" name="order" min="1">
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active" value="1">
                                    <label class="form-check-label" for="edit_is_active">
                                        Status Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>Perbarui
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan
@endsection

@push('styles')
<style>
.widget-user-tools {
    position: absolute;
    top: 10px;
    right: 10px;
}

.widget-user-tools .btn {
    margin: 2px;
    opacity: 0.9;
}

.widget-user-tools .btn:hover {
    opacity: 1;
}

.card-footer .btn {
    margin: 0 5px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Edit Official
    $('.btn-edit-official').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const position = $(this).data('position');
        const nip = $(this).data('nip');
        const phone = $(this).data('phone');
        const email = $(this).data('email');
        const address = $(this).data('address');
        const startDate = $(this).data('start_date');
        const endDate = $(this).data('end_date');
        const order = $(this).data('order');
        const isActive = $(this).data('is_active');

        // Set form action
        const editUrl = '{{ url("admin/village-officials") }}/' + id;
        $('#editOfficialForm').attr('action', editUrl);
        
        // Fill form fields
        $('#edit_name').val(name);
        $('#edit_position').val(position);
        $('#edit_nip').val(nip);
        $('#edit_phone').val(phone);
        $('#edit_email').val(email);
        $('#edit_address').val(address);
        $('#edit_start_date').val(startDate);
        $('#edit_end_date').val(endDate);
        $('#edit_order').val(order);
        $('#edit_is_active').prop('checked', isActive == 1);

        // Show modal
        $('#editOfficialModal').modal('show');
    });

    // Form validation for edit
    $('#editOfficialForm').on('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const requiredFields = $(this).find('[required]');
        
        // Reset validation states
        $('.is-invalid').removeClass('is-invalid');
        
        // Check required fields
        requiredFields.each(function() {
            if (!$(this).val() || $(this).val().trim() === '') {
                $(this).addClass('is-invalid');
                isValid = false;
            }
        });

        if (isValid) {
            // Show loading state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');
            
            // Submit form
            this.submit();
        } else {
            toastr.error('Mohon lengkapi semua field yang wajib diisi.');
        }
    });

    // Delete Official
    $('.btn-delete-official').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: 'Hapus Perangkat Desa?',
            text: `Anda yakin ingin menghapus ${name}? Data yang dihapus tidak dapat dikembalikan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create form for deletion
                const form = $('<form>', {
                    method: 'POST',
                    action: '{{ url("admin/village-officials") }}/' + id
                });
                
                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '{{ csrf_token() }}'
                }));
                
                form.append($('<input>', {
                    type: 'hidden',
                    name: '_method',
                    value: 'DELETE'
                }));
                
                $('body').append(form);
                form.submit();
            }
        });
    });

    // Form validation
    $('form').on('submit', function(e) {
        const requiredFields = $(this).find('[required]');
        let isValid = true;
        
        requiredFields.each(function() {
            if (!$(this).val() || $(this).val().trim() === '') {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            toastr.error('Mohon lengkapi semua field yang wajib diisi.');
        }
    });

    // Real-time validation
    $('input[required], textarea[required]').on('input blur', function() {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
        }
    });

    // Photo preview
    $('input[type="file"]').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add image preview here if needed
                console.log('Image selected:', file.name);
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush