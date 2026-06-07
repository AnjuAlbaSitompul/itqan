<!-- PAGE-HEADER -->
<div class="page-header">

    @php
        $currentPage =
            $pageTitle ?? ucwords(str_replace(['.', '-', '_'], ' ', request()->route()?->getName() ?? 'Dashboard'));
    @endphp

    <h1 class="page-title">
        {{ $currentPage }}
    </h1>

    <div>
        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>
            </li>

            @if ($currentPage !== 'Dashboard')
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $currentPage }}
                </li>
            @endif

        </ol>
    </div>

</div>
<!-- PAGE-HEADER END -->
