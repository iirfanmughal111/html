<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGameGuideRequest extends FormRequest
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
            'game_id' => [
                'required',
            ],
            'short_description' => [
                'required',
            ],
            'description' => [
                'required',
            ],
            'image' => [
                'mimes:jpg,jpeg,png,gif, |max:4096',
            ],
            /*'transcript.duration.*'=>[
              'date_format:H:i:s'
            ],
            'transcript.content.*' => [
              'required','exists:transcript.duration'
            ]*/
        ];
    }

    public function messages()
    {
        return [
          'image.mimes' => 'Only jpg,jpeg,png,gif are allowed.',
        ];
             
    }
}
