<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AccessCodes extends Model
{
  //  use SoftDeletes;

    public $timestamps = false;
    protected $table = 'add_access_codes';

    protected $dates = [
        'used_date',
        'end_date'
    ];

    protected $fillable = [
        'number',
        'category_id',
        'skey',
        'user_id',
        'is_manual',
    ];
}
