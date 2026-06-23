@extends('layouts.app')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="fw-bold mb-1">User Management</h2>
                        <p class="mb-0" style="opacity: 0.7;">Kelola data lengkap user, role, dan profile pegawai</p>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-outline-secondary shadow-sm rounded-pill px-4 fw-semibold"
                            data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fe fe-upload me-2 text-primary"></i> Bulk Import
                        </button>
                        <button type="button" class="btn btn-primary shadow-sm rounded-pill px-4 fw-semibold"
                            id="btnAddUser">
                            <i class="fe fe-plus me-2"></i> Add New User
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle custom-table dt-responsive nowrap" id="userTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="3%"></th>
                                    <th width="3%">No</th>
                                    <th>NIP</th>
                                    <th>Full Name</th>
                                    <th>Status User</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Golongan</th>
                                    <th>Gender</th>
                                    <th>Tgl Lahir</th>
                                    <th>Tgl Masuk</th>
                                    <th>Pendidikan</th>
                                    <th>BPJS</th>
                                    <th>Domisili</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header user-header border-0 p-4">
                    <h4 class="modal-title fw-bold text-white mb-0" id="modalTitle">Add New User</h4>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 p-md-5">
                    <div class="wizard-progress position-relative mb-5">
                        <div class="progress position-absolute w-100"
                            style="height: 4px; top: 50%; transform: translateY(-50%); z-index: 1;">
                            <div class="progress-bar bg-primary transition-all" id="wizardProgressBar" style="width: 0%">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between position-relative" style="z-index: 2;">
                            <div class="step-circle active" id="step-circle-1"><i class="fe fe-user"></i></div>
                            <div class="step-circle" id="step-circle-2"><i class="fe fe-info"></i></div>
                            <div class="step-circle" id="step-circle-3"><i class="fe fe-map-pin"></i></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 fw-semibold" style="font-size: 12px; opacity: 0.7;">
                            <span>Account Details</span>
                            <span>Employee Profile</span>
                            <span>Additional Info</span>
                        </div>
                    </div>

                    <form id="userForm">
                        <div class="wizard-step" id="step-1">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">Full Name</label>
                                    <div class="modern-input">
                                        <span><i class="fe fe-user"></i></span>
                                        <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">Username</label>
                                    <div class="modern-input">
                                        <span><i class="fe fe-at-sign"></i></span>
                                        <input type="text" id="username" name="username" placeholder="Masukkan username">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">Password</label>
                                    <div class="modern-input">
                                        <span><i class="fe fe-lock"></i></span>
                                        <input type="password" id="password" name="password"
                                            placeholder="Masukkan password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">Role Permissions</label>
                                    <select class="form-select modern-select select2" id="role" name="role">
                                        <option value="">Pilih Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">
                                                {{ Str::of($role->name)->replace('_', ' ')->upper() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="wizard-step d-none" id="step-2">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">NIP</label>
                                    <div class="modern-input">
                                        <span><i class="fe fe-credit-card"></i></span>
                                        <input type="text" id="nip" name="nip" placeholder="Masukkan NIP">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">Jenis Kelamin</label>
                                    <select class="form-select modern-select select2" id="jenis_kelamin"
                                        name="jenis_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-Laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">Tanggal Lahir</label>
                                    <div class="modern-input">
                                        <span><i class="fe fe-calendar"></i></span>
                                        <input type="date" id="tanggal_lahir" name="tanggal_lahir">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">Tanggal Masuk</label>
                                    <div class="modern-input">
                                        <span><i class="fe fe-calendar"></i></span>
                                        <input type="date" id="tanggal_masuk" name="tanggal_masuk">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">Status</label>
                                    <select class="form-select modern-select select2" id="status" name="status">
                                        <option value="">Pilih Status</option>
                                        <option value="magang">Magang</option>
                                        <option value="contract">Contract</option>
                                        <option value="permanent">Permanent</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">Tanggal Berakhir <small
                                            class="text-muted">Kosongkan Jika Permanent</small></label>
                                    <div class="modern-input">
                                        <span><i class="fe fe-calendar"></i></span>
                                        <input type="date" id="due_date" name="due_date">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wizard-step d-none" id="step-3">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">Tamatan</label>
                                    <select class="form-select modern-select select2" id="tamatan" name="tamatan">
                                        <option value="">Pilih Tamatan</option>
                                        <option value="SMA">SMA</option>
                                        <option value="D3">D3</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">Domisili</label>
                                    <div class="modern-input">
                                        <span><i class="fe fe-map-pin"></i></span>
                                        <input type="text" id="domisili" name="domisili" placeholder="Masukkan domisili">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">Tipe BPJS</label>
                                    <select class="form-select modern-select select2" id="tipe_bpjs" name="tipe_bpjs">
                                        <option value="">Pilih Tipe BPJS</option>
                                        <option value="Kesehatan">Kesehatan</option>
                                        <option value="Ketenagakerjaan">Ketenagakerjaan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">Golongan</label>
                                    <select class="form-select modern-select select2" id="golongan" name="golongan">
                                        <option value="">Pilih Golongan</option>
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
                                <div class="col-12">
                                    <label class="form-label fw-semibold mb-2">Alamat Lengkap</label>
                                    <textarea class="form-control modern-textarea" rows="3" id="alamat" name="alamat"
                                        placeholder="Masukkan alamat lengkap"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" id="btnPrev"
                                style="display: none;">
                                <i class="fe fe-arrow-left me-2"></i> Previous
                            </button>

                            <div class="ms-auto d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary shadow-sm rounded-pill px-5 fw-semibold"
                                    id="btnNext">
                                    Next <i class="fe fe-arrow-right ms-2"></i>
                                </button>
                                <button type="submit" class="btn btn-success shadow-sm rounded-pill px-5 fw-semibold"
                                    id="submitButton" style="display: none;">
                                    <i class="fe fe-save me-2"></i> Save User
                                </button>
                                <button type="button" class="btn btn-success shadow-sm rounded-pill px-5 fw-semibold"
                                    id="updateButton" style="display: none;">
                                    <i class="fe fe-check-circle me-2"></i> Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bulk-header border-0 p-4">
                    <h4 class="modal-title fw-bold text-white mb-0">Bulk Import User</h4>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <p class="mb-0 small" style="opacity: 0.7;">Upload file Excel (.xlsx) untuk import massal.</p>
                        <a href="{{ route('user.template') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="fe fe-download me-1"></i> Template
                        </a>
                    </div>
                    <form id="bulkImportForm" enctype="multipart/form-data">
                        <div class="upload-area" id="uploadArea">
                            <input type="file" id="xlsxFile" name="file" accept=".xlsx,.xls" hidden>
                            <div class="upload-content">
                                <div class="upload-icon"><i class="fe fe-upload-cloud"></i></div>
                                <h6 class="fw-bold mb-1">Drag & Drop File Disini</h6>
                                <p class="small mb-3" style="opacity: 0.7;">atau klik area ini untuk memilih</p>
                                <button type="button" class="btn btn-primary rounded-pill px-4 btn-sm" id="chooseFile">Pilih
                                    File</button>
                            </div>
                        </div>
                        <div class="selected-file mt-3 d-none">
                            <div class="file-card p-3 border rounded-3 d-flex align-items-center gap-3 bg-body-tertiary">
                                <div class="file-icon bg-success text-white rounded p-2"><i class="fe fe-file-text"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="mb-0 text-truncate file-name">-</h6>
                                    <small class="file-size" style="opacity: 0.7;">-</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile"><i
                                        class="fe fe-trash"></i></button>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success rounded-pill py-2">
                                <i class="fe fe-upload me-2"></i> Proses Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .user-header {
            background: linear-gradient(135deg, #6259ca 0%, #867efc 100%);
        }

        .bulk-header {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
        }

        /* ----------------------------------------------------
                                                                                                                                       MEMAKSA WARNA TEXT MENGIKUTI TEMPLATE (INHERIT)
                                                                                                                                       ---------------------------------------------------- */
        body,
        label,
        h2,
        h6 {
            color: inherit;
        }

        /* Override paksa warna DataTables agar tidak jadi hitam (#333) */
        table.dataTable,
        table.dataTable th,
        table.dataTable td,
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: inherit !important;
        }

        /* WIZARD CSS */
        .step-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--bs-tertiary-bg);
            border: 1px solid var(--bs-border-color);
            color: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 0 0 5px var(--bs-body-bg);
            opacity: 0.6;
        }

        .step-circle.active {
            background: #6259ca;
            color: #fff;
            box-shadow: 0 0 0 5px rgba(98, 89, 202, 0.2);
            border-color: #6259ca;
            opacity: 1;
        }

        .wizard-step {
            animation: fadeIn 0.4s ease;
        }

        /* INPUT STYLES */
        .modern-input,
        .modern-select {
            height: 48px;
            border: 1px solid var(--bs-border-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            overflow: hidden;
            transition: .3s;
            background: var(--bs-tertiary-bg);
            color: inherit;
        }

        .modern-input:focus-within {
            border-color: #6259ca;
            background: var(--bs-body-bg);
            box-shadow: 0 0 0 3px rgba(98, 89, 202, .12);
        }

        .modern-input span {
            width: 45px;
            display: flex;
            justify-content: center;
            opacity: 0.7;
            font-size: 16px;
        }

        .modern-input input {
            border: none;
            outline: none;
            width: 100%;
            height: 100%;
            padding-right: 16px;
            background: transparent;
            font-size: 14px;
            color: inherit;
        }

        .modern-input input::placeholder {
            color: inherit;
            opacity: 0.5;
        }

        .modern-textarea {
            border-radius: 12px;
            border: 1px solid var(--bs-border-color);
            padding: 12px 16px;
            resize: none;
            background: var(--bs-tertiary-bg);
            color: inherit;
            transition: .3s;
            font-size: 14px;
        }

        .modern-textarea:focus {
            border-color: #6259ca;
            background: var(--bs-body-bg);
            box-shadow: 0 0 0 3px rgba(98, 89, 202, .12);
            outline: none;
        }

        .modern-textarea::placeholder {
            color: inherit;
            opacity: 0.5;
        }

        /* SELECT2 DARK MODE FIX */
        .select2-container--default .select2-selection--single {
            height: 48px !important;
            border-radius: 12px !important;
            border: 1px solid var(--bs-border-color) !important;
            display: flex !important;
            align-items: center !important;
            background-color: var(--bs-tertiary-bg) !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #6259ca !important;
            background-color: var(--bs-body-bg) !important;
            box-shadow: 0 0 0 3px rgba(98, 89, 202, .12) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 16px;
            font-size: 14px;
            color: inherit !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px !important;
            right: 10px !important;
        }

        .select2-dropdown {
            background-color: var(--bs-body-bg) !important;
            border: 1px solid var(--bs-border-color) !important;
            color: inherit !important;
        }

        .select2-results__option {
            color: inherit !important;
            background-color: transparent;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6259ca !important;
            color: white !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: var(--bs-tertiary-bg);
            border: 1px solid var(--bs-border-color);
            color: inherit;
            border-radius: 6px;
        }

        /* UPLOAD AREA */
        .upload-area {
            border: 2px dashed var(--bs-border-color);
            border-radius: 16px;
            padding: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: .3s;
            background-color: var(--bs-tertiary-bg);
        }

        .upload-area:hover,
        .upload-area.dragover {
            border-color: #6259ca;
            background-color: var(--bs-secondary-bg);
        }

        .upload-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background: rgba(37, 99, 235, .1);
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        /* ACTION BUTTONS IN TABLE */
        .btn-action {
            background-color: var(--bs-tertiary-bg);
            color: inherit;
            transition: .2s;
        }

        .btn-action.text-primary:hover {
            color: #0d6efd !important;
        }

        .btn-action.text-danger:hover {
            color: #dc3545 !important;
        }

        /* TABLE STYLES */
        .custom-table thead th {
            background: var(--bs-tertiary-bg);
            border-bottom: 2px solid var(--bs-border-color);
            padding: 14px 16px;
            font-size: 12px;
            text-transform: uppercase;
            opacity: 0.85;
            font-weight: 700;
            transition: 0.3s;
        }

        .custom-table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            font-size: 14px;
            border-color: var(--bs-border-color);
            transition: 0.3s;
        }

        .custom-table tbody tr:hover td {
            background: var(--bs-secondary-bg);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            $('.select2').select2({ width: '100%', dropdownParent: $('#userModal') });

            // ================== DATATABLES (SEMUA DATA) ==================
            let table = $('#userTable').DataTable({
                processing: true,
                serverSide: false,
                responsive: { details: { type: 'column', target: 0 } },
                columnDefs: [{ className: 'dtr-control', orderable: false, targets: 0 }],
                ajax: { url: '/user', type: 'GET', dataSrc: '' },
                columns: [
                    { data: null, defaultContent: '' },
                    { data: 'no' },
                    { data: 'nip' },
                    { data: 'name' },
                    {
                        data: 'user_status',
                        render: function (data, type, row) {
                            let badgeClass = '';
                            let icon = '';
                            switch (data) {
                                case true:
                                    badgeClass = 'bg-success';
                                    icon = 'fe fe-check-circle';
                                    break;
                                case false:
                                    badgeClass = 'bg-danger';
                                    icon = 'fe fe-x-circle';
                                    break;
                            }
                            return `<span class=""><i class="${icon}"></i></span>`;
                        }
                    },
                    { data: 'username' },
                    { data: 'role' },
                    { data: 'golongan' },
                    { data: 'jenis_kelamin' },
                    { data: 'tanggal_lahir' },
                    { data: 'tanggal_masuk' },
                    { data: 'tamatan' },
                    { data: 'tipe_bpjs' },
                    { data: 'domisili' },
                    { data: 'alamat' },
                    { data: 'status' },
                    {
                        data: 'due_date',
                        render: function (data, type, row) {
                            console.log(data)
                            return data ? new Date(data).toLocaleDateString('id-ID') : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            if (row.user_status) {
                                return `
                                                                    <div class="d-flex gap-2">
                                                                        <button type="button" class="btn btn-action text-primary" onclick="editUser(${row.id})" title="Edit User">
                                                                            <i class="fe fe-edit" style="font-size: 16px;"></i>
                                                                        </button>
                                                                        <button type="button" class="btn btn-action text-danger" onclick="deleteUser(${row.id})" title="Delete User">
                                                                            <i class="fe fe-trash-2" style="font-size: 16px;"></i>
                                                                        </button>
                                                                    </div>
                                                                    `;
                            } else {
                                return `
                                                                    <div class="d-flex gap-2">
                                                                        <button type="button" class="btn btn-action text-primary" onclick="activateUser(${row.id})" title="Activate User">
                                                                            <i class="fe fe-check" style="font-size: 16px;"></i>
                                                                        </button>
                                                                        </div>
                                                                `;
                            }

                        }
                    }
                ]
            });

            // ================== MODAL & FORM LOGIC ==================
            let currentStep = 1;
            const totalSteps = 3;
            let isUpdateMode = false;

            window.resetForm = function () {
                $('#userForm')[0].reset();
                $('#userForm .select2').val('').trigger('change');
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
                currentStep = 1;
                isUpdateMode = false;
                $('#modalTitle').text('Add New User');
                updateWizardState();
            };

            $('#btnAddUser').on('click', function () {
                resetForm();
                $('#userModal').modal('show');
            });

            function updateWizardState() {
                $('.wizard-step').addClass('d-none');
                $(`#step-${currentStep}`).removeClass('d-none');
                $('.step-circle').removeClass('active');
                for (let i = 1; i <= currentStep; i++) { $(`#step-circle-${i}`).addClass('active'); }
                $('#wizardProgressBar').css('width', ((currentStep - 1) / (totalSteps - 1)) * 100 + '%');
                $('#btnPrev').toggle(currentStep > 1);

                if (currentStep === totalSteps) {
                    $('#btnNext').hide();
                    if (isUpdateMode) { $('#updateButton').show(); $('#submitButton').hide(); }
                    else { $('#submitButton').show(); $('#updateButton').hide(); }
                } else {
                    $('#btnNext').show();
                    $('#submitButton, #updateButton').hide();
                }
            }

            $('#btnNext').click(function () { if (currentStep < totalSteps) { currentStep++; updateWizardState(); } });
            $('#btnPrev').click(function () { if (currentStep > 1) { currentStep--; updateWizardState(); } });

            // ================== AJAX CRUD ==================
            $('#userForm').on('submit', function (e) {
                e.preventDefault();
                let data = $(this).serializeArray();
                $.post('/user/create', data)
                    .done(function (res) {
                        $('#userModal').modal('hide');
                        setTimeout(() => {
                            swal({ type: 'success', title: 'Berhasil', text: res.message ?? 'User berhasil disimpan' });
                            $('#userTable').DataTable().ajax.reload(null, false);
                        }, 300);
                    })
                    .fail(function (xhr) { handleValidationErrors(xhr); });
            });

            window.activateUser = function (userId) {
                swal({
                    title: 'Are you sure?', text: 'This action will activate the user.', type: 'warning',
                    showCancelButton: true, confirmButtonText: 'Yes, activate it!', cancelButtonText: 'Cancel'
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: `/user/activate/${userId}`, type: 'patch',
                            success: function (res) {
                                swal({ type: 'success', title: 'Activated', text: res.message });
                                $('#userTable').DataTable().ajax.reload(null, false);
                            },
                            error: function (err) { swal({ type: 'error', title: 'Error', text: 'Gagal mengaktifkan user' }); }
                        });
                    }
                });
            };

            window.editUser = function (userId) {
                let data = $('#userTable').DataTable().rows().data().toArray().find(item => Number(item.id) === Number(userId));
                if (!data) return swal({ type: 'error', title: 'Error', text: 'Data tidak ditemukan.' });

                resetForm();
                isUpdateMode = true;
                $('#modalTitle').text('Edit User');
                $('#updateButton').attr('data-id', userId);

                $('#name').val(data.name);
                $('#username').val(data.username);
                $('#password').val('');
                $('#role').val(data.role_id).trigger('change');
                $('#golongan').val(data.golongan).trigger('change');
                $('#jenis_kelamin').val(data.jenis_kelamin).trigger('change');
                $('#tanggal_lahir').val(data.tanggal_lahir);
                $('#tanggal_masuk').val(data.tanggal_masuk);
                $('#nip').val(data.nip);
                $('#tamatan').val(data.tamatan).trigger('change');
                $('#domisili').val(data.domisili);
                $('#tipe_bpjs').val(data.tipe_bpjs).trigger('change');
                $('#alamat').val(data.alamat);

                $('#userModal').modal('show');
            };

            $('#updateButton').click(function () {
                let userId = $(this).data('id');
                let data = $('#userForm').serializeArray();

                $.ajax({ url: '/user/update/' + userId, type: 'PATCH', data: data })
                    .done(function (res) {
                        $('#userModal').modal('hide');
                        setTimeout(() => {
                            swal({ type: 'success', title: 'Berhasil', text: res.message ?? 'User berhasil diupdate' });
                            $('#userTable').DataTable().ajax.reload(null, false);
                        }, 300);
                    })
                    .fail(function (xhr) { handleValidationErrors(xhr); });
            });

            window.deleteUser = function (userId) {
                swal({
                    title: 'Are you sure?', text: 'This action cannot be undone.', type: 'warning',
                    showCancelButton: true, confirmButtonText: 'Yes, delete it!', cancelButtonText: 'Cancel'
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: `/user/delete/${userId}`, type: 'delete',
                            success: function (res) {
                                swal({ type: 'success', title: 'Deleted', text: res.message });
                                $('#userTable').DataTable().ajax.reload(null, false);
                            },
                            error: function (err) { swal({ type: 'error', title: 'Error', text: 'Gagal menghapus data' }); }
                        });
                    }
                });
            };

            function handleValidationErrors(xhr) {
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (field, messages) {
                        let input = $(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.parent().after(`<div class="invalid-feedback d-block mt-1">${messages[0]}</div>`);
                    });
                } else {
                    swal({ type: 'error', title: 'Error', text: xhr.responseJSON?.message ?? 'Terjadi kesalahan' });
                }
            }

            // ================== BULK IMPORT LOGIC ==================
            $('#uploadArea').on('dragover', function (e) { e.preventDefault(); $(this).addClass('dragover'); })
                .on('dragleave', function () { $(this).removeClass('dragover'); })
                .on('drop', function (e) {
                    e.preventDefault(); $(this).removeClass('dragover');
                    const files = e.originalEvent.dataTransfer.files;
                    if (files.length) { $('#xlsxFile')[0].files = files; $('#xlsxFile').trigger('change'); }
                })
                .on('click', function (e) { if (!$(e.target).is('#xlsxFile')) $('#xlsxFile').click(); });

            $('#chooseFile').click(function (e) { e.stopPropagation(); $('#xlsxFile').click(); });

            $('#xlsxFile').on('change', function () {
                const file = this.files[0];
                if (!file) return;
                const ext = file.name.split('.').pop().toLowerCase();
                if (!['xlsx', 'xls'].includes(ext)) {
                    swal({ type: 'error', title: 'File Tidak Valid', text: 'Hanya format .xlsx atau .xls' });
                    $(this).val(''); return;
                }
                $('.selected-file').removeClass('d-none');
                $('.file-name').text(file.name);
                $('.file-size').text((file.size / 1024).toFixed(2) + ' KB');
            });

            $('#removeFile').click(function () { $('#xlsxFile').val(''); $('.selected-file').addClass('d-none'); });

            $('#bulkImportForm').submit(function (e) {
                e.preventDefault();
                if (!$('#xlsxFile')[0].files[0]) return swal({ type: 'warning', title: 'Pilih File', text: 'Silakan pilih file Excel' });
                let formData = new FormData(this);
                $.ajax({
                    url: '/user/import', type: 'POST', data: formData, processData: false, contentType: false,
                    success: function (res) {
                        $('#importModal').modal('hide');
                        $('#xlsxFile').val(''); $('.selected-file').addClass('d-none');
                        setTimeout(() => {
                            swal({ type: 'success', title: 'Berhasil', text: res.message });
                            $('#userTable').DataTable().ajax.reload(null, false);
                        }, 300);
                    },
                    error: function (err) { swal({ type: 'error', title: 'Error', text: err.responseJSON?.error }); }
                });
            });
        });
    </script>
@endsection