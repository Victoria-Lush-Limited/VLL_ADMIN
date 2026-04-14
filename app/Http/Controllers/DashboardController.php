<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Outgoing;
use App\Models\Reseller;
use App\Models\SmsClient;
use App\Models\SmsOrder;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = time();

        $stats = [
            'clients' => SmsClient::query()->count(),
            'resellers' => Reseller::query()->count(),
            'agents' => Agent::query()->count(),
            'pending_orders' => SmsOrder::query()->where('order_status', 'Pending')->count(),
            'scheduled_sms' => Outgoing::query()->where('date_created', '>', $now)->count(),
            'sent_today' => Outgoing::query()
                ->where('sms_status', '!=', 'Cancelled')
                ->whereBetween('date_created', [strtotime('today'), $now])
                ->count(),
        ];

        $recentOrders = SmsOrder::query()
            ->orderByDesc('order_date')
            ->limit(8)
            ->get();

        return view('dashboard', compact('stats', 'recentOrders'));
    }
}
