<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingScheme extends Model
{
    protected $table = 'pricing_schemes';

    protected $primaryKey = 'scheme_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'scheme_name',
        'account_type',
        'user_id',
    ];

    public function tiers(): HasMany
    {
        return $this->hasMany(Pricing::class, 'scheme_id', 'scheme_id');
    }
}
