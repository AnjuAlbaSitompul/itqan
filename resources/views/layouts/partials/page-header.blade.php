<!-- PAGE-HEADER -->
<div class="page-header">
    <h1 class="page-title">{{ $pageTitle ?? 'Dashboard' }}</h1>

    <div>
        <ol class="breadcrumb">

            {{-- Dashboard --}}
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>

            @php
                $segments = request()->segments();
                $url = '';
            @endphp

            @foreach ($segments as $index => $segment)
                @php
                    $url .= '/' . $segment;

                    $isLast = $index === count($segments) - 1;

                    $label = ucwords(str_replace(['-', '_'], ' ', $segment));
                @endphp

                @if ($isLast)
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $label }}
                    </li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ url($url) }}">{{ $label }}</a>
                    </li>
                @endif
            @endforeach

        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
