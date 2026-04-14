<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reseller extends Model
{
    protected $table = 'resellers';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $hidden = ['password', 'vcode', 'rcode'];

    protected $fillable = [
        'user_id',
        'password',
        'business_name',
        'business_address',
        'phone_number',
        'email',
        'status',
        'vcode',
        'rcode',
        'scheme_id',
        'sender_id',
        'date_created',
    ];

    public function clients(): HasMany
    {
        return $this->hasMany(SmsClient::class, 'reseller_id', 'user_id');
    }

    public function balance(): int
    {
        $row = Transaction::query()
            ->where('user_id', $this->user_id)
            ->selectRaw('COALESCE(SUM(allocated),0) - COALESCE(SUM(consumed),0) as bal')
            ->first();

        return (int) ($row->bal ?? 0);
    }
}
