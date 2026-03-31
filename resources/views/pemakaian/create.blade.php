@extends('layouts.app')

@section('title', 'Input Pemakaian Air - PDAM')

@section('custom_style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/choices.js/public/assets/styles/choices.css') }}">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Input Pemakaian Air</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('petugas.pemakaian.index') }}">Pemakaian Air</a></li>
                <li class="breadcrumb-item active">Input</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Input Pemakaian</h4>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible show fade">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form class="form" method="POST" action="{{ route('petugas.pemakaian.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="pelanggan_id" class="form-label fw-bold">Pilih Pelanggan <span class="text-danger">*</span></label>
                                <select class="choices form-select @error('pelanggan_id') is-invalid @enderror" id="pelanggan_id" name="pelanggan_id" required>
                                    <option value="" selected disabled>Ketik Nomor / Nama Pelanggan...</option>
                                    @foreach($pelanggan as $p)
                                        <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>{{ $p->nomor_pelanggan }} - {{ $p->nama }} ({{ $p->alamat }})</option>
                                    @endforeach
                                </select>
                                @error('pelanggan_id') <div class="text-danger mt-1"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="periode_bulan" class="form-label fw-bold">Bulan <span class="text-danger">*</span></label>
                                <select id="periode_bulan" class="form-select @error('periode_bulan') is-invalid @enderror" name="periode_bulan" required>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('periode_bulan', $bulan) == $i ? 'selected' : '' }}>{{ $i }} - {{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                    @endfor
                                </select>
                                @error('periode_bulan') <div class="invalid-feedback"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="periode_tahun" class="form-label fw-bold">Tahun <span class="text-danger">*</span></label>
                                <input type="number" id="periode_tahun" class="form-control @error('periode_tahun') is-invalid @enderror" name="periode_tahun" value="{{ old('periode_tahun', $tahun) }}" readonly>
                                @error('periode_tahun') <div class="invalid-feedback"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="meter_awal" class="form-label fw-bold">Angka Meter Awal</label>
                                <input type="number" id="meter_awal" class="form-control" name="meter_awal" value="{{ old('meter_awal', 0) }}" min="0" oninput="calculateTotal()">
                                <small id="meter_awal_info" class="text-muted d-block mt-1"><i class="mdi mdi-information-outline me-1"></i>Pilih pelanggan terlebih dahulu.</small>
                                @error('meter_awal') <div class="text-danger mt-1"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="meter_akhir" class="form-label fw-bold">Angka Meter Akhir <span class="text-danger">*</span></label>
                                <input type="number" id="meter_akhir" class="form-control @error('meter_akhir') is-invalid @enderror" name="meter_akhir" value="{{ old('meter_akhir') }}" min="0" required oninput="calculateTotal()">
                                @error('meter_akhir') <div class="invalid-feedback"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="alert alert-info mb-3 text-center">
                                <h5 class="mb-0 text-info">Total Pemakaian: <span id="total_pemakaian" class="fw-bold">0</span> m³</h5>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-4">
                            <a href="{{ route('petugas.pemakaian.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save me-1"></i> Simpan & Buat Tagihan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('library_script')
    <script src="{{ asset('assets/vendor/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var choices = new Choices('#pelanggan_id', {
                searchEnabled: true,
                itemSelectText: '',
            });

            // Saat pelanggan dipilih, ambil meter_akhir terakhir secara otomatis
            document.getElementById('pelanggan_id').addEventListener('change', function() {
                let pelangganId = this.value;
                if (!pelangganId) return;

                fetch('/api/pelanggan/' + pelangganId + '/last-meter')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('meter_awal').value = data.meter_awal;
                        if (data.periode) {
                            document.getElementById('meter_awal_info').innerHTML =
                                '<i class="mdi mdi-check-circle text-success me-1"></i>Diambil dari meter akhir periode <strong>' + data.periode + '</strong> = <strong>' + data.meter_awal + '</strong>';
                        } else {
                            document.getElementById('meter_awal_info').innerHTML =
                                '<i class="mdi mdi-information-outline me-1"></i>Pelanggan baru, belum ada riwayat. Meter awal = <strong>0</strong>';
                        }
                        calculateTotal();
                    })
                    .catch(err => {
                        console.error('Gagal mengambil data meter:', err);
                    });
            });
        });

        function calculateTotal() {
            let awal = parseInt(document.getElementById('meter_awal').value) || 0;
            let akhir = parseInt(document.getElementById('meter_akhir').value) || 0;
            let total = akhir - awal;
            if(total < 0) total = 0;
            document.getElementById('total_pemakaian').innerText = total;
        }
    </script>
@endsection

