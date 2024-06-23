<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameGuide extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'game_id',
        'guide_type_id',
        'title',
        'slug',
        'short_description',
        'embed_video',
        'image',
        'original_image',
        'mimes',
		'description',
        'status',
		'video_notes',
		'guide_tag'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if($this->image == NULL)
            return url('frontend/images/user-profile.png');
        else
            return url('/uploads/games').'/'. $this->game_id .'/'. $this->image;
    }

    public function game(){
        return $this->belongsTo("App\Models\Game");
    }

    public function guideType(){
        return $this->belongsTo("App\Models\GuideType");
    }

    /*Has many gameGuidetranscript*/
    public function gameGuidetranscript()
    {
      return $this->hasMany('App\Models\GameGuideTranscript');
    }

    /*Has many gameGuidetranscript*/
    public function gameGuideKey()
    {
      return $this->hasMany('App\Models\GameGuideKey');
    }

}
