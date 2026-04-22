@extends('layouts.app')

@section('content')
@php($isAdmin = (auth()->user()->account_type ?? null) === 'administrator')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Users</h5>
            <form class="d-flex" method="GET" action="{{ route('web.users.index') }}">
                <input class="form-control form-control-sm me-2" name="q" value="{{ $search }}" placeholder="Search users">
                <button class="btn btn-sm btn-outline-secondary">Search</button>
            </form>
        </div>
        <table class="table table-sm">
            <thead><tr><th>ID</th><th>Name</th><th>User ID</th><th>Role</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->user_id }}</td>
                    <td>{{ $user->account_type }}</td>
                    <td>{{ $user->status }}</td>
                    <td>
                        @if($isAdmin)
                            <form method="POST" action="{{ route('web.users.status', $user->id) }}" class="d-flex gap-1">
                                @csrf
                                <select name="status" class="form-select form-select-sm">
                                    <option value="active" @selected($user->status === 'active')>active</option>
                                    <option value="disabled" @selected($user->status === 'disabled')>disabled</option>
                                    <option value="pending" @selected($user->status === 'pending')>pending</option>
                                </select>
                                <button class="btn btn-sm btn-primary">Save</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</div>
@endsection
