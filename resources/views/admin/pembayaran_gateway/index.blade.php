@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">History Pembayaran Gateway (Online)</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Metode / Channel</th>
                                    <th>Kode Bayar</th>
                                    <th>Gateway Ref ID</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembayaran as $p)
                                <tr>
                                    <td>{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y H:i') : '-' }}</td>
                                    <td>
                                        <strong>{{ $p->tagihan->pelanggan->nama ?? '-' }}</strong><br>
                                        <small class="text-muted">{{ $p->tagihan->pelanggan->nomor_pelanggan ?? '-' }}</small>
                                    </td>
                                    <td>Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                                    <td>{{ $p->penyedia_layanan }}</td>
                                    <td>{{ $p->kode_pembayaran }}</td>
                                    <td>{{ $p->referensi_gateway }}</td>
                                    <td>
                                        @if($p->status_pembayaran == 'Sukses')
                                            <span class="badge bg-success">Sukses</span>
                                        @elseif($p->status_pembayaran == 'Pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">{{ $p->status_pembayaran }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada transaksi pembayaran online</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $pembayaran->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
