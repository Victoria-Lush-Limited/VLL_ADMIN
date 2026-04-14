<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsOrder extends Model
{
    protected $table = 'sms_orders';

    protected $primaryKey = 'order_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    public function getRouteKeyName(): string
    {
        return 'order_id';
    }

    protected $fillable = [
        'user_id',
        'account_type',
        'quantity',
        'price',
        'amount',
        'order_date',
        'order_status',
        'reference',
        'receipt',
        'payment_method',
    ];
}
