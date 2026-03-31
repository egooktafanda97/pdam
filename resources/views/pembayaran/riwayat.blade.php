@extends('layouts.app')

@section('title', 'Riwayat Pembayaran - PDAM Tirta Bening')

@section('library_style')
    <link href="{{ asset('admin-template/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Riwayat Pembayaran</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Riwayat Pembayaran</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Daftar Riwayat Pembayaran</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No. Referensi</th>
                                <th>Tanggal Bayar</th>
                                <th>Periode Tagihan</th>
                                <th>Jumlah Bayar (Rp)</th>
                                <th>Metode</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembayaran as $bayar)
                            <tr>
                                <td class="fw-bold tracking-wider">{{ $bayar->reference_number }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime($bayar->tanggal_bayar)) }}</td>
                                <td>{{ $bayar->tagihan->periode ?? '-' }}</td>
                                <td class="fw-bold">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                                <td>{{ $bayar->metode_pembayaran }}</td>
                                <td>
                                    @if($bayar->status_pembayaran == 'Sukses')
                                        <span class="badge bg-success"><i class="mdi mdi-check-circle me-1"></i> Sukses</span>
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
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $pembayaran->links() }}
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
