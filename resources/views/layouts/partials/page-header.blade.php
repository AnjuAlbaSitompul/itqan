<!-- PAGE-HEADER -->
<div class="page-header">

    @php
        $currentPage =
            $pageTitle ?? ucwords(str_replace(['.', '-', '_'], ' ', request()->route()?->getName() ?? 'Dashboard'));
    @endphp

    <h1 class="page-title">
        {{ strtoupper($currentPage) }}
    </h1>

    <div>
        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    DASHBOARD
                </a>
            </li>

            @if ($currentPage !== 'Dashboard')
                <li class="breadcrumb-item active" aria-current="page">
                    {{ strtoupper($currentPage) }}
                </li>
            @endif

        </ol>
    </div>

</div>
<!-- PAGE-HEADER END -->