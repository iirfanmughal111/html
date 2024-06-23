<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class WebinarNotification extends Model
{
    protected $table = 'webinar_notifications';
    public $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $fillable=[
        'webinar_id',
        'notification_datetime',
        'notification_time',
        'status',


    ];
    //
}
