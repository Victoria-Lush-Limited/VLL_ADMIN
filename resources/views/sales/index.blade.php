@extends('layouts.admin')

@section('title', 'Sales — ' . config('app.name'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Sales'), 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ __('Sales') }}</h1>
            <p class="vll-page-subtitle">{{ __('SMS credit orders, payment confirmation, and allocation.') }}</p>
        </div>
    </div>

    <div class="vll-toolbar">
        <form method="get" class="vll-search" action="{{ route('sales.index') }}">
            <input type="search" name="q" value="{{ $keyword }}"
                placeholder="{{ __('Reference, receipt, user ID…') }}">
            <button type="submit" class="vll-btn vll-btn-sm">{{ __('Search') }}</button>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-receipt" aria-hidden="true"></i> {{ __('Orders') }}</h2>
        </div>
        <div class="vll-table-wrap">
            <table class="vll-table">
                <thead>
                    <tr>
                        <th>{{ __('Ref') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Qty') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $o)
                        <tr>
                            <td>{{ $o->reference ?? $o->order_id }}</td>
                            <td>{{ $o->user_id }}</td>
                            <td>{{ $o->account_type }}</td>
                            <td>{{ number_format($o->quantity) }}</td>
                            <td>TSH {{ number_format($o->amount) }}</td>
                            <td>@include('partials.status-order', ['status' => $o->order_status])</td>
                            <td class="vll-table-actions">
                                @if ($o->order_status === 'Pending')
                                    <button type="button" class="vll-btn vll-btn-sm"
                                        onclick="document.getElementById('alloc-{{ $o->order_id }}').showModal()">{{ __('Allocate') }}</button>
                                    <form method="post" action="{{ route('sales.destroy', $o) }}" class="vll-inline-form"
                                        onsubmit="return confirm(@json(__('Delete pending order?')));">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="vll-btn vll-btn-sm vll-btn-danger">{{ __('Delete') }}</button>
                                    </form>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="vll-empty">{{ __('No orders found.') }}</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    </div>

    @foreach ($orders as $o)
        @if ($o->order_status === 'Pending')
            <dialog id="alloc-{{ $o->order_id }}" class="vll-dialog">
                <div class="vll-dialog-body">
                    <form method="post" action="{{ route('sales.allocate', $o) }}">
                        @csrf
                        <h3>{{ __('Allocate credits') }} #{{ $o->order_id }}</h3>
                        <div class="vll-field"><label>{{ __('Receipt no.') }}</label><input name="receipt_number" required
                                autocomplete="off"></div>
                        <div class="vll-field"><label>{{ __('Payment method') }}</label>
                            <select name="payment_method" required>
                                <option value="">— {{ __('Select') }} —</option>
                                @foreach ($paymentMethods as $m)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="display:flex;gap:0.5rem;justify-content:flex-end;margin-top:1.25rem;flex-wrap:wrap;">
                            <button type="button" class="vll-btn vll-btn-sm vll-btn-muted"
                                onclick="document.getElementById('alloc-{{ $o->order_id }}').close()">{{ __('Cancel') }}</button>
                            <button type="submit" class="vll-btn vll-btn-sm">{{ __('Allocate') }}</button>
                        </div>
                    </form>
                </div>
            </dialog>
        @endif
    @endforeach
@endsection
