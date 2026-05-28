@extends('layouts.app')

@section('content')
    <div class="row">

        <!-- FORM CARD -->
        <div class="col-12">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- HEADER -->
                <div class="card-header border-0 p-4 user-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>
                            <h3 class="fw-bold text-white mb-1">
                                User Management
                            </h3>

                            <p class="text-white-50 mb-0">
                                Kelola data user dan role permissions
                            </p>
                        </div>

                        <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" id="btnReset">

                            <i class="fe fe-refresh-ccw me-2"></i>
                            Reset Form

                        </button>

                    </div>

                </div>

                <!-- BODY -->
                <div class="card-body p-4">

                    <form id="userForm">

                        <!-- FORM -->
                        <div class="row g-4">

                            <!-- FULL NAME -->
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    Full Name
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-user"></i>
                                    </span>

                                    <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap">

                                </div>

                            </div>

                            <!-- USERNAME -->
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    Username
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-at-sign"></i>
                                    </span>

                                    <input type="text" id="username" name="username" placeholder="Masukkan username">

                                </div>

                            </div>

                            <!-- PASSWORD -->
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    Password
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-lock"></i>
                                    </span>

                                    <input type="password" id="password" name="password" placeholder="Masukkan password">

                                </div>

                            </div>

                            <!-- ROLE -->
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    Role Permissions
                                </label>

                                <select class="form-select modern-select select2" id="role" name="role">

                                    <option value="">
                                        Pilih Role
                                    </option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" data-name="{{ strtolower($role->name) }}">

                                            {{ Str::of($role->name)->replace('_', ' ')->upper() }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            <!-- OUTLET -->
                            <div class="col-xl-4 col-lg-6 d-none" id="outletWrapper">

                                <label class="form-label fw-semibold mb-2">
                                    Outlet
                                </label>

                                <select class="form-select modern-select select2" id="outlet_id" name="outlet_id">

                                    <option value="">
                                        Pilih Outlet
                                    </option>

                                    @foreach ($outlet as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            <!-- SECTION TITLE -->
                            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">

                                <div>

                                    <h4 class="fw-bold mb-1">
                                        User & Profile Information
                                    </h4>

                                    <p class="text-muted mb-0">
                                        Lengkapi data user dan profile pegawai
                                    </p>

                                </div>

                                <div class="profile-badge">

                                    <i class="fe fe-user-check me-2"></i>
                                    Employee Form

                                </div>

                            </div>



                            <!-- NIP -->
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    NIP
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-credit-card"></i>
                                    </span>

                                    <input type="text" id="nip" name="nip" placeholder="Masukkan NIP">

                                </div>

                            </div>

                            <!-- JENIS KELAMIN -->
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    Jenis Kelamin
                                </label>

                                <select class="form-select modern-select select2" id="jenis_kelamin" name="jenis_kelamin">

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
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    Tanggal Lahir
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-calendar"></i>
                                    </span>

                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir">

                                </div>

                            </div>

                            <!-- TANGGAL MASUK -->
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    Tanggal Masuk
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-calendar"></i>
                                    </span>

                                    <input type="date" id="tanggal_masuk" name="tanggal_masuk">

                                </div>

                            </div>

                            <!-- TAMATAN -->
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    Tamatan
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-book-open"></i>
                                    </span>

                                    <input type="text" id="tamatan" name="tamatan"
                                        placeholder="Contoh: S1 Teknik Informatika">

                                </div>

                            </div>

                            <!-- DOMISILI -->
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    Domisili
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-map-pin"></i>
                                    </span>

                                    <input type="text" id="domisili" name="domisili"
                                        placeholder="Masukkan domisili">

                                </div>

                            </div>

                            <!-- TIPE BPJS -->
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    Tipe BPJS
                                </label>

                                <select class="form-select modern-select select2" id="tipe_bpjs" name="tipe_bpjs">

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
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    Golongan
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-award"></i>
                                    </span>

                                    <input type="text" id="golongan" name="golongan"
                                        placeholder="Masukkan golongan">

                                </div>

                            </div>

                            <!-- UNIT -->
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    Unit
                                </label>

                                <select class="form-select modern-select select2" id="unit_id" name="unit_id">

                                    <option value="">
                                        Pilih Unit
                                    </option>

                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            <!-- JABATAN -->
                            <div class="col-xl-4 col-lg-6">

                                <label class="form-label fw-semibold mb-2">
                                    Jabatan
                                </label>

                                <select class="form-select modern-select select2" id="jabatan_id" name="jabatan_id">

                                    <option value="">
                                        Pilih Jabatan
                                    </option>

                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}">
                                            {{ $jabatan->name }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            <!-- ALAMAT -->
                            <div class="col-12">

                                <label class="form-label fw-semibold mb-2">
                                    Alamat
                                </label>

                                <textarea class="form-control modern-textarea" rows="4" id="alamat" name="alamat"
                                    placeholder="Masukkan alamat lengkap"></textarea>

                            </div>

                        </div>

                        <!-- ACTION -->
                        <div class="d-flex justify-content-end gap-2 mt-5">

                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-semibold shadow-sm"
                                id="submitButton">

                                <i class="fe fe-save me-2"></i>
                                Save User

                            </button>

                            <button type="button"
                                class="btn btn-success rounded-pill px-5 py-2 fw-semibold shadow-sm d-none"
                                id="updateButton">

                                <i class="fe fe-check-circle me-2"></i>
                                Update User

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- TABLE -->
        <div class="col-12 mt-4">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <div class="card-header bg-white border-0 p-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>

                            <h3 class="fw-bold mb-1">
                                User List
                            </h3>

                            <p class="text-muted mb-0">
                                Data seluruh user yang terdaftar di sistem
                            </p>

                        </div>

                    </div>

                </div>

                <div class="card-body p-4">

                    <div class="table-responsive">
                        <table class="table align-middle table-hover custom-table" id="userTable" width="100%">

                            <thead>

                                <tr>
                                    <th width="5%">#</th>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Outlet</th>
                                    <th width="15%">Action</th>
                                </tr>

                            </thead>

                            <tbody></tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- STYLE -->
    <style>
        .user-header {
            background: linear-gradient(135deg, #6259ca 0%, #867efc 100%);
        }

        .modern-input {
            height: 52px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            display: flex;
            align-items: center;
            overflow: hidden;
            transition: .3s;
            background: #fff;
        }

        .modern-input:focus-within {
            border-color: #6259ca;
            box-shadow: 0 0 0 4px rgba(98, 89, 202, .12);
        }

        .modern-input span {
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6259ca;
            font-size: 16px;
        }

        .modern-input input {
            border: none;
            outline: none;
            width: 100%;
            height: 100%;
            padding-right: 16px;
            background: transparent;
        }

        .modern-select {
            height: 52px;
            border-radius: 14px !important;
            border: 1px solid #e5e7eb !important;
        }

        .select2-container--default .select2-selection--single {
            height: 52px !important;
            border-radius: 14px !important;
            border: 1px solid #e5e7eb !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 52px !important;
            padding-left: 14px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 52px !important;
        }

        .custom-table thead th {
            background: #f8fafc;
            border-bottom: none !important;
            padding: 16px;
            font-size: 13px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
        }

        .custom-table tbody td {
            padding: 16px;
            vertical-align: middle;
        }

        .custom-table tbody tr {
            transition: .2s;
        }

        .custom-table tbody tr:hover {
            background: #f8fafc;
        }

        .dataTables_filter input {
            border-radius: 12px !important;
            border: 1px solid #e5e7eb !important;
            padding: 8px 14px !important;
        }

        .dataTables_length select {
            border-radius: 10px !important;
            border: 1px solid #e5e7eb !important;
        }

        .profile-badge {
            background: rgba(98, 89, 202, .1);
            color: #6259ca;
            padding: 10px 18px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 13px;
        }

        .modern-textarea {
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            padding: 16px;
            resize: none;
            transition: .3s;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .03);
        }

        .modern-textarea:focus {
            border-color: #6259ca;
            box-shadow: 0 0 0 4px rgba(98, 89, 202, .12);
        }

        .row.g-4>div {
            animation: fadeInUp .3s ease;
        }

        @keyframes fadeInUp {

            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- SCRIPT -->
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function toggleOutlet() {

                let roleName = $('#role option:selected')
                    .data('name');

                if (
                    roleName === 'spv' ||
                    roleName === 'pegawai' ||
                    roleName === 'manager'
                ) {

                    $('#outletWrapper').removeClass('d-none');

                } else {

                    $('#outletWrapper').addClass('d-none');

                    $('#outlet_id').val('').trigger('change');

                }
            }

            toggleOutlet();

            $('#role').on('change', function() {
                toggleOutlet();
            });

            $('.select2').select2({
                width: '100%'
            });

            let table = $('#userTable').DataTable({

                processing: true,
                serverSide: false,

                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search user..."
                },

                ajax: {
                    url: '/user',
                    type: 'GET',
                    dataSrc: ''
                },

                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'username'
                    },
                    {
                        data: 'role'
                    },
                    {
                        data: 'outlet'
                    },
                    {
                        data: 'action'
                    }
                ]

            });


            window.deleteUser = function(userId) {

                swal({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }, function(isConfirm) {

                    if (isConfirm) {

                        $.ajax({

                            url: `/user/delete/${userId}`,
                            type: 'delete',
                            success: function(res) {
                                swal({
                                    type: 'success',
                                    title: 'Deleted',
                                    text: res.message
                                });

                                $('#userTable').DataTable().ajax.reload();

                            },

                            error: function(err) {

                                swal({
                                    type: 'error',
                                    title: 'Error',
                                    text: err.responseJSON?.message ??
                                        'An error occurred.'
                                });

                            }

                        });

                    }

                });
            };

            window.editUser = function(userId) {
                $('#updateButton').attr('data-id', userId);
                $('#submitButton').hide();
                $('#updateButton').show();
                let data = table
                    .rows()
                    .data()
                    .toArray()
                    .find(item => Number(item.id) === Number(userId));

                if (!data) {

                    swal({
                        type: 'error',
                        title: 'Error',
                        text: 'User data not found.'
                    });

                    return;
                }

                $('#name').val(data.name);

                $('#username').val(data.username);

                $('#password').val('');

                $('#role')
                    .val(data.role_id)
                    .trigger('change');

                $('#outlet_id')
                    .val(data.outlet_id)
                    .trigger('change');
            };

            $('#userForm').on('submit', function(e) {

                e.preventDefault();

                let data = $(this).serializeArray();
                console.log(data);
                let roleName = $('#role option:selected')
                    .data('name');

                $.post('/user/create', data)
                    .done(function(res) {

                        swal({
                            type: 'success',
                            title: 'Berhasil',
                            text: res.message ??
                                'User berhasil disimpan'
                        });

                        $('#userForm')[0].reset();
                        $('#userTable').DataTable().ajax.reload();

                        $('#role').val('').trigger('change');

                    })

                    .fail(function(xhr) {
                        $('.invalid-feedback').remove();
                        $('.is-invalid').removeClass('is-invalid');

                        if (xhr.status === 422) {

                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function(field, messages) {

                                let input = $(`[name="${field}"]`);

                                input.addClass('is-invalid');

                                input.after(`
                        <div class="invalid-feedback">
                            ${messages[0]}
                        </div>
                    `);
                            });
                        }
                        swal({
                            type: 'error',
                            title: 'Error',
                            text: err.responseJSON?.message ??
                                'Terjadi kesalahan'
                        });

                    });

            });

            function resetForm() {
                $('#userForm')[0].reset();
                $('#submitButton').show();
                $('#updateButton').hide();
                $('#role').val('').trigger('change');
            }

            $('#btnReset').click(function() {
                resetForm();
            });

            $('#updateButton').click(function() {
                let userId = $(this).data('id');
                let name = $('#name').val();
                let username = $('#username').val();
                let password = $('#password').val();
                let role = $('#role').val();
                let outlet = $('#outlet_id').val();

                if (
                    name === '' ||
                    username === '' ||
                    password === '' ||
                    role === ''
                ) {
                    swal({
                        type: 'warning',
                        title: 'Oops...',
                        text: 'Semua field wajib diisi'
                    });

                    return;
                }
                $.ajax({

                        url: '/user/update/' + userId,

                        type: 'PATCH',

                        data: {
                            name: name,
                            username: username,
                            password: password,
                            role: role,
                            outlet_id: outlet,
                            _token: '{{ csrf_token() }}'
                        }

                    })

                    .done(function(res) {
                        resetForm();
                        $('#userTable').DataTable().ajax.reload();

                        swal({
                            type: 'success',
                            title: 'Berhasil',
                            text: res.message ??
                                'User berhasil diupdate'
                        });
                    })
                    .fail(function(err) {
                        swal({
                            type: 'error',
                            title: 'Error',
                            text: err.responseJSON?.message ??
                                'Terjadi kesalahan'
                        });
                    });
            });
        });
    </script>
@endsection
