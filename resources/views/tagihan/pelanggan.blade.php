@extends('layouts.app')

@section('title', 'Tagihan Saya - PDAM Tirta Bening')

@section('library_style')
    <link href="{{ asset('admin-template/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-template/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('breadcrumb')
    <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Tagihan Saya</h3>
        <p class="text-subtitle text-muted">Daftar seluruh tagihan air Anda.</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tagihan Saya</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Tagihan</h4>
            
            <form action="{{ route('pelanggan.tagihan') }}" method="GET" class="d-flex align-items-center gap-2">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="Semua" {{ $status == 'Semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="Belum" {{ $status == 'Belum' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="Lunas" {{ $status == 'Lunas' ? 'selected' : '' }}>Lunas</option>
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
                <table class="table table-hover table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Periode</th>
                            <th>Pemakaian</th>
                            <th>Total (Rp)</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tagihan as $index => $t)
                        <tr>
                            <td>{{ $tagihan->firstItem() + $index }}</td>
                            <td>{{ $t->periode }}</td>
                            <td>{{ $t->pemakaianAir->total_pemakaian ?? 0 }} m³</td>
                            <td class="fw-bold">Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}</td>
                            <td>{{ date('d/m/Y', strtotime($t->tanggal_jatuh_tempo)) }}</td>
                            <td>
                                @if($t->status == 'Lunas')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Lunas</span>
                                @elseif($t->status == 'Pending')
                                    <span class="badge bg-warning"><i class="bi bi-hourglass me-1"></i> Pending</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Belum</span>
                                @endif
                            </td>
                            <td>
                                @if($t->status == 'Belum Bayar' || $t->status == 'Pending')
                                    <a href="{{ route('pelanggan.pembayaran', $t->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-credit-card me-1"></i> Bayar
                                    </a>
                                @elseif($t->status == 'Lunas')
                                    @php
                                        // Cari pembayaran yg sukses untuk tagihan ini (bisa difetch lwt relation)
                                        $pembayaran_sukses = $t->pembayaran->where('status_pembayaran', 'Sukses')->first();
                                    @endphp
                                    @if($pembayaran_sukses)
                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="alert('Bukti pembayaran akan segera tersedia. Modul cetak bukti untuk pelanggan dapat dikembangkan lebih lanjut.')">
                                            <i class="bi bi-file-earmark-text me-1"></i> Bukti
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $tagihan->appends(['status' => $status])->links() }}
            </div>
        </div>
    </div>
</section>
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
