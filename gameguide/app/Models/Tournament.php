<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tournament extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'image',
		'link'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if($this->image == NULL)
            return url('frontend/images/user-profile.png');
        else
            return url('/uploads/tournament').'/'. $this->id .'/'. $this->image;
    }
}
