<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class WebinarRegistraion extends Model
{
    use  Notifiable;
    protected $table = 'webinar_registraions';
    public $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        
    ];
    public $fillable=[
        'user_id',
        'user_full_name',
        'user_email',
        'webinar_id',
        'registraion_date',
        'registraion_time',
    ];
    //
}