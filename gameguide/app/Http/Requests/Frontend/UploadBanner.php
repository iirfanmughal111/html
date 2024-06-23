<?php

namespace App\Http\Requests\Frontend;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UploadBanner extends FormRequest
{
   

    public function rules()
    {
        return [
          'upload_banner_file' => 'required|image|mimes:jpeg,png,jpg',
          'upload_banner_crop_file' => 'required'
           // 'upload_banner_file' => 'required|image|mimes:jpeg,png,jpg|max:2024'
        ];
    }
	
	
	
}