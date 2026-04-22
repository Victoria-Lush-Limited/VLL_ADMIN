<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VLL Admin Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-size: 12px; }
        .report-title { font-size: 1.4rem; font-weight: 700; }
        .report-meta { color: #666; font-size: 0.9rem; }
        .summary-card { border: 1px solid #ddd; border-radius: 6px; padding: 10px; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
            .container { max-width: 100% !important; }
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <div class="report-title">VLL Admin Report</div>
            <div class="report-meta">Generated: {{ $generatedAt->format('Y-m-d H:i:s') }}</div>
            <div class="report-meta">
                Filters:
                {{ ($filters['from_date'] ?? null) ? 'From '.$filters['from_date'] : 'From start' }},
                {{ ($filters['to_date'] ?? null) ? 'To '.$filters['to_date'] : 'Up to now' }},
                {{ ($filters['status'] ?? null) ? 'Status '.$filters['status'] : 'All statuses' }}
            </div>
        </div>
        <div class="no-print d-flex gap-2">
            <button class="btn btn-dark btn-sm" onclick="window.print()">Print</button>
            <button class="btn btn-outline-secondary btn-sm" onclick="window.close()">Close</button>
        </div>
    </div>

    <div class="row g-2 mb-4">
        <div class="col-6 col-md-3">
            <div class="summary-card">
                <div class="text-muted">Orders Count</div>
                <div class="fw-bold">{{ number_format((int) ($summary['orders_count'] ?? 0)) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="summary-card">
                <div class="text-muted">Order Amount</div>
                <div class="fw-bold">{{ number_format((float) ($summary['orders_total_amount'] ?? 0), 2) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="summary-card">
                <div class="text-muted">Allocated Credits</div>
                <div class="fw-bold">{{ number_format((int) ($summary['allocated_total'] ?? 0)) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="summary-card">
                <div class="text-muted">Consumed Credits</div>
                <div class="fw-bold">{{ number_format((int) ($summary['consumed_total'] ?? 0)) }}</div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <h6>Orders by Status</h6>
        <table class="table table-bordered table-sm">
            <thead>
            <tr>
                <th>Status</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ordersByStatus as $status => $total)
                <tr>
                    <td>{{ $status }}</td>
                    <td>{{ (int) $total }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mb-4">
        <h6>Top Users by Consumed Credits</h6>
        <table class="table table-bordered table-sm">
            <thead>
            <tr>
                <th>User</th>
                <th>Allocated</th>
                <th>Consumed</th>
            </tr>
            </thead>
            <tbody>
            @foreach($topUsers as $row)
                <tr>
                    <td>{{ $row->user_id }}</td>
                    <td>{{ (int) $row->allocated }}</td>
                    <td>{{ (int) $row->consumed }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div>
        <h6>Recent Orders</h6>
        <table class="table table-bordered table-sm">
            <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Qty</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
            </thead>
            <tbody>
            @foreach($recentOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user_id }}</td>
                    <td>{{ (int) $order->quantity }}</td>
                    <td>{{ number_format((float) $order->amount, 2) }}</td>
                    <td>{{ $order->order_status }}</td>
                    <td>{{ optional($order->created_at)->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
