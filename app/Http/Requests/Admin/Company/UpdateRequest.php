<?php

namespace App\Http\Requests\Admin\Company;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'company_name' => 'required|string|max:255',
            'activity_domain' => 'required|string',
            'vat_id' => 'required|string',
            'registration_number' => 'required|string',
            'country' => 'required|string',
            'county' => 'required|string',
            'city' => 'required|string',
            'zip_code' => 'required|string',
            'address' => 'required|string',
            'building' => 'required|string',
            'person_name' => 'required|string',
            'job_title' => 'required|string',
            'phone_number' => 'required|string'
            // 'phone_number' => 'required|string|regex:/^\+?[0-9]{10,15}$/'

        ];
    }
}
