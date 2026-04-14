@extends('layouts.admin')

@section('title', 'Sender IDs — ' . config('app.name'))

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'items' => [
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Sender ID'), 'url' => null],
        ],
    ])
@endsection

@section('content')
    <div class="vll-page-head">
        <div class="vll-page-head-text">
            <h1 class="vll-page-title">{{ __('Sender ID') }}</h1>
            <p class="vll-page-subtitle">{{ __('Approve and manage requested sender names for clients.') }}</p>
        </div>
    </div>

    <div class="vll-toolbar">
        <form method="get" class="vll-search" action="{{ route('senders.index') }}">
            <input type="search" name="q" value="{{ $keyword }}" placeholder="{{ __('Search sender or message…') }}">
            <button type="submit" class="vll-btn vll-btn-sm">{{ __('Search') }}</button>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-plus" aria-hidden="true"></i> {{ __('New request') }}</h2>
        </div>
        <form method="post" action="{{ route('senders.store') }}" class="vll-form-grid vll-form-grid-wide">
            @csrf
            <div class="vll-field"><label>{{ __('Client') }}</label>
                <select name="user_id" required>
                    <option value="">— {{ __('Select client') }} —</option>
                    @foreach ($clients as $cl)
                        <option value="{{ $cl->user_id }}" @selected(old('user_id') == $cl->user_id)>
                            {{ $cl->client_name }} ({{ $cl->user_id }})</option>
                    @endforeach
                </select>
            </div>
            <div class="vll-field"><label>{{ __('Sender ID') }}</label><input name="sender_id" maxlength="32" required
                    value="{{ old('sender_id') }}"></div>
            <div class="vll-field"><label>{{ __('Sample message') }}</label><textarea name="message" required>{{ old('message') }}</textarea>
            </div>
            <div><button type="submit" class="vll-btn">{{ __('Submit request') }}</button></div>
        </form>
    </div>

    <div class="vll-card">
        <div class="vll-card-header">
            <h2 class="vll-card-title"><i class="fas fa-list" aria-hidden="true"></i> {{ __('Requests') }}</h2>
        </div>
        <div class="vll-table-wrap">
            <table class="vll-table">
                <thead>
                    <tr>
                        <th>{{ __('Sender') }}</th>
                        <th>{{ __('Client') }}</th>
                        <th>{{ __('Message') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Requested') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($senders as $s)
                        @php
                            $cn = optional($s->client)->client_name ?? $s->user_id;
                            $st = $s->id_status ?? $s->status ?? '—';
                        @endphp
                        <tr>
                            <td><strong>{{ $s->sender_id }}</strong></td>
                            <td>{{ $cn }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($s->message, 48) }}</td>
                            <td>{{ $s->id_type }}</td>
                            <td>{{ $s->date_requested ? date('d-m-Y', $s->date_requested) : '—' }}</td>
                            <td>@include('partials.status-account', ['status' => $st])</td>
                            <td class="vll-table-actions">
                                <button type="button" class="vll-btn vll-btn-sm vll-btn-muted"
                                    onclick="document.getElementById('edit-{{ $s->id }}').showModal()">{{ __('Edit') }}</button>
                                <form method="post" action="{{ route('senders.destroy', $s) }}" class="vll-inline-form"
                                    onsubmit="return confirm(@json(__('Delete this request?')));">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="vll-btn vll-btn-sm vll-btn-danger">{{ __('Del') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="vll-empty">{{ __('No sender requests yet.') }}</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $senders->links() }}</div>
    </div>

    @foreach ($senders as $s)
        <dialog id="edit-{{ $s->id }}" class="vll-dialog">
            <div class="vll-dialog-body">
                <form method="post" action="{{ route('senders.update', $s) }}">
                    @csrf
                    @method('PUT')
                    <h3>{{ __('Update') }} {{ $s->sender_id }}</h3>
                    <div class="vll-field"><label>{{ __('Type') }}</label>
                        <select name="id_type" required>
                            @foreach ($idTypes as $t)
                                <option value="{{ $t }}" @selected($s->id_type == $t)>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vll-field"><label>{{ __('Status') }}</label>
                        <select name="id_status" required>
                            @foreach ($idStatuses as $t)
                                <option value="{{ $t }}" @selected(($s->id_status ?? $s->status) == $t)>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex;gap:0.5rem;justify-content:flex-end;margin-top:1.25rem;flex-wrap:wrap;">
                        <button type="button" class="vll-btn vll-btn-sm vll-btn-muted"
                            onclick="document.getElementById('edit-{{ $s->id }}').close()">{{ __('Cancel') }}</button>
                        <button type="submit" class="vll-btn vll-btn-sm">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </dialog>
    @endforeach
@endsection
