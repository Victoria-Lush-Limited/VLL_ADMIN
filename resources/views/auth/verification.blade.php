<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#b91c1c">
    <title>{{ __('Verify') }} — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/vll-admin.css') }}">
</head>

<body class="vll-login-page">
    <div class="vll-login-shell">
        <div class="vll-login-brand">
            <div class="vll-login-brand-mark" aria-hidden="true"><i class="fas fa-shield-halved"></i></div>
            <div class="vll-login-brand-name">{{ config('app.name') }}</div>
        </div>
        <div class="vll-login-card">
            <h1 class="vll-login-title">{{ __('Verify your account') }}</h1>
            <p class="vll-login-meta">{{ __('Enter the code sent for') }}
                <strong>{{ Auth::guard('admin')->user()->user_id }}</strong>.</p>
            @include('partials.flash')
            <form method="post" action="{{ route('admin.verification.verify') }}" class="vll-form-grid" style="max-width:100%;">
                @csrf
                <div class="vll-field">
                    <label for="vcode">{{ __('Verification code') }}</label>
                    <input id="vcode" name="vcode" type="text" required autocomplete="one-time-code" inputmode="numeric">
                </div>
                <button type="submit" class="vll-btn" style="width:100%;justify-content:center;">{{ __('Submit') }}</button>
            </form>
            <form method="post" action="{{ route('logout') }}" style="margin-top:1.25rem;text-align:center;">
                @csrf
                <button type="submit" class="vll-btn vll-btn-sm vll-btn-secondary">{{ __('Sign out') }}</button>
            </form>
        </div>
    </div>
</body>

</html>
