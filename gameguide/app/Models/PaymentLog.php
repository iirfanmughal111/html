<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id', 'invoice_id','charge_id','total','invoice_pdf','payment_status'
    ];


    public function order()
    {
        return $this->belongsTo('App\Model\Order');
    }
}
