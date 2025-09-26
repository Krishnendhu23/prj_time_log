@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-5">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Welcome {{ Auth::user()->name }}</h4>
        </div>

        <div class="card-body">
            <p class="lead">Here you can manage your work logs and leaves here.</p>
        </div>
    </div>
</div>
@endsection