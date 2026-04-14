@extends('layouts.admin')

@section('title', 'My account — ' . config('app.name'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('My account'), 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ __('My account') }}</h1>
            <p class="vll-page-subtitle">{{ __('Administrator profile and password.') }}</p>
        </div>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-id-card" aria-hidden="true"></i> {{ __('Profile') }}</h2>
        </div>
        <dl style="display:grid;gap:0.85rem;margin:0;max-width:480px;">
            <div>
                <dt style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--vll-gray-400);font-weight:700;margin:0 0 0.2rem;">
                    {{ __('Name') }}</dt>
                <dd style="margin:0;font-weight:600;">{{ $admin->full_name ?? '—' }}</dd>
            </div>
            <div>
                <dt style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--vll-gray-400);font-weight:700;margin:0 0 0.2rem;">
                    {{ __('Username / ID') }}</dt>
                <dd style="margin:0;font-weight:600;">{{ $admin->user_id }}</dd>
            </div>
            <div>
                <dt style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--vll-gray-400);font-weight:700;margin:0 0 0.2rem;">
                    {{ __('Status') }}</dt>
                <dd style="margin:0;">@include('partials.status-account', ['status' => $admin->status])</dd>
            </div>
        </dl>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-key" aria-hidden="true"></i> {{ __('Change password') }}</h2>
        </div>
        <p style="color:var(--vll-gray-400);font-size:0.88rem;margin:-0.25rem 0 1rem;max-width:520px;">
            {{ __('New passwords are stored securely. Legacy MD5 passwords still work until you change them.') }}</p>
        <form method="post" action="{{ route('account.password') }}" class="vll-form-grid">
            @csrf
            <div class="vll-field"><label>{{ __('Current password') }}</label><input type="password"
                    name="current_password" required autocomplete="current-password"></div>
            <div class="vll-field"><label>{{ __('New password') }}</label><input type="password" name="new_password"
                    required autocomplete="new-password"></div>
            <div class="vll-field"><label>{{ __('Confirm') }}</label><input type="password"
                    name="new_password_confirmation" required autocomplete="new-password"></div>
            <div><button type="submit" class="vll-btn">{{ __('Update password') }}</button></div>
        </form>
    </div>
@endsection
