@extends('layouts.signin')
@section('content')
    <div class="position-relative min-vh-100">
        <div class="row gx-0">
            <div class="col-xl-5">
                <div
                    class="row justify-content-center align-items-center p-10 min-vh-100 bg-body-secondary position-relative">
                    <div class="col-md-7 col-lg-6 col-xl-8 col-xxl-7">
                        <a href="index-2.html" class="text-nowrap d-block w-100">
                            <img src="assetsSignIn/images/logo-sm.png" class="dark-logo" height="30" alt="Logo-Dark">
                        </a>
                        <h3 class="mb-3 mt-8">Sign In</h3>
                        <p class="text-muted mb-8">Access aplikasi untuk monitoring perkembangan pekerjaan anda</p>
                        <form id="signin-form" action="javascript:void(0);" method="POST">
                            @csrf
                            <div class="signin-other-title position-relative text-center mt-8 mb-6">
                                <span class="mb-0 title">Sign in with your account</span>
                            </div>
                            <div class="mb-5">
                                <label for="Email" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" id="Email"
                                    placeholder="Enter your username">
                            </div>
                            <div class="mb-5">
                                <label for="Password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="Password"
                                    placeholder="Enter your password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Sign In</button>
                            <div class="mt-5">
                                <p class="mb-0">Don't have an account ? <a href="/signup" class="fw-medium text-primary">
                                        Sign Up </a> </p>
                            </div>
                            <div class="text-muted pt-14">
                                <p>©
                                    <script>
                                        document.write(new Date().getFullYear())
                                    </script> Aquiry. Crafted with <i class="mdi mdi-heart text-danger"></i>
                                    by Codubucks
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 d-none d-md-block">

                <div
                    class="h-100 d-flex align-items-center overflow-hidden justify-content-center position-relative z-2 hero-section bg-body">
                    <div class="floating-card position-absolute card-1">
                        <div class="d-flex gap-5">
                            <div class="bg-body-secondary shadow-lg rounded-3 px-4 py-2 team-info">
                                <h6 class="mb-0">Olivia Martinez</h6>
                                <small class="text-muted mb-0">Project Manager</small>
                            </div>
                            <div class="avatar avatar-md bg-body-secondary shadow-lg rounded-3 linkedin">
                                <img src="assetsSignIn/images/users/avatar-2.png" alt="Avatar Image" class="avatar-xs">
                            </div>
                        </div>
                    </div>
                    <div class="floating-card position-absolute card-2 d-none d-xxl-block">
                        <div class="d-flex gap-5">
                            <div class="bg-body-secondary shadow-lg rounded-3 px-4 py-2 team-info">
                                <h6 class="mb-0">James Anderson</h6>
                                <small class="text-muted mb-0">UI/UX Designer</small>
                            </div>
                            <div class="avatar avatar-md bg-body-secondary shadow-lg rounded-3 linkedin">
                                <img src="assetsSignIn/images/users/avatar-1.png" alt="Avatar Image" class="avatar-xs">
                            </div>
                        </div>
                    </div>
                    <div class="floating-card position-absolute card-3">
                        <div class="d-flex gap-5">
                            <div class="avatar avatar-md bg-body-secondary shadow-lg rounded-3 linkedin">
                                <img src="assetsSignIn/images/users/avatar-7.png" alt="Avatar Image" class="avatar-xs">
                            </div>
                            <div class="bg-body-secondary shadow-lg rounded-3 px-4 py-2 team-info">
                                <h6 class="mb-0">Sophia Lee</h6>
                                <small class="text-muted mb-0">Frontend Developer</small>
                            </div>
                        </div>
                    </div>
                    <div class="floating-card position-absolute card-4 d-none d-xxl-block">
                        <div class="d-flex gap-5">
                            <div class="avatar avatar-md bg-body-secondary shadow-lg rounded-3 linkedin">
                                <img src="assetsSignIn/images/users/avatar-8.png" alt="Avatar Image" class="avatar-xs">
                            </div>
                            <div class="bg-body-secondary shadow-lg rounded-3 px-4 py-2 team-info">
                                <h6 class="mb-0">Daniel Kim</h6>
                                <small class="text-muted mb-0">Backend Developer</small>
                            </div>
                        </div>
                    </div>
                    <div class="floating-card position-absolute card-5">
                        <div class="d-flex gap-5">
                            <div class="avatar avatar-md bg-body-secondary shadow-lg rounded-3 linkedin">
                                <img src="assetsSignIn/images/users/avatar-9.png" alt="Avatar Image" class="avatar-xs">
                            </div>
                            <div class="bg-body-secondary shadow-lg rounded-3 px-4 py-2 team-info">
                                <h6 class="mb-0">Emma Johnson</h6>
                                <small class="text-muted mb-0">Data Scientist</small>
                            </div>
                        </div>
                    </div>
                    <div class="floating-card position-absolute card-6">
                        <div class="w-72 shadow-lg rounded-3 overflow-hidden">
                            <img src="assetsSignIn/images/auth/img-1.png" alt="Image"
                                class="img-fluid h-100 w-100 object-fit-cover">
                        </div>
                    </div>
                    <div class="floating-card position-absolute card-7">
                        <div class="w-80 shadow-lg rounded-3 overflow-hidden">
                            <img src="assetsSignIn/images/auth/img-4.png" alt="Image"
                                class="img-fluid w-100 h-100 object-fit-cover">
                        </div>
                    </div>
                    <div class="floating-card position-absolute card-8">
                        <div class="h-40 shadow-lg rounded-3 overflow-hidden">
                            <img src="assetsSignIn/images/auth/img-3.png" alt="Image"
                                class="img-fluid w-100 h-100 object-fit-cover">
                        </div>
                    </div>
                    <div class="text-center z-index-2 position-relative">
                        <p class="display-5 text-body fw-normal mb-6">
                            ITQAN<br>
                            <span class="text-primary display-6 fw-normal">ITQAN App</span>
                        </p>
                        <p class="mb-14 px-4 fs-14 max-w-75 mx-auto">Management Perkembangan Pekerjaan Anda.</p>
                    </div>
                    <div class="social-icons position-absolute d-flex gap-8">
                        <div class="avatar avatar-md bg-body-secondary shadow-lg rounded-3 linkedin">
                            <img src="assetsSignIn/images/social-icons/linkedin.png" alt="Avatar Image"
                                class="avatar-2xs">
                        </div>
                        <div class="avatar avatar-md bg-body-secondary shadow-lg rounded-3 google">
                            <img src="assetsSignIn/images/social-icons/google.png" alt="Avatar Image" class="avatar-2xs">
                        </div>
                        <div class="avatar avatar-md bg-body-secondary shadow-lg rounded-3 instagram">
                            <img src="assetsSignIn/images/social-icons/instagram.png" alt="Avatar Image"
                                class="avatar-2xs">
                        </div>
                        <div class="avatar avatar-md bg-body-secondary shadow-lg rounded-3 facebook">
                            <img src="assetsSignIn/images/social-icons/facebook.png" alt="Avatar Image"
                                class="avatar-2xs">
                        </div>
                        <div class="avatar avatar-md bg-body-secondary shadow-lg rounded-3 apple">
                            <img src="assetsSignIn/images/social-icons/apple.png" alt="Avatar Image"
                                class="avatar-2xs dafault-img">
                            <img src="assetsSignIn/images/social-icons/apple-white.png" alt="Avatar Image"
                                class="avatar-2xs dark-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toast1" class="toast align-items-center text-white bg-primary border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">Hello, world! This is a toast message.</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            function toast(message, type = 'primary') {
                var toastEl = $('#toast1');
                toastEl.removeClass(
                        'bg-primary bg-secondary bg-success bg-danger bg-warning bg-info bg-light bg-dark')
                    .addClass('bg-' + type);
                toastEl.find('.toast-body').text(message);
                var toast = new bootstrap.Toast(toastEl[0]);
                toast.show();
            }

            $('#signin-form').on('submit', function(e) {
                e.preventDefault();
                var data = {
                    username: $(this).find('input[name="username"]').val(),
                    password: $(this).find('input[name="password"]').val(),
                };
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/signin',
                    method: 'POST',
                    data: data,
                    success: function(res) {
                        toast(res.message || 'Signed in successfully', 'success');
                        if (res.redirect) window.location.href = res.redirect;
                    },
                    error: function(xhr) {
                        var msg = 'Sign in failed';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON
                            .message;
                        toast(msg, 'danger');
                    }
                });
            });
        })
    </script>
@endsection
