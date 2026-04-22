<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PricingScheme;
use App\Models\SmsOrder;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $actor = Auth::user();
        $actorUserId = (string) $actor->user_id;
        $isAdministrator = (string) $actor->account_type === 'administrator';

        $users = User::query();
        $orders = SmsOrder::query();
        $transactions = Transaction::query();
        $schemes = PricingScheme::query();

        if (! $isAdministrator) {
            $users->where('user_id', $actorUserId);
            $orders->where(function ($query) use ($actor, $actorUserId): void {
                $query->where('user_id', $actorUserId);
                if ((string) $actor->account_type === 'reseller') {
                    $query->orWhere('reseller_id', $actorUserId);
                }
                if ((string) $actor->account_type === 'agent') {
                    $query->orWhere('agent_id', $actorUserId);
                }
            });
            $transactions->where('user_id', $actorUserId);
            $schemes->where('owner_user_id', $actorUserId);
        }

        return view('dashboard', [
            'usersCount' => $users->count(),
            'ordersCount' => $orders->count(),
            'pendingOrdersCount' => (clone $orders)->where('order_status', 'pending')->count(),
            'allocatedCredits' => (int) $transactions->sum('allocated'),
            'consumedCredits' => (int) (clone $transactions)->sum('consumed'),
            'schemesCount' => $schemes->count(),
        ]);
    }
}
