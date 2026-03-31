@extends('layouts.app')

@section('title', 'Daftar Tagihan - PDAM')

@section('library_style')
    <link href="{{ asset('admin-template/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Daftar Tagihan & Pembayaran</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Tagihan</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                <h4 class="card-title mb-0">Daftar Tagihan</h4>
                
                <form action="{{ route('petugas.tagihan') }}" method="GET" class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                    <select name="status" class="form-select form-select-sm">
                        <option value="Semua" {{ $status == 'Semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="Belum Bayar" {{ $status == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending (Gateway)</option>
                        <option value="Lunas" {{ $status == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                    <select name="bulan" class="form-select form-select-sm">
                        <option value="">Smua Bln</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ ($bulan ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <input type="number" name="tahun" class="form-control form-control-sm" value="{{ $tahun ?? '' }}" placeholder="Tahun" style="width:80px">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="mdi mdi-filter"></i> Filter</button>
                </form>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible show fade">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible show fade">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Periode</th>
                                <th>Pemakaian</th>
                                <th>Total Tagihan</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tagihan as $t)
                            <tr>
                                <td>
                                    <strong>{{ $t->pelanggan->nomor_pelanggan }}</strong><br>
                                    {{ $t->pelanggan->nama }}
                                </td>
                                <td>{{ $t->periode_bulan }}/{{ $t->periode_tahun }}</td>
                                <td class="text-center">{{ $t->pemakaianAir->total_pemakaian ?? 0 }} m³</td>
                                <td class="text-end fw-bold">Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}</td>
                                <td>{{ $t->tanggal_jatuh_tempo ? date('d/m/Y', strtotime($t->tanggal_jatuh_tempo)) : '-' }}</td>
                                <td>
                                    @if($t->status == 'Lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif($t->status == 'Pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Belum Bayar</span>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    @if($t->status == 'Belum Bayar' && Auth::user()->hasRole('petugas'))
                                        <a href="{{ route('petugas.pembayaran', $t->id) }}" class="btn btn-sm btn-primary" title="Bayar Loket">
                                            <i class="mdi mdi-cash me-1"></i> Bayar
                                        </a>
                                    @elseif($t->status == 'Lunas')
                                        @php
                                            $pembayaran = $t->pembayaran()->where('status_pembayaran', 'Sukses')->first();
                                        @endphp
                                        @if($pembayaran && Auth::user()->hasRole('petugas'))
                                        <a href="{{ route('petugas.pembayaran.cetak', $pembayaran->id) }}" target="_blank" class="btn btn-sm btn-success" title="Cetak Bukti">
                                            <i class="mdi mdi-printer me-1"></i> Struk
                                        </a>
                                        @endif
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
