@extends('layouts.admin')

@section('title', $agent->agent_name)

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Agents'), 'url' => route('agents.index')],
            ['label' => $agent->agent_name, 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ $agent->agent_name }}</h1>
            <p class="vll-page-subtitle">{{ __('Balance:') }} <strong>{{ number_format($balance) }}</strong> SMS</p>
        </div>
        <div class="vll-page-head-actions">
            <form method="post" action="{{ route('agents.destroy', $agent->user_id) }}"
                onsubmit="return confirm(@json(__('Delete this agent?')));">
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
        <form method="post" action="{{ route('agents.update', $agent->user_id) }}"
            class="vll-form-grid vll-form-grid-wide">
            @csrf
            @method('PUT')
            <div class="vll-field"><label>{{ __('Name') }}</label><input name="agent_name"
                    value="{{ old('agent_name', $agent->agent_name) }}" required></div>
            <div class="vll-field"><label>{{ __('Region') }}</label><input name="region"
                    value="{{ old('region', $agent->region) }}"></div>
            <div class="vll-field"><label>{{ __('Address') }}</label><input name="agent_address"
                    value="{{ old('agent_address', $agent->agent_address) }}"></div>
            <div class="vll-field"><label>{{ __('Phone') }}</label><input name="phone_number"
                    value="{{ old('phone_number', $agent->phone_number) }}" required></div>
            <div class="vll-field"><label>{{ __('Email') }}</label><input type="email" name="email"
                    value="{{ old('email', $agent->email) }}" required></div>
            <div class="vll-field"><label>{{ __('Scheme') }}</label>
                <select name="scheme_id" required>
                    @foreach ($schemes as $s)
                        <option value="{{ $s->scheme_id }}" @selected(old('scheme_id', $agent->scheme_id) == $s->scheme_id)>
                            {{ $s->scheme_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="vll-field"><label>{{ __('Status') }}</label>
                <select name="status" required>
                    @foreach ($statuses as $st)
                        <option value="{{ $st }}" @selected(old('status', $agent->status) == $st)>{{ $st }}</option>
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
        <form method="post" action="{{ route('agents.password', $agent->user_id) }}" class="vll-form-grid">
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

    <p><a href="{{ route('agents.index') }}" class="vll-link-back"><i class="fas fa-arrow-left" aria-hidden="true"></i>
            {{ __('Back to agents') }}</a></p>
@endsection
