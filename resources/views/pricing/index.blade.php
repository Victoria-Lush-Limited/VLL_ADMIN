@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Create Pricing Scheme</h5>
                <form method="POST" action="{{ route('web.pricing.store') }}">
                    @csrf
                    <div class="mb-2"><input class="form-control" name="name" placeholder="Scheme name" required></div>
                    <div class="mb-2"><input class="form-control" type="number" name="min_sms" placeholder="Min SMS" required></div>
                    <div class="mb-2"><input class="form-control" type="number" name="max_sms" placeholder="Max SMS (optional)"></div>
                    <div class="mb-2"><input class="form-control" type="number" step="0.01" name="price" placeholder="Price" required></div>
                    <button class="btn btn-primary w-100">Save Scheme</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Pricing Schemes</h5>
                <table class="table table-sm">
                    <thead><tr><th>ID</th><th>Name</th><th>Tiers</th></tr></thead>
                    <tbody>
                    @foreach($schemes as $scheme)
                        <tr>
                            <td>{{ $scheme->id }}</td>
                            <td>{{ $scheme->name }}</td>
                            <td>
                                @foreach($scheme->tiers as $tier)
                                    <span class="badge text-bg-secondary">{{ $tier->min_sms }} - {{ $tier->max_sms ?? '∞' }}: {{ $tier->price }}</span>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
