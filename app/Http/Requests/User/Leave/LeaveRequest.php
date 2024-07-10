<?php

namespace App\Http\Requests\User\Leave;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'type' => ['required'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,doc,docx,pdf'],
            'description' => ['nullable'],
            'remaining' => ['nullable'],
            'leave_days' => ['nullable', 'integer']
        ];
        if ($this->input('type') === 'Hourly Leave') {
            $rules['leave_time'] = ['required'];
            $rules['end_date'] = ['nullable', 'date'];
        }
        if ($this->input('type') === 'Allowed Leave') {
            $rules['leave_days'] = ['required', 'integer'];
        }
        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('type') !== 'Hourly Leave' && $this->input('end_date') && $this->input('start_date')) {
                if (strtotime($this->input('end_date')) < strtotime($this->input('start_date'))) {
                    $validator->errors()->add('end_date', 'End date must be greater than or equal to start date.');
                }
            }
            if ($this->input('type') === 'Allowed Leave') {
                $leaveDays = (int) $this->input('leave_days');
                $leaveBalance = auth()->user()->leave_balance;
                if ($leaveDays > ($leaveBalance / 8)) {
                    $validator->errors()->add('leave_days', 'Leave days must not exceed your leave balance.');
                }
                if ($leaveDays <= 0) {
                    $validator->errors()->add('leave_days', 'Leave days must be greater than zero.');
                }
            }
        });
    }
}
