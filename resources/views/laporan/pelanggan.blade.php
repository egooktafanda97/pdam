@extends('layouts.app')

@section('title', 'Laporan Pelanggan - PDAM')

@section('library_style')
    <link href="{{ asset('admin-template/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Laporan Pelanggan</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ Auth::user()->role == 'pimpinan' ? route('pimpinan.dashboard') : route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Lap. Pelanggan</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                <h4 class="card-title mb-0">Filter Golongan</h4>
                
                <form action="{{ route('laporan.pelanggan') }}" method="GET" class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                    <select name="golongan_id" class="form-select form-select-sm">
                        <option value="Semua" {{ $golongan_id == 'Semua' ? 'selected' : '' }}>Semua Golongan</option>
                        @foreach($golongan as $g)
                            <option value="{{ $g->id }}" {{ $golongan_id == $g->id ? 'selected' : '' }}>{{ $g->nama_golongan }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="mdi mdi-filter"></i> Tampilkan</button>
                </form>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="badge bg-info mb-0 font-size-14 px-3 py-2">Total: {{ $pelanggan->count() }} Pelanggan</h6>
                    <button class="btn btn-sm btn-danger" onclick="window.print()"><i class="mdi mdi-file-pdf"></i> Ekspor PDF / Cetak</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No. Pelanggan</th>
                                <th>Nama Lengkap</th>
                                <th>Golongan Tarif</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th>Tgl Daftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelanggan as $p)
                            <tr>
                                <td>{{ $p->nomor_pelanggan }}</td>
                                <td>{{ $p->nama }}</td>
                                <td>{{ $p->golonganTarif->nama_golongan }}</td>
                                <td>{{ $p->no_telepon ?? '-' }}</td>
                                <td>{{ \Str::limit($p->alamat, 30) }}</td>
                                <td>{{ $p->created_at->format('d/m/Y') }}</td>
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
