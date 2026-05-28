@extends('layouts.signin')

@section('content')
    <div class="row gx-0 vh-100 overflow-hidden">

        <!-- LEFT SIDE -->
        <div class="col-xl-5 h-100">

            <div
                class="signup-scroll-container
            bg-body-secondary
            position-relative
            h-100
            overflow-auto">

                <div class="row justify-content-center py-5 px-4">

                    <div class="col-md-10 col-lg-9 col-xl-10">

                        <!-- LOGO -->
                        <a href="/" class="text-nowrap d-block mb-4">

                            <img src="{{ asset('assetsSignIn/images/logo-sm.png') }}" class="dark-logo" height="38"
                                alt="Logo">

                        </a>

                        <!-- TITLE -->
                        <div class="mb-4">

                            <h2 class="fw-bold mb-2">
                                Create Account
                            </h2>

                            <p class="text-muted mb-0">
                                Lengkapi data akun dan profile pegawai
                            </p>

                        </div>

                        <form id="signup-form" class="row g-4">

                            @csrf

                            <!-- ACCOUNT SECTION -->
                            <div class="col-12">

                                <div class="d-flex align-items-center justify-content-between mb-2">

                                    <div>

                                        <h4 class="fw-bold mb-1">
                                            Account Information
                                        </h4>

                                        <p class="text-muted mb-0">
                                            Lengkapi informasi akun anda
                                        </p>

                                    </div>

                                    <span class="badge rounded-pill bg-primary-transparent text-primary px-3 py-2">
                                        Account
                                    </span>

                                </div>

                            </div>

                            <!-- NAME -->
                            <div class="col-xl-12">

                                <label class="form-label fw-semibold">
                                    Full Name
                                </label>

                                <div class="input-group modern-group">

                                    <span class="input-group-text">
                                        <i class="far fa-user"></i>
                                    </span>

                                    <input type="text" class="form-control" name="name"
                                        placeholder="Masukkan nama lengkap">

                                </div>

                            </div>

                            <!-- USERNAME -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Username
                                </label>

                                <div class="input-group modern-group">

                                    <span class="input-group-text">
                                        @
                                    </span>

                                    <input type="text" class="form-control" name="username"
                                        placeholder="Masukkan username">

                                </div>

                            </div>

                            <!-- PASSWORD -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Password
                                </label>

                                <div class="input-group modern-group">

                                    <span class="input-group-text">
                                        <i class="far fa-eye"></i>
                                    </span>

                                    <input type="password" class="form-control" name="password"
                                        placeholder="Masukkan password">

                                </div>

                            </div>

                            <!-- CONFIRM PASSWORD -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Confirm Password
                                </label>

                                <div class="input-group modern-group">

                                    <span class="input-group-text">
                                        <i class="far fa-check-circle"></i>
                                    </span>

                                    <input type="password" class="form-control" name="password_confirmation"
                                        placeholder="Konfirmasi password">

                                </div>

                            </div>


                            <!-- PROFILE SECTION -->
                            <div class="col-12 mt-2">

                                <div class="d-flex align-items-center justify-content-between mb-2">

                                    <div>

                                        <h4 class="fw-bold mb-1">
                                            Employee Profile
                                        </h4>

                                        <p class="text-muted mb-0">
                                            Lengkapi data profile pegawai
                                        </p>

                                    </div>

                                    <span class="badge rounded-pill bg-success-transparent text-success px-3 py-2">
                                        Profile
                                    </span>

                                </div>

                            </div>


                            <!-- NIP -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    NIP
                                </label>

                                <div class="input-group modern-group">

                                    <span class="input-group-text">
                                        <i class="far fa-credit-card"></i>
                                    </span>

                                    <input type="text" class="form-control" name="nip" placeholder="Masukkan NIP">

                                </div>

                            </div>

                            <!-- GENDER -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Jenis Kelamin
                                </label>

                                <select class="form-select modern-select select2" name="jenis_kelamin">

                                    <option value="">
                                        Pilih Jenis Kelamin
                                    </option>

                                    <option value="L">
                                        Laki-Laki
                                    </option>

                                    <option value="P">
                                        Perempuan
                                    </option>

                                </select>

                            </div>

                            <!-- TANGGAL LAHIR -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Tanggal Lahir
                                </label>

                                <div class="input-group modern-group">

                                    <span class="input-group-text">
                                        <i class="far fa-calendar"></i>
                                    </span>

                                    <input type="date" class="form-control" name="tanggal_lahir">

                                </div>

                            </div>
                            <!-- TANGGAL MASUK -->
                            {{-- <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Tanggal Masuk
                                </label>

                                <div class="input-group modern-group">

                                    <span class="input-group-text">
                                        <i class="far fa-calendar"></i>
                                    </span>

                                    <input type="date" class="form-control" name="tanggal_masuk">

                                </div>

                            </div> --}}

                            <!-- TAMATAN -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Tamatan
                                </label>

                                <div class="input-group modern-group">

                                    <span class="input-group-text">
                                        <i class="far fa-file-alt"></i>
                                    </span>

                                    <input type="text" class="form-control" name="tamatan"
                                        placeholder="Contoh: S1 Teknik Informatika">

                                </div>

                            </div>

                            <!-- DOMISILI -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Domisili
                                </label>

                                <div class="input-group modern-group">

                                    <span class="input-group-text">
                                        <i class="far fa-map"></i>
                                    </span>

                                    <input type="text" class="form-control" name="domisili"
                                        placeholder="Masukkan domisili">

                                </div>

                            </div>

                            {{-- <!-- BPJS -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Tipe BPJS
                                </label>

                                <select class="form-select modern-select select2" name="tipe_bpjs">

                                    <option value="">
                                        Pilih Tipe BPJS
                                    </option>

                                    <option value="Kesehatan">
                                        Kesehatan
                                    </option>

                                    <option value="Ketenagakerjaan">
                                        Ketenagakerjaan
                                    </option>

                                </select>

                            </div>

                            <!-- GOLONGAN -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Golongan
                                </label>

                                <div class="input-group modern-group">

                                    <span class="input-group-text">
                                        <i class="far fa-id-card"></i>
                                    </span>

                                    <input type="text" class="form-control" name="golongan"
                                        placeholder="Masukkan golongan">

                                </div>

                            </div> --}}

                            <!-- ALAMAT -->
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Alamat
                                </label>

                                <textarea class="form-control modern-textarea" rows="4" name="alamat" placeholder="Masukkan alamat lengkap"></textarea>

                            </div>

                            <!-- SUBMIT -->
                            <div class="col-12 mt-3">

                                <button type="submit"
                                    class="btn btn-primary w-100 py-3 rounded-pill fw-semibold shadow-sm">

                                    <i class="far fa-user-plus me-2"></i>

                                    Create Account

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="col-xl-7 d-none d-xl-block h-100">
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
                        <img src="assetsSignIn/images/social-icons/linkedin.png" alt="Avatar Image" class="avatar-2xs">
                    </div>
                    <div class="avatar avatar-md bg-body-secondary shadow-lg rounded-3 google">
                        <img src="assetsSignIn/images/social-icons/google.png" alt="Avatar Image" class="avatar-2xs">
                    </div>
                    <div class="avatar avatar-md bg-body-secondary shadow-lg rounded-3 instagram">
                        <img src="assetsSignIn/images/social-icons/instagram.png" alt="Avatar Image" class="avatar-2xs">
                    </div>
                    <div class="avatar avatar-md bg-body-secondary shadow-lg rounded-3 facebook">
                        <img src="assetsSignIn/images/social-icons/facebook.png" alt="Avatar Image" class="avatar-2xs">
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
    <style>
        .signup-scroll-container {
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
        }

        .signup-scroll-container::-webkit-scrollbar {
            width: 6px;
        }

        .signup-scroll-container::-webkit-scrollbar-thumb {
            background: rgba(98, 89, 202, .3);
            border-radius: 999px;
        }

        .fixed-right-section {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow: hidden;

            background:
                linear-gradient(135deg,
                    #6259ca 0%,
                    #867efc 100%);
        }

        .hero-overlay {
            position: absolute;
            inset: 0;

            background:
                radial-gradient(circle at top right,
                    rgba(255, 255, 255, .18),
                    transparent 30%);
        }

        .hero-content {
            position: relative;
            z-index: 2;

            height: 100%;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            padding: 60px;

            color: white;
        }

        .hero-title {
            font-size: 64px;
            font-weight: 800;
        }

        .hero-description {
            max-width: 500px;
            font-size: 17px;
            opacity: .9;
        }

        .hero-badge {
            padding: 10px 20px;
            border-radius: 999px;

            background: rgba(255, 255, 255, .15);

            backdrop-filter: blur(10px);

            font-weight: 600;
        }

        .modern-input {
            height: 54px;

            border-radius: 16px;
            border: 1px solid #e5e7eb;

            overflow: hidden;

            display: flex;
            align-items: center;

            transition: .3s;

            background: white;
        }

        .modern-input:focus-within {
            border-color: #6259ca;
            box-shadow: 0 0 0 4px rgba(98, 89, 202, .12);
        }

        .modern-input span {
            width: 54px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #6259ca;
        }

        .modern-input input {
            border: none;
            outline: none;

            width: 100%;
            height: 100%;

            background: transparent;

            padding-right: 16px;
        }

        .modern-select,
        .modern-textarea {
            border-radius: 16px !important;
            border: 1px solid #e5e7eb !important;
        }

        .modern-textarea {
            padding: 16px;
            resize: none;
        }

        .modern-textarea:focus,
        .modern-select:focus {
            border-color: #6259ca !important;
            box-shadow: 0 0 0 4px rgba(98, 89, 202, .12) !important;
        }

        .select2-container--default .select2-selection--single {
            height: 54px !important;

            border-radius: 16px !important;
            border: 1px solid #e5e7eb !important;

            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection__rendered {
            line-height: 54px !important;
            padding-left: 14px !important;
        }

        .select2-container--default .select2-selection__arrow {
            height: 54px !important;
        }

        @media (max-width: 1199px) {

            .signup-scroll-container {
                height: auto;
            }

            .fixed-right-section {
                position: relative;
                height: auto;
            }
        }
    </style>

    <script>
        $(document).ready(function() {

            // $('.select2').select2({
            //     width: '100%'
            // });

            function resetForm() {
                $('#signup-form')[0].reset();
            }

            function toast(message, type = 'primary') {
                var toastEl = $('#toast1');
                toastEl.removeClass(
                        'bg-primary bg-secondary bg-success bg-danger bg-warning bg-info bg-light bg-dark')
                    .addClass('bg-' + type);
                toastEl.find('.toast-body').text(message);
                var toast = new bootstrap.Toast(toastEl[0]);
                toast.show();
            }

            $('#signup-form').submit(function(e) {

                e.preventDefault();

                let button = $(this).find('button[type="submit"]');

                button.prop('disabled', true);

                $.ajax({
                    url: '/signup',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        toast(
                            res.message ||
                            'Account created successfully'
                        );
                        resetForm();
                    },
                    error: function(xhr) {
                        resetForm();
                        toast(
                            xhr.responseJSON?.message ||
                            'Registration failed',
                            'danger'
                        );
                    },

                    complete: function() {

                        button.prop('disabled', false);
                    }
                });
            });

        });
    </script>
@endsection
