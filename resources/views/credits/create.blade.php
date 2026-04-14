@extends('layouts.admin')

@section('title', 'Buy credits — ' . config('app.name'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Buy credits'), 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ __('Buy SMS credits') }}</h1>
            <p class="vll-page-subtitle">{{ __('Order follows your administrator pricing scheme. Pay, then allocate from Sales.') }}</p>
        </div>
    </div>

    <div class="vll-card" style="max-width:520px;">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-shopping-cart" aria-hidden="true"></i> {{ __('Quantity') }}</h2>
        </div>
        <form method="post" action="{{ route('credits.store') }}" class="vll-form-grid" style="max-width:100%;">
            @csrf
            <div class="vll-field"><label>{{ __('SMS quantity') }}</label>
                <input type="number" name="quantity" min="1" required value="{{ old('quantity') }}"></div>
            <div><button type="submit" class="vll-btn">{{ __('Continue to payment') }}</button></div>
        </form>
    </div>

    @if ($tiers->isNotEmpty())
        <div class="vll-card">
            <div class="vll-card-header">
                <h2 class="vll-card-title"><i class="fas fa-tags" aria-hidden="true"></i> {{ __('Your pricing tiers') }}</h2>
            </div>
            <div class="vll-table-wrap">
                <table class="vll-table">
                    <thead>
                        <tr>
                            <th>{{ __('Range') }}</th>
                            <th style="text-align:right;">{{ __('TSH / SMS') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tiers as $p)
                            @php
                                $maxLabel = $p->max_sms == 0 ? '∞' : number_format($p->max_sms);
                            @endphp
                            <tr>
                                <td>{{ number_format($p->min_sms) }} — {{ $maxLabel }}</td>
                                <td style="text-align:right;">{{ number_format($p->price) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
