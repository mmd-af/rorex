<?php

namespace App\Http\Requests\Admin\Truck;

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
            'name' => 'required|string|max:255',
            'l' => 'required|numeric',
            'w' => 'required|numeric',
            'h' => 'required|numeric',
            'total_height' => 'required|numeric',
            'load_capacity' => 'required|numeric'
        ];
    }
}
