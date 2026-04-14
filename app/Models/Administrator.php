<?php

namespace App\Models;

use App\Support\LegacyPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrator extends Authenticatable
{
    protected $rememberTokenName = null;

    protected $table = 'administrators';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'user_id',
        'password',
        'full_name',
        'status',
        'scheme_id',
        'vcode',
        'rcode',
    ];

    public function getAuthIdentifierName(): string
    {
        return 'user_id';
    }

    public function getAuthPassword(): string
    {
        return (string) $this->password;
    }

    public function validatePassword(string $plain): bool
    {
        return LegacyPassword::verify($plain, $this->getAuthPassword());
    }
}
