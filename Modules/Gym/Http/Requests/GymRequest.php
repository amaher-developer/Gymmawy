<?php

namespace Modules\Gym\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GymRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'lat' => 'required',
            'lng' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'address' => 'required',
            'phones' => 'required',
            'categories' => 'required|array',
            'services' => 'required|array',
            'image' => 'mimes:jpeg,png,jpg|max:2048',

        ];
    }
}
