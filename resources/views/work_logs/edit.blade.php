@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Work Log</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('work-log.update', $entry->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-2 row">
            <label for="date" class="col-sm-2 col-form-label">Date</label>
            <div class="col-sm-4">
                <input type="date" name="date" id="date" class="form-control" max="{{ date('Y-m-d') }}" value="{{ old('date', $entry->date->format('Y-m-d')) }}" required>
            </div>
        </div>

        <div>
            <div class="row mb-2 align-items-center">
                <div class="col-md-3">
                    <label for="" class="col-sm-2 col-form-label">Project</label>
                </div>
                <div class="col-md-6">
                    <label for="" class="col-sm-2 col-form-label">Task Description</label>
                </div>
                <div class="col-md-2">
                    <label for="" class="col-sm-2 col-form-label">Time</label>
                </div>
                <div class="col-md-1">

                </div>
            </div>
        </div>

        <div id="tasks-container" class="mb-3">

            @php
                $oldTasks = old('tasks', $entry->tasks->map(function($task){
                    return [
                        'project_id' => $task->project_id,
                        'task_description' => $task->task_description,
                        'log_hours' => $task->log_hours
                    ];
                })->toArray());
            @endphp

            @foreach($oldTasks  as $index => $task)
            <div class="task-item row mb-2 align-items-center">
                <div class="col-md-3">
                    <select name="tasks[{{ $index }}][project_id]" class="form-control @error('tasks.' . $index . '.project_id') is-invalid @enderror" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ $project->id == ($task['project_id'] ?? '') ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('tasks.' . $index . '.project_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                     <textarea  
                        name="tasks[{{ $index }}][task_description]"
                        class="form-control @error('tasks.' . $index . '.task_description') is-invalid @enderror"
                        placeholder="Task Description"
                        required>{{ $task['task_description'] ?? '' }}</textarea>
                    @error('tasks.' . $index . '.task_description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <input type="text"
                        name="tasks[{{ $index }}][log_hours]"
                        class="form-control @error('tasks.' . $index . '.log_hours') is-invalid @enderror"
                        placeholder="HH:MM"
                        pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9]$"
                        title="Enter time in 24-hour format HH:MM"
                        oninput="this.value = this.value.replace(/[^0-9:]/g, '')"
                        value="{{ $task['log_hours'] ?? '' }}"
                        required>
                    @error('tasks.' . $index . '.log_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-task">&times;</button>
                </div>
            </div>
            @endforeach


        </div>

        <button type="button" id="add-task" class="btn btn-secondary">Add Task</button>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary me-2">Update</button>
            <button type="button" class="btn btn-warning" onclick="window.location='{{ route('work-log.index') }}'">Cancel</button>
    </form>
</div>
</div>


<script>
    let taskIndex = {{ count($oldTasks) }};

    // Add new task row
    document.getElementById('add-task').addEventListener('click', function() {
        const container = document.getElementById('tasks-container');
        const taskHtml = `
        <div class="task-item row mb-2 align-items-center">
            <div class="col-md-3">
                <select name="tasks[${taskIndex}][project_id]" class="form-control" required>
                    <option value="">Select Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <textarea name="tasks[${taskIndex}][task_description]" class="form-control" placeholder="Task Description" required></textarea>
            </div>
            <div class="col-md-2">
                <input type="text" name="tasks[${taskIndex}][log_hours]" class="form-control" placeholder="HH:MM" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-task">&times;</button>
            </div>
        </div>
    `;
        container.insertAdjacentHTML('afterend', taskHtml);
        taskIndex++;
    });

    // Remove task row
    document.addEventListener('click', function(e) {

        if ($('.task-item').length > 1) {

            if (e.target && e.target.classList.contains('remove-task')) {
                e.target.closest('.task-item').remove();
            }
        }
    });
</script>
@endsection