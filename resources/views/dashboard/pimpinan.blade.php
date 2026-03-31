@extends('layouts.app')

@section('title', 'Dashboard Pimpinan - PDAM')

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Dashboard Pimpinan</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('pimpinan.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row border-bottom mb-4">
    <div class="col-12 py-3">
        <h4 class="mb-0">Selamat Datang, Bapak/Ibu {{ Auth::user()->name }}!</h4>
    </div>
</div>

<div class="row">
    <!-- Total Pendapatan -->
    <div class="col-md-4">
        <div class="card text-center">
            <div class="mb-2 card-body text-muted">
                <h3 class="text-success mt-2">
                    Rp {{ number_format($data['total_pendapatan'], 0, ',', '.') }}
                </h3>
                <p class="text-truncate mb-2">Total Pendapatan</p>
                <div class="avatar-sm mx-auto mb-4">
                    <span class="avatar-title rounded-circle bg-soft-success text-success font-size-24">
                        <i class="mdi mdi-wallet"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Pelanggan -->
    <div class="col-md-4">
        <div class="card text-center">
            <div class="mb-2 card-body text-muted">
                <h3 class="text-primary mt-2">
                    {{ number_format($data['total_pelanggan'], 0, ',', '.') }}
                </h3>
                <p class="text-truncate mb-2">Total Pelanggan</p>
                <div class="avatar-sm mx-auto mb-4">
                    <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                        <i class="mdi mdi-account-group"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Aduan Selesai -->
    <div class="col-md-4">
        <div class="card text-center">
            <div class="mb-2 card-body text-muted">
                <h3 class="text-info mt-2">
                    {{ number_format($data['aduan_selesai'], 0, ',', '.') }}
                </h3>
                <p class="text-truncate mb-2">Aduan Diselesaikan</p>
                <div class="avatar-sm mx-auto mb-4">
                    <span class="avatar-title rounded-circle bg-soft-info text-info font-size-24">
                        <i class="mdi mdi-checkbox-marked"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 text-center mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Akses Laporan Lengkap</h4>
                <p>Untuk melihat rincian laporan keuangan, pelanggan, dan operasional, silakan akses menu Laporan di sebelah kiri.</p>
                <a href="{{ route('laporan.pembayaran') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="mdi mdi-file-document me-2"></i> Buka Laporan Pembayaran
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
