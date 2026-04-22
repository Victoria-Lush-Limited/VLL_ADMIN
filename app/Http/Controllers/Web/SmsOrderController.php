<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use App\Models\PricingScheme;
use App\Models\SmsOrder;
use App\Models\Transaction;
use App\Services\Sms\AllocationNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SmsOrderController extends Controller
{
    public function index(): View
    {
        $actor = Auth::user();
        $orders = $this->scopeOrdersForActor(SmsOrder::query(), $actor)->orderByDesc('id');
        $schemes = PricingScheme::query()->orderBy('name');
        if ((string) $actor->account_type !== 'administrator') {
            $schemes->where(function ($query) use ($actor): void {
                $query->where('owner_user_id', (string) $actor->user_id)
                    ->orWhere('is_default', true);
            });
        }

        return view('orders.index', [
            'orders' => $orders->paginate(20),
            'schemes' => $schemes->get(),
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

        $actor = Auth::user();
        if ((string) $actor->account_type !== 'administrator') {
            $data['user_id'] = (string) $actor->user_id;
        }

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
            'account_type' => (string) $actor->account_type,
            'quantity' => $qty,
            'price' => $price,
            'amount' => $qty * $price,
            'order_status' => 'pending',
            'reference' => 'ORD-'.strtoupper(Str::random(12)),
            'payment_method' => $data['payment_method'] ?? null,
            'receipt' => $data['receipt'] ?? null,
            'reseller_id' => (string) $actor->account_type === 'reseller' ? (string) $actor->user_id : null,
            'agent_id' => (string) $actor->account_type === 'agent' ? (string) $actor->user_id : null,
        ]);

        return back()->with('success', 'Order created successfully.');
    }

    public function allocate(int $orderId, AllocationNotificationService $notificationService): RedirectResponse
    {
        $order = SmsOrder::findOrFail($orderId);
        $actor = Auth::user();
        $this->abortIfOrderOutOfScope($order, $actor);
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
        $notificationService->sendAllocatedCreditsNotice($order->fresh());

        return back()->with('success', 'Credits allocated.');
    }

    private function scopeOrdersForActor($query, $actor)
    {
        if ((string) $actor->account_type === 'administrator') {
            return $query;
        }

        $actorUserId = (string) $actor->user_id;

        return $query->where(function ($inner) use ($actor, $actorUserId): void {
            $inner->where('user_id', $actorUserId);
            if ((string) $actor->account_type === 'reseller') {
                $inner->orWhere('reseller_id', $actorUserId);
            }
            if ((string) $actor->account_type === 'agent') {
                $inner->orWhere('agent_id', $actorUserId);
            }
        });
    }

    private function abortIfOrderOutOfScope(SmsOrder $order, $actor): void
    {
        if ((string) $actor->account_type === 'administrator') {
            return;
        }

        $actorUserId = (string) $actor->user_id;
        $inScope = (string) $order->user_id === $actorUserId;
        if ((string) $actor->account_type === 'reseller') {
            $inScope = $inScope || (string) $order->reseller_id === $actorUserId;
        }
        if ((string) $actor->account_type === 'agent') {
            $inScope = $inScope || (string) $order->agent_id === $actorUserId;
        }

        abort_unless($inScope, 403, 'Forbidden.');
    }
}
