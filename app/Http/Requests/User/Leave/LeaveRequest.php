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
            'file' => ['nullable'],
            'description' => ['nullable'],
            'remaining' => ['nullable']
        ];
        if ($this->input('type') === 'Hourly Leave') {
            $rules['end_date'] = ['nullable', 'date'];
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('type') !== 'Hourly Leave' && $this->input('end_date') && $this->input('start_date')) {
                if (strtotime($this->input('end_date')) <= strtotime($this->input('start_date'))) {
                    $validator->errors()->add('end_date', 'End date must be greater than start date.');
                }
            }
        });
    }
}
