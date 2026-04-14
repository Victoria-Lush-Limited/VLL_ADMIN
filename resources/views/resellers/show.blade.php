@extends('layouts.admin')

@section('title', $reseller->business_name)

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Resellers'), 'url' => route('resellers.index')],
            ['label' => $reseller->business_name, 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ $reseller->business_name }}</h1>
            <p class="vll-page-subtitle">{{ __('Balance:') }} <strong>{{ number_format($balance) }}</strong> SMS</p>
        </div>
        <div class="vll-page-head-actions">
            <form method="post" action="{{ route('resellers.destroy', $reseller->user_id) }}"
                onsubmit="return confirm(@json(__('Delete this reseller?')));">
                @csrf
                @method('DELETE')
                <button type="submit" class="vll-btn vll-btn-sm vll-btn-danger">{{ __('Delete') }}</button>
            </form>
        </div>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-pen" aria-hidden="true"></i> {{ __('Details') }}</h2>
        </div>
        <form method="post" action="{{ route('resellers.update', $reseller->user_id) }}"
            class="vll-form-grid vll-form-grid-wide">
            @csrf
            @method('PUT')
            <div class="vll-field"><label>{{ __('Name') }}</label><input name="business_name"
                    value="{{ old('business_name', $reseller->business_name) }}" required></div>
            <div class="vll-field"><label>{{ __('Address') }}</label><input name="business_address"
                    value="{{ old('business_address', $reseller->business_address) }}"></div>
            <div class="vll-field"><label>{{ __('Phone') }}</label><input name="phone_number"
                    value="{{ old('phone_number', $reseller->phone_number) }}" required></div>
            <div class="vll-field"><label>{{ __('Email') }}</label><input type="email" name="email"
                    value="{{ old('email', $reseller->email) }}" required></div>
            <div class="vll-field"><label>{{ __('Scheme') }}</label>
                <select name="scheme_id" required>
                    @foreach ($schemes as $s)
                        <option value="{{ $s->scheme_id }}" @selected(old('scheme_id', $reseller->scheme_id) == $s->scheme_id)>
                            {{ $s->scheme_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="vll-field"><label>{{ __('Sender ID') }}</label><input name="sender_id"
                    value="{{ old('sender_id', $reseller->sender_id) }}"></div>
            <div class="vll-field"><label>{{ __('Status') }}</label>
                <select name="status" required>
                    @foreach ($statuses as $st)
                        <option value="{{ $st }}" @selected(old('status', $reseller->status) == $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div><button type="submit" class="vll-btn">{{ __('Save') }}</button></div>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-key" aria-hidden="true"></i> {{ __('Password') }}</h2>
        </div>
        <form method="post" action="{{ route('resellers.password', $reseller->user_id) }}" class="vll-form-grid">
            @csrf
            <div class="vll-field"><label>{{ __('New password') }}</label><input type="password" name="new_password"
                    required></div>
            <div class="vll-field"><label>{{ __('Confirm') }}</label><input type="password"
                    name="new_password_confirmation" required></div>
            <div><button type="submit" class="vll-btn vll-btn-secondary">{{ __('Update') }}</button></div>
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

    <p><a href="{{ route('resellers.index') }}" class="vll-link-back"><i class="fas fa-arrow-left"
                aria-hidden="true"></i> {{ __('Back to resellers') }}</a></p>
@endsection
