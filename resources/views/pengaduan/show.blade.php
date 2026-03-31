@extends('layouts.app')

@section('title', 'Detail Pengaduan - PDAM Tirta Bening')

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Detail Pengaduan</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pengaduan') }}">Pengaduan</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Rincian Pengaduan -->
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Rincian Laporan</h4>
                @if($pengaduan->status == 'Baru')
                    <span class="badge bg-danger">Status: Baru</span>
                @elseif($pengaduan->status == 'Diproses')
                    <span class="badge bg-warning">Status: Diproses</span>
                @else
                    <span class="badge bg-success">Status: Selesai</span>
                @endif
            </div>
            <div class="card-body mt-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Tanggal Pengaduan</small>
                        <h6 class="mb-0">{{ $pengaduan->tanggal_pengaduan ? $pengaduan->tanggal_pengaduan->format('d/m/Y H:i') : $pengaduan->created_at->format('d/m/Y H:i') }}</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Kategori</small>
                        <h6 class="mb-0">{{ $pengaduan->kategori }}</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Nomor Pelanggan</small>
                        <h6 class="mb-0">{{ $pengaduan->pelanggan->nomor_pelanggan }}</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Nama Pelanggan</small>
                        <h6 class="mb-0">{{ $pengaduan->pelanggan->nama }}</h6>
                    </div>
                    <div class="col-12 mb-3">
                        <small class="text-muted d-block">Alamat</small>
                        <p class="mb-0">{{ $pengaduan->pelanggan->alamat }}</p>
                        @if($pengaduan->pelanggan->koordinat)
                            <a href="https://maps.google.com/?q={{ $pengaduan->pelanggan->koordinat }}" target="_blank" class="badge bg-soft-primary text-primary mt-1"><i class="mdi mdi-map-marker"></i> Buka Maps</a>
                        @endif
                    </div>
                    <div class="col-12 mt-2">
                        <hr>
                        <h6 class="fw-bold">Judul Masalah:</h6>
                        <h5 class="text-primary">{{ $pengaduan->judul_pengaduan }}</h5>
                        
                        <h6 class="fw-bold mt-4">Deskripsi / Kronologi:</h6>
                        <div class="p-3 bg-light rounded border">
                            {!! nl2br(e($pengaduan->deskripsi)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Respon / Tindak Lanjut -->
    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header bg-info border-bottom">
                <h4 class="card-title text-white mb-0"><i class="mdi mdi-message-text me-2"></i> Tindak Lanjut</h4>
            </div>
            <div class="card-body mt-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible show fade">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Jika sudah ditanggapi -->
                @if($pengaduan->tanggapan)
                    <div class="alert alert-success border-0 py-3 px-4 mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar avatar-sm bg-success me-2">
                                <span class="avatar-title rounded-circle shadow-sm"><i class="mdi mdi-account"></i></span>
                            </div>
                            <h6 class="mb-0 fw-bold">{{ $pengaduan->petugas->name ?? 'Petugas PDAM' }} - {{ $pengaduan->status }}</h6>
                        </div>
                        <small class="text-muted d-block mb-3">Tanggapan terakhir: {{ $pengaduan->updated_at->format('d/m/Y H:i') }}</small>
                        
                        <p class="mb-0 text-dark border-top pt-2 mt-2">{!! nl2br(e($pengaduan->tanggapan)) !!}</p>
                        
                        @if($pengaduan->tanggal_tanggapan)
                            <hr>
                            <small class="fw-bold text-success"><i class="mdi mdi-check-circle"></i> Selesai pada: {{ $pengaduan->tanggal_tanggapan->format('d/m/Y H:i') }}</small>
                        @endif
                    </div>
                @endif

                <form action="{{ route('admin.pengaduan.respon', $pengaduan->id) }}" method="POST">
                    @csrf
                    
                    <div class="form-group mb-3">
                        <label for="status" class="fw-bold form-label">Ubah Status Laporan</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Baru" {{ old('status', $pengaduan->status) == 'Baru' ? 'selected' : '' }}>Baru (Belum Ditangani)</option>
                            <option value="Diproses" {{ old('status', $pengaduan->status) == 'Diproses' ? 'selected' : '' }}>Diproses (Sedang Ditangani)</option>
                            <option value="Selesai" {{ old('status', $pengaduan->status) == 'Selesai' ? 'selected' : '' }}>Selesai (Masalah Teratasi)</option>
                        </select>
                        @error('status') <span class="text-danger mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="tanggapan" class="fw-bold form-label">Tulis Tanggapan / Solusi</label>
                        <textarea name="tanggapan" id="tanggapan" rows="5" class="form-control @error('tanggapan') is-invalid @enderror" required placeholder="Tulis rincian penanganan atau jawaban kepada pelanggan...">{{ old('tanggapan', $pengaduan->tanggapan) }}</textarea>
                        @error('tanggapan') <span class="text-danger mt-1 d-block">{{ $message }}</span> @enderror
                        <small class="text-muted d-block mt-1">Pesan ini akan bisa dibaca oleh pelanggan melalui halamannya.</small>
                    </div>

                    <div class="d-grid mt-2 gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Simpan tanggapan ini?')">
                            <i class="mdi mdi-send me-1"></i> Update Tindak Lanjut
                        </button>
                        <a href="{{ route('admin.pengaduan') }}" class="btn btn-secondary">Kembali ke Daftar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
