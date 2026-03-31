@extends('layouts.app')

@section('title', 'Blank Page - PDAM')

@section('custom_style')
    <!-- Tambahkan custom CSS di sini -->
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Blank Page</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Blank Page</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                Konten halaman kosong.
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
    <!-- Tambahkan custom JS di sini -->
@endsection
