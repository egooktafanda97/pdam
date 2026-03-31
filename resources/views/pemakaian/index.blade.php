@extends('layouts.app')

@section('title', 'Pemakaian Air - PDAM Tirta Bening')

@section('library_style')
    <link href="{{ asset('admin-template/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Data Pemakaian Air</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Pemakaian Air</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                <h4 class="card-title mb-0">Pemakaian Air (Periode: {{ $bulan }}/{{ $tahun }})</h4>
                <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                    <form action="{{ route('petugas.pemakaian.index') }}" method="GET" class="d-flex align-items-center me-2">
                        <label class="me-2 text-nowrap">Bulan:</label>
                        <select name="bulan" class="form-select form-select-sm me-1">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        <label class="me-2 text-nowrap ms-2">Tahun:</label>
                        <input type="number" name="tahun" class="form-control form-control-sm" value="{{ $tahun }}" style="width:80px">
                        <button type="submit" class="btn btn-sm btn-secondary ms-1"><i class="mdi mdi-magnify"></i></button>
                    </form>
                    <a href="{{ route('petugas.pemakaian.create') }}" class="btn btn-primary btn-sm"><i class="mdi mdi-plus me-1"></i> Input Baru</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible show fade">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Tgl Catat</th>
                                <th>No. Pelanggan</th>
                                <th>Nama</th>
                                <th>Meter Awal</th>
                                <th>Meter Akhir</th>
                                <th>Total (m³)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pemakaian as $p)
                            <tr>
                                <td>{{ $p->created_at->format('d/m/Y') }}</td>
                                <td>{{ $p->pelanggan->nomor_pelanggan }}</td>
                                <td>{{ $p->pelanggan->nama }}</td>
                                <td class="text-center">{{ $p->meter_awal }}</td>
                                <td class="text-center">{{ $p->meter_akhir }}</td>
                                <td class="text-center fw-bold text-primary">{{ $p->total_pemakaian }}</td>
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
