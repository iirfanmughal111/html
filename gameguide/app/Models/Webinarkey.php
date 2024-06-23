<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Webinarkey extends Model
{
   


 protected $table = 'webinarkeys';
    public $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $fillable=[
        'webinar_id',
        'content',
    ];
    //
}
