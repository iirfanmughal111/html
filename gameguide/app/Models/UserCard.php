<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCard extends Model
{
    Use SoftDeletes;

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
    	'user_id',
    	'stripe_card_id',
        'last4',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
