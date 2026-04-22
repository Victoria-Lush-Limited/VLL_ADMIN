@extends('layouts.app')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('web.reports.index') }}" class="row g-3 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from_date" class="form-control" value="{{ $filters['from_date'] ?? '' }}">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to_date" class="form-control" value="{{ $filters['to_date'] ?? '' }}">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">Order Status</label>
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    @foreach($availableStatuses as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button class="btn btn-primary w-100" type="submit">
                    <i class="fas fa-filter me-1"></i> Apply
                </button>
                <a href="{{ route('web.reports.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <a href="{{ route('web.reports.print', request()->query()) }}" target="_blank" class="btn btn-outline-dark">
                    <i class="fas fa-print me-1"></i> Print Report
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Orders Count</div>
                <div class="fs-4 fw-bold">{{ number_format((int) ($summary['orders_count'] ?? 0)) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Order Amount</div>
                <div class="fs-4 fw-bold">{{ number_format((float) ($summary['orders_total_amount'] ?? 0), 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Allocated Credits</div>
                <div class="fs-4 fw-bold">{{ number_format((int) ($summary['allocated_total'] ?? 0)) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Consumed Credits</div>
                <div class="fs-4 fw-bold">{{ number_format((int) ($summary['consumed_total'] ?? 0)) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Orders by Status</h5>
                <ul class="mb-0">
                    @foreach($ordersByStatus as $status => $total)
                        <li><strong>{{ $status }}:</strong> {{ $total }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5>Top Users by Consumed Credits</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>User</th><th>Allocated</th><th>Consumed</th></tr></thead>
                        <tbody>
                        @foreach($topUsers as $row)
                            <tr>
                                <td>{{ $row->user_id }}</td>
                                <td>{{ (int)$row->allocated }}</td>
                                <td>{{ (int)$row->consumed }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5>Recent Orders</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>ID</th><th>User</th><th>Qty</th><th>Amount</th><th>Status</th></tr></thead>
                        <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user_id }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>{{ number_format((float)$order->amount, 2) }}</td>
                                <td>{{ $order->order_status }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
