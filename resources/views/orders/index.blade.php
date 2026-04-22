@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Create SMS Order</h5>
                <form method="POST" action="{{ route('web.orders.store') }}">
                    @csrf
                    @php($isAdmin = (auth()->user()->account_type ?? null) === 'administrator')
                    <div class="mb-2">
                        <input
                            class="form-control"
                            name="user_id"
                            placeholder="Target User ID"
                            value="{{ $isAdmin ? old('user_id') : auth()->user()->user_id }}"
                            @disabled(! $isAdmin)
                            required
                        >
                        @if(! $isAdmin)
                            <input type="hidden" name="user_id" value="{{ auth()->user()->user_id }}">
                            <small class="text-muted">Orders are restricted to your account.</small>
                        @endif
                    </div>
                    <div class="mb-2"><input class="form-control" type="number" name="quantity" placeholder="Quantity" required></div>
                    <div class="mb-2">
                        <select class="form-select" name="pricing_scheme_id" required>
                            <option value="">Select pricing scheme</option>
                            @foreach($schemes as $scheme)
                                <option value="{{ $scheme->id }}">{{ $scheme->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2"><input class="form-control" name="payment_method" placeholder="Payment Method"></div>
                    <div class="mb-2"><input class="form-control" name="receipt" placeholder="Receipt"></div>
                    <button class="btn btn-primary w-100">Save Order</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Orders</h5>
                <table class="table table-sm">
                    <thead><tr><th>ID</th><th>User</th><th>Qty</th><th>Status</th><th>Amount</th><th>Action</th></tr></thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user_id }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ $order->order_status }}</td>
                            <td>{{ number_format((float)$order->amount, 2) }}</td>
                            <td>
                                @if($order->order_status !== 'allocated')
                                    <form method="POST" action="{{ route('web.orders.allocate', $order->id) }}">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Allocate</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
