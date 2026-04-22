<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ApiResponder;
use App\Http\Requests\StorePricingSchemeRequest;
use App\Models\Pricing;
use App\Models\PricingScheme;
use Illuminate\Http\JsonResponse;

class PricingSchemeController extends Controller
{
    use ApiResponder;

    public function index(): JsonResponse
    {
        return $this->ok('Pricing schemes retrieved.', PricingScheme::with('tiers')->orderBy('id', 'desc')->get());
    }

    public function store(StorePricingSchemeRequest $request): JsonResponse
    {
        $data = $request->validated();

        $scheme = PricingScheme::create([
            'name' => $data['name'],
            'owner_user_id' => $data['owner_user_id'] ?? null,
            'is_default' => (bool) ($data['is_default'] ?? false),
        ]);

        foreach (($data['tiers'] ?? []) as $tier) {
            Pricing::create([
                'pricing_scheme_id' => $scheme->id,
                'min_sms' => $tier['min_sms'],
                'max_sms' => $tier['max_sms'] ?? null,
                'price' => $tier['price'],
            ]);
        }

        return $this->ok('Pricing scheme created.', $scheme->load('tiers'), 201);
    }
}
