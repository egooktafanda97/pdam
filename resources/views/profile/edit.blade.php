@extends('layouts.app')

@section('title', 'Profile - PDAM Tirta Bening')

@section('breadcrumb')
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4>Profile</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border border-danger">
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
