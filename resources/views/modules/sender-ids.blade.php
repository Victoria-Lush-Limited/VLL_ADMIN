@extends('layouts.app')
@section('content')
<div class="legacy-box">
    <div class="legacy-title">Sender IDs</div>
    <form method="POST" action="{{ route('web.sender-ids.store') }}" class="row g-2 mb-3">
        @csrf
        <div class="col-md-4"><input class="form-control" name="sender_id" maxlength="11" placeholder="Sender ID" required></div>
        <div class="col-md-4"><input class="form-control" name="user_id" placeholder="Owner User ID" required></div>
        <div class="col-md-2"><button class="btn btn-primary w-100">Submit</button></div>
    </form>
    <table>
        <thead><tr><th>ID</th><th>Sender ID</th><th>User ID</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
        @foreach($senders as $sender)
            <tr>
                <td>{{ $sender->id }}</td>
                <td>{{ $sender->sender_id }}</td>
                <td>{{ $sender->user_id }}</td>
                <td>{{ $sender->id_status }}</td>
                <td>
                    <form method="POST" action="{{ route('web.sender-ids.status', $sender->id) }}" class="d-flex gap-2">
                        @csrf
                        <select class="form-select form-select-sm" name="id_status">
                            @foreach(['pending', 'active', 'rejected', 'inactive'] as $status)
                                <option value="{{ $status }}" @selected($sender->id_status === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-primary">Update</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $senders->links() }}
</div>
@endsection
