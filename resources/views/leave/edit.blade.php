@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Leave Request</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('leave.update', $leave->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-2 row">
            <label for="start_date" class="col-sm-2 col-form-label">Start Date</label>
            <div class="col-sm-4">
                <input type="date" name="start_date" id="start_date" class="form-control"  value="{{ old('start_date', $leave->start_date->format('Y-m-d')) }}" required>
            </div>
        </div>
        <div class="mb-2 row">
            <label for="end_date" class="col-sm-2 col-form-label">End Date</label>
            <div class="col-sm-4">
                <input type="date" name="end_date" id="end_date" class="form-control"  value="{{ old('end_date', $leave->end_date->format('Y-m-d')) }}" required>
            </div>
        </div>
        <div class="mb-2 row">
            <label for="reason" class="col-sm-2 col-form-label">Reason</label>
            <div class="col-sm-6">
                <textarea name="reason" id="reason" class="form-control" rows="4" required>{{ old('reason', $leave->reason) }}</textarea>
            </div>
        </div>
        <div class="d-flex justify-content-center mb-3">
            <button type="submit" class="btn btn-primary me-2">Update</button>
            <button type="button" class="btn btn-warning" onclick="window.location='{{ route('leave.index') }}'">Cancel</button>
        </div>
    </form>
</div>
@endsection