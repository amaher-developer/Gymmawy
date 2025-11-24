<?php

namespace Modules\Access\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required',
//            'phone' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required'
        ];
    }


//    public function messages()
//    {
//        return [
//            'name.required' => trans('global.error_name'),
////            'phone.required' => trans('global.error_phone'),
//            'email.required' => trans('global.error_email'),
//            'password.required' => trans('global.error_password'),
//            'password_confirmation.same' => trans('global.error_confirmed_password'),
//            'password_confirmation.required' => trans('global.error_confirmed_password'),
//            'email.unique' => trans('global.error_email_unigue'),
//            ];
//    }
}
