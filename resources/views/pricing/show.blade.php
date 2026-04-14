@extends('layouts.admin')

@section('title', $scheme->scheme_name . ' — ' . __('Pricing'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Pricing'), 'url' => route('pricing.index')],
            ['label' => $scheme->scheme_name, 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ $scheme->scheme_name }}</h1>
            <p class="vll-page-subtitle">{{ __('Account type:') }} <strong>{{ $scheme->account_type }}</strong></p>
        </div>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-plus" aria-hidden="true"></i> {{ __('Add tier') }}</h2>
        </div>
        <form method="post" action="{{ route('pricing.tiers.store', $scheme) }}"
            style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:1rem;align-items:end;max-width:800px;">
            @csrf
            <div class="vll-field" style="margin:0;">
                <label>{{ __('Min SMS') }}</label>
                <input type="number" name="min_sms" min="0" required value="{{ old('min_sms') }}">
            </div>
            <div class="vll-field" style="margin:0;">
                <label>{{ __('Max SMS (0 = unlimited)') }}</label>
                <input type="number" name="max_sms" min="0" required value="{{ old('max_sms', 0) }}">
            </div>
            <div class="vll-field" style="margin:0;">
                <label>{{ __('Price (TSH / SMS)') }}</label>
                <input type="number" step="0.01" name="price" min="0" required value="{{ old('price') }}">
            </div>
            <div>
                <button type="submit" class="vll-btn">{{ __('Add') }}</button>
            </div>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-table" aria-hidden="true"></i> {{ __('Tiers') }}</h2>
        </div>
        <div class="vll-table-wrap">
            <table class="vll-table">
                <thead>
                    <tr>
                        <th>{{ __('Range') }}</th>
                        <th style="text-align:right;">{{ __('Price') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tiers as $p)
                        @php
                            $maxLabel = $p->max_sms == 0 ? __('Above') : number_format($p->max_sms);
                        @endphp
                        <tr>
                            <td>{{ number_format($p->min_sms) }} — {{ $maxLabel }}</td>
                            <td style="text-align:right;">{{ number_format($p->price) }} TSH</td>
                            <td class="vll-table-actions">
                                <form method="post" action="{{ route('pricing.tiers.destroy', [$scheme, $p]) }}"
                                    class="vll-inline-form" onsubmit="return confirm(@json(__('Remove tier?')));">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="vll-btn vll-btn-sm vll-btn-danger">{{ __('Remove') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="vll-empty">{{ __('No tiers yet.') }}</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p><a href="{{ route('pricing.index') }}" class="vll-link-back"><i class="fas fa-arrow-left" aria-hidden="true"></i>
            {{ __('Back to schemes') }}</a></p>
@endsection
