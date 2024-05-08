<?php

namespace App\Http\Requests\Admin\Transportation;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'product_name' => 'required|string',
            'from_date' => 'required|date',
            'until_date' => 'required|date',
            'country_of_origin' => 'required|string',
            'city_of_origin' => 'required|string',
            'destination_country' => 'required|string',
            'destination_city' => 'required|string',
            'truck_type' => 'required|string',
            'weight_of_each_car' => 'required|string',
            'description' => 'required|string',
        ];
    }
}
