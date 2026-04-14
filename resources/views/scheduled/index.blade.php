@extends('layouts.admin')

@section('title', 'Scheduled — ' . config('app.name'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Scheduled'), 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ __('Scheduled SMS') }}</h1>
            <p class="vll-page-subtitle">{{ __('Messages queued for a future send time. Cancel before they go out.') }}</p>
        </div>
    </div>

    <div class="vll-toolbar">
        <form method="get" class="vll-search" action="{{ route('scheduled.index') }}">
            <input type="search" name="q" value="{{ $keyword }}" placeholder="{{ __('Search…') }}">
            <button type="submit" class="vll-btn vll-btn-sm">{{ __('Search') }}</button>
        </form>
    </div>

    <form method="post" action="{{ route('scheduled.cancel') }}" id="cancel-form">
        @csrf
        <div class="vll-card">
            <div class="vll-card-header">
                <h2 class="vll-card-title"><i class="fas fa-calendar-alt" aria-hidden="true"></i> {{ __('Queue') }}</h2>
                <button type="submit" class="vll-btn vll-btn-sm vll-btn-danger"
                    onclick="return confirm(@json(__('Cancel selected messages?')));">{{ __('Cancel selected') }}</button>
            </div>
            <div class="vll-table-wrap">
                <table class="vll-table">
                    <thead>
                        <tr>
                            <th style="width:2.5rem;"><span class="visually-hidden">{{ __('Select') }}</span></th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Sender') }}</th>
                            <th>{{ __('Message') }}</th>
                            <th>{{ __('Credits') }}</th>
                            <th>{{ __('When') }}</th>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($messages as $m)
                            @php
                                $uname = $clients[$m->user_id] ?? $m->user_id;
                            @endphp
                            <tr>
                                <td><input type="checkbox" name="sms_ids[]" value="{{ $m->sms_id }}"
                                        aria-label="{{ __('Select row') }}"></td>
                                <td>{{ $m->phone_number }}</td>
                                <td>{{ $m->sender_id }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($m->message, 40) }}</td>
                                <td>{{ $m->credits }}</td>
                                <td>{{ date('d-m-Y H:i', $m->date_created) }}</td>
                                <td>{{ $uname }}</td>
                                <td>@include('partials.status-account', ['status' => $m->sms_status])</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="vll-empty">{{ __('Nothing scheduled.') }}</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $messages->links() }}</div>
        </div>
    </form>
@endsection
