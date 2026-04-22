<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ApiResponder;
use App\Http\Requests\StoreSmsOrderRequest;
use App\Models\Pricing;
use App\Models\SmsOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class SmsOrderController extends Controller
{
    use ApiResponder;

    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', SmsOrder::class);

        $query = SmsOrder::query()->orderBy('id', 'desc');
        if ($request->user()->account_type !== 'administrator') {
            $query->where('user_id', (string) $request->user()->user_id);
        }

        return $this->ok('Orders retrieved.', $query->paginate(20));
    }

    public function store(StoreSmsOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        Gate::authorize('create', [SmsOrder::class, (string) $data['user_id']]);

        $tier = Pricing::where('pricing_scheme_id', $data['pricing_scheme_id'])
            ->where('min_sms', '<=', $data['quantity'])
            ->where(function ($q) use ($data) {
                $q->whereNull('max_sms')->orWhere('max_sms', '>=', $data['quantity']);
            })
            ->orderBy('min_sms', 'desc')
            ->first();

        if (! $tier) {
            return $this->fail('No matching pricing tier found.');
        }

        $price = (float) $tier->price;
        $quantity = (int) $data['quantity'];

        $order = SmsOrder::create([
            'user_id' => $data['user_id'],
            'account_type' => $data['account_type'] ?? 'broadcaster',
            'quantity' => $quantity,
            'price' => $price,
            'amount' => $price * $quantity,
            'order_status' => 'pending',
            'reference' => 'ORD-'.strtoupper(Str::random(12)),
            'payment_method' => $data['payment_method'] ?? null,
            'receipt' => $data['receipt'] ?? null,
            'reseller_id' => $data['reseller_id'] ?? null,
            'agent_id' => $data['agent_id'] ?? null,
        ]);

        return $this->ok('Order created.', $order, 201);
    }
}
