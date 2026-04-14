@if (! empty($items))
    <nav class="vll-breadcrumbs" aria-label="Breadcrumb">
        <ol>
            @foreach ($items as $index => $crumb)
                @php
                    $isLast = $index === count($items) - 1;
                    $url = $crumb['url'] ?? null;
                @endphp
                <li>
                    @if ($url && ! $isLast)
                        <a href="{{ $url }}">{{ $crumb['label'] }}</a>
                        <span class="vll-bc-sep" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
                    @else
                        <span @if ($isLast) aria-current="page" @endif>{{ $crumb['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
