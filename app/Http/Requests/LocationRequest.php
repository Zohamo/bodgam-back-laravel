<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
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
            'name'             => 'bail|required|string|max:128',
            'isDefault'        => 'bail|required|boolean',
            'isPublic'         => 'bail|required|boolean',
            // Address
            'address1'         => 'bail|nullable|string|max:64',
            'address2'         => 'bail|nullable|string|max:32',
            'zipCode'          => 'bail|nullable|string|max:8',
            'district'         => 'bail|nullable|string|max:64',
            'city'             => 'bail|nullable|string|max:64',
            'country'          => 'bail|required|string|max:3',
            // Coordinates
            'latitude'         => 'bail|nullable|numeric',
            'longitude'        => 'bail|nullable|numeric',
            'accuracy'         => 'bail|nullable|numeric',
            // Details
            'description'      => 'bail|nullable|string|max:255',
            'isAllowedSmoking' => 'bail|required|boolean',
            'isAccessible'     => 'bail|required|boolean',
        ];
    }
}
