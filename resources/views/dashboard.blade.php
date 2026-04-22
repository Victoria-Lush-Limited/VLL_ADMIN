@extends('layouts.app')

@section('content')
<h3 class="mb-4">Dashboard</h3>
<div class="row g-3">
    <div class="col-md-4"><div class="card"><div class="card-body"><h6>Users</h6><h3>{{ $usersCount }}</h3></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h6>Orders</h6><h3>{{ $ordersCount }}</h3></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h6>Pending Orders</h6><h3>{{ $pendingOrdersCount }}</h3></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h6>Allocated Credits</h6><h3>{{ $allocatedCredits }}</h3></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h6>Consumed Credits</h6><h3>{{ $consumedCredits }}</h3></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h6>Pricing Schemes</h6><h3>{{ $schemesCount }}</h3></div></div></div>
</div>
@endsection
