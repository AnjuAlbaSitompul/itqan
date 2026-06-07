<!doctype html>
<html lang="en" dir="ltr">

@include('layouts.partials.head')

<head>
    @stack('styles')
</head>

<body class="app sidebar-mini ltr light-mode">

    @include('layouts.partials.loader')

    <div class="page">

        <div class="page-main">

            @include('layouts.partials.header')
            @include('layouts.partials.sidebar')

            <div class="main-content app-content mt-0">

                <div class="side-app">

                    <div class="main-container container-fluid">

                        @include('layouts.partials.page-header')

                        @yield('content')

                    </div>

                </div>

            </div>

        </div>

        @include('layouts.partials.footer')

    </div>

    @include('layouts.partials.script')

    @stack('scripts')

</body>

</html>