@extends('layouts.app')
@section('content')
<div class="legacy-box">
    <div class="legacy-title">Scheduled</div>
    <table>
        <thead><tr><th>ID</th><th>User</th><th>Qty</th><th>Status</th><th>Created</th></tr></thead>
        <tbody>
        @foreach($scheduled as $row)
            <tr><td>{{ $row->id }}</td><td>{{ $row->user_id }}</td><td>{{ $row->quantity }}</td><td>{{ $row->order_status }}</td><td>{{ $row->created_at }}</td></tr>
        @endforeach
        </tbody>
    </table>
    {{ $scheduled->links() }}
</div>
@endsection
