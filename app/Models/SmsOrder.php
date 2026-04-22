<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsOrder extends Model
{
    protected $fillable = [
        'user_pk',
        'user_id',
        'account_type',
        'quantity',
        'price',
        'amount',
        'order_status',
        'reference',
        'receipt',
        'payment_method',
        'reseller_id',
        'agent_id',
    ];
}
