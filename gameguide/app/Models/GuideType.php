<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuideType extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
		'description'
    ];

    /*Has many gameGuide*/
    public function gameGuide()
    {
      return $this->hasMany('App\Models\GameGuide');
    }
}
