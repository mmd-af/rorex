<?php

namespace App\Http\Requests\User\StaffRequest;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequestRequest extends FormRequest
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
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'staff_code' => ['required'],
            'subject' => ['required'],
            'description' => ['required'],
            'email' => ['required'],
            'start_date' => ['required'],
            'vacation_day' => ['required'],
            'departmentRole' => ['required'],
            'assigned_to' => ['required']
        ];
    }
}
