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
        return [
            'user_id' => ['required'],
            'request_id' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'type' => ['required'],
            'file' => ['nullable'],
            'hour' => ['required'],
            'description' => ['nullable'],
            'remaining' => ['nullable']
        ];
    }
}
