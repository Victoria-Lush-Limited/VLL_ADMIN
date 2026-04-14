@extends('layouts.admin')

@section('title', 'Resellers — ' . config('app.name'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Resellers'), 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ __('Resellers') }}</h1>
            <p class="vll-page-subtitle">{{ __('Partner accounts, schemes, and SMS balances.') }}</p>
        </div>
    </div>

    <div class="vll-toolbar">
        <form method="get" class="vll-search" action="{{ route('resellers.index') }}">
            <input type="search" name="q" value="{{ $keyword }}" placeholder="{{ __('Search…') }}">
            <button type="submit" class="vll-btn vll-btn-sm">{{ __('Search') }}</button>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-plus" aria-hidden="true"></i> {{ __('New reseller') }}</h2>
        </div>
        <form method="post" action="{{ route('resellers.store') }}" class="vll-form-grid vll-form-grid-wide">
            @csrf
            <div class="vll-field"><label>{{ __('Business name') }}</label><input name="business_name" required
                    value="{{ old('business_name') }}"></div>
            <div class="vll-field"><label>{{ __('Phone') }}</label><input name="phone_number" required
                    value="{{ old('phone_number') }}"></div>
            <div class="vll-field"><label>{{ __('Office address') }}</label><input name="business_address"
                    value="{{ old('business_address') }}"></div>
            <div class="vll-field"><label>{{ __('Email (login ID)') }}</label><input type="email" name="email" required
                    value="{{ old('email') }}"></div>
            <div class="vll-field"><label>{{ __('Scheme') }}</label>
                <select name="scheme_id" required>
                    <option value="">— {{ __('Select') }} —</option>
                    @foreach ($schemes as $s)
                        <option value="{{ $s->scheme_id }}" @selected(old('scheme_id') == $s->scheme_id)>{{ $s->scheme_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="vll-field"><label>{{ __('Default sender ID') }}</label><input name="sender_id"
                    value="{{ old('sender_id') }}"></div>
            <div class="vll-field"><label>{{ __('Password') }}</label><input type="password" name="new_password" required>
            </div>
            <div class="vll-field"><label>{{ __('Confirm') }}</label><input type="password"
                    name="new_password_confirmation" required></div>
            <div><button type="submit" class="vll-btn">{{ __('Create reseller') }}</button></div>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-list" aria-hidden="true"></i> {{ __('All resellers') }}</h2>
        </div>
        <div class="vll-table-wrap">
            <table class="vll-table">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th style="text-align:right;">{{ __('Balance') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($resellers as $r)
                        <tr>
                            <td>{{ $r->business_name }}</td>
                            <td>{{ $r->user_id }}</td>
                            <td style="text-align:right;">{{ number_format($r->balance()) }}</td>
                            <td>@include('partials.status-account', ['status' => $r->status])</td>
                            <td class="vll-table-actions"><a class="vll-btn vll-btn-sm"
                                    href="{{ route('resellers.show', $r->user_id) }}">{{ __('Manage') }}</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="vll-empty">{{ __('No resellers found.') }}</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $resellers->links() }}</div>
    </div>
@endsection
