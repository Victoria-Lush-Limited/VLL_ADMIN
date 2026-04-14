@extends('layouts.admin')

@section('title', 'Clients — ' . config('app.name'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Clients'), 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ __('Clients') }}</h1>
            <p class="vll-page-subtitle">{{ __('Broadcaster accounts, balances, and pricing schemes.') }}</p>
        </div>
    </div>

    <div class="vll-toolbar">
        <form method="get" class="vll-search" action="{{ route('clients.index') }}">
            <input type="search" name="q" value="{{ $keyword }}" placeholder="{{ __('Search name, phone, email…') }}"
                aria-label="{{ __('Search') }}">
            <button type="submit" class="vll-btn vll-btn-sm">{{ __('Search') }}</button>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-user-plus" aria-hidden="true"></i> {{ __('New client') }}</h2>
        </div>
        <form method="post" action="{{ route('clients.store') }}" class="vll-form-grid vll-form-grid-wide">
            @csrf
            <div class="vll-field"><label>{{ __('Client name') }}</label><input name="client_name"
                    value="{{ old('client_name') }}" required autocomplete="name"></div>
            <div class="vll-field"><label>{{ __('Phone (login ID)') }}</label><input name="phone_number"
                    value="{{ old('phone_number') }}" required inputmode="tel" autocomplete="tel"></div>
            <div class="vll-field"><label>{{ __('Email') }}</label><input type="email" name="email"
                    value="{{ old('email') }}" required autocomplete="email"></div>
            <div class="vll-field"><label>{{ __('Pricing scheme') }}</label>
                <select name="scheme_id" required>
                    <option value="">— {{ __('Select') }} —</option>
                    @foreach ($schemes as $s)
                        <option value="{{ $s->scheme_id }}" @selected(old('scheme_id') == $s->scheme_id)>{{ $s->scheme_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="vll-field"><label>{{ __('Username') }}</label><input name="username" value="{{ old('username') }}"
                    required autocomplete="username"></div>
            <div class="vll-field"><label>{{ __('Password') }}</label><input type="password" name="new_password" required
                    autocomplete="new-password"></div>
            <div class="vll-field"><label>{{ __('Confirm password') }}</label><input type="password"
                    name="new_password_confirmation" required autocomplete="new-password"></div>
            <div>
                <button type="submit" class="vll-btn">{{ __('Create client') }}</button>
            </div>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-list" aria-hidden="true"></i> {{ __('All clients') }}</h2>
        </div>
        <div class="vll-table-wrap">
            <table class="vll-table">
                <thead>
                    <tr>
                        <th>{{ __('Username') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th style="text-align:right;">{{ __('Balance') }}</th>
                        <th>{{ __('Reseller') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $c)
                        @php
                            $reseller = \App\Models\Reseller::query()->where('user_id', $c->reseller_id)->first();
                        @endphp
                        <tr>
                            <td>{{ $c->username }}</td>
                            <td>{{ $c->client_name }}</td>
                            <td>{{ $c->contact_phone }}</td>
                            <td>{{ $c->email }}</td>
                            <td style="text-align:right;">{{ number_format($c->balance()) }}</td>
                            <td>{{ $reseller->business_name ?? $c->reseller_id }}</td>
                            <td>@include('partials.status-account', ['status' => $c->status])</td>
                            <td class="vll-table-actions"><a href="{{ route('clients.show', $c->user_id) }}"
                                    class="vll-btn vll-btn-sm">{{ __('Manage') }}</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="vll-empty">
                                    <i class="fas fa-users" aria-hidden="true"></i>
                                    {{ __('No clients match your search.') }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $clients->links() }}</div>
    </div>
@endsection
