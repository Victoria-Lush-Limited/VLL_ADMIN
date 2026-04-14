<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use App\Models\PricingScheme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PricingManageController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = $request->string('q')->toString();

        $schemes = PricingScheme::query()
            ->where('user_id', 'Administrator')
            ->when($keyword !== '', fn ($q) => $q->where('scheme_name', 'like', '%'.$keyword.'%'))
            ->orderBy('scheme_name')
            ->paginate(15)
            ->withQueryString();

        try {
            $accountTypes = DB::table('account_types')->orderBy('account_type')->pluck('account_type');
        } catch (\Throwable) {
            $accountTypes = collect(['Broadcaster', 'Reseller', 'Agent']);
        }

        return view('pricing.index', compact('schemes', 'keyword', 'accountTypes'));
    }

    public function show(PricingScheme $scheme): View
    {
        abort_unless($scheme->user_id === 'Administrator', 404);

        $tiers = Pricing::query()
            ->where('scheme_id', $scheme->scheme_id)
            ->orderBy('min_sms')
            ->get();

        return view('pricing.show', compact('scheme', 'tiers'));
    }

    public function storeScheme(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'scheme_name' => ['required', 'string', 'max:191'],
            'account_type' => ['required', 'string', 'max:64'],
        ]);

        $exists = PricingScheme::query()
            ->where('scheme_name', $data['scheme_name'])
            ->where('account_type', $data['account_type'])
            ->where('user_id', 'Administrator')
            ->exists();

        if ($exists) {
            return redirect()->route('pricing.index')->withErrors(__('A scheme with this name already exists for that account type.'));
        }

        PricingScheme::query()->create([
            'scheme_name' => $data['scheme_name'],
            'account_type' => $data['account_type'],
            'user_id' => 'Administrator',
        ]);

        return redirect()->route('pricing.index')->with('status', __('Scheme created.'));
    }

    public function destroyScheme(PricingScheme $scheme): RedirectResponse
    {
        abort_unless($scheme->user_id === 'Administrator', 404);

        Pricing::query()->where('scheme_id', $scheme->scheme_id)->delete();
        $scheme->delete();

        return redirect()->route('pricing.index')->with('status', __('Scheme removed.'));
    }

    public function storeTier(Request $request, PricingScheme $scheme): RedirectResponse
    {
        abort_unless($scheme->user_id === 'Administrator', 404);

        $data = $request->validate([
            'min_sms' => ['required', 'integer', 'min:0'],
            'max_sms' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        Pricing::query()->create([
            'scheme_id' => $scheme->scheme_id,
            'min_sms' => $data['min_sms'],
            'max_sms' => $data['max_sms'],
            'price' => $data['price'],
        ]);

        return redirect()->route('pricing.show', $scheme)->with('status', __('Pricing tier added.'));
    }

    public function destroyTier(PricingScheme $scheme, Pricing $tier): RedirectResponse
    {
        abort_unless($scheme->user_id === 'Administrator', 404);
        abort_unless((int) $tier->scheme_id === (int) $scheme->scheme_id, 404);

        $tier->delete();

        return redirect()->route('pricing.show', $scheme)->with('status', __('Tier removed.'));
    }
}
