<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PricingScheme;
use App\Models\SmsOrder;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'usersCount' => User::count(),
            'ordersCount' => SmsOrder::count(),
            'pendingOrdersCount' => SmsOrder::where('order_status', 'pending')->count(),
            'allocatedCredits' => (int) Transaction::sum('allocated'),
            'consumedCredits' => (int) Transaction::sum('consumed'),
            'schemesCount' => PricingScheme::count(),
        ]);
    }
}
