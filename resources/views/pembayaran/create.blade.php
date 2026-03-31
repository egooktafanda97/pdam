@extends('layouts.app')

@section('title', 'Bayar Tagihan Loket - PDAM')

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Bayar Tagihan Loket</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('petugas.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('petugas.tagihan') }}">Tagihan</a></li>
                <li class="breadcrumb-item active">Bayar</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Rincian Tagihan -->
    <div class="col-md-7 col-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h4 class="card-title text-white mb-0"><i class="mdi mdi-receipt me-2"></i> Rincian Tagihan</h4>
            </div>
            <div class="card-body mt-3">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="30%" class="fw-bold">No. Pelanggan</td>
                        <td width="5%">:</td>
                        <td>{{ $tagihan->pelanggan->nomor_pelanggan }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nama Lengkap</td>
                        <td>:</td>
                        <td>{{ $tagihan->pelanggan->nama }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Alamat</td>
                        <td>:</td>
                        <td>{{ $tagihan->pelanggan->alamat }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Gol. Tarif</td>
                        <td>:</td>
                        <td>{{ $tagihan->pelanggan->golonganTarif->nama_golongan }} (Rp {{ number_format($tagihan->pelanggan->golonganTarif->tarif_per_m3, 0, ',', '.') }}/m³)</td>
                    </tr>
                </table>

                <hr>

                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="30%" class="fw-bold">Periode</td>
                        <td width="5%">:</td>
                        <td>{{ $tagihan->periode }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Pemakaian Air</td>
                        <td>:</td>
                        <td>
                            {{ $tagihan->pemakaianAir->meter_awal }} - {{ $tagihan->pemakaianAir->meter_akhir }} 
                            (Total: <strong>{{ $tagihan->pemakaianAir->total_pemakaian }} m³</strong>)
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Biaya Admin</td>
                        <td>:</td>
                        <td>Rp {{ number_format($tagihan->biaya_admin, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Denda</td>
                        <td>:</td>
                        <td>Rp {{ number_format($tagihan->denda, 0, ',', '.') }}</td>
                    </tr>
                </table>

                <div class="alert alert-danger mt-4 mb-0 text-center">
                    <h4 class="mb-0 text-danger">TOTAL BAYAR: <span class="fw-bold fs-3 mt-2 d-block">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</span></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pembayaran -->
    <div class="col-md-5 col-12">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title text-white mb-0"><i class="mdi mdi-wallet me-2"></i> Proses Pembayaran</h4>
            </div>
            <div class="card-body mt-3">
                <form action="{{ route('petugas.pembayaran.bayar', $tagihan->id) }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="metode_pembayaran" class="fw-bold form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                            <option value="Tunai" selected>Uang Tunai (Cash)</option>
                            <option value="Transfer Bank/EDC">Transfer Bank / EDC Loket</option>
                        </select>
                        @error('metode_pembayaran') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="jumlah_bayar" class="fw-bold form-label">Jumlah Uang Diterima (Rp)</label>
                        <input type="number" id="jumlah_bayar" class="form-control form-control-lg text-end @error('jumlah_bayar') is-invalid @enderror" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}" min="{{ $tagihan->total_tagihan }}" required oninput="hitungKembalian()">
                        @error('jumlah_bayar') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="text-muted text-end mt-1"><small>Minimal Rp {{ number_format($tagihan->total_tagihan, 0, ',', '') }}</small></div>
                    </div>

                    <div class="alert alert-info mt-4 mb-4">
                        <h5 class="text-center mb-0 text-info">Kembalian: <span id="kembalian" class="fw-bold fs-4 d-block mt-2">Rp 0</span></h5>
                    </div>

                    <div class="d-grid mt-4 gap-2">
                        <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Proses pembayaran ini? Data tidak dapat diubah setelah disimpan.')">
                            <i class="mdi mdi-check-circle me-1"></i> Proses Transaksi & Cetak
                        </button>
                        <a href="{{ route('petugas.tagihan') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
    <script>
        const totalTagihan = {{ $tagihan->total_tagihan }};
        
        function hitungKembalian() {
            let uangMsk = document.getElementById('jumlah_bayar').value;
            uangMsk = parseInt(uangMsk) || 0;
            let kembali = uangMsk - totalTagihan;
            
            if (kembali < 0) {
                document.getElementById('kembalian').innerText = "Rp 0 (Kurang)";
            } else {
                let formatRp = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(kembali);
                document.getElementById('kembalian').innerText = formatRp;
            }
        }
    </script>
@endsection
