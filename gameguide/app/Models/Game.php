<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'image',
        'original_image',
        'mimes',
		'description',
        'status'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if($this->image == NULL)
            return url('frontend/images/user-profile.png');
        else
            return url('/uploads/games').'/'. $this->id .'/'. $this->image;
    }

    /*Has many gameGuide*/
    public function gameGuide()
    {
      return $this->hasMany('App\Models\GameGuide');
    }
}
