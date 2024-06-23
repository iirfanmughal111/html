<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name'
    ];

    /**
     * The users that belong to the group.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
