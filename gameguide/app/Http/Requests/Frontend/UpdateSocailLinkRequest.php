<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSocailLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    /*public function authorize()
    {
        return false;
    }*/

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'     => [
                'required',
            ],
            'facebook_link' =>[
                'nullable','url'
            ],
            'instagram_link' =>[
                'nullable','url'
            ],
            'twitter_link' =>[
                'nullable','url'
            ],
        ];
    }
}
