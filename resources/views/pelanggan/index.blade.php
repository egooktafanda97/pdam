@extends('layouts.app')

@section('title', 'Data Pelanggan - PDAM Tirta Bening')

@section('library_style')
    <link href="{{ asset('admin-template/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Data Pelanggan</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Pelanggan</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Daftar Pelanggan</h4>
                <a href="{{ route('admin.pelanggan.create') }}" class="btn btn-primary btn-sm"><i class="mdi mdi-plus me-1"></i> Tambah Pelanggan</a>
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
                                <th>No. Pelanggan</th>
                                <th>Nama Lengkap</th>
                                <th>Golongan Tarif</th>
                                <th>Alamat</th>
                                <th>No Hp</th>
                                <th>Petugas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelanggan as $p)
                            <tr>
                                <td class="fw-bold">{{ $p->nomor_pelanggan }}</td>
                                <td>
                                    {{ $p->nama }}<br>
                                    <small class="text-muted">Username: {{ $p->user ? $p->user->username : '-' }}</small>
                                </td>
                                <td>{{ $p->golonganTarif ? $p->golonganTarif->nama_golongan : '-' }}</td>
                                <td>{{ Str::limit($p->alamat, 30) }}</td>
                                <td>{{ $p->no_telepon ?? '-' }}</td>
                                <td>{{ $p->petugas ? $p->petugas->name : '-' }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.pelanggan.edit', $p->id) }}" class="btn btn-sm btn-warning"><i class="mdi mdi-pencil"></i></a>
                                    <form action="{{ route('admin.pelanggan.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data pelanggan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
                                    </form>
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
