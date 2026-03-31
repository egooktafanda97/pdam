@extends('layouts.app')

@section('title', 'Edit Pelanggan - PDAM Tirta Bening')

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Edit Pelanggan</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pelanggan.index') }}">Pelanggan</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form class="form" method="POST" action="{{ route('admin.pelanggan.update', $pelanggan->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Informasi Dasar</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label>Nomor Pelanggan</label>
                                <input type="text" class="form-control" value="{{ $pelanggan->nomor_pelanggan }}" readonly disabled>
                            </div>

                            <div class="form-group mb-3">
                                <label for="user_info">Akun User Terhubung</label>
                                <input type="text" id="user_info" class="form-control" value="{{ $pelanggan->user ? $pelanggan->user->name . ' (' . $pelanggan->user->username . ')' : '-' }}" readonly disabled>
                                <small class="text-muted d-block mt-1">Akun login untuk pelanggan ini (Nama diperbarui jika diganti di bawah).</small>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="nama">Nama Lengkap Sesuai KTP <span class="text-danger">*</span></label>
                                <input type="text" id="nama" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $pelanggan->nama) }}" required>
                                @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="nomor_meter">Nomor Meter Air <span class="text-danger">*</span></label>
                                <input type="text" id="nomor_meter" class="form-control @error('nomor_meter') is-invalid @enderror" name="nomor_meter" value="{{ old('nomor_meter', $pelanggan->nomor_meter) }}" required>
                                @error('nomor_meter') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="no_telepon">No. Handphone/WhatsApp</label>
                                <input type="text" id="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" name="no_telepon" value="{{ old('no_telepon', $pelanggan->no_telepon) }}">
                                @error('no_telepon') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="golongan_tarif_id">Golongan Tarif <span class="text-danger">*</span></label>
                                <select class="form-select @error('golongan_tarif_id') is-invalid @enderror" id="golongan_tarif_id" name="golongan_tarif_id" required>
                                    @foreach($golongan as $g)
                                        <option value="{{ $g->id }}" {{ (old('golongan_tarif_id', $pelanggan->golongan_tarif_id) == $g->id) ? 'selected' : '' }}>{{ $g->nama_golongan }} - Rp {{ number_format($g->tarif_per_m3, 0, ',', '.') }}/m³</option>
                                    @endforeach
                                </select>
                                @error('golongan_tarif_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="petugas_id">Petugas Pendata (Opsional)</label>
                                <select class="form-select @error('petugas_id') is-invalid @enderror" id="petugas_id" name="petugas_id">
                                    <option value="" {{ is_null($pelanggan->petugas_id) ? 'selected' : '' }}>Pilih Petugas Pendata...</option>
                                    @foreach($list_petugas as $petugas)
                                        <option value="{{ $petugas->id }}" {{ (old('petugas_id', $pelanggan->petugas_id) == $petugas->id) ? 'selected' : '' }}>
                                            {{ $petugas->name }} ({{ $petugas->username }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('petugas_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Lokasi & Berkas</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="alamat">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" rows="3" name="alamat" required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
                                @error('alamat') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="koordinat">Koordinat (Latitude, Longitude)</label>
                                <input type="text" id="koordinat" class="form-control @error('koordinat') is-invalid @enderror" name="koordinat" value="{{ old('koordinat', $pelanggan->koordinat) }}" placeholder="Contoh: -6.200000, 106.816666">
                                @error('koordinat') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="foto_ktp">Foto KTP (Opsional perbarui)</label>
                                @if($pelanggan->foto_ktp)
                                    <div class="mb-2">
                                        <span class="badge bg-success">Sudah ada berkas KTP terlampir</span>
                                    </div>
                                @endif
                                <input class="form-control @error('foto_ktp') is-invalid @enderror" type="file" id="foto_ktp" name="foto_ktp" accept="image/*">
                                @error('foto_ktp') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="d-flex justify-content-end mt-5">
                                <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary me-2">Batal</a>
                                <button type="submit" class="btn btn-warning"><i class="mdi mdi-content-save me-1"></i> Perbarui Data</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
