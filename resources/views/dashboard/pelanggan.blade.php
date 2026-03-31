@extends('layouts.app')

@section('title', 'Dashboard Pelanggan - PDAM')

@section('library_style')
    <link href="{{ asset('admin-template/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Dashboard Pelanggan</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row border-bottom mb-4">
    <div class="col-12 py-3">
        <h4 class="mb-1">Selamat Datang, {{ $pelanggan->nama }}!</h4>
        <p class="text-muted mb-0">No. Pelanggan: {{ $pelanggan->nomor_pelanggan }}</p>
    </div>
</div>

<div class="row">
    <!-- Tagihan Aktif -->
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title text-white mb-0"><i class="mdi mdi-wallet me-2"></i> TAGIHAN AKTIF</h4>
            </div>
            <div class="card-body mt-3">
                @if ($tagihanAktif)
                    <h5>Periode: {{ $tagihanAktif->periode }}</h5>
                    <p class="mb-1">Pemakaian: {{ $tagihanAktif->pemakaianAir->total_pemakaian ?? 0 }} m³</p>
                    <h3 class="text-primary mt-3">Total: Rp {{ number_format($tagihanAktif->total_tagihan, 0, ',', '.') }}</h3>
                    <p class="mb-3">Status: 
                        @if($tagihanAktif->status == 'Pending')
                            <span class="badge bg-warning">PENDING</span>
                        @else
                            <span class="badge bg-danger">BELUM BAYAR</span>
                        @endif
                    </p>
                    
                    @if($tagihanAktif->status == 'Belum Bayar')
                        <a href="{{ route('pelanggan.pembayaran', $tagihanAktif->id) }}" class="btn btn-primary rounded-pill mt-2">
                            <i class="mdi mdi-credit-card me-1"></i> Bayar Sekarang
                        </a>
                    @else
                        <a href="{{ route('pelanggan.pembayaran', $tagihanAktif->id) }}" class="btn btn-warning rounded-pill mt-2">
                            <i class="mdi mdi-eye me-1"></i> Cek Status Bayar
                        </a>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="mdi mdi-check-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Hore! Tidak ada tagihan aktif.</h5>
                        <p class="text-muted">Semua tagihan Anda sudah lunas.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Pemakaian Bulan Ini -->
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="card-title text-white mb-0"><i class="mdi mdi-water me-2"></i> PEMAKAIAN BULAN INI</h4>
            </div>
            <div class="card-body mt-3">
                @if ($pemakaianBulanIni)
                    <h5>Periode: {{ $pemakaianBulanIni->periode }}</h5>
                    <div class="d-flex justify-content-between my-2 mt-4">
                        <span>Meter Awal:</span>
                        <span class="fw-bold">{{ $pemakaianBulanIni->meter_awal }}</span>
                    </div>
                    <div class="d-flex justify-content-between my-2">
                        <span>Meter Akhir:</span>
                        <span class="fw-bold">{{ $pemakaianBulanIni->meter_akhir }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fs-5">Total Pemakaian:</span>
                        <span class="fs-4 fw-bold text-info">{{ $pemakaianBulanIni->total_pemakaian }} m³</span>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="mdi mdi-history text-secondary" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Pemakaian belum dicatat</h5>
                        <p class="text-muted">Petugas belum mencatat meteran Anda bulan ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Riwayat Pembayaran Terakhir -->
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title"><i class="mdi mdi-history me-2"></i> Riwayat Pembayaran Terakhir</h4>
            </div>
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="table-riwayat">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Total (Rp)</th>
                                <th>Tgl Bayar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayatPembayaran as $bayar)
                                <tr>
                                    <td>{{ $bayar->tagihan->periode ?? '-' }}</td>
                                    <td>Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                                    <td>{{ date('d/m/Y', strtotime($bayar->tanggal_bayar)) }}</td>
                                    <td>
                                        @if($bayar->status_pembayaran == 'Sukses')
                                            <span class="badge bg-success"><i class="mdi mdi-check-circle me-1"></i> Lunas</span>
                                        @elseif($bayar->status_pembayaran == 'Pending')
                                            <span class="badge bg-warning"><i class="mdi mdi-timer-sand me-1"></i> Pending</span>
                                        @else
                                            <span class="badge bg-danger"><i class="mdi mdi-close-circle me-1"></i> Gagal</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('library_script')
    <script src="{{ asset('admin-template/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-template/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function() {
            $('#table-riwayat').DataTable({
                "paging": false,
                "info": false,
                "searching": false
            });
        });
    </script>
@endsection
