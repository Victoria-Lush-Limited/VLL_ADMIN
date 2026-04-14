@extends('layouts.admin')

@section('title', $client->client_name . ' — ' . __('Clients'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Clients'), 'url' => route('clients.index')],
            ['label' => $client->client_name, 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ $client->client_name }}</h1>
            <p class="vll-page-subtitle">
                {{ __('Balance:') }} <strong>{{ number_format($balance) }}</strong> SMS
                @if ($scheme)
                    · {{ __('Scheme:') }} <strong>{{ $scheme->scheme_name }}</strong>
                @endif
                @if ($reseller)
                    · {{ __('Reseller:') }} <strong>{{ $reseller->business_name }}</strong>
                @endif
            </p>
        </div>
        <div class="vll-page-head-actions">
            @if ($client->reseller_id === 'Administrator')
                <form method="post" action="{{ route('clients.destroy', $client->user_id) }}"
                    onsubmit="return confirm(@json(__('Delete this client permanently?')));">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="vll-btn vll-btn-sm vll-btn-danger">{{ __('Delete') }}</button>
                </form>
            @endif
        </div>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-pen" aria-hidden="true"></i> {{ __('Profile') }}</h2>
        </div>
        <form method="post" action="{{ route('clients.update', $client->user_id) }}"
            class="vll-form-grid vll-form-grid-wide">
            @csrf
            @method('PUT')
            <div class="vll-field"><label>{{ __('Name') }}</label><input name="client_name"
                    value="{{ old('client_name', $client->client_name) }}" required></div>
            <div class="vll-field"><label>{{ __('Phone') }}</label><input name="contact_phone"
                    value="{{ old('contact_phone', $client->contact_phone) }}" required></div>
            <div class="vll-field"><label>{{ __('Email') }}</label><input type="email" name="email"
                    value="{{ old('email', $client->email) }}" required></div>
            <div class="vll-field"><label>{{ __('Username') }}</label><input name="username"
                    value="{{ old('username', $client->username) }}" required></div>
            <div class="vll-field"><label>{{ __('Scheme') }}</label>
                <select name="scheme_id" required>
                    @foreach ($schemes as $s)
                        <option value="{{ $s->scheme_id }}" @selected(old('scheme_id', $client->scheme_id) == $s->scheme_id)>
                            {{ $s->scheme_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="vll-field"><label>{{ __('Status') }}</label>
                <select name="status" required>
                    @foreach ($statuses as $st)
                        <option value="{{ $st }}" @selected(old('status', $client->status) == $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div><button type="submit" class="vll-btn">{{ __('Save changes') }}</button></div>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-key" aria-hidden="true"></i> {{ __('Password') }}</h2>
        </div>
        <form method="post" action="{{ route('clients.password', $client->user_id) }}" class="vll-form-grid">
            @csrf
            <div class="vll-field"><label>{{ __('New password') }}</label><input type="password" name="new_password"
                    required autocomplete="new-password"></div>
            <div class="vll-field"><label>{{ __('Confirm') }}</label><input type="password"
                    name="new_password_confirmation" required autocomplete="new-password"></div>
            <div><button type="submit" class="vll-btn vll-btn-secondary">{{ __('Update password') }}</button></div>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-exchange-alt" aria-hidden="true"></i> {{ __('Transfer') }}</h2>
        </div>
        <form method="post" action="{{ route('clients.transfer', $client->user_id) }}" class="vll-form-grid">
            @csrf
            <div class="vll-field"><label>{{ __('Reseller user ID (email)') }}</label>
                <input name="transfer_reseller_id" value="{{ old('transfer_reseller_id') }}" required
                    placeholder="reseller@example.com" autocomplete="off"></div>
            <div><button type="submit" class="vll-btn">{{ __('Transfer client') }}</button></div>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-receipt" aria-hidden="true"></i> {{ __('Recent orders') }}</h2>
        </div>
        <div class="vll-table-wrap">
            <table class="vll-table">
                <thead>
                    <tr>
                        <th>{{ __('Ref') }}</th>
                        <th>{{ __('Qty') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $o)
                        <tr>
                            <td>{{ $o->reference ?? $o->order_id }}</td>
                            <td>{{ number_format($o->quantity) }}</td>
                            <td>{{ number_format($o->amount) }}</td>
                            <td>@include('partials.status-order', ['status' => $o->order_status])</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="vll-empty">{{ __('No orders.') }}</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p><a href="{{ route('clients.index') }}" class="vll-link-back"><i class="fas fa-arrow-left" aria-hidden="true"></i>
            {{ __('Back to clients') }}</a></p>
@endsection
