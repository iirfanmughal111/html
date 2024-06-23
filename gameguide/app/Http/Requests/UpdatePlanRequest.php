<?php
namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
{
   /*  public function authorize()
    {
        return \Gate::allows('user_create');
    }
 */
    public function rules()
    {
        return [
            'payment_method' => [
                'required'
            ],
            'plan' => [
                'required'
            ]
			/*'terms_condition'   => [
                'required',
            ]*/
			
        ];
    }
	
}
