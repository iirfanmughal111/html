<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $dates = [
        'read_at',
        'updated_at',
        'deleted_at',
    ];
    public $fillable=[
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'user_friend_id',
        'webinar_id',

    ];
}
