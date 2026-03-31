@extends('layouts.app')

@section('title', 'Dashboard Admin - PDAM Tirta Bening')

@section('library_style')
    <link href="{{ asset('admin-template/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Dashboard Admin</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Total Pelanggan -->
    <div class="col-md-6 col-xl-3">
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
    
    <!-- Pemakaian Bulan Ini -->
    <div class="col-md-6 col-xl-3">
        <div class="card text-center">
            <div class="mb-2 card-body text-muted">
                <h3 class="text-info mt-2">
                    {{ number_format($data['pemakaian_bulan_ini'], 0, ',', '.') }} m³
                </h3>
                <p class="text-truncate mb-2">Pemakaian Bln Ini</p>
                <div class="avatar-sm mx-auto mb-4">
                    <span class="avatar-title rounded-circle bg-soft-info text-info font-size-24">
                        <i class="mdi mdi-file-document"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sudah Bayar -->
    <div class="col-md-6 col-xl-3">
        <div class="card text-center">
            <div class="mb-2 card-body text-muted">
                <h3 class="text-success mt-2">
                    {{ number_format($data['sudah_bayar'], 0, ',', '.') }}
                </h3>
                <p class="text-truncate mb-2">Sdh Bayar Bln Ini</p>
                <div class="avatar-sm mx-auto mb-4">
                    <span class="avatar-title rounded-circle bg-soft-success text-success font-size-24">
                        <i class="mdi mdi-chart-line"></i>
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

<div class="row">
    <!-- Tagihan Terbaru -->
    <div class="col-12 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4>Tagihan Terbaru</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="table-tagihan">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Periode</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['tagihan_terbaru'] as $tag)
                                <tr>
                                    <td>{{ $tag->pelanggan->nama ?? '-' }}</td>
                                    <td>{{ $tag->periode_bulan }}/{{ $tag->periode_tahun }}</td>
                                    <td class="fw-bold">Rp {{ number_format($tag->total_tagihan, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pembayaran Terakhir -->
    <div class="col-12 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4>Pembayaran Terakhir</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="table-pembayaran">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['pembayaran_terakhir'] as $bayar)
                                <tr>
                                    <td>{{ $bayar->tagihan->pelanggan->nama ?? '-' }}</td>
                                    <td>{{ date('d/m/Y', strtotime($bayar->tanggal_bayar)) }}</td>
                                    <td class="fw-bold">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengaduan Terbaru -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Pengaduan Terbaru</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="table-pengaduan">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Kategori</th>
                                <th>Judul</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['pengaduan_terbaru'] as $aduan)
                                <tr>
                                    <td>{{ $aduan->pelanggan->nama ?? '-' }}</td>
                                    <td>{{ $aduan->kategori }}</td>
                                    <td>{{ $aduan->judul_pengaduan }}</td>
                                    <td>
                                        @if($aduan->status == 'Baru')
                                            <span class="badge bg-danger">Baru</span>
                                        @elseif($aduan->status == 'Diproses')
                                            <span class="badge bg-warning">Diproses</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
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
            let dtConfig = {
                "paging": false,
                "info": false,
                "searching": false
            };
            $('#table-tagihan').DataTable(dtConfig);
            $('#table-pembayaran').DataTable(dtConfig);
            $('#table-pengaduan').DataTable(dtConfig);
        });
    </script>
@endsection
