@extends('layouts.app')
@section('content')
<div class="legacy-box">
    <div class="legacy-title">History</div>
    <table>
        <thead><tr><th>ID</th><th>User</th><th>Allocated</th><th>Consumed</th><th>Reference</th><th>Date</th></tr></thead>
        <tbody>
        @foreach($history as $row)
            <tr><td>{{ $row->id }}</td><td>{{ $row->user_id }}</td><td>{{ $row->allocated }}</td><td>{{ $row->consumed }}</td><td>{{ $row->reference }}</td><td>{{ $row->created_at }}</td></tr>
        @endforeach
        </tbody>
    </table>
    {{ $history->links() }}
</div>
@endsection
