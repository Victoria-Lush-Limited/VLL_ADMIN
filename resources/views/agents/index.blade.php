@extends('layouts.admin')

@section('title', 'Agents — ' . config('app.name'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Agents'), 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ __('Agents') }}</h1>
            <p class="vll-page-subtitle">{{ __('Field agents, regions, and commission-related access.') }}</p>
        </div>
    </div>

    <div class="vll-toolbar">
        <form method="get" class="vll-search" action="{{ route('agents.index') }}">
            <input type="search" name="q" value="{{ $keyword }}" placeholder="{{ __('Search…') }}">
            <button type="submit" class="vll-btn vll-btn-sm">{{ __('Search') }}</button>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-user-plus" aria-hidden="true"></i> {{ __('New agent') }}</h2>
        </div>
        <form method="post" action="{{ route('agents.store') }}" class="vll-form-grid vll-form-grid-wide">
            @csrf
            <div class="vll-field"><label>{{ __('Agent name') }}</label><input name="agent_name" required
                    value="{{ old('agent_name') }}"></div>
            <div class="vll-field"><label>{{ __('Region') }}</label><input name="region" value="{{ old('region') }}">
            </div>
            <div class="vll-field"><label>{{ __('Address') }}</label><input name="agent_address"
                    value="{{ old('agent_address') }}"></div>
            <div class="vll-field"><label>{{ __('Phone') }}</label><input name="phone_number" required
                    value="{{ old('phone_number') }}"></div>
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
            <div class="vll-field"><label>{{ __('Password') }}</label><input type="password" name="new_password" required>
            </div>
            <div class="vll-field"><label>{{ __('Confirm') }}</label><input type="password"
                    name="new_password_confirmation" required></div>
            <div><button type="submit" class="vll-btn">{{ __('Create agent') }}</button></div>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-list" aria-hidden="true"></i> {{ __('All agents') }}</h2>
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
                    @forelse ($agents as $a)
                        <tr>
                            <td>{{ $a->agent_name }}</td>
                            <td>{{ $a->user_id }}</td>
                            <td style="text-align:right;">{{ number_format($a->balance()) }}</td>
                            <td>@include('partials.status-account', ['status' => $a->status])</td>
                            <td class="vll-table-actions"><a class="vll-btn vll-btn-sm"
                                    href="{{ route('agents.show', $a->user_id) }}">{{ __('Manage') }}</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="vll-empty">{{ __('No agents found.') }}</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $agents->links() }}</div>
    </div>
@endsection
