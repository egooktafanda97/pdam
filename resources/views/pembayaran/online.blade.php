@extends('layouts.app')

@section('title', 'Bayar Tagihan Online - PDAM Tirta Bening')

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Bayar Tagihan Online</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.tagihan') }}">Tagihan</a></li>
                <li class="breadcrumb-item active">Bayar Online</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Detail Tagihan -->
    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header bg-primary">
                <h4 class="card-title text-white mb-0"><i class="mdi mdi-file-document-outline me-2"></i> Rincian Tagihan</h4>
            </div>
            <div class="card-body mt-3">
                <div class="mb-3">
                    <small class="text-muted d-block">Nomor Pelanggan</small>
                    <h6 class="mb-0 fw-bold">{{ $tagihan->pelanggan->nomor_pelanggan }}</h6>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block">Nama Lengkap</small>
                    <h6 class="mb-0">{{ $tagihan->pelanggan->nama }}</h6>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Periode Tagihan</small>
                    <h6 class="mb-0">{{ $tagihan->periode }}</h6>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block">Total Pemakaian</small>
                    <h6 class="mb-0">{{ $tagihan->pemakaianAir->total_pemakaian ?? 0 }} m³</h6>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-2">
                    <span>Tagihan Air</span>
                    <span>Rp {{ number_format(($tagihan->pemakaianAir->total_pemakaian ?? 0) * $tagihan->pelanggan->golonganTarif->tarif_per_m3, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Biaya Admin</span>
                    <span>Rp {{ number_format($tagihan->biaya_admin, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Denda</span>
                    <span>Rp {{ number_format($tagihan->denda, 0, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between py-2 border-top border-bottom">
                    <h5 class="mb-0 fw-bold">TOTAL TAGIHAN</h5>
                    <h5 class="mb-0 fw-bold text-primary">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Opsi Pembayaran & Feedback -->
    <div class="col-12 col-lg-7">
        <!-- Jika Ada Pesan Success (Simulasi respon gateway) -->
        @if(session('success'))
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="text-center">
                        <i class="mdi mdi-check-circle" style="font-size: 3rem;"></i>
                        <h4 class="text-white mt-3">Pesanan Pembayaran Berhasil Dibuat!</h4>
                        <p class="text-white-50">Segera selesaikan pembayaran sesuai instruksi di bawah.</p>
                    </div>
                </div>
            </div>

            @if(session('payment_data'))
                @php $pData = session('payment_data'); @endphp
                <div class="card border border-warning">
                    <div class="card-header bg-warning">
                        <h5 class="card-title text-dark mb-0"><i class="mdi mdi-information me-2"></i> Instruksi Pembayaran</h5>
                    </div>
                    <div class="card-body mt-3">
                        <div class="text-center mb-4">
                            <span class="badge bg-dark mb-2">Metode: {{ $pData['method'] }}</span>
                            <h3 class="fw-bold tracking-widest text-primary">{{ $pData['code'] }}</h3>
                            <p class="text-muted">Nominal Transfer: <strong class="text-dark">Rp {{ number_format($pData['amount'], 0, ',', '.') }}</strong></p>
                        </div>
                        
                        <div class="alert alert-info border-0">
                            <strong>Batas Waktu Pembayaran:</strong> Selesaikan sebelum {{ $pData['limit'] }}. Jika lewat, silahkan buat ulang permintaan pembayaran.
                        </div>

                        <div class="d-grid mt-4">
                            <a href="{{ route('pelanggan.tagihan') }}" class="btn btn-outline-primary">Kembali ke Daftar Tagihan</a>
                        </div>
                    </div>
                </div>
            @endif
        @elseif($pembayaranPending)
            <!-- Jika Ada Transaksi Pending Sblmnya (blum dibypass session yg brusan) -->
            <div class="card border border-warning">
                <div class="card-header bg-warning">
                    <h5 class="card-title text-dark mb-0"><i class="mdi mdi-timer-sand me-2"></i> Transaksi Sedang Diproses</h5>
                </div>
                <div class="card-body mt-3">
                    <p class="text-muted">Anda memiliki permintaan pembayaran yang belum diselesaikan untuk tagihan ini.</p>
                    <div class="text-center mb-4 mt-3">
                        <span class="badge bg-dark mb-2">Metode: {{ $pembayaranPending->payment_gateway_code }}</span>
                        <h3 class="fw-bold tracking-widest text-primary">{{ substr($pembayaranPending->reference_number, 8) }}</h3>
                        <p class="text-muted">Nominal Transfer: <strong class="text-dark">Rp {{ number_format($pembayaranPending->jumlah_bayar, 0, ',', '.') }}</strong></p>
                    </div>
                    
                    <hr>
                    <p class="mb-3 text-center text-muted fw-bold">Ingin mengganti metode pembayaran?</p>
                    <form action="{{ route('pelanggan.pembayaran.bayar', $tagihan->id) }}" method="POST">
                        @csrf
                        <div class="d-grid gap-2">
                            <button type="submit" name="payment_method" value="GANTI_METODE" class="btn btn-danger">Batalkan & Buat Transaksi Baru</button>
                            <a href="{{ route('pelanggan.tagihan') }}" class="btn btn-outline-secondary">Nanti Saja</a>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <!-- Form Pilih Pembayaran -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title mb-0">Pilih Metode Pembayaran</h4>
                </div>
                <div class="card-body mt-3">
                    <form action="{{ route('pelanggan.pembayaran.bayar', $tagihan->id) }}" method="POST">
                        @csrf
                        
                        <p class="text-muted mb-3">Pilih salah satu metode pembayaran di bawah ini:</p>

                        <!-- QRIS -->
                        <div class="form-check border rounded p-3 mb-3 d-flex align-items-center cursor-pointer">
                            <input class="form-check-input ms-0 me-3 mt-0" type="radio" name="payment_method" id="qris" value="QRIS" required style="width: 1.5rem; height: 1.5rem;">
                            <label class="form-check-label ms-1 w-100 cursor-pointer" for="qris">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">QRIS</h6>
                                        <small class="text-muted">OVO, GoPay, Dana, LinkAja, ShopeePay & M-Banking yg support QRIS</small>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Virtual Account BCA -->
                        <div class="form-check border rounded p-3 mb-3 d-flex align-items-center cursor-pointer">
                            <input class="form-check-input ms-0 me-3 mt-0" type="radio" name="payment_method" id="bca" value="BCA_VA" style="width: 1.5rem; height: 1.5rem;">
                            <label class="form-check-label ms-1 w-100 cursor-pointer" for="bca">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-0 fw-bold text-dark">BCA Virtual Account</h6>
                                    <small class="text-muted">Bayar otomatis 24 jam</small>
                                </div>
                            </label>
                        </div>

                        <!-- Virtual Account Mandiri -->
                        <div class="form-check border rounded p-3 mb-3 d-flex align-items-center cursor-pointer">
                            <input class="form-check-input ms-0 me-3 mt-0" type="radio" name="payment_method" id="mandiri" value="MANDIRI_VA" style="width: 1.5rem; height: 1.5rem;">
                            <label class="form-check-label ms-1 w-100 cursor-pointer" for="mandiri">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-0 fw-bold text-dark">Mandiri Virtual Account</h6>
                                    <small class="text-muted">Bayar otomatis 24 jam via Livin'/ATM Mandiri</small>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Virtual Account BNI -->
                        <div class="form-check border rounded p-3 d-flex align-items-center cursor-pointer">
                            <input class="form-check-input ms-0 me-3 mt-0" type="radio" name="payment_method" id="bni" value="BNI_VA" style="width: 1.5rem; height: 1.5rem;">
                            <label class="form-check-label ms-1 w-100 cursor-pointer" for="bni">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-0 fw-bold text-dark">BNI Virtual Account</h6>
                                    <small class="text-muted">Bayar otomatis 24 jam via BNI Mobile/ATM BNI</small>
                                </div>
                            </label>
                        </div>

                        <div class="mt-4 pt-3 border-top d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Proses Pembayaran</button>
                            <a href="{{ route('pelanggan.tagihan') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
