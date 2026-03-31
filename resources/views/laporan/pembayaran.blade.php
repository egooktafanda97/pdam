@extends('layouts.app')

@section('title', 'Laporan Pembayaran - PDAM Tirta Bening')

@section('library_style')
    <link href="{{ asset('admin-template/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Laporan Pembayaran</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ Auth::user()->role == 'pimpinan' ? route('pimpinan.dashboard') : route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Lap. Pembayaran</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                <h4 class="card-title mb-0">Filter Laporan</h4>
                
                <form action="{{ route('laporan.pembayaran') }}" method="GET" class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                    <select name="bulan" class="form-select form-select-sm">
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                            </option>
                        @endfor
                    </select>
                    <select name="tahun" class="form-select form-select-sm">
                        @for($i=date('Y'); $i>=2020; $i--)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="mdi mdi-filter"></i> Tampilkan</button>
                </form>
            </div>
            <div class="card-body">
                <div class="alert alert-success border-0 text-center py-3 mb-4">
                    <h6 class="mb-1 text-muted">Total Pendapatan (Bulan {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} {{ $tahun }})</h6>
                    <h2 class="text-success mb-0 fw-bold">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</h2>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-muted mb-0">List Data Pembayaran</h6>
                    <button class="btn btn-sm btn-danger" onclick="window.print()"><i class="mdi mdi-file-pdf"></i> Ekspor PDF / Cetak</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Ref</th>
                                <th>Pelanggan</th>
                                <th>Periode Tagihan</th>
                                <th>Metode</th>
                                <th>Jumlah Terima</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembayaran as $p)
                            <tr>
                                <td>{{ date('d/m/Y H:i', strtotime($p->tanggal_bayar)) }}</td>
                                <td><small>{{ $p->reference_number }}</small></td>
                                <td>
                                    <strong>{{ $p->tagihan->pelanggan->nomor_pelanggan }}</strong><br>
                                    {{ $p->tagihan->pelanggan->nama }}
                                </td>
                                <td>{{ $p->tagihan->periode }}</td>
                                <td>{{ $p->metode_pembayaran }}</td>
                                <td class="text-end fw-bold text-primary">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
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
    <script src="{{ asset('admin-template/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-template/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function() {
            $('#table1').DataTable();
        });
    </script>
@endsection
