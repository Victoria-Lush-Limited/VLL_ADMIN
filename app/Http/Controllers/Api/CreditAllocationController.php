<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ApiResponder;
use App\Http\Requests\AllocateCreditsRequest;
use App\Models\SmsOrder;
use App\Models\Transaction;
use App\Services\Sms\AllocationNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CreditAllocationController extends Controller
{
    use ApiResponder;

    public function allocate(AllocateCreditsRequest $request, int $orderId, AllocationNotificationService $notificationService): JsonResponse
    {
        $data = $request->validated();

        $order = SmsOrder::findOrFail($orderId);
        Gate::authorize('allocate', $order);

        if ($order->order_status === 'allocated') {
            return $this->fail('Order already allocated.');
        }

        Transaction::create([
            'user_id' => $order->user_id,
            'username' => $data['username'] ?? null,
            'allocated' => $order->quantity,
            'consumed' => 0,
            'reference' => $order->reference,
            'description' => $data['description'] ?? 'Order allocation',
        ]);

        $order->update(['order_status' => 'allocated']);
        $notificationService->sendAllocatedCreditsNotice($order->fresh());

        return $this->ok('Credits allocated successfully.', $order->fresh());
    }
}
