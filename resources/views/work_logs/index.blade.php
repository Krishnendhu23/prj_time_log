@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Work Logs</h2>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('work-log.create') }}" class="btn btn-primary">Add Work Log</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($entries->isEmpty())
    <p>No work logs found.</p>
    @else
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Project</th>
                <th>Task Description</th>
                <th>Time (HH:MM)</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entries as $entry)
                @php
                    $taskCount = $entry->tasks->count();
                    
                    $totalMinutes = $entry->tasks->sum(function($task) {
                        [$h, $m] = explode(':', $task->log_hours); // assuming column name is 'time'
                        return ((int)$h * 60) + (int)$m;
                    });

                    $totalHours = floor($totalMinutes / 60);
                    $totalRemainingMinutes = $totalMinutes % 60;
                @endphp

            @foreach($entry->tasks as $index => $task)
            <tr>
                @if($index === 0)
                {{-- Date rowspan --}}
                <td rowspan="{{ $taskCount }}">{{ $entry->formatted_date  }}</td>
                @endif

                <td>{{ $task->project->name }}</td>
                <td>{{ $task->task_description }}</td>
                <td>{{ $task->log_hours }}</td>

                @if($index === 0)
                {{-- Total time rowspan --}}
                <td rowspan="{{ $taskCount }}">{{ $totalHours }}:{{ str_pad($totalRemainingMinutes, 2, '0', STR_PAD_LEFT) }}</td>

                {{-- Actions rowspan --}}
                <td rowspan="{{ $taskCount }}">
                    <a href="{{ route('work-log.edit', $entry->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('work-log.destroy', $entry->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
                @endif
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection