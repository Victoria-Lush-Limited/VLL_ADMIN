@extends('layouts.app')
@section('content')
<div class="legacy-box">
    <div class="legacy-title">Agents</div>
    <form method="POST" action="{{ route('web.agents.store') }}" class="row g-2 mb-3">
        @csrf
        <div class="col-md-3"><input class="form-control" name="name" placeholder="Agent name" required></div>
        <div class="col-md-3"><input class="form-control" name="phone_number" placeholder="Phone" required></div>
        <div class="col-md-3"><input class="form-control" name="email" placeholder="Email"></div>
        <div class="col-md-2">
            <select class="form-select" name="status">
                <option value="active">active</option>
                <option value="pending">pending</option>
                <option value="suspended">suspended</option>
                <option value="disabled">disabled</option>
            </select>
        </div>
        <div class="col-md-1"><button class="btn btn-primary w-100">Add</button></div>
    </form>
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>User ID</th><th>Phone</th><th>Status</th></tr></thead>
        <tbody>
        @foreach($agents as $u)
            <tr><td>{{ $u->id }}</td><td>{{ $u->name }}</td><td>{{ $u->user_id }}</td><td>{{ $u->phone_number }}</td><td>{{ $u->status }}</td></tr>
        @endforeach
        </tbody>
    </table>
    {{ $agents->links() }}
</div>
@endsection
