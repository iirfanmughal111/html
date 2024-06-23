<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGameRequest extends FormRequest
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
            'title'     => [
                'required',
            ],
            'short_description' => [
                'required',
            ],
            'description' => [
                'required',
            ],
            'image' => [
                'mimes:jpg,jpeg,png,gif,|max:4096',
            ],
            'game_id' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
          'image.mimes' => 'Only jpg,jpeg,png,gif are allowed.',
          'game_id.required' => 'Something went wrong, please try later'
        ];
             
    }
}
