<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#b91c1c">
    <title>@yield('title', config('app.name'))</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/vll-admin.css') }}">
</head>

<body class="vll-body">
    <div class="vll-app">
        <button type="button" class="vll-nav-toggle" aria-label="Open menu" aria-expanded="false"
            id="vll-nav-open">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </button>
        <aside class="vll-sidebar" id="vll-sidebar" aria-label="Main navigation">
            @include('partials.nav')
        </aside>
        <div class="vll-backdrop" id="vll-backdrop" role="presentation" tabindex="-1"></div>
        <div class="vll-main">
            <header class="vll-topbar">
                <div class="vll-brand">{{ config('app.name') }}</div>
                <div class="vll-topbar-actions">
                    <span class="vll-balance d-none d-md-inline"><i class="fas fa-sms" aria-hidden="true"></i>
                        {{ number_format($adminSmsBalance ?? 0) }} SMS</span>
                    <span class="vll-balance-pill d-md-none" title="SMS balance"><i class="fas fa-sms"
                            aria-hidden="true"></i> {{ number_format($adminSmsBalance ?? 0) }}</span>
                    <a class="vll-btn vll-btn-sm vll-btn-outline d-none d-sm-inline-flex"
                        href="{{ route('credits.create') }}">Buy credits</a>
                    <span class="vll-user d-none d-md-inline"><i class="fas fa-user" aria-hidden="true"></i>
                        {{ Auth::guard('admin')->user()->full_name ?? Auth::guard('admin')->user()->user_id }}</span>
                    <a class="vll-btn vll-btn-sm vll-btn-ghost" href="{{ route('account.show') }}">Account</a>
                    <form method="post" action="{{ route('logout') }}" class="vll-inline-form">
                        @csrf
                        <button type="submit" class="vll-btn vll-btn-sm vll-btn-ghost">Logout</button>
                    </form>
                </div>
            </header>
            <main class="vll-content">
                <div class="vll-content-inner">
                    @hasSection('breadcrumbs')
                        @yield('breadcrumbs')
                    @endif
                    @include('partials.flash')
                    @yield('content')
                </div>
                @include('partials.footer')
            </main>
        </div>
    </div>
    <script>
        (function() {
            var body = document.body;
            var openBtn = document.getElementById('vll-nav-open');
            var backdrop = document.getElementById('vll-backdrop');
            var sidebar = document.getElementById('vll-sidebar');

            function setOpen(open) {
                body.classList.toggle('vll-nav-open', open);
                if (openBtn) openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (openBtn) openBtn.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
            }
            if (openBtn) openBtn.addEventListener('click', function() {
                setOpen(!body.classList.contains('vll-nav-open'));
            });
            if (backdrop) backdrop.addEventListener('click', function() {
                setOpen(false);
            });
            document.querySelectorAll('.vll-sidebar .vll-nav a').forEach(function(a) {
                a.addEventListener('click', function() {
                    setOpen(false);
                });
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') setOpen(false);
            });
        })();
    </script>
    @stack('scripts')
</body>

</html>
