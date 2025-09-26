<?php

namespace App\Http\Requests;

use App\Models\Leave;
use App\Models\WorkLogUserEntry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateLeaveRequest extends FormRequest
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

            //check leave exists for the same date range excluding current leave id
            $leaveId = $this->route('id');
             
            $leaveExists = Leave::where('user_id', $userId)
                ->where('id', '!=', $leaveId)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('start_date', [$start, $end])
                        ->orWhereBetween('end_date', [$start, $end])
                        ->orWhere(function ($q) use ($start, $end) {
                            $q->where('start_date', '<=', $start)
                                ->where('end_date', '>=', $end);
                        });
                })
                ->whereNull('deleted_at')
                ->exists();

            if ($leaveExists) {
                $validator->errors()->add('start_date', 'Leave dates overlap with an existing leave.');
            }

            // Check for existing work log entries within the leave date range
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
