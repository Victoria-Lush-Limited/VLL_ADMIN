@extends('layouts.app')

@section('content')
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
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5>Recent Orders</h5>
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
@endsection
