<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class WebinarActiveUser extends Model
{
    protected $table = 'webinar_activeUsers';
    public $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $fillable=[
        'user_id',
        'webinar_id',
        'active_datetime',
        
    ];
}
