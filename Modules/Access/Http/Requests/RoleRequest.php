<?php

namespace Modules\Access\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('role');
        return [
            'name' => 'required|unique:roles,name,' . $id,
            'display_name' => 'required',
        ];

    }
}
