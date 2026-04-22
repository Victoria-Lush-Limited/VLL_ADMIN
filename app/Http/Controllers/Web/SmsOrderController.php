<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use App\Models\PricingScheme;
use App\Models\SmsOrder;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SmsOrderController extends Controller
{
    public function index(): View
    {
        return view('orders.index', [
            'orders' => SmsOrder::orderByDesc('id')->paginate(20),
            'schemes' => PricingScheme::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'string', 'max:64'],
            'quantity' => ['required', 'integer', 'min:1'],
            'pricing_scheme_id' => ['required', 'integer', 'exists:pricing_schemes,id'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'receipt' => ['nullable', 'string', 'max:64'],
        ]);

        $tier = Pricing::where('pricing_scheme_id', $data['pricing_scheme_id'])
            ->where('min_sms', '<=', $data['quantity'])
            ->where(function ($q) use ($data) {
                $q->whereNull('max_sms')->orWhere('max_sms', '>=', $data['quantity']);
            })
            ->orderByDesc('min_sms')
            ->first();

        if (! $tier) {
            return back()->withErrors(['quantity' => 'No pricing tier found for this quantity.']);
        }

        $qty = (int) $data['quantity'];
        $price = (float) $tier->price;

        SmsOrder::create([
            'user_id' => $data['user_id'],
            'account_type' => 'broadcaster',
            'quantity' => $qty,
            'price' => $price,
            'amount' => $qty * $price,
            'order_status' => 'pending',
            'reference' => 'ORD-'.strtoupper(Str::random(12)),
            'payment_method' => $data['payment_method'] ?? null,
            'receipt' => $data['receipt'] ?? null,
        ]);

        return back()->with('success', 'Order created successfully.');
    }

    public function allocate(int $orderId): RedirectResponse
    {
        $order = SmsOrder::findOrFail($orderId);
        if ($order->order_status === 'allocated') {
            return back()->withErrors(['order' => 'Order is already allocated.']);
        }

        Transaction::create([
            'user_id' => $order->user_id,
            'allocated' => (int) $order->quantity,
            'consumed' => 0,
            'reference' => $order->reference,
            'description' => 'Web order allocation',
        ]);

        $order->update(['order_status' => 'allocated']);

        return back()->with('success', 'Credits allocated.');
    }
}
