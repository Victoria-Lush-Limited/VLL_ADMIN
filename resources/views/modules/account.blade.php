@extends('layouts.app')
@section('content')
<div class="legacy-box">
    <div class="legacy-title">My Account</div>
    <table>
        <tr><th style="width:220px;">Name</th><td>{{ $user->name }}</td></tr>
        <tr><th>User ID</th><td>{{ $user->user_id }}</td></tr>
        <tr><th>Email</th><td>{{ $user->email }}</td></tr>
        <tr><th>Phone</th><td>{{ $user->phone_number }}</td></tr>
        <tr><th>Role</th><td>{{ $user->account_type }}</td></tr>
        <tr><th>Status</th><td>{{ $user->status }}</td></tr>
    </table>
</div>
@endsection
