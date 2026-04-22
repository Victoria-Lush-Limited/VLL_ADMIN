<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use App\Models\PricingScheme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingSchemeController extends Controller
{
    public function index(): View
    {
        return view('pricing.index', [
            'schemes' => PricingScheme::with('tiers')->orderByDesc('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'min_sms' => ['required', 'integer', 'min:1'],
            'max_sms' => ['nullable', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $scheme = PricingScheme::create([
            'name' => $data['name'],
            'owner_user_id' => (string) auth()->user()->user_id,
            'is_default' => false,
        ]);

        Pricing::create([
            'pricing_scheme_id' => $scheme->id,
            'min_sms' => $data['min_sms'],
            'max_sms' => $data['max_sms'] ?? null,
            'price' => $data['price'],
        ]);

        return back()->with('success', 'Pricing scheme created.');
    }
}
