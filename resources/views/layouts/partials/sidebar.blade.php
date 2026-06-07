            <div class="sticky">
                <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
                <div class="app-sidebar">
                    <div class="side-header">
                        <a class="header-brand1" href="{{ url('/') }}">
                            <img src="{{ asset('assets/images/brand/logo-white.png') }}"
                                class="header-brand-img desktop-logo" alt="logo">

                            <img src="{{ asset('assets/images/brand/icon-white.png') }}"
                                class="header-brand-img toggle-logo" alt="logo">

                            <img src="{{ asset('assets/images/brand/icon-dark.png') }}"
                                class="header-brand-img light-logo" alt="logo">

                            <img src="{{ asset('assets/images/brand/logo-dark.png') }}"
                                class="header-brand-img light-logo1" alt="logo">
                        </a>
                        <!-- LOGO -->
                    </div>
                    <div class="main-sidemenu">
                        <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg"
                                fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                            </svg></div>
                        <ul class="side-menu">
                            <li class="sub-category">
                                <h3>Main</h3>
                            </li>
                            @foreach ($sidebar_menus as $menu)
                                {{-- SINGLE MENU --}}
                                @if (empty($menu['children']))
                                    <li class="slide">

                                        <a class="side-menu__item" href="{{ route($menu['route']) }}">

                                            <i class="side-menu__icon {{ $menu['icon'] }}"></i>

                                            <span class="side-menu__label">

                                                {{ $menu['name'] }}

                                            </span>

                                        </a>

                                    </li>
                                @else
                                    {{-- HAS SUBMENU --}}
                                    <li class="slide has-sub">

                                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)">

                                            <i class="side-menu__icon {{ $menu['icon'] }}"></i>

                                            <span class="side-menu__label">

                                                {{ $menu['name'] }}

                                            </span>

                                            <i class="angle fe fe-chevron-right"></i>

                                        </a>

                                        <ul class="slide-menu">

                                            @foreach ($menu['children'] as $child)
                                                <li>

                                                    <a href="{{ route($child['route']) }}" class="slide-item">

                                                        {{ $child['name'] }}

                                                    </a>

                                                </li>
                                            @endforeach

                                        </ul>

                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                                width="24" height="24" viewBox="0 0 24 24">
                                <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                            </svg></div>
                    </div>
                </div>
            </div>
