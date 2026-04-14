@extends('layouts.admin')

@section('title', 'Pricing — ' . config('app.name'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Pricing'), 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ __('Pricing schemes') }}</h1>
            <p class="vll-page-subtitle">{{ __('Define tiers per account type (broadcaster, reseller, agent).') }}</p>
        </div>
    </div>

    <div class="vll-toolbar">
        <form method="get" class="vll-search" action="{{ route('pricing.index') }}">
            <input type="search" name="q" value="{{ $keyword }}" placeholder="{{ __('Search scheme…') }}">
            <button type="submit" class="vll-btn vll-btn-sm">{{ __('Search') }}</button>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-layer-group" aria-hidden="true"></i> {{ __('New scheme') }}</h2>
        </div>
        <form method="post" action="{{ route('pricing.schemes.store') }}" class="vll-form-grid vll-form-grid-wide">
            @csrf
            <div class="vll-field"><label>{{ __('Name') }}</label><input name="scheme_name" required
                    value="{{ old('scheme_name') }}"></div>
            <div class="vll-field"><label>{{ __('Account type') }}</label>
                <select name="account_type" required>
                    <option value="">— {{ __('Select') }} —</option>
                    @foreach ($accountTypes as $t)
                        <option value="{{ $t }}" @selected(old('account_type') == $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div><button type="submit" class="vll-btn">{{ __('Create scheme') }}</button></div>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-list" aria-hidden="true"></i> {{ __('Schemes') }}</h2>
        </div>
        <div class="vll-table-wrap">
            <table class="vll-table">
                <thead>
                    <tr>
                        <th>{{ __('Scheme') }}</th>
                        <th>{{ __('Account type') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schemes as $s)
                        <tr>
                            <td><strong>{{ $s->scheme_name }}</strong></td>
                            <td>{{ $s->account_type }}</td>
                            <td class="vll-table-actions">
                                <a class="vll-btn vll-btn-sm" href="{{ route('pricing.show', $s) }}">{{ __('Tiers') }}</a>
                                <form method="post" action="{{ route('pricing.schemes.destroy', $s) }}"
                                    class="vll-inline-form"
                                    onsubmit="return confirm(@json(__('Delete scheme and all tiers?')));">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="vll-btn vll-btn-sm vll-btn-danger">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="vll-empty">{{ __('No schemes yet.') }}</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $schemes->links() }}</div>
    </div>
@endsection
