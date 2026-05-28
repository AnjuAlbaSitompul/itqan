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
                                User Request
                            </h3>

                            <p class="text-white-50 mb-0">
                                Manage user requests and permissions
                            </p>
                        </div>
                    </div>
                </div>

                <!-- BODY -->
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover custom-table" id="userRequestTable" width="100%">

                            <thead>

                                <tr>
                                    <th width="5%">#</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th width="10%">
                                        <i class="fe fe-settings"></i>
                                    </th>
                                </tr>

                            </thead>

                            <tbody></tbody>

                        </table>

                    </div>
                </div>

            </div>

        </div>
    </div>
    <x-modal.scroll-modal title="Detail User Request">

        <form id="requestForm">

            {{-- STATUS BADGE --}}
            <div class="mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

                    <div class="d-flex align-items-center gap-3">
                        <div class="request-avatar">
                            <i class="fe fe-user"></i>
                        </div>

                        <div>
                            <h4 class="mb-1 fw-bold" id="headerName">
                                -
                            </h4>

                            <small class="text-muted">
                                User Registration Request
                            </small>
                        </div>
                    </div>

                    <span class="badge rounded-pill bg-warning text-dark px-3 py-2 text-uppercase" id="statusBadge">
                        Pending
                    </span>

                </div>
            </div>

            {{-- ACCOUNT INFORMATION --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="fw-bold mb-0">
                        <i class="fe fe-user me-2 text-primary"></i>
                        Account Information
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row">

                        {{-- NAME --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Nama
                                </label>

                                <input type="text" class="form-control modern-input" id="name" readonly>
                            </div>
                        </div>

                        {{-- USERNAME --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Username
                                </label>

                                <input type="text" class="form-control modern-input" id="username" readonly>
                            </div>
                        </div>

                        {{-- NIP --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    NIP
                                </label>

                                <input type="text" class="form-control modern-input" id="nip" readonly>
                            </div>
                        </div>

                        {{-- TAMATAN --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Tamatan
                                </label>

                                <input type="text" class="form-control modern-input" id="tamatan" readonly>
                            </div>
                        </div>

                        {{-- JENIS KELAMIN --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Jenis Kelamin
                                </label>

                                <input type="text" class="form-control modern-input" id="jenis_kelamin" readonly>
                            </div>
                        </div>

                        {{-- TANGGAL LAHIR --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Tanggal Lahir
                                </label>

                                <input type="date" class="form-control modern-input" id="tanggal_lahir" readonly>
                            </div>
                        </div>

                        {{-- DOMISILI --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Domisili
                                </label>

                                <input type="text" class="form-control modern-input" id="domisili" readonly>
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Status
                                </label>

                                <input type="text" class="form-control modern-input" id="status" readonly>
                            </div>
                        </div>

                        {{-- ALAMAT --}}
                        <div class="col-12">
                            <div class="mb-0">
                                <label class="form-label fw-semibold">
                                    Alamat
                                </label>

                                <textarea class="form-control modern-textarea" id="alamat" rows="4" readonly></textarea>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ADMIN SECTION --}}
            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="fw-bold mb-0">
                        <i class="fe fe-shield me-2 text-success"></i>
                        Admin Approval
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Role
                                </label>

                                <select class="form-select modern-select" id="role_id">

                                    <option value="">
                                        Select Role
                                    </option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" data-name="{{ $role->name }}">
                                            {{ $role->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6" id="outletWrapper">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Outlet
                                </label>

                                <select class="form-select modern-select" id="outlet_id">

                                    <option value="">
                                        Select Outlet
                                    </option>

                                    @foreach ($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">
                                            {{ $outlet->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Unit
                                </label>

                                <select class="form-select modern-select" id="unit_id">

                                    <option value="">
                                        Select Unit
                                    </option>

                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Jabatan
                                </label>

                                <select class="form-select modern-select" id="jabatan_id">

                                    <option value="">
                                        Select Jabatan
                                    </option>

                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}">
                                            {{ $jabatan->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Tipe BPJS
                                </label>

                                <select class="form-select modern-select" id="tipe_bpjs">

                                    <option value="">
                                        Select Tipe BPJS
                                    </option>

                                    <option value="Kesehatan">
                                        BPJS Kesehatan
                                    </option>
                                    <option value="Ketenagakerjaan">
                                        BPJS Ketenagakerjaan
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Golongan
                                </label>

                                <input type="text" name="golongan" class="form-control modern-input"
                                    placeholder="Enter Golongan" />
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <x-slot name="footer">
                {{-- ACTION BUTTON --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">

                        <i class="fe fe-x me-1"></i>
                        Close
                    </button>

                    <button type="button" class="btn btn-danger rounded-pill px-4" id="rejectButton">

                        <i class="fe fe-slash me-1"></i>
                        Reject
                    </button>

                    <button type="button" class="btn btn-success rounded-pill px-4" id="approveButton">

                        <i class="fe fe-check me-1"></i>
                        Approve
                    </button>

                </div>
            </x-slot>
        </form>

    </x-modal.scroll-modal>

    <!-- STYLE -->
    <style>
        .request-avatar {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: linear-gradient(135deg, #6259ca 0%, #867efc 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 24px;
            box-shadow: 0 10px 25px rgba(98, 89, 202, .25);
        }

        .modern-input,
        .modern-select,
        .modern-textarea {
            border-radius: 16px !important;
            border: 1px solid #e5e7eb !important;
            min-height: 52px;
            padding: 12px 16px;
            transition: .3s;
            background: #fff;
        }

        .modern-textarea {
            min-height: 110px;
            resize: none;
        }

        .modern-input:focus,
        .modern-select:focus,
        .modern-textarea:focus {
            border-color: #6259ca !important;
            box-shadow: 0 0 0 4px rgba(98, 89, 202, .12) !important;
        }

        .card {
            border-radius: 24px !important;
        }

        .form-label {
            margin-bottom: 8px;
            color: #374151;
        }

        .btn {
            height: 46px;
            font-weight: 600;
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
    </style>

    <!-- SCRIPT -->
    <script>
        $(document).ready(function() {
            function toggleOutlet() {

                let roleName = $('#role_id option:selected')
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

            $('#role_id').on('change', function() {
                toggleOutlet();
            });
            let table = $('#userRequestTable').DataTable({

                processing: true,
                serverSide: false,

                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search users..."
                },

                ajax: {
                    url: '/request',
                    type: 'GET',
                    dataSrc: ''
                },

                columns: [{
                        data: 'no',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'nip'
                    },
                    {
                        data: 'name',
                        render: function(data, type, row) {
                            return `
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fe fe-users"></i>
                                    <span>${data}</span>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'alamat'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-success" onclick="openModal(${row.id})">
                                        <i class="fe fe-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteUserRequest(${row.id})">
                                        <i class="fe fe-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]

            });
            window.openModal = function(requestId) {

                let request = table
                    .rows()
                    .data()
                    .toArray()
                    .find(item => Number(item.id) === Number(requestId));

                if (!request) {

                    swal({
                        type: 'error',
                        title: 'Error',
                        text: 'User request data not found.'
                    });

                    return;
                }

                $('#name').val(request.name ?? '');
                $('#username').val(request.username ?? '');
                $('#nip').val(request.nip ?? '');
                $('#alamat').val(request.alamat ?? '');
                $('#tamatan').val(request.tamatan ?? '');

                $('#jenis_kelamin').val(
                    request.jenis_kelamin === 'L' ?
                    'Laki-laki' :
                    'Perempuan'
                );

                $('#tanggal_lahir').val(
                    request.tanggal_lahir ?? ''
                );

                $('#domisili').val(
                    request.domisili ?? ''
                );

                $('#status').val(
                    request.status ?? ''
                );
                $('#approveButton').attr('onclick', `approveUserRequest(${request.id})`);
                $('#scrollingmodal').modal('show');
            };
            window.deleteUserRequest = function(requestId) {

                swal({
                    title: 'Are you sure?',
                    text: 'Permintaan akan dihapus.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya Hapus!',
                    cancelButtonText: 'Cancel'
                }, function(isConfirm) {

                    if (isConfirm) {

                        $.ajax({

                            url: `/request/reject/${requestId}`,
                            type: 'delete',
                            success: function(res) {
                                swal({
                                    type: 'success',
                                    title: 'Deleted',
                                    text: res.message
                                });

                                $('#userRequestTable').DataTable().ajax.reload();

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

            window.approveUserRequest = function(requestId) {
                $data = {
                    role_id: $('#role_id').val(),
                    outlet_id: $('#outlet_id').val(),
                    unit_id: $('#unit_id').val(),
                    jabatan_id: $('#jabatan_id').val(),
                    tipe_bpjs: $('#tipe_bpjs').val(),
                    golongan: $('input[name="golongan"]').val(),
                }
                $.ajax({
                        url: '/request/approve/' + requestId,
                        type: 'POST',
                        data: $data
                    })
                    .done(function(res) {
                        $('#scrollingmodal').modal('hide');
                        $('#requestForm')[0].reset();
                        $('#userRequestTable').DataTable().ajax.reload();
                        swal({
                            type: 'success',
                            title: 'Success',
                            text: res.message ?? 'User request approved.'
                        });
                    })
                    .fail(function(err) {
                        console.error(err);
                        swal({
                            type: 'error',
                            title: 'Error',
                            text: err.responseJSON?.message ??
                                'An error occurred while approving the request.'
                        });
                    });
            };
        });
    </script>
@endsection
