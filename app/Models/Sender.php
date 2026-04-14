<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sender extends Model
{
    protected $table = 'senders';

    public $timestamps = false;

    protected $fillable = [
        'sender_id',
        'message',
        'id_type',
        'user_id',
        'date_requested',
        'id_status',
        'status',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(SmsClient::class, 'user_id', 'user_id');
    }
}
