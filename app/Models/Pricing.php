<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pricing extends Model
{
    protected $table = 'pricing';

    protected $fillable = ['pricing_scheme_id', 'min_sms', 'max_sms', 'price'];

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(PricingScheme::class, 'pricing_scheme_id');
    }
}
