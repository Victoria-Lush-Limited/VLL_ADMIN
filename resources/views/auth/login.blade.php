<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#b91c1c">
    <title>{{ __('Sign in') }} — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/vll-admin.css') }}">
</head>

<body class="vll-login-page">
    <div class="vll-login-shell">
        <div class="vll-login-brand">
            <div class="vll-login-brand-mark" aria-hidden="true"><i class="fas fa-paper-plane"></i></div>
            <div class="vll-login-brand-name">{{ config('app.name') }}</div>
        </div>
        <div class="vll-login-card">
            <h1 class="vll-login-title">{{ __('Administrator sign in') }}</h1>
            @include('partials.flash')
            <form method="post" action="{{ route('login') }}" class="vll-form-grid" style="max-width:100%;">
                @csrf
                <div class="vll-field">
                    <label for="user_id">{{ __('Username') }}</label>
                    <input id="user_id" name="user_id" type="text" value="{{ old('user_id') }}" required
                        autocomplete="username" autofocus>
                    @error('user_id')
                        <div style="color:#b91c1c;font-size:0.82rem;margin-top:0.35rem;font-weight:600;">{{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="vll-field">
                    <label for="password">{{ __('Password') }}</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password">
                </div>
                <button type="submit" class="vll-btn" style="width:100%;justify-content:center;">{{ __('Sign in') }}</button>
            </form>
        </div>
    </div>
</body>

</html>
