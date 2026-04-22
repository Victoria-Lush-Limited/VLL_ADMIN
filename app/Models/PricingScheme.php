<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingScheme extends Model
{
    protected $fillable = ['name', 'owner_user_id', 'is_default'];

    public function tiers(): HasMany
    {
        return $this->hasMany(Pricing::class, 'pricing_scheme_id');
    }
}
