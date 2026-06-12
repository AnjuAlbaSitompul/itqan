<div class="app-header header sticky">
    <div class="container-fluid main-container">
        <div class="d-flex">
            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar"
                href="javascript:void(0)"></a>

            <!-- sidebar-toggle-->

            <a class="logo-horizontal" href="{{ url('/') }}">
                <img src="{{ asset('assets/images/brand/logo-white.png') }}" class="header-brand-img desktop-logo"
                    alt="logo">

                <img src="{{ asset('assets/images/brand/logo-dark.png') }}" class="header-brand-img light-logo1"
                    alt="logo">
            </a>

            <!-- LOGO -->

            <div class="d-flex order-lg-2 ms-auto header-right-icons">

                <!-- SEARCH -->

                <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                    aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">

                    <span class="navbar-toggler-icon fe fe-more-vertical"></span>

                </button>

                <div class="navbar navbar-collapse responsive-navbar p-0">

                    <div class="collapse navbar-collapse" id="navbarSupportedContent-4">

                        <div class="d-flex order-lg-2">

                            <!-- COUNTRY -->

                            <div class="d-flex">
                                <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                                    <span class="dark-layout"><i class="fe fe-moon"></i></span>
                                    <span class="light-layout"><i class="fe fe-sun"></i></span>
                                </a>
                            </div>

                            <!-- Theme-Layout -->

                            <!-- CART -->

                            <div class="dropdown d-flex">
                                <a class="nav-link icon full-screen-link nav-link-bg">
                                    <i class="fe fe-minimize fullscreen-button"></i>
                                </a>
                            </div>

                            <!-- FULL-SCREEN -->

                            @php
                                $notifications = auth()->user()
                                    ->notifications()
                                    ->latest()
                                    ->take(10)
                                    ->get();

                                $unreadCount = auth()->user()
                                    ->unreadNotifications()
                                    ->count();
                            @endphp

                            <div class="dropdown d-flex message">
                                <a class="nav-link icon text-center" data-bs-toggle="dropdown">
                                    <i class="fe fe-bell"></i>
                                    @if($unreadCount > 0)
                                        <span class="pulse-danger" id="notification-pulse"></span>
                                    @endif
                                </a>

                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <div class="drop-heading border-bottom">
                                        <div class="d-flex">
                                            <h6 class="mt-1 mb-0 fs-16 fw-semibold text-dark">
                                                You have <span id="unread-count">{{ $unreadCount }}</span> unread
                                                notifications
                                            </h6>

                                            @if($unreadCount > 0)
                                                <div class="ms-auto">
                                                    <form action="{{ route('notifications.read-all') }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-link text-muted p-0 fs-12">
                                                            Mark all as read
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="message-menu message-menu-scroll">
                                        @forelse($notifications as $notification)
                                            <a class="dropdown-item d-flex notification-item {{ is_null($notification->read_at) ? 'bg-light unread' : '' }}"
                                                href="{{ $notification->data['url'] ?? '#' }}"
                                                data-id="{{ $notification->id }}">

                                                <span
                                                    class="avatar avatar-md brround me-3 align-self-center bg-primary text-white">
                                                    <i class="fe fe-bell"></i>
                                                </span>

                                                <div class="wd-90p">
                                                    <div class="d-flex">
                                                        <h5 class="mb-1">
                                                            {{ $notification->data['title'] ?? 'Notification' }}
                                                        </h5>
                                                        <small class="text-muted ms-auto text-end">
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                    <span>
                                                        {{ $notification->data['message'] ?? '' }}
                                                    </span>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="text-center p-4 text-muted">
                                                No notifications
                                            </div>
                                        @endforelse
                                    </div>

                                    <div class="dropdown-divider m-0"></div>

                                    <a href="" class="dropdown-item text-center p-3 text-muted">
                                        See all Notifications
                                    </a>
                                </div>
                            </div>

                            <!-- MESSAGE-BOX -->

                            <!-- SIDE-MENU -->

                            <div class="dropdown d-flex profile-1">

                                <a href="javascript:void(0)" data-bs-toggle="dropdown"
                                    class="nav-link leading-none d-flex">

                                    <img src="{{ asset('assets/images/users/21.jpg') }}" alt="profile-user"
                                        class="avatar profile-user brround cover-image">

                                </a>

                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

                                    <div class="drop-heading">
                                        <div class="text-center">
                                            <h5 class="text-dark mb-0 fs-14 fw-semibold">
                                                {{ auth()->user()->name }}
                                            </h5>

                                            {{-- <small class="text-muted">{{ auth()->user()->role }}</small> --}}
                                        </div>
                                    </div>

                                    <div class="dropdown-divider m-0"></div>

                                    <a class="dropdown-item" href="login.html">
                                        <i class="dropdown-icon fe fe-alert-circle"></i>
                                        Sign out
                                    </a>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>