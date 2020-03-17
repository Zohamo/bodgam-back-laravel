<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'title'         => 'bail|required|string|max:128',
            'startDatetime' => 'bail|nullable|date|after_or_equal:today',
            'endDatetime'   => 'bail|nullable|date|after:startDatetime',
            'minPlayers'    => 'bail|nullable|numeric',
            'maxPlayers'    => 'bail|nullable|numeric',
            'description'   => 'bail|nullable|string|max:255',
            'level'         => 'bail|required|numeric',
            'atmosphere'    => 'bail|required|numeric',
        ];
    }
}
