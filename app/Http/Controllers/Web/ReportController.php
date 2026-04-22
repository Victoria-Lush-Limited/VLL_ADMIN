<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SmsOrder;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $ordersByStatus = SmsOrder::select('order_status', DB::raw('COUNT(*) as total'))
            ->groupBy('order_status')
            ->pluck('total', 'order_status');

        $topUsers = Transaction::select('user_id', DB::raw('SUM(allocated) as allocated'), DB::raw('SUM(consumed) as consumed'))
            ->groupBy('user_id')
            ->orderByDesc(DB::raw('SUM(consumed)'))
            ->limit(10)
            ->get();

        return view('reports.index', [
            'ordersByStatus' => $ordersByStatus,
            'topUsers' => $topUsers,
            'recentOrders' => SmsOrder::orderByDesc('id')->limit(20)->get(),
        ]);
    }
}
