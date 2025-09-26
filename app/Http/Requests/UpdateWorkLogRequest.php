<?php

namespace App\Http\Requests;

use App\Models\Leave;
use App\Models\WorkLogUserEntry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateWorkLogRequest extends FormRequest
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
            'date' => ['required', 'date', 'before_or_equal:today'],
            'tasks' => 'required|array|min:1',
            'tasks.*.project_id' => 'required|exists:projects,id',
            'tasks.*.task_description' => 'required|string',
            'tasks.*.log_hours' => ['required', 'regex:/^\d{1,2}:\d{1,2}$/'], // HH:MM
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $userId = Auth::id();
            $date   = $this->input('date');

            $entryId = $this->route('id');

            // Check if a work log already exists for this date
            if (WorkLogUserEntry::where('user_id', $userId)
                ->where('id', '!=', $entryId)
                ->where('date', $date)
                ->whereNull('deleted_at')
                ->exists()
            ) {
                $validator->errors()->add('date', 'A work log for this date already exists.');
            }

            // Check if a leave exists for this date
            if (Leave::where('user_id', $userId)
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->whereNull('deleted_at')
                ->exists()
            ) {
                $validator->errors()->add('date', 'You cannot submit a work log on a leave day.');
            }
        });
    }

    // Custom messages (optional)
    public function messages(): array
    {
        return [
            'date.required' => 'Please select a work date.',
            'tasks.required' => 'Please add at least one task.',
            'tasks.*.project_id.required' => 'Please select a project for each task.',
            'tasks.*.task_description.required' => 'Please enter a task description.',
            'tasks.*.log_hours.required' => 'Please enter time spent in HH:MM format.',
            'tasks.*.log_hours.regex' => 'Time must be in HH:MM format (e.g., 02:30).',
        ];
    }
}
