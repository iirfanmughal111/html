<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'description',
        'rating',
        'total_review',
        'total_rating',
        'facebook_link',
        'instagram_link',
        'twitter_link',
        'coache_photo',
        'coache_original_photo',
        'subscription_id'
    ];

    public function user(){
        return $this->belongsTo("App\Models\User");
    }

    protected $appends = ['coache_photo_url'];

    public function getCoachePhotoUrlAttribute()
    {
        if($this->coache_photo == NULL)
            return url('frontend/images/user-profile.png');
        else
            return url('/uploads/users').'/'. $this->user_id .'/'. $this->coache_photo;
    }
}
