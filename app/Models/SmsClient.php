<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmsClient extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $hidden = ['password', 'vcode', 'rcode'];

    protected $fillable = [
        'user_id',
        'password',
        'client_name',
        'status',
        'vcode',
        'rcode',
        'scheme_id',
        'username',
        'email',
        'contact_phone',
        'reseller_id',
        'user_date_created',
    ];

    public function reseller(): BelongsTo
    {
        return $this->belongsTo(Reseller::class, 'reseller_id', 'user_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'user_id');
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
