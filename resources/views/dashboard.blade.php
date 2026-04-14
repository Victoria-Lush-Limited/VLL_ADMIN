@extends('layouts.admin')

@section('title', 'Dashboard — ' . config('app.name'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [['label' => __('Dashboard'), 'url' => null]],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ __('Dashboard') }}</h1>
            <p class="vll-page-subtitle">{{ __('Overview of clients, orders, and messaging activity.') }}</p>
        </div>
    </div>

    <div class="vll-stat-grid">
        @foreach ([
            ['icon' => 'fa-user', 'label' => __('Clients'), 'value' => $stats['clients']],
            ['icon' => 'fa-users', 'label' => __('Resellers'), 'value' => $stats['resellers']],
            ['icon' => 'fa-user-friends', 'label' => __('Agents'), 'value' => $stats['agents']],
            ['icon' => 'fa-clock', 'label' => __('Pending orders'), 'value' => $stats['pending_orders']],
            ['icon' => 'fa-calendar-alt', 'label' => __('Scheduled SMS'), 'value' => $stats['scheduled_sms']],
            ['icon' => 'fa-paper-plane', 'label' => __('Sent today'), 'value' => $stats['sent_today']],
        ] as $stat)
            <div class="vll-stat-card">
                <div class="vll-stat-icon"><i class="fas {{ $stat['icon'] }}" aria-hidden="true"></i></div>
                <div class="vll-stat-value">{{ number_format($stat['value']) }}</div>
                <div class="vll-stat-label">{{ $stat['label'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-credit-card" aria-hidden="true"></i> {{ __('Recent sales') }}</h2>
            <a href="{{ route('sales.index') }}" class="vll-btn vll-btn-sm vll-btn-muted">{{ __('View all') }}</a>
        </div>
        <div class="vll-table-wrap">
            <table class="vll-table">
                <thead>
                    <tr>
                        <th>{{ __('Order') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Qty') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $o)
                        <tr>
                            <td>{{ $o->reference ?? $o->order_id }}</td>
                            <td>{{ $o->user_id }}</td>
                            <td>{{ number_format($o->quantity) }}</td>
                            <td>TSH {{ number_format($o->amount) }}</td>
                            <td>@include('partials.status-order', ['status' => $o->order_status])</td>
                            <td>{{ $o->order_date ? date('d-m-Y H:i', $o->order_date) : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="vll-empty">
                                    <i class="fas fa-receipt" aria-hidden="true"></i>
                                    {{ __('No orders yet.') }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
