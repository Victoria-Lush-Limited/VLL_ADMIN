<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ApiResponder;
use App\Http\Requests\StorePricingSchemeRequest;
use App\Models\Pricing;
use App\Models\PricingScheme;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricingSchemeController extends Controller
{
    use ApiResponder;

    public function index(Request $request): JsonResponse
    {
        $query = PricingScheme::with('tiers')->orderBy('id', 'desc');
        if ((string) $request->user()->account_type !== 'administrator') {
            $query->where(function ($inner) use ($request): void {
                $inner->where('owner_user_id', (string) $request->user()->user_id)
                    ->orWhere('is_default', true);
            });
        }

        return $this->ok('Pricing schemes retrieved.', $query->get());
    }

    public function store(StorePricingSchemeRequest $request): JsonResponse
    {
        $data = $request->validated();
        if ((string) $request->user()->account_type !== 'administrator') {
            $data['owner_user_id'] = (string) $request->user()->user_id;
            $data['is_default'] = false;
        }

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
