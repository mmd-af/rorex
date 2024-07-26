<?php

namespace App\Http\Requests\User\DailyReport;

use Illuminate\Foundation\Http\FormRequest;

class CheckRequest extends FormRequest
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
            'staff_code' => ['required'],
            'last_name' => ['required'],
            'subject' => ['required'],
            'description' => ['required'],
            'departmentRole' => ['required'],
            'assigned_to' => ['required']
        ];
    }
}
