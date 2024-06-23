<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageMedia extends Model
{
    use SoftDeletes;

	protected $appends = ['media_url'];

    public function message(){
    	return $this->belongsTo("App\Models\Message");
   }

   	public function getMediaUrlAttribute()
	{
		$url=url('/uploads/chats').'/'.$this->message->from.'/'.$this->message_id.'/'.$this->media;
		/*Check if upload type is image*/
		/*if($this->upload_type == 'image')
			$url = timthumb($url,791,445);*/
		
		return $url;
	}
}
