@extends('layouts.app')

@section('title', 'Dashboard - PDAM')

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Dashboard</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">You're logged in!</h4>
        </div>
        <div class="card-body">
            Welcome to the Morvin-powered dashboard!
        </div>
    </div>
@endsection
