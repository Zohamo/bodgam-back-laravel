<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * User Request
 */
class UserRequest extends FormRequest
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
            'name'     => 'bail|required|string|max:64',
            'email'    => 'bail|required|email|unique:users',
            'password' => 'required|string|min:6'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'    => 'Email is required!',
            'name.required'     => 'Name is required!',
            'password.required' => 'Password is required!'
        ];
    }
}
