@php
    $s = (string) ($status ?? '');
    $lower = strtolower($s);
    $class = 'vll-badge-muted';
    if (str_contains($lower, 'active')) {
        $class = 'vll-badge-success';
    } elseif (str_contains($lower, 'pending')) {
        $class = 'vll-badge-warn';
    } elseif (str_contains($lower, 'suspend') || str_contains($lower, 'block')) {
        $class = 'vll-badge-danger';
    }
@endphp
<span class="vll-badge {{ $class }}">{{ $s }}</span>
