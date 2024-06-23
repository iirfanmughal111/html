<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
 
  protected $table = 'client_acess_log';

public $timestamps = false;
    protected $fillable = [
        'generation_client',
        'access_code',
        'generation_date',
		'redeemed_status',
		'redeemed_user_profile'
    ];
	

}