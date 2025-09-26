<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreWorkLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => [
                'required',
                'date',
                'before_or_equal:today',
                Rule::unique('work_log_user_entries')->where(function ($query) {
                    return $query->where('user_id', Auth::id())
                    ->whereNull('deleted_at');
                }),
            ],
            'tasks' => 'required|array|min:1',
            'tasks.*.project_id' => 'required|exists:projects,id',
            'tasks.*.task_description' => 'required|string',
            'tasks.*.hours_minutes' => ['required', 'regex:/^\d{1,2}:\d{1,2}$/'], // HH:MM
        ];
    }

    // Custom messages (optional)
    public function messages(): array
    {
        return [
            'date.required' => 'Please select a work date.',
            'tasks.required' => 'Please add at least one task.',
            'tasks.*.project_id.required' => 'Please select a project for each task.',
            'tasks.*.task_description.required' => 'Please enter a task description.',
            'tasks.*.hours_minutes.required' => 'Please enter time spent in HH:MM format.',
            'tasks.*.hours_minutes.regex' => 'Time must be in HH:MM format (e.g., 02:30).',
        ];
    }
}
