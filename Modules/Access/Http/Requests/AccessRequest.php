<?php

namespace Modules\Access\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccessRequest extends FormRequest
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
            'phone' => 'required',
            'email' => 'required',
        ];
    }

    public function response(array $errors)
    {

        return $this->redirector->to('login')
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
