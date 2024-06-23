<?php

namespace App\Models;

use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Models\UserFriend;
use DB;


class User extends Authenticatable
{
    use  Notifiable;
    //use SoftDeletes;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];

    protected $fillable = [
        'hash',
		'first_name',
		'last_name',
		'email',
		'password',
		'role_id',
        'banner_photo',
        'banner_original_photo',
        'profile_photo',
        'profile_original_photo',
		'status',
		'remember_token',
		'verify_token',
        'plan_id',
        'plan_start_on',
        'tag_line',
        'email_status',
        'stripe_customer_id',
    ];

    protected $appends = ['full_name','profile_photo_url','profile_original_photo_url'];

	
    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' .                  config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }
	
	 public function friendRequestUserStatus($id){
        
        $userFriend = UserFriend::
          where('user_request', '=', Auth::user()->id)
        ->where('user_accept', '=', $id)->first();
    

      
        if($userFriend){
            
            return $userFriend->status ? $userFriend->status : '0';
        }
        
     
    }

    public function firendAcceptUserStatus($id){

        $userFriendAccept = UserFriend::
          where('user_request', '=',$id )
        ->where('user_accept', '=', Auth::user()->id)->first();
  
    
      if($userFriendAccept){
          
          return $userFriendAccept->status ? $userFriendAccept->status : '0';
      }

    }
	
	 public function tagLine()
	{
		return strlen($this->tag_line) > 88 ? substr($this->tag_line, 0, 90) . '...' : $this->tag_line;
	}

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
	
	public function role() {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    public function tempRequestUser() {
        return $this->hasMany('App\Models\TempRequestUser','user_id');
    }

    public function getFullNameAttribute()
    {
        return ucfirst("{$this->first_name} {$this->last_name}");
    }

    public function getProfilePhotoUrlAttribute()
    {
        if($this->profile_photo == NULL)
            return url('frontend/images/user-profile.png');
        else
            return url('/uploads/users').'/'. $this->id .'/'. $this->profile_photo;
    }

    public function getProfileOriginalPhotoUrlAttribute()
    {
        if($this->profile_original_photo == NULL)
            return '';
        else
            return url('/uploads/users').'/'. $this->id .'/'. $this->profile_original_photo;
    }

    public function userProfile()
    {
        return $this->hasOne('App\Models\UserProfile','user_id');
    }

    public function coacheRating()
    {
        return $this->hasMany('App\Models\CoacheRating','coache_id');
    }

    public function userRate()
    {
        return $this->hasMany('App\Models\CoacheRating','user_id');
    }

    /**
     * The groups that belong to the user.
     */
    public function group()
    {
        return $this->belongsToMany('App\Models\Group');
    }

    public function card()
    {
        return $this->hasMany('App\Models\UserCard');
    }
    public function coach()
    {
        return $this->hasOne('App\Models\Webinar');
    }
   
}
