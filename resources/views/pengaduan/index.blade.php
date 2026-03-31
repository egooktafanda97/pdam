@extends('layouts.app')

@section('title', 'Daftar Pengaduan - PDAM Tirta Bening')

@section('library_style')
    <link href="{{ asset('admin-template/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Daftar Pengaduan</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Pengaduan</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                <h4 class="card-title mb-0">Semua Pengaduan</h4>
                
                <form action="{{ route('admin.pengaduan') }}" method="GET" class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="Semua" {{ $status == 'Semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="Baru" {{ $status == 'Baru' ? 'selected' : '' }}>Baru</option>
                        <option value="Diproses" {{ $status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Selesai" {{ $status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </form>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible show fade">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="table1">
                        <thead>
                            <tr>
                                <th>Tgl Aduan</th>
                                <th>Pelanggan</th>
                                <th>Kategori</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengaduan as $aduan)
                            <tr>
                                <td>
                                    {{ $aduan->created_at->format('d/m/Y') }}<br>
                                    <small class="text-muted">{{ $aduan->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $aduan->pelanggan->nomor_pelanggan }}</strong><br>
                                    {{ $aduan->pelanggan->nama }}
                                </td>
                                <td>{{ $aduan->kategori }}</td>
                                <td>{{ \Str::limit($aduan->judul_pengaduan, 40) }}</td>
                                <td>
                                    @if($aduan->status == 'Baru')
                                        <span class="badge bg-danger">Baru</span>
                                    @elseif($aduan->status == 'Diproses')
                                        <span class="badge bg-warning">Diproses</span>
                                    @else
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.pengaduan.show', $aduan->id) }}" class="btn btn-sm btn-info text-white">
                                        <i class="mdi mdi-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $pengaduan->links() }}
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
            $('#table1').DataTable({
                "paging": false, 
                "info": false 
            });
        });
    </script>
@endsection
