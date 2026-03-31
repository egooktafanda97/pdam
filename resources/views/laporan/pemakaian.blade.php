@extends('layouts.app')

@section('title', 'Laporan Pemakaian Air - PDAM')

@section('library_style')
    <link href="{{ asset('admin-template/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Laporan Pemakaian Air</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Lap. Pemakaian</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                <h4 class="card-title mb-0">Filter Periode</h4>
                
                <form action="{{ route('laporan.pemakaian') }}" method="GET" class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                    <select name="bulan" class="form-select form-select-sm">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <input type="number" name="tahun" class="form-control form-control-sm" value="{{ $tahun }}" style="width:80px">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="mdi mdi-magnify"></i> Cari</button>
                </form>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0 text-center py-3 mb-4 d-flex justify-content-around align-items-center">
                    <div>
                        <h6 class="mb-1 text-muted">Periode</h6>
                        <h4 class="text-info mb-0 fw-bold">{{ $bulan }}/{{ $tahun }}</h4>
                    </div>
                    <div class="vr bg-info bg-opacity-25" style="width: 2px;"></div>
                    <div>
                        <h6 class="mb-1 text-muted">Total Distribusi Air Terjual</h6>
                        <h3 class="text-primary mb-0 fw-bold">{{ number_format($total_volume, 0, ',', '.') }} <small class="fs-6">m³</small></h3>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-muted mb-0">Data Pemakaian Pelanggan</h6>
                    <button class="btn btn-sm btn-danger" onclick="window.print()"><i class="mdi mdi-file-pdf"></i> Ekspor PDF / Cetak</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Golongan</th>
                                <th>Meter Awal</th>
                                <th>Meter Akhir</th>
                                <th>Volume (m³)</th>
                                <th>Estimasi Tagihan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pemakaian as $p)
                            <tr>
                                <td>
                                    <strong>{{ $p->pelanggan->nomor_pelanggan }}</strong><br>
                                    {{ $p->pelanggan->nama }}
                                </td>
                                <td>{{ $p->pelanggan->golonganTarif->nama_golongan }}</td>
                                <td class="text-center">{{ $p->meter_awal }}</td>
                                <td class="text-center">{{ $p->meter_akhir }}</td>
                                <td class="text-center fw-bold">{{ $p->total_pemakaian }}</td>
                                <td class="text-end">Rp {{ number_format(($p->total_pemakaian * $p->pelanggan->golonganTarif->tarif_per_m3) + ($p->pelanggan->golonganTarif->biaya_admin ?? 2500), 0, ',', '.') }}</td>
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
