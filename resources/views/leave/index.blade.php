@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Leave Requests</h2>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('leave.create') }}" class="btn btn-primary">Add Leave Request</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <!-- <th>Status</th> -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($leaves->isEmpty())
                <tr><td colspan="5" class="text-center text-danger">No leave requests found.</td></tr>
            @else
                @foreach($leaves as $leave)
                <tr>
                    <td>{{ $leave->start_date->format('d-m-Y') }}</td>
                    <td>{{ $leave->end_date->format('d-m-Y') }}</td>
                    <td>{{ $leave->reason }}</td>
                    <!-- <td>{{ $leave->status }}</td> -->
                    <td>
                        <a href="{{ route('leave.edit', $leave->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('leave.destroy', $leave->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    
</div>
@endsection