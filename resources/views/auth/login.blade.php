@extends('layouts.app')

@section('content')
<div class="auth-shell">
    <div class="auth-card card shadow-sm border-0">
        <div class="row g-0">
            <div class="col-lg-5 auth-brand-panel">
                <div class="auth-brand-content">
                    <h2 class="auth-title">Secure Access</h2>
                    <p class="auth-subtitle">
                        Professional login portal for authorized users.
                    </p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card-body p-4 p-md-5">
                    <h4 class="mb-1">Account Login</h4>
                    <p class="text-muted mb-4">Use your email, username, or user ID.</p>
                    <form method="POST" action="{{ route('web.login.submit') }}" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email / Username / User ID</label>
                            <input
                                type="text"
                                name="login"
                                class="form-control form-control-lg @error('login') is-invalid @enderror"
                                value="{{ old('login') }}"
                                placeholder="e.g. admin@vll.local or ADM-XXXX"
                                autocomplete="username"
                                autofocus
                                required
                            >
                            @error('login')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control form-control-lg @error('password') is-invalid @enderror"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-primary btn-lg w-100">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
