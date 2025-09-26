<?php

namespace App\Http\Requests;

use App\Models\WorkLogUserEntry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreLeaveRequest extends FormRequest
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
            'start_date' => ['required', 'date', 'before_or_equal:end_date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'reason'     => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $userId = Auth::id();
            $start  = $this->input('start_date');
            $end    = $this->input('end_date');

            // 🚫 Prevent leave if work log already exists in that range
            $exists = WorkLogUserEntry::where('user_id', $userId)
                ->whereBetween('date', [$start, $end])
                ->whereNull('deleted_at')
                ->exists();

            if ($exists) {
                $validator->errors()->add('start_date', 'You already have a work log entry within this leave range.');
            }
        });
    }
}
