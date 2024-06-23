<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoacheRating extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'coache_id',
        'rating',
        'comment'
    ];

    public function coacheUser(){
        return $this->belongsTo("App\Models\User","coache_id");
    }

    public function user(){
        return $this->belongsTo("App\Models\User","user_id");
    }

}
