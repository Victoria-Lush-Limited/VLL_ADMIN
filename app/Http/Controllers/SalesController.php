<?php

namespace App\Http\Controllers;

use App\Models\SmsOrder;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SalesController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = $request->string('q')->toString();

        $orders = SmsOrder::query()
            ->when($keyword !== '', function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where('reference', 'like', '%'.$keyword.'%')
                        ->orWhere('receipt', 'like', '%'.$keyword.'%')
                        ->orWhere('user_id', 'like', '%'.$keyword.'%');
                });
            })
            ->orderByDesc('order_date')
            ->paginate(20)
            ->withQueryString();

        try {
            $paymentMethods = DB::table('payment_methods')
                ->where('reseller_id', 'Administrator')
                ->orderBy('payment_method')
                ->pluck('payment_method');
        } catch (\Throwable) {
            $paymentMethods = collect(['M-Pesa', 'Bank', 'Cash']);
        }

        return view('sales.index', compact('orders', 'keyword', 'paymentMethods'));
    }

    public function allocate(Request $request, SmsOrder $order): RedirectResponse
    {
        $data = $request->validate([
            'receipt_number' => ['required', 'string', 'max:191'],
            'payment_method' => ['required', 'string', 'max:191'],
        ]);

        abort_unless($order->order_status === 'Pending', 404);

        DB::transaction(function () use ($order, $data) {
            $order->receipt = $data['receipt_number'];
            $order->payment_method = $data['payment_method'];
            $order->save();

            Transaction::query()->create([
                'user_id' => $order->user_id,
                'allocated' => $order->quantity,
                'consumed' => 0,
                'tdate' => time(),
            ]);

            $order->order_status = 'Allocated';
            $order->save();
        });

        return redirect()->route('sales.index')->with('status', __('Credits allocated.'));
    }

    public function destroy(SmsOrder $order): RedirectResponse
    {
        abort_unless($order->order_status === 'Pending', 404);

        $order->delete();

        return redirect()->route('sales.index')->with('status', __('Order removed.'));
    }
}
