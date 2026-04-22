<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SmsOrder;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $data = $this->buildReportData($request);

        return view('reports.index', $data);
    }

    public function print(Request $request): View
    {
        $data = $this->buildReportData($request);
        $data['generatedAt'] = now();

        return view('reports.print', $data);
    }

    private function buildReportData(Request $request): array
    {
        $filters = $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
            'status' => ['nullable', 'string', 'max:25'],
        ]);

        $ordersQuery = $this->scopeOrdersForActor(SmsOrder::query(), Auth::user());
        $transactionsQuery = $this->scopeTransactionsForActor(Transaction::query(), Auth::user());

        if (! empty($filters['from_date'])) {
            $ordersQuery->whereDate('created_at', '>=', $filters['from_date']);
            $transactionsQuery->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (! empty($filters['to_date'])) {
            $ordersQuery->whereDate('created_at', '<=', $filters['to_date']);
            $transactionsQuery->whereDate('created_at', '<=', $filters['to_date']);
        }

        if (! empty($filters['status'])) {
            $ordersQuery->where('order_status', $filters['status']);
        }

        $ordersByStatus = (clone $ordersQuery)
            ->select('order_status', DB::raw('COUNT(*) as total'))
            ->groupBy('order_status')
            ->pluck('total', 'order_status');

        $topUsers = (clone $transactionsQuery)
            ->select('user_id', DB::raw('SUM(allocated) as allocated'), DB::raw('SUM(consumed) as consumed'))
            ->groupBy('user_id')
            ->orderByDesc(DB::raw('SUM(consumed)'))
            ->limit(10)
            ->get();

        $recentOrders = (clone $ordersQuery)->orderByDesc('id')->limit(50)->get();
        $summary = [
            'orders_count' => (clone $ordersQuery)->count(),
            'orders_total_amount' => (float) ((clone $ordersQuery)->sum('amount')),
            'allocated_total' => (int) ((clone $transactionsQuery)->sum('allocated')),
            'consumed_total' => (int) ((clone $transactionsQuery)->sum('consumed')),
        ];

        return [
            'filters' => Arr::only($filters, ['from_date', 'to_date', 'status']),
            'availableStatuses' => SmsOrder::query()
                ->select('order_status')
                ->whereNotNull('order_status')
                ->distinct()
                ->orderBy('order_status')
                ->pluck('order_status'),
            'ordersByStatus' => $ordersByStatus,
            'topUsers' => $topUsers,
            'recentOrders' => $recentOrders,
            'summary' => $summary,
        ];
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

    private function scopeTransactionsForActor($query, $actor)
    {
        if ((string) $actor->account_type === 'administrator') {
            return $query;
        }

        return $query->where('user_id', (string) $actor->user_id);
    }
}
