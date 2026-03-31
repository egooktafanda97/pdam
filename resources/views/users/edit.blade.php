@extends('layouts.app')

@section('title', 'Edit User - Manajemen User')

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Edit User</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Manajemen User</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title">Data Pengguna: {{ $user->name }}</h4>
            </div>
            <div class="card-body mt-3">
                <form class="form form-vertical" method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="name" class="fw-bold form-label">Nama Lengkap</label>
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name') <span class="text-danger mt-1 d-block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="username" class="fw-bold form-label">Username</label>
                                <input type="text" id="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}" required>
                                @error('username') <span class="text-danger mt-1 d-block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="email" class="fw-bold form-label">Email</label>
                                <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email') <span class="text-danger mt-1 d-block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="role" class="fw-bold form-label">Role / Akses</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                    <option value="pelanggan" {{ old('role', $user->role) == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                    <option value="pimpinan" {{ old('role', $user->role) == 'pimpinan' ? 'selected' : '' }}>Pimpinan / Kepsek</option>
                                </select>
                                @error('role') <span class="text-danger mt-1 d-block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-12 mt-4 pt-4 border-top">
                            <h5 class="font-size-14 font-weight-bold mb-3">Ganti Password <small class="text-muted fw-normal">(Opsional)</small></h5>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group mb-3">
                                <label for="password" class="fw-bold form-label">Password Baru</label>
                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Kosongkan jika tidak diubah">
                                @error('password') <span class="text-danger mt-1 d-block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="fw-bold form-label">Konfirmasi Password Baru</label>
                                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="Kosongkan jika tidak diubah">
                            </div>
                        </div>

                        <div class="col-12 mb-3 mt-4">
                            <div class="form-check form-switch form-switch-lg cursor-pointer">
                                <input class="form-check-input mt-0 cursor-pointer" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label ms-2 cursor-pointer" for="is_active">Aktifkan Akun</label>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">Kembali</a>
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save me-1"></i> Perbarui Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
