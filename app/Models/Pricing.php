<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pricing extends Model
{
    protected $table = 'pricing';

    protected $primaryKey = 'pricing_id';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'scheme_id',
        'min_sms',
        'max_sms',
        'price',
    ];

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(PricingScheme::class, 'scheme_id', 'scheme_id');
    }
}
