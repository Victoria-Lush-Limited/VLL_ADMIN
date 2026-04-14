@extends('layouts.admin')

@section('title', 'History — ' . config('app.name'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('History'), 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ __('SMS history') }}</h1>
            <p class="vll-page-subtitle">{{ __('Past outbound messages with filters by client, status, and date range.') }}</p>
        </div>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-filter" aria-hidden="true"></i> {{ __('Filters') }}</h2>
        </div>
        <form method="get" action="{{ route('history.index') }}"
            style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;align-items:end;">
            <div class="vll-field" style="margin:0;">
                <label>{{ __('From') }}</label>
                <input type="date" name="from" value="{{ $fromInput }}">
            </div>
            <div class="vll-field" style="margin:0;">
                <label>{{ __('To') }}</label>
                <input type="date" name="to" value="{{ $toInput }}">
            </div>
            <div class="vll-field" style="margin:0;">
                <label>{{ __('Client') }}</label>
                <select name="client_id">
                    <option value="">{{ __('All') }}</option>
                    @foreach ($clients as $c)
                        <option value="{{ $c->user_id }}" @selected($clientId === $c->user_id)>
                            {{ $c->username }} — {{ $c->client_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="vll-field" style="margin:0;">
                <label>{{ __('Status') }}</label>
                <select name="sms_status">
                    <option value="">{{ __('All') }}</option>
                    @foreach ($statuses as $st)
                        <option value="{{ $st }}" @selected($smsStatus === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="vll-field" style="margin:0;">
                <label>{{ __('Keyword') }}</label>
                <input type="search" name="q" value="{{ $keyword }}">
            </div>
            <div>
                <button type="submit" class="vll-btn">{{ __('Apply') }}</button>
            </div>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-history" aria-hidden="true"></i> {{ __('Records') }}</h2>
        </div>
        <div class="vll-table-wrap">
            <table class="vll-table">
                <thead>
                    <tr>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Sender') }}</th>
                        <th>{{ __('Message') }}</th>
                        <th>{{ __('Credits') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $m)
                        @php
                            $cl = \App\Models\SmsClient::query()->where('user_id', $m->user_id)->first();
                            $uname = $cl->username ?? $m->user_id;
                        @endphp
                        <tr>
                            <td>{{ $m->phone_number }}</td>
                            <td>{{ $m->sender_id }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($m->message, 44) }}</td>
                            <td>{{ $m->credits }}</td>
                            <td>{{ date('d-m-Y H:i', $m->date_created) }}</td>
                            <td>{{ $uname }}</td>
                            <td>@include('partials.status-account', ['status' => $m->sms_status])</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="vll-empty">{{ __('No messages match your filters.') }}</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $messages->links() }}</div>
    </div>
@endsection
