@extends('layouts.app')

@section('title', 'Riwayat Pengaduan - PDAM Tirta Bening')

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Riwayat Pengaduan Anda</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Pengaduan</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border border-primary shadow-sm mb-4">
            <div class="card-body p-4 text-center">
                <h5 class="mb-3">Punya keluhan layanan air atau mendapati kerusakan pipa?</h5>
                <a href="{{ route('pelanggan.pengaduan.create') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                    <i class="mdi mdi-square-edit-outline me-1"></i> Buat Pengaduan Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h4 class="mb-3"><i class="mdi mdi-history me-2"></i> Laporan Saya</h4>
        
        @forelse($pengaduan as $aduan)
            <div class="card mb-4 border shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                    <div>
                        <span class="badge bg-secondary mb-1">{{ $aduan->kategori }}</span>
                        <h5 class="card-title text-primary mb-0">{{ $aduan->judul_pengaduan }}</h5>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block"><i class="mdi mdi-calendar"></i> {{ $aduan->created_at->format('d/m/Y H:i') }}</small>
                        <div class="mt-1">
                            @if($aduan->status == 'Baru')
                                <span class="badge bg-danger">Status: Menunggu</span>
                            @elseif($aduan->status == 'Diproses')
                                <span class="badge bg-warning">Status: Sedang Ditangani</span>
                            @else
                                <span class="badge bg-success">Status: Selesai</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body mt-3">
                    <div class="row">
                        <div class="col-md-7 mb-3 mb-md-0">
                            <h6 class="fw-bold text-muted">Deskripsi Laporan:</h6>
                            <p class="text-dark mb-0">{!! nl2br(e($aduan->deskripsi)) !!}</p>
                            
                            @if($aduan->foto_lampiran)
                                <div class="mt-3">
                                    <a href="{{ asset('storage/' . $aduan->foto_lampiran) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="mdi mdi-image"></i> Lihat Foto Lampiran
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-5">
                            <div class="bg-soft-info rounded p-3 h-100 border border-info">
                                <h6 class="fw-bold"><i class="mdi mdi-message-text-outline text-info"></i> Tanggapan / Solusi:</h6>
                                @if($aduan->tanggapan)
                                    <p class="mt-2 mb-0">{!! nl2br(e($aduan->tanggapan)) !!}</p>
                                    @if($aduan->tanggal_tanggapan)
                                        <hr class="my-2 border-info">
                                        <small class="fw-bold text-success d-block"><i class="mdi mdi-check-circle"></i> Selesai ditangani pada: {{ date('d/m/Y', strtotime($aduan->tanggal_tanggapan)) }}</small>
                                    @endif
                                @else
                                    <div class="text-center text-muted mt-3">
                                        <i class="mdi mdi-timer-sand" style="font-size: 2rem;"></i>
                                        <p class="mt-2 text-sm">Laporan Anda sedang menunggu respon dari petugas kami. Mohon bersabar.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card text-center py-5 border shadow-sm">
                <div class="card-body">
                    <i class="mdi mdi-file-document-outline text-light m-0" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 text-muted">Belum ada riwayat pengaduan.</h5>
                    <p class="text-muted">Jika punya keluhan, jangan ragu untuk menyampaikannya kepada kami.</p>
                </div>
            </div>
        @endforelse
        
        <div class="d-flex justify-content-center mt-3">
            {{ $pengaduan->links() }}
        </div>
    </div>
</div>
@endsection
