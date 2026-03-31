@extends('layouts.app')

@section('title', 'Edit Golongan Tarif - PDAM')

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Edit Golongan Tarif</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.golongan_tarif.index') }}">Tarif Air</a></li>
                <li class="breadcrumb-item active">Edit Data</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Formulir Edit Golongan Tarif</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-warning mb-4">
                    <i class="mdi mdi-alert me-2"></i> Mengubah Tarif per m³ akan mempengaruhi perhitungan tagihan pelanggan di periode selanjutnya, tagihan yang sudah terbit tidak akan berubah.
                </div>

                <form class="form form-vertical" method="POST" action="{{ route('admin.golongan_tarif.update', $golonganTarif->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group mb-3">
                                <label for="kode_golongan" class="form-label fw-bold">Kode Golongan <span class="text-danger">*</span></label>
                                <input type="text" id="kode_golongan" class="form-control @error('kode_golongan') is-invalid @enderror" name="kode_golongan" value="{{ old('kode_golongan', $golonganTarif->kode_golongan) }}" required autocomplete="off">
                                @error('kode_golongan')
                                    <div class="invalid-feedback"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-8">
                            <div class="form-group mb-3">
                                <label for="nama_golongan" class="form-label fw-bold">Nama Golongan <span class="text-danger">*</span></label>
                                <input type="text" id="nama_golongan" class="form-control @error('nama_golongan') is-invalid @enderror" name="nama_golongan" value="{{ old('nama_golongan', $golonganTarif->nama_golongan) }}" required autocomplete="off">
                                @error('nama_golongan')
                                    <div class="invalid-feedback"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group mb-3">
                                <label for="tarif_per_m3" class="form-label fw-bold">Tarif per m³ (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" id="tarif_per_m3" class="form-control @error('tarif_per_m3') is-invalid @enderror" name="tarif_per_m3" value="{{ old('tarif_per_m3', $golonganTarif->tarif_per_m3) }}" min="0" required>
                                </div>
                                @error('tarif_per_m3')
                                    <div class="text-danger mt-1" style="font-size: 0.875em;"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group mb-3">
                                <label for="biaya_admin" class="form-label fw-bold">Biaya Admin (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" id="biaya_admin" class="form-control @error('biaya_admin') is-invalid @enderror" name="biaya_admin" value="{{ old('biaya_admin', $golonganTarif->biaya_admin) }}" min="0">
                                </div>
                                @error('biaya_admin')
                                    <div class="text-danger mt-1" style="font-size: 0.875em;"><i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12 d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.golongan_tarif.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Simpan pembaruan tarif ini?')">
                                <i class="mdi mdi-content-save me-1"></i> Update Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
