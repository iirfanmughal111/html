<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageStatus extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'message_id',
        'user_id',
        'group_id',
        'read_unread'
    ];

    public function message(){
    	return $this->belongsTo("App\Models\Message");
   }
}
