<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'payment_method_id',
        'status',
        'subscription_id',
        'plan_id',
        'plan_price',
		'payer_name',
        'payer_mail',
        'subscription_start',
        'subscription_end'
    ];

    
}