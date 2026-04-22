<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VLL Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/legacy-red.css') }}">
</head>
<body>
    <header class="app-header">
        <div class="app-logo">SMS Admin</div>
        @auth
        <div class="d-flex gap-3 align-items-center">
            <div>{{ auth()->user()->name ?? auth()->user()->user_id }}</div>
            <form method="POST" action="{{ route('web.logout') }}">
                @csrf
                <button class="btn btn-sm btn-light">Logout</button>
            </form>
        </div>
        @endauth
    </header>
    <div class="app-shell">
        @auth
        <aside class="app-menu">
            <ul>
                <li><a href="{{ route('web.dashboard') }}"><i class="fas fa-bars me-2"></i>Dashboard</a></li>
                <li><a href="{{ route('web.sales.index') }}"><i class="fas fa-credit-card me-2"></i>Sales</a></li>
                <li><a href="{{ route('web.clients.index') }}"><i class="fas fa-user me-2"></i>Clients</a></li>
                <li><a href="{{ route('web.resellers.index') }}"><i class="fas fa-users me-2"></i>Resellers</a></li>
                <li><a href="{{ route('web.agents.index') }}"><i class="fas fa-user-friends me-2"></i>Agents</a></li>
                <li><a href="{{ route('web.sender-ids.index') }}"><i class="fas fa-id-card me-2"></i>Sender ID</a></li>
                <li><a href="{{ route('web.reports.index') }}"><i class="fas fa-chart-line me-2"></i>Reports</a></li>
                <li><a href="{{ route('web.scheduled.index') }}"><i class="fas fa-calendar-alt me-2"></i>Scheduled</a></li>
                <li><a href="{{ route('web.history.index') }}"><i class="fas fa-history me-2"></i>History</a></li>
                <li><a href="{{ route('web.pricing.index') }}"><i class="fas fa-table me-2"></i>Pricing</a></li>
                <li><a href="{{ route('web.orders.index') }}"><i class="fas fa-money-bill me-2"></i>Orders</a></li>
                <li><a href="{{ route('web.account.index') }}"><i class="fas fa-user-cog me-2"></i>My Account</a></li>
            </ul>
        </aside>
        @endauth
        <main class="app-content">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
        </main>
    </div>
</body>
</html>
