@php
    $r = fn ($p) => request()->routeIs($p);
@endphp
<div class="vll-sidebar-logo">
    <i class="fas fa-paper-plane" aria-hidden="true"></i>
    <span>{{ config('app.name') }}</span>
</div>
<p class="vll-nav-label">Menu</p>
<nav class="vll-nav">
    <a href="{{ route('dashboard') }}" class="{{ $r('dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"
            aria-hidden="true"></i> Dashboard</a>
    <a href="{{ route('sales.index') }}" class="{{ $r('sales.*') ? 'active' : '' }}"><i class="fas fa-credit-card"
            aria-hidden="true"></i> Sales</a>
    <a href="{{ route('clients.index') }}" class="{{ $r('clients.*') ? 'active' : '' }}"><i class="fas fa-user"
            aria-hidden="true"></i> Clients</a>
    <a href="{{ route('resellers.index') }}" class="{{ $r('resellers.*') ? 'active' : '' }}"><i class="fas fa-users"
            aria-hidden="true"></i> Resellers</a>
    <a href="{{ route('agents.index') }}" class="{{ $r('agents.*') ? 'active' : '' }}"><i class="fas fa-user-friends"
            aria-hidden="true"></i> Agents</a>
    <a href="{{ route('senders.index') }}" class="{{ $r('senders.*') ? 'active' : '' }}"><i class="fas fa-id-card"
            aria-hidden="true"></i> Sender ID</a>
    <a href="{{ route('scheduled.index') }}" class="{{ $r('scheduled.*') ? 'active' : '' }}"><i
            class="fas fa-calendar-alt" aria-hidden="true"></i> Scheduled</a>
    <a href="{{ route('history.index') }}" class="{{ $r('history.*') ? 'active' : '' }}"><i class="fas fa-history"
            aria-hidden="true"></i> History</a>
    <a href="{{ route('pricing.index') }}" class="{{ $r('pricing.*') ? 'active' : '' }}"><i class="fas fa-table"
            aria-hidden="true"></i> Pricing</a>
</nav>
<p class="vll-nav-label">Account</p>
<nav class="vll-nav">
    <a href="{{ route('credits.create') }}" class="{{ $r('credits.*') ? 'active' : '' }}"><i class="fas fa-shopping-cart"
            aria-hidden="true"></i> Buy credits</a>
    <a href="{{ route('account.show') }}" class="{{ $r('account.*') ? 'active' : '' }}"><i class="fas fa-user-cog"
            aria-hidden="true"></i> My account</a>
</nav>
