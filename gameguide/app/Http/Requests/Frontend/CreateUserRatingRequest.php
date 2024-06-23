<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRatingRequest extends FormRequest
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
            'coache_id'  => [
                'required',
            ],
            'rating'  => [
                'required',
            ],
            'comment'  => [
                'required',
            ],
        ];
    }

    public function messages()
    {
        return [
          'user_id.required' => 'Something went wrong, please try later.',
          'coache_id.required' => 'Something went wrong, please try later.'
        ];
             
    }
}
