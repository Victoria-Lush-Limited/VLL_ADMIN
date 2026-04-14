@php
    $s = (string) ($status ?? '');
    $lower = strtolower($s);
    $class = 'vll-badge-muted';
    if (str_contains($lower, 'pending')) {
        $class = 'vll-badge-warn';
    } elseif (str_contains($lower, 'alloc') || str_contains($lower, 'complete') || str_contains($lower, 'paid')) {
        $class = 'vll-badge-success';
    } elseif (str_contains($lower, 'cancel')) {
        $class = 'vll-badge-danger';
    }
@endphp
<span class="vll-badge {{ $class }}">{{ $s }}</span>
