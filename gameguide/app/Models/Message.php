<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $appends = ['sent_time'];


    public function getSentTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }
    public function fromContact()
    {
        return $this->hasOne(User::class, 'id', 'from');
    }
    
    public function senderInfo()
    {
        return $this->hasOne('App\Models\User','id','from');
    }

    public function group()
    {
        return $this->hasOne('App\Models\Group','id','group_id');
    }

    public function messageMedia()
    {
      return $this->hasMany('App\Models\MessageMedia');
    }

    public function messageStatus()
    {
      return $this->hasMany('App\Models\MessageStatus');
    }
}
