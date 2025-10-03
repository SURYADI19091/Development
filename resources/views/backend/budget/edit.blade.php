@extends('backend.layout.main')

@section('page_title', 'Edit Anggaran')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.budget.index') }}">Anggaran Desa</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Anggaran</h3>
                </div>
                
                <form action="{{ route('backend.budget.update', $budget) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fiscal_year">Tahun Anggaran *</label>
                                    <select class="form-control @error('fiscal_year') is-invalid @enderror" id="fiscal_year" name="fiscal_year" required>
                                        @for($year = date('Y') + 1; $year >= 2020; $year--)
                                            <option value="{{ $year }}" {{ old('fiscal_year', $budget->fiscal_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                    @error('fiscal_year')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="budget_type">Tipe Anggaran *</label>
                                    <select class="form-control @error('budget_type') is-invalid @enderror" id="budget_type" name="budget_type" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="pendapatan" {{ old('budget_type', $budget->budget_type) == 'pendapatan' ? 'selected' : '' }}>Pendapatan</option>
                                        <option value="belanja" {{ old('budget_type', $budget->budget_type) == 'belanja' ? 'selected' : '' }}>Belanja</option>
                                    </select>
                                    @error('budget_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="category">Kategori *</label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                           id="category" name="category" value="{{ old('category', $budget->category) }}" required>
                                    @error('category')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="sub_category">Sub Kategori</label>
                                    <input type="text" class="form-control @error('sub_category') is-invalid @enderror" 
                                           id="sub_category" name="sub_category" value="{{ old('sub_category', $budget->sub_category) }}">
                                    @error('sub_category')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="planned_amount">Jumlah Anggaran *</label>
                                    <input type="number" class="form-control @error('planned_amount') is-invalid @enderror" 
                                           id="planned_amount" name="planned_amount" value="{{ old('planned_amount', $budget->planned_amount) }}" min="0" step="1000" required>
                                    @error('planned_amount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="realized_amount">Jumlah Realisasi</label>
                                    <input type="number" class="form-control @error('realized_amount') is-invalid @enderror" 
                                           id="realized_amount" name="realized_amount" value="{{ old('realized_amount', $budget->realized_amount) }}" min="0" step="1000" readonly>
                                    <small class="form-text text-muted">Realisasi akan diupdate otomatis berdasarkan transaksi.</small>
                                    @error('realized_amount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" required>{{ old('description', $budget->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Anggaran
                        </button>
                        <a href="{{ route('backend.budget.show', $budget) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('backend.budget.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection