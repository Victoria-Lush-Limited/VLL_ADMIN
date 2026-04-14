<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Outgoing extends Model
{
    protected $table = 'outgoing';

    protected $primaryKey = 'sms_id';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'phone_number',
        'sender_id',
        'message',
        'credits',
        'schedule',
        'start_date',
        'end_date',
        'date_created',
        'attempts',
        'sms_status',
        'user_id',
        'smsc_id',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(SmsClient::class, 'user_id', 'user_id');
    }
}
