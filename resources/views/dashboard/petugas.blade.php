@extends('layouts.app')

@section('title', 'Dashboard Petugas - PDAM Tirta Bening')

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Dashboard Petugas</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('petugas.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Pelanggan Aktif -->
    <div class="col-md-6 col-xl-3">
        <div class="card text-center">
            <div class="mb-2 card-body text-muted">
                <h3 class="text-primary mt-2">
                    {{ number_format($data['pelanggan_aktif'], 0, ',', '.') }}
                </h3>
                <p class="text-truncate mb-2">Total Pelanggan Aktif</p>
                <div class="avatar-sm mx-auto mb-4">
                    <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                        <i class="mdi mdi-account-group"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pemakaian Belum Diinput -->
    <div class="col-md-6 col-xl-3">
        <div class="card text-center">
            <div class="mb-2 card-body text-muted">
                <h3 class="text-info mt-2">
                    {{ number_format($data['belum_diinput'], 0, ',', '.') }}
                </h3>
                <p class="text-truncate mb-2">Belum Diinput (Bln Ini)</p>
                <div class="avatar-sm mx-auto mb-4">
                    <span class="avatar-title rounded-circle bg-soft-info text-info font-size-24">
                        <i class="mdi mdi-file-document"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tagihan Belum Lunas -->
    <div class="col-md-6 col-xl-3">
        <div class="card text-center">
            <div class="mb-2 card-body text-muted">
                <h3 class="text-warning mt-2">
                    {{ number_format($data['tagihan_belum_lunas'], 0, ',', '.') }}
                </h3>
                <p class="text-truncate mb-2">Tagihan Belum Lunas</p>
                <div class="avatar-sm mx-auto mb-4">
                    <span class="avatar-title rounded-circle bg-soft-warning text-warning font-size-24">
                        <i class="mdi mdi-wallet"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Aduan Baru -->
    <div class="col-md-6 col-xl-3">
        <div class="card text-center">
            <div class="mb-2 card-body text-muted">
                <h3 class="text-danger mt-2">
                    {{ number_format($data['aduan_baru'], 0, ',', '.') }}
                </h3>
                <p class="text-truncate mb-2">Aduan Baru</p>
                <div class="avatar-sm mx-auto mb-4">
                    <span class="avatar-title rounded-circle bg-soft-danger text-danger font-size-24">
                        <i class="mdi mdi-message-alert"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
