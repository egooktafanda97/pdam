@extends('layouts.app')

@section('title', 'Buat Pengaduan - PDAM')

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Buat Pengaduan Baru</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.pengaduan.index') }}">Riwayat</a></li>
                <li class="breadcrumb-item active">Buat Baru</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary">
                <h4 class="card-title text-white mb-0"><i class="mdi mdi-square-edit-outline me-2"></i> Formulir Pengaduan Pelanggan</h4>
            </div>
            <div class="card-body mt-3">
                <div class="alert alert-info border-0 mb-4">
                    <i class="mdi mdi-information me-1"></i> Pastikan Anda menyampaikan kendala dengan jelas dan melampirkan foto agar petugas dapat menindaklanjutinya dengan cepat.
                </div>

                <form class="form" method="POST" action="{{ route('pelanggan.pengaduan.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group mb-3">
                        <label for="kategori" class="fw-bold form-label">Kategori Masalah <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                            <option value="" selected disabled>Pilih Kategori...</option>
                            <option value="Air Tidak Mengalir" {{ old('kategori') == 'Air Tidak Mengalir' ? 'selected' : '' }}>Air Tidak Mengalir / Mati</option>
                            <option value="Kebocoran Pipa" {{ old('kategori') == 'Kebocoran Pipa' ? 'selected' : '' }}>Pipa PDAM Bocor</option>
                            <option value="Tagihan Tidak Sesuai" {{ old('kategori') == 'Tagihan Tidak Sesuai' ? 'selected' : '' }}>Tagihan / Pemakaian Tidak Sesuai</option>
                            <option value="Kualitas Air" {{ old('kategori') == 'Kualitas Air' ? 'selected' : '' }}>Kualitas Air (Keruh, Berbau)</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Keluhan Lainnya</option>
                        </select>
                        @error('kategori') <span class="text-danger mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="judul" class="fw-bold form-label">Judul Singkat Keluhan <span class="text-danger">*</span></label>
                        <input type="text" id="judul" class="form-control @error('judul') is-invalid @enderror" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Air 3 hari tidak mengalir di jl. mawar" required>
                        @error('judul') <span class="text-danger mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="deskripsi" class="fw-bold form-label">Deskripsi Lengkap <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" id="deskripsi" rows="5" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Ceritakan kronologi sedetail mungkin..." required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <span class="text-danger mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="foto_lampiran" class="fw-bold form-label">Foto Lampiran / Bukti <span class="text-muted">(Opsional, max 2MB)</span></label>
                        <input class="form-control @error('foto_lampiran') is-invalid @enderror" type="file" id="foto_lampiran" name="foto_lampiran" accept="image/*">
                        @error('foto_lampiran') <span class="text-danger mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('pelanggan.pengaduan.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Kirim pengaduan ini?')">
                            <i class="mdi mdi-send me-1"></i> Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
