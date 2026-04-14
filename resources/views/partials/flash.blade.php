@if (session('status'))
    <div class="vll-alert vll-alert-success" role="status">
        <i class="fas fa-circle-check" aria-hidden="true"></i>
        <span>{{ session('status') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="vll-alert vll-alert-error" role="alert">
        <i class="fas fa-circle-exclamation" aria-hidden="true"></i>
        <div>
            @if ($errors->count() === 1)
                {{ $errors->first() }}
            @else
                <strong>{{ __('Please fix the following:') }}</strong>
                <ul style="margin:0.35rem 0 0;padding-left:1.1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endif
