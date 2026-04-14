<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $table = 'agents';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $hidden = ['password', 'vcode', 'rcode'];

    protected $fillable = [
        'user_id',
        'password',
        'agent_name',
        'region',
        'agent_address',
        'phone_number',
        'email',
        'status',
        'vcode',
        'rcode',
        'scheme_id',
        'date_created',
    ];

    public function balance(): int
    {
        $row = Transaction::query()
            ->where('user_id', $this->user_id)
            ->selectRaw('COALESCE(SUM(allocated),0) - COALESCE(SUM(consumed),0) as bal')
            ->first();

        return (int) ($row->bal ?? 0);
    }
}
