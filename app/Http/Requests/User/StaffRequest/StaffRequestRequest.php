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
            'prenumele_tatalui' => ['required'],
            'name' => ['required'],
            'cod_staff' => ['required'],
            'departament' => ['required'],
            'subject' => ['required'],
            'description' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'vacation_day' => ['required'],
            'email' => ['required'],
            'departamentRole' => ['required'],
            'assigned_to' => ['required']
        ];
    }
}
