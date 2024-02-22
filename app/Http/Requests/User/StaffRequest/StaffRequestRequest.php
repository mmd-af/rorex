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
            'name' => ['required'],
            'cod_staff' => ['required'],
            'subject' => ['required'],
            'description' => ['required'],
            'email' => ['required'],
            'start_date' => ['required'],
            'vacation_day' => ['required'],
            'departamentRole' => ['required'],
            'assigned_to' => ['required']
        ];
    }
}
