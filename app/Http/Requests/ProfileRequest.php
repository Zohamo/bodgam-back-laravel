<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name'        => 'bail|required|string|max:64',
            'email'       => 'bail|required|email',
            'avatar'      => 'bail|nullable|image|dimensions:max_width=250,max_height=250',
            'gender'      => 'bail|nullable|string|max:6',
            'description' => 'bail|nullable|string|max:5000',
            'birthdate'   => 'bail|nullable|date|before:today',
            'bggName'     => 'bail|nullable|string|max:128',
            'phoneNumber' => 'bail|nullable|string|max:48',
            'website'     => 'bail|nullable|url|max:128',
            'district'    => 'bail|nullable|string|max:64',
            'city'        => 'bail|nullable|string|max:64',
            'country'     => 'bail|nullable|string|max:3',
        ];
    }
}
