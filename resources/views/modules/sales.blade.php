@extends('layouts.app')
@section('content')
<div class="legacy-box">
    <div class="legacy-title">Sales</div>
    <table>
        <thead><tr><th>ID</th><th>User</th><th>Qty</th><th>Amount</th><th>Status</th></tr></thead>
        <tbody>
        @foreach($sales as $row)
            <tr><td>{{ $row->id }}</td><td>{{ $row->user_id }}</td><td>{{ $row->quantity }}</td><td>{{ number_format((float)$row->amount,2) }}</td><td>{{ $row->order_status }}</td></tr>
        @endforeach
        </tbody>
    </table>
    {{ $sales->links() }}
</div>
@endsection
