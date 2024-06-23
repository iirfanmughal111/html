<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
	use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'amount',
		'description',
        'stripe_plan_id',
        'paypal_plan_id'
    ];

    public function user()
    {
    	return $this->hasMany('App\Models\User','plan_id');
    }
    
}
