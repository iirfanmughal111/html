<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Models\WebinarRegistraion;
use App\Models\Webinarkey;

use App\Models\User;


class Webinar extends Model
{
 
    public $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $fillable=[
        'title',
        'start_datetime',
        // 'start_time',


        'end_datetime',
        'end_time',
        'logo_image',
        'logo_orignal_image',
        'logo_mimes',
        'featuredImg_image',
        'featuredImg_orignal_image',
        'featuredImg_mimes',
        'status',
        'coach_user_id',
        'webinar_link',
        'description',
        'streamKey',
        'lang_code',

        

    ];
    public function registeredUers()
    {
        return $this->hasMany(WebinarRegistraion::class);
    }
    public function keypoints()
    {
        return $this->hasMany(Webinarkey::class);
    }
    public function CoachDetial()
    {
        return $this->belongsTo(User::class, 'coach_user_id', 'id');
    }

    
}