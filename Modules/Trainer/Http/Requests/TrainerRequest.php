<?php

namespace Modules\Trainer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainerRequest extends FormRequest
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
            'birthday' => 'required',
            'gender' => 'required',
            'about_'.$lang => 'required',
            'phone' => 'required',
//            'districts' => 'required|array',
            'categories' => 'required|array',
            'image' => 'mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('global.error_name'),
            'phone.required' => trans('global.error_phone'),
            'title.required' => trans('global.error_job_title'),
            'about.required' => trans('global.error_about_yourself'),
            'gender.required' =>  trans('global.error_gender'),
            'birthday.required' =>  trans('global.error_birthday'),
//            'image.required' =>  trans('global.error_image'),
            'image.mimes' =>  trans('global.error_image_mimes'),
            'image.max' =>  trans('global.error_image_max'),
//            'districts.required' =>  trans('global.error_districts'),
        ];
    }
}
