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
                                Outlet Management
                            </h3>

                            <p class="text-white-50 mb-0">
                                Kelola data outlet yang terdaftar di sistem
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

                    <form id="outletForm">

                        <div class="row">

                            <!-- USERNAME -->
                            <div class="col-xl-4 col-lg-6 mb-4">

                                <label class="form-label fw-semibold mb-2">
                                    Nama Outlet
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-map-pin"></i>
                                    </span>

                                    <input type="text" id="name" name="name" placeholder="Masukkan nama outlet">

                                </div>

                            </div>

                            <div class="col-xl-4 col-lg-6 mb-4">

                                <label class="form-label fw-semibold mb-2">
                                    Jumlah Tenaga Kerja
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-users"></i>
                                    </span>

                                    <input type="number" id="man_power" name="man_power"
                                        placeholder="Masukkan jumlah tenaga kerja" min="0">

                                </div>

                            </div>

                        </div>
                        <div class="row">

                            <!-- USERNAME -->
                            <div class="col-12 mb-4">

                                <label class="form-label fw-semibold mb-2">
                                    Alamat Outlet
                                </label>

                                <textarea class="form-control modern-textarea" rows="4" id="alamat" name="alamat"
                                    placeholder="Masukkan alamat outlet..."></textarea>

                            </div>
                        </div>

                        <!-- ACTION -->
                        <div class="d-flex justify-content-end mt-2">

                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-semibold"
                                id="submitButton">

                                <i class="fe fe-save me-2"></i>
                                Save Outlet

                            </button>


                            <button type="button" class="btn btn-primary rounded-pill px-5 py-2 fw-semibold"
                                id="updateButton" style="display: none">

                                <i class="fe fe-save me-2"></i>
                                Update Outlet

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
                                Outlet List
                            </h3>

                            <p class="text-muted mb-0">
                                Data seluruh outlet yang terdaftar di sistem
                            </p>

                        </div>

                    </div>

                </div>

                <div class="card-body p-4">

                    <div class="table-responsive">
                        <table class="table align-middle table-hover custom-table" id="outletTable" width="100%">

                            <thead>

                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nama</th>
                                    <th>Tenaga Kerja</th>
                                    <th>Alamat</th>
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
    </style>

    <!-- SCRIPT -->
    <script>
        $(document).ready(function() {

            let table = $('#outletTable').DataTable({

                processing: true,
                serverSide: false,

                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search outlet..."
                },

                ajax: {
                    url: '/outlet',
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
                        data: 'name'
                    },
                    {
                        data: 'man_power',
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
          <div class="d-flex gap-2 justify-content-center">
    <button 
        class="btn btn-icon btn-primary rounded-circle"
        onclick="editOutlet(${row.id})"
    >
        <i class="fe fe-edit-2"></i>
    </button>

    <button 
        class="btn btn-icon btn-danger rounded-circle"
        onclick="deleteOutlet(${row.id})"
    >
        <i class="fe fe-trash-2"></i>
    </button>
</div>
                            `;
                        }
                    }
                ]

            });


            window.deleteOutlet = function(outletId) {

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

                            url: `/outlet/${outletId}`,
                            type: 'delete',
                            success: function(res) {
                                swal({
                                    type: 'success',
                                    title: 'Deleted',
                                    text: res.message
                                });

                                $('#outletTable').DataTable().ajax.reload();

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

            window.editOutlet = function(outletId) {
                $('#updateButton').attr('data-id', outletId);
                $('#submitButton').hide();
                $('#updateButton').show();
                let data = table
                    .rows()
                    .data()
                    .toArray()
                    .find(item => Number(item.id) === Number(outletId));

                if (!data) {

                    swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Outlet data not found.'
                    });

                    return;
                }

                $('#name').val(data.name);
                $('#alamat').val(data.alamat);
                $('#man_power').val(data.man_power);

                // Smooth scroll to form
                $('html, body').animate({
                    scrollTop: $('#outletForm').offset().top - 80
                }, 600);
            };

            $('#outletForm').on('submit', function(e) {

                e.preventDefault();

                let name = $('#name').val();
                let alamat = $('#alamat').val();
                let man_power = $('#man_power').val();
                if (
                    name === '' ||
                    alamat === '' ||
                    man_power === ''
                ) {
                    swal({
                        type: 'warning',
                        title: 'Oops...',
                        text: 'Semua field wajib diisi'
                    });

                    return;
                }

                $.post('/outlet', {
                        name: name,
                        alamat: alamat,
                        man_power: man_power
                    })

                    .done(function(res) {

                        swal({
                            type: 'success',
                            title: 'Berhasil',
                            text: res.message ??
                                'Outlet berhasil disimpan'
                        });

                        $('#outletForm')[0].reset();
                        $('#outletTable').DataTable().ajax.reload();
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

            function resetForm() {
                $('#outletForm')[0].reset();
                $('#submitButton').show();
                $('#updateButton').hide();
            }

            $('#btnReset').click(function() {
                resetForm();
            });

            $('#updateButton').click(function() {
                let outletId = $(this).data('id');
                let name = $('#name').val();
                let alamat = $('#alamat').val();
                let man_power = $('#man_power').val();
                if (
                    name === '' ||
                    alamat === '' ||
                    man_power === ''
                ) {
                    swal({
                        type: 'warning',
                        title: 'Oops...',
                        text: 'Semua field wajib diisi'
                    });

                    return;
                }
                $.ajax({
                        url: '/outlet/' + outletId,
                        type: 'PATCH',
                        data: {
                            name: name,
                            alamat: alamat,
                            man_power: man_power
                        }
                    })
                    .done(function(res) {
                        resetForm();
                        $('#outletTable').DataTable().ajax.reload();
                        swal({
                            type: 'success',
                            title: 'Berhasil',
                            text: res.message ??
                                'Outlet berhasil diupdate'
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
