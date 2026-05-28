<!doctype html>
<html lang="en" dir="ltr">
@include('layouts.partials.head')

<body class="app sidebar-mini ltr light-mode">


    @include('layouts.partials.loader')

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">
            @include('layouts.partials.header')
            @include('layouts.partials.sidebar')

            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">
                        @include('layouts.partials.page-header')
                        @yield('content')
                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!--app-content close-->
        </div>
        @include('layouts.partials.footer')
        @include('layouts.partials.script')
</body>

</html>
