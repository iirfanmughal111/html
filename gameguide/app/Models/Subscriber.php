<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    //use SoftDeletes;

    protected $dates = [
        'updated_at',
        'created_at'
    ];

    protected $fillable = [
        'subscriber_by',
        'user_id',
        'group_id',	  	 
        'status', 	 
    ];
	
	public function users(){
     return $this->belongsTo("App\Models\User");
   }
}
