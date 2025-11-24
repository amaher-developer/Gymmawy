<?php

namespace Modules\Gym\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GymBrandRequest extends FormRequest
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

        $lang = (request()->segment(1));
        if($lang == 'operate') $lang = 'ar';
        return [
            'name_'.$lang => 'required',
            'main_phone' => 'required',
//            'address' => 'required',
//            'district_id' => 'required',
//            'image2' => 'mimes:jpeg,png,jpg|max:2048',
//            'image3' => 'mimes:jpeg,png,jpg|max:2048',
//            'image4' => 'mimes:jpeg,png,jpg|max:2048',
            'logo' => 'mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {

        return [
            'name.required' => trans('global.error_name'),
            'main_phone.required' => trans('global.error_phone1'),
//            'address.required' =>  trans('global.error_address'),
            'district_id.required' =>  trans('global.error_district'),
            'image.required' =>  trans('global.error_image'),
            'image.mimes' =>  trans('global.error_image_mimes'),
            'image.max' =>  trans('global.error_image_max'),
            'categories.required' =>  trans('global.error_categories'),
            'services.required' =>  trans('global.error_services'),
        ];
    }
}
