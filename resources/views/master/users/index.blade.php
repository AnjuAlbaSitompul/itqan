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


                                <select class="form-select modern-select select2" id="tamatan" name="tamatan">

                                    <option value="">
                                        Pilih Tamatan
                                    </option>

                                    <option value="SMA">
                                        SMA
                                    </option>

                                    <option value="D3">
                                        D3
                                    </option>

                                    <option value="S1">
                                        S1
                                    </option>

                                    <option value="S2">
                                        S2
                                    </option>

                                    <option value="S3">
                                        S3
                                    </option>
                                </select>
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

                                    <input type="text" id="domisili" name="domisili" placeholder="Masukkan domisili">

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

                                <select class="form-select modern-select select2" id="golongan" name="golongan">

                                    <option value="">
                                        Pilih Golongan
                                    </option>

                                    @php
                                        $golonganRomawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX'];
                                        $golonganHuruf = ['A', 'B', 'C', 'D', 'E'];
                                    @endphp

                                    @foreach ($golonganRomawi as $romawi)
                                        @foreach ($golonganHuruf as $huruf)
                                            <option value="{{ $romawi . $huruf }}">{{ $romawi . $huruf }}</option>
                                        @endforeach
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

                            <button type="button" class="btn btn-success rounded-pill px-5 py-2 fw-semibold shadow-sm"
                                style="display: none;" id="updateButton">

                                <i class="fe fe-check-circle me-2"></i>
                                Update User

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- BULK IMPORT CARD -->
        <div class="col-12 mt-4">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- HEADER -->
                <div class="card-header bulk-header p-4 border-0">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>
                            <h3 class="fw-bold text-white mb-1">
                                Bulk Import User
                            </h3>

                            <p class="text-white-50 mb-0">
                                Upload file Excel (.xlsx) untuk membuat banyak user sekaligus
                            </p>
                        </div>

                        <a href="{{ route('user.template') }}" class="btn btn-light rounded-pill px-4 fw-semibold">

                            <i class="fe fe-download me-2"></i>
                            Download Template

                        </a>

                    </div>

                </div>

                <!-- BODY -->
                <div class="card-body p-4">

                    <form id="bulkImportForm" enctype="multipart/form-data">

                        <div class="upload-area" id="uploadArea">

                            <input type="file" id="xlsxFile" name="file" accept=".xlsx,.xls" hidden>

                            <div class="upload-content">

                                <div class="upload-icon">
                                    <i class="fe fe-upload-cloud"></i>
                                </div>

                                <h5 class="fw-bold mb-2">
                                    Drag & Drop File Excel
                                </h5>

                                <p class="text-muted mb-3">
                                    atau klik area ini untuk memilih file
                                </p>

                                <button type="button" class="btn btn-primary rounded-pill px-4" id="chooseFile">

                                    Pilih File

                                </button>

                            </div>

                        </div>

                        <!-- FILE INFO -->
                        <div class="selected-file mt-4 d-none">

                            <div class="file-card">

                                <div class="file-icon">
                                    <i class="fe fe-file-text"></i>
                                </div>

                                <div class="flex-grow-1">

                                    <h6 class="mb-1 file-name">
                                        -
                                    </h6>

                                    <small class="text-muted file-size">
                                        -
                                    </small>

                                </div>

                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" id="removeFile">

                                    Remove

                                </button>

                            </div>

                        </div>

                        <!-- ACTION -->
                        <div class="d-flex justify-content-end mt-4">

                            <button type="submit" class="btn btn-success rounded-pill px-5">

                                <i class="fe fe-upload me-2"></i>
                                Import User

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
        .bulk-header {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
        }

        .upload-area {
            border: 2px dashed #dbeafe;
            border-radius: 20px;
            min-height: 260px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: .3s;
            background: #f8fbff;
        }

        .upload-area:hover {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .upload-area.dragover {
            border-color: #2563eb;
            background: #dbeafe;
        }

        .upload-content {
            text-align: center;
        }

        .upload-icon {
            width: 90px;
            height: 90px;
            margin: auto;
            border-radius: 50%;
            background: rgba(37, 99, 235, .1);
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            margin-bottom: 20px;
        }

        .file-card {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            background: #fff;
        }

        .file-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            background: rgba(16, 185, 129, .12);
            color: #10b981;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

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
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#uploadArea')
                .on('dragover', function (e) {
                    e.preventDefault();
                    $(this).addClass('dragover');
                })
                .on('dragleave', function () {
                    $(this).removeClass('dragover');
                })
                .on('drop', function (e) {

                    e.preventDefault();

                    $(this).removeClass('dragover');

                    const files = e.originalEvent.dataTransfer.files;

                    if (files.length) {

                        $('#xlsxFile')[0].files = files;
                        $('#xlsxFile').trigger('change');

                    }

                });

            $('#chooseFile').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                document.getElementById('xlsxFile').click();
            });

            $('#uploadArea').on('click', function (e) {

                if ($(e.target).is('#xlsxFile')) {
                    return;
                }

                document.getElementById('xlsxFile').click();
            });

            $('#xlsxFile').on('change', function () {

                const file = this.files[0];

                if (!file) return;

                const ext = file.name.split('.').pop().toLowerCase();

                if (!['xlsx', 'xls'].includes(ext)) {

                    swal({
                        type: 'error',
                        title: 'File Tidak Valid',
                        text: 'Hanya file Excel (.xlsx atau .xls)'
                    });

                    $(this).val('');

                    return;
                }

                $('.selected-file').removeClass('d-none');

                $('.file-name').text(file.name);

                $('.file-size').text(
                    (file.size / 1024).toFixed(2) + ' KB'
                );

            });
            $('#removeFile').on('click', function () {

                $('#xlsxFile').val('');

                $('.selected-file').addClass('d-none');

            });

            $('#bulkImportForm').submit(function (e) {

                e.preventDefault();

                let file = $('#xlsxFile')[0].files[0];

                if (!file) {

                    swal({
                        type: 'warning',
                        title: 'Pilih File',
                        text: 'Silakan pilih file Excel terlebih dahulu'
                    });

                    return;
                }

                let formData = new FormData(this);

                $.ajax({

                    url: '/user/import',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function (res) {

                        swal({
                            type: 'success',
                            title: 'Berhasil',
                            text: res.message
                        });

                        $('#userTable').DataTable().ajax.reload();

                    },

                    error: function (err) {
                        console.error(err.responseJSON?.error)
                        swal({
                            type: 'error',
                            title: 'Error',
                            text: err.responseJSON?.error
                        });

                    }

                });
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
                    data: 'golongan'
                },
                {
                    data: 'action'
                }
                ]

            });


            window.deleteUser = function (userId) {

                swal({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }, function (isConfirm) {

                    if (isConfirm) {

                        $.ajax({

                            url: `/user/delete/${userId}`,
                            type: 'delete',
                            success: function (res) {
                                swal({
                                    type: 'success',
                                    title: 'Deleted',
                                    text: res.message
                                });

                                $('#userTable').DataTable().ajax.reload();

                            },

                            error: function (err) {

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

            window.editUser = function (userId) {
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
                $('#golongan')
                    .val(data.golongan)
                    .trigger('change');
                $('#jenis_kelamin')
                    .val(data.jenis_kelamin)
                    .trigger('change');
                $('#tanggal_lahir').val(data.tanggal_lahir);
                $('#tanggal_masuk').val(data.tanggal_masuk);
                $('#nip').val(data.nip);
                $('#tamatan')
                    .val(data.tamatan)
                    .trigger('change');
                $('#domisili').val(data.domisili);
                $('#tipe_bpjs')
                    .val(data.tipe_bpjs).trigger('change');
                $('#alamat').val(data.alamat);

            };

            $('#userForm').on('submit', function (e) {

                e.preventDefault();

                let data = $(this).serializeArray();
                console.log(data);

                $.post('/user/create', data)
                    .done(function (res) {

                        swal({
                            type: 'success',
                            title: 'Berhasil',
                            text: res.message ??
                                'User berhasil disimpan'
                        });

                        $('#userForm')[0].reset();
                        $('#userTable').DataTable().ajax.reload();

                    })

                    .fail(function (xhr) {
                        $('.invalid-feedback').remove();
                        $('.is-invalid').removeClass('is-invalid');

                        if (xhr.status === 422) {

                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function (field, messages) {

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
                $('#golongan').val('').trigger('change');
                $('#jenis_kelamin').val('').trigger('change');
                $('#tanggal_lahir').val('');
                $('#tanggal_masuk').val('');
                $('#nip').val('');
                $('#domisili').val('');
                $('#tipe_bpjs').val('').trigger('change');
                $('#alamat').val('');
                $('#tamatan').val('').trigger('change');
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
            }

            $('#btnReset').click(function () {
                resetForm();
            });

            $('#updateButton').click(function () {
                let userId = $(this).data('id');
                let name = $('#name').val();
                let username = $('#username').val();
                let password = $('#password').val();
                let role = $('#role').val();
                let outlet = $('#outlet_id').val();
                let data = $('#userForm').serializeArray();
                if (
                    name === '' ||
                    username === '' ||
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

                    data: data

                })

                    .done(function (res) {
                        resetForm();
                        $('#userTable').DataTable().ajax.reload();

                        swal({
                            type: 'success',
                            title: 'Berhasil',
                            text: res.message ??
                                'User berhasil diupdate'
                        });
                    })
                    .fail(function (err) {
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