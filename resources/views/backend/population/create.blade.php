@extends('backend.layout.main')

@section('title', 'Tambah Data Penduduk')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Tambah Data Penduduk</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('backend.population.index') }}">Data Penduduk</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Form Tambah Data Penduduk</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <form action="{{ route('backend.population.store') }}" method="POST" id="populationForm">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <!-- Data Identitas -->
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-id-card mr-2"></i>Data Identitas
                                    </h5>
                                    
                                    <div class="form-group">
                                        <label for="serial_number">No. Urut *</label>
                                        <input type="number" class="form-control @error('serial_number') is-invalid @enderror" 
                                               id="serial_number" name="serial_number" value="{{ old('serial_number') }}" required>
                                        @error('serial_number')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="family_card_number">No. KK *</label>
                                        <input type="text" class="form-control @error('family_card_number') is-invalid @enderror" 
                                               id="family_card_number" name="family_card_number" value="{{ old('family_card_number') }}" 
                                               maxlength="16" required>
                                        @error('family_card_number')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="identity_card_number">NIK *</label>
                                        <input type="text" class="form-control @error('identity_card_number') is-invalid @enderror" 
                                               id="identity_card_number" name="identity_card_number" value="{{ old('identity_card_number') }}" 
                                               maxlength="16" required>
                                        @error('identity_card_number')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Nama Lengkap *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="birth_place">Tempat Lahir *</label>
                                        <input type="text" class="form-control @error('birth_place') is-invalid @enderror" 
                                               id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required>
                                        @error('birth_place')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="birth_date">Tanggal Lahir *</label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                               id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                        @error('birth_date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="age">Umur *</label>
                                        <input type="text" class="form-control @error('age') is-invalid @enderror" 
                                               id="age" name="age" value="{{ old('age') }}" readonly>
                                        @error('age')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="gender">Jenis Kelamin *</label>
                                        <select class="form-control @error('gender') is-invalid @enderror" 
                                                id="gender" name="gender" required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Data Keluarga dan Alamat -->
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-home mr-2"></i>Data Keluarga & Alamat
                                    </h5>

                                    <div class="form-group">
                                        <label for="marital_status">Status Perkawinan *</label>
                                        <select class="form-control @error('marital_status') is-invalid @enderror" 
                                                id="marital_status" name="marital_status" required>
                                            <option value="">Pilih Status Perkawinan</option>
                                            <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Belum Menikah</option>
                                            <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Menikah</option>
                                            <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Cerai Hidup</option>
                                            <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Cerai Mati</option>
                                        </select>
                                        @error('marital_status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="family_relationship">Hubungan dengan Kepala Keluarga *</label>
                                        <input type="text" class="form-control @error('family_relationship') is-invalid @enderror" 
                                               id="family_relationship" name="family_relationship" value="{{ old('family_relationship') }}" 
                                               placeholder="Contoh: Kepala Keluarga, Istri, Anak, dll" required>
                                        @error('family_relationship')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="head_of_family">Nama Kepala Keluarga *</label>
                                        <input type="text" class="form-control @error('head_of_family') is-invalid @enderror" 
                                               id="head_of_family" name="head_of_family" value="{{ old('head_of_family') }}" required>
                                        @error('head_of_family')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Alamat *</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="settlement_id">RT/RW *</label>
                                        <select class="form-control @error('settlement_id') is-invalid @enderror" 
                                                id="settlement_id" name="settlement_id" required>
                                            <option value="">Pilih RT/RW</option>
                                            @foreach($settlements as $settlement)
                                                <option value="{{ $settlement->id }}" 
                                                        {{ old('settlement_id') == $settlement->id ? 'selected' : '' }}>
                                                    {{ $settlement->name }} - RT {{ $settlement->neighborhood_number ?? '-' }} / RW {{ $settlement->community_number ?? '-' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('settlement_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="religion">Agama *</label>
                                        <select class="form-control @error('religion') is-invalid @enderror" 
                                                id="religion" name="religion" required>
                                            <option value="">Pilih Agama</option>
                                            <option value="Islam" {{ old('religion') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                            <option value="Kristen" {{ old('religion') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                            <option value="Katholik" {{ old('religion') == 'Katholik' ? 'selected' : '' }}>Katholik</option>
                                            <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                            <option value="Buddha" {{ old('religion') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                            <option value="Konghucu" {{ old('religion') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                        </select>
                                        @error('religion')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="occupation">Pekerjaan *</label>
                                        <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                               id="occupation" name="occupation" value="{{ old('occupation') }}" required>
                                        @error('occupation')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="residence_type">Jenis Tinggal *</label>
                                        <select class="form-control @error('residence_type') is-invalid @enderror" 
                                                id="residence_type" name="residence_type" required>
                                            <option value="">Pilih Jenis Tinggal</option>
                                            <option value="Tetap" {{ old('residence_type') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                            <option value="Kontrak" {{ old('residence_type') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                                            <option value="Sementara" {{ old('residence_type') == 'Sementara' ? 'selected' : '' }}>Sementara</option>
                                        </select>
                                        @error('residence_type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status Hidup *</label>
                                        <select class="form-control @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="Hidup" {{ old('status', 'Hidup') == 'Hidup' ? 'selected' : '' }}>Hidup</option>
                                            <option value="Mati" {{ old('status') == 'Mati' ? 'selected' : '' }}>Mati</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group" id="death_date_group" style="display: none;">
                                        <label for="death_date">Tanggal Kematian</label>
                                        <input type="date" class="form-control @error('death_date') is-invalid @enderror" 
                                               id="death_date" name="death_date" value="{{ old('death_date') }}">
                                        @error('death_date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group" id="death_cause_group" style="display: none;">
                                        <label for="death_cause">Penyebab Kematian</label>
                                        <textarea class="form-control @error('death_cause') is-invalid @enderror" 
                                                  id="death_cause" name="death_cause" rows="3" 
                                                  placeholder="Jelaskan penyebab kematian...">{{ old('death_cause') }}</textarea>
                                        @error('death_cause')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Data Wilayah -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-map-marker-alt mr-2"></i>Data Wilayah
                                    </h5>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="independent_family_head">Kepala Keluarga Mandiri *</label>
                                        <input type="text" class="form-control @error('independent_family_head') is-invalid @enderror" 
                                               id="independent_family_head" name="independent_family_head" 
                                               value="{{ old('independent_family_head') }}" required>
                                        @error('independent_family_head')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="district">Kecamatan *</label>
                                        <input type="text" class="form-control @error('district') is-invalid @enderror" 
                                               id="district" name="district" value="{{ old('district', 'Telagsari') }}" required>
                                        @error('district')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="regency">Kabupaten *</label>
                                        <input type="text" class="form-control @error('regency') is-invalid @enderror" 
                                               id="regency" name="regency" value="{{ old('regency', 'Cirebon') }}" required>
                                        @error('regency')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="province">Provinsi *</label>
                                        <input type="text" class="form-control @error('province') is-invalid @enderror" 
                                               id="province" name="province" value="{{ old('province', 'Jawa Barat') }}" required>
                                        @error('province')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Field dengan tanda (*) wajib diisi
                                    </small>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="{{ route('backend.population.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-2"></i>Simpan Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto calculate age when birth date changes
    $('#birth_date').on('change', function() {
        const birthDate = new Date($(this).val());
        const today = new Date();
        
        if (birthDate && birthDate <= today) {
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            $('#age').val(age + ' Tahun');
        }
    });

    // Auto fill head of family when relationship is "Kepala Keluarga"
    $('#family_relationship').on('input', function() {
        const relationship = $(this).val().toLowerCase();
        if (relationship.includes('kepala') || relationship.includes('kk')) {
            const name = $('#name').val();
            if (name && !$('#head_of_family').val()) {
                $('#head_of_family').val(name);
            }
        }
    });

    // Sync name with head of family for head of household
    $('#name').on('input', function() {
        const relationship = $('#family_relationship').val().toLowerCase();
        if (relationship.includes('kepala') || relationship.includes('kk')) {
            $('#head_of_family').val($(this).val());
        }
    });

    // Form validation
    $('#populationForm').on('submit', function(e) {
        let isValid = true;
        const requiredFields = $(this).find('[required]');
        
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
            
            Swal.fire({
                icon: 'error',
                title: 'Form Tidak Lengkap',
                text: 'Mohon lengkapi semua field yang wajib diisi.',
                confirmButtonColor: '#007bff'
            });

            // Focus to first invalid field
            const firstInvalid = $(this).find('.is-invalid').first();
            if (firstInvalid.length) {
                $('html, body').animate({
                    scrollTop: firstInvalid.offset().top - 100
                }, 500);
                firstInvalid.focus();
            }
        }
    });

    // NIK validation (16 digits)
    $('#identity_card_number').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 16) {
            value = value.substring(0, 16);
        }
        $(this).val(value);
        
        if (value.length > 0 && value.length < 16) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').text('NIK harus 16 digit');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Family Card Number validation (16 digits)
    $('#family_card_number').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 16) {
            value = value.substring(0, 16);
        }
        $(this).val(value);
        
        if (value.length > 0 && value.length < 16) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').text('No. KK harus 16 digit');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Toggle death fields based on status
    function toggleDeathFields() {
        const status = $('#status').val();
        const deathDateGroup = $('#death_date_group');
        const deathCauseGroup = $('#death_cause_group');
        const deathDateInput = $('#death_date');
        const deathCauseInput = $('#death_cause');
        
        if (status === 'Mati') {
            deathDateGroup.show();
            deathCauseGroup.show();
            deathDateInput.attr('required', true);
            deathCauseInput.attr('required', true);
        } else {
            deathDateGroup.hide();
            deathCauseGroup.hide();
            deathDateInput.attr('required', false);
            deathCauseInput.attr('required', false);
            deathDateInput.val('');
            deathCauseInput.val('');
        }
    }

    // Initialize toggle and bind change event
    toggleDeathFields();
    $('#status').on('change', toggleDeathFields);
});
</script>
@endpush