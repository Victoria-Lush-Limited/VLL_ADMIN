@extends('layouts.admin')

@section('title', 'Payment — ' . config('app.name'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Buy credits'), 'url' => route('credits.create')],
            ['label' => __('Payment'), 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ __('How to pay') }}</h1>
            <p class="vll-page-subtitle">{{ __('Complete payment, then allocate the order from Sales when funds are confirmed.') }}</p>
        </div>
    </div>

    <div class="vll-card" style="max-width:560px;">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-receipt" aria-hidden="true"></i> {{ __('Order summary') }}</h2>
        </div>
        <dl style="display:grid;gap:0.75rem;margin:0;">
            <div style="display:flex;justify-content:space-between;gap:1rem;">
                <dt style="margin:0;color:var(--vll-gray-400);font-weight:600;">{{ __('Order') }}</dt>
                <dd style="margin:0;font-weight:700;">{{ $order->reference ?? $order->order_id }}</dd>
            </div>
            <div style="display:flex;justify-content:space-between;gap:1rem;">
                <dt style="margin:0;color:var(--vll-gray-400);font-weight:600;">{{ __('Amount') }}</dt>
                <dd style="margin:0;font-weight:700;">TSH {{ number_format($order->amount) }}</dd>
            </div>
            <div style="display:flex;justify-content:space-between;gap:1rem;">
                <dt style="margin:0;color:var(--vll-gray-400);font-weight:600;">{{ __('SMS quantity') }}</dt>
                <dd style="margin:0;font-weight:700;">{{ number_format($order->quantity) }}</dd>
            </div>
        </dl>
    </div>

    <div class="vll-card" style="max-width:560px;">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-mobile-alt" aria-hidden="true"></i> M-Pesa</h2>
        </div>
        <ol style="line-height:1.65;padding-left:1.2rem;margin:0;">
            <li>{{ __('Dial') }} <strong>*150*01#</strong></li>
            <li>{{ __('Select option') }} <strong>4</strong> ({{ __('Pay by M-Pesa') }})</li>
            <li>{{ __('Complete payment for the amount shown above.') }}</li>
            <li>{{ __('Open') }} <a href="{{ route('sales.index') }}">{{ __('Sales') }}</a>
                {{ __('and allocate this order once payment is verified.') }}</li>
        </ol>
        <p style="margin-top:1.25rem;margin-bottom:0;">
            <a href="{{ route('dashboard') }}" class="vll-btn vll-btn-sm">{{ __('Back to dashboard') }}</a>
        </p>
    </div>
@endsection
