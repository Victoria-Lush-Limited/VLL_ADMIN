<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use App\Models\SmsOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CreditPurchaseController extends Controller
{
    public function create(): View
    {
        $admin = Auth::guard('admin')->user();
        $tiers = Pricing::query()
            ->where('scheme_id', $admin->scheme_id)
            ->orderBy('min_sms')
            ->get();

        return view('credits.create', compact('tiers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $admin = Auth::guard('admin')->user();
        $quantity = $data['quantity'];

        $tier = Pricing::query()
            ->where('scheme_id', $admin->scheme_id)
            ->where('min_sms', '<=', $quantity)
            ->where(function ($q) use ($quantity) {
                $q->where('max_sms', '>=', $quantity)
                    ->orWhere('max_sms', 0);
            })
            ->orderByDesc('min_sms')
            ->first();

        if (! $tier) {
            $tier = Pricing::query()
                ->where('scheme_id', $admin->scheme_id)
                ->where('min_sms', '<=', $quantity)
                ->where('max_sms', 0)
                ->first();
        }

        if (! $tier) {
            return redirect()->route('credits.create')->withErrors(__('No pricing tier matches this quantity.'));
        }

        $price = (float) $tier->price;
        $amount = $price * $quantity;
        $orderDate = time();

        $order = new SmsOrder;
        $order->user_id = $admin->user_id;
        $order->account_type = 'Administrator';
        $order->quantity = $quantity;
        $order->price = $price;
        $order->amount = $amount;
        $order->order_date = $orderDate;
        $order->order_status = 'Pending';
        $order->save();

        $order->reference = (string) $order->order_id;
        $order->save();

        return redirect()->route('credits.pay', $order)->with('status', __('Order created. Complete payment to receive credits.'));
    }

    public function pay(SmsOrder $order): View
    {
        $this->authorizeOrder($order);

        return view('credits.pay', compact('order'));
    }

    protected function authorizeOrder(SmsOrder $order): void
    {
        $admin = Auth::guard('admin')->user();
        abort_unless($order->user_id === $admin->user_id, 403);
    }
}
