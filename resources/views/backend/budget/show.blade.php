@extends('backend.layout.main')

@section('page_title', 'Detail Anggaran')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.budget.index') }}">Anggaran Desa</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Detail Anggaran: {{ $budget->name ?? 'N/A' }}</h3>
                        <div>
                            @can('edit-budget')
                            <a href="{{ route('backend.budget.edit', $budget ?? 0) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @endcan
                            <a href="{{ route('backend.budget.transactions', $budget ?? 0) }}" class="btn btn-primary">
                                <i class="fas fa-list"></i> Transaksi
                            </a>
                            <a href="{{ route('backend.budget.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Nama Anggaran</th>
                                    <td>{{ $budget->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Kode</th>
                                    <td>{{ $budget->code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tahun</th>
                                    <td>{{ $budget->year ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tipe</th>
                                    <td>
                                        @if(isset($budget->type))
                                            @if($budget->type == 'income')
                                                <span class="badge badge-success">Pendapatan</span>
                                            @else
                                                <span class="badge badge-danger">Pengeluaran</span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $budget->category ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Jumlah Anggaran</th>
                                    <td>Rp {{ number_format($budget->amount ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Realisasi</th>
                                    <td>Rp {{ number_format($budget->realization ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Sisa Anggaran</th>
                                    <td>Rp {{ number_format(($budget->amount ?? 0) - ($budget->realization ?? 0), 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Persentase</th>
                                    <td>
                                        @php
                                            $percentage = isset($budget->amount) && $budget->amount > 0 ? (($budget->realization ?? 0) / $budget->amount) * 100 : 0;
                                        @endphp
                                        <div class="progress">
                                            <div class="progress-bar {{ $percentage > 100 ? 'bg-danger' : ($percentage > 80 ? 'bg-warning' : 'bg-success') }}" 
                                                 role="progressbar" style="width: {{ min($percentage, 100) }}%">
                                                {{ number_format($percentage, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if(isset($budget->is_active) && $budget->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if(isset($budget->description) && $budget->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Deskripsi</h5>
                            <p class="text-muted">{{ $budget->description }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Recent Transactions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Transaksi Terbaru</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Deskripsi</th>
                                            <th>Jumlah</th>
                                            <th>Tipe</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions ?? [] as $transaction)
                                        <tr>
                                            <td>{{ $transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y') : 'N/A' }}</td>
                                            <td>{{ $transaction->description ?? 'N/A' }}</td>
                                            <td>Rp {{ number_format($transaction->amount ?? 0, 0, ',', '.') }}</td>
                                            <td>
                                                @if(isset($transaction->type))
                                                    @if($transaction->type == 'income')
                                                        <span class="badge badge-success">Pemasukan</span>
                                                    @else
                                                        <span class="badge badge-danger">Pengeluaran</span>
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada transaksi</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            @if(count($transactions ?? []) > 0)
                            <div class="text-center">
                                <a href="{{ route('backend.budget.transactions', $budget ?? 0) }}" class="btn btn-outline-primary">
                                    Lihat Semua Transaksi
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection