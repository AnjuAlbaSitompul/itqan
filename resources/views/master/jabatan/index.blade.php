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
                                Jabatan Management
                            </h3>

                            <p class="text-white-50 mb-0">
                                Kelola data jabatan yang terdaftar di sistem
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

                    <form id="jabatanForm">

                        <div class="row">

                            <!-- USERNAME -->
                            <div class="col-xl-4 col-lg-6 mb-4">

                                <label class="form-label fw-semibold mb-2">
                                    Nama Jabatan
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-user"></i>
                                    </span>

                                    <input type="text" id="name" name="name" placeholder="Masukkan nama jabatan">

                                </div>

                            </div>

                        </div>
                        <div class="row">

                            <!-- USERNAME -->
                            <div class="col-12 mb-4">

                                <label class="form-label fw-semibold mb-2">
                                    Deskripsi Jabatan
                                </label>

                                <textarea class="form-control modern-textarea" rows="4" id="description" name="description"
                                    placeholder="Masukkan deskripsi jabatan..."></textarea>

                            </div>

                            <!-- ACTION -->
                            <div class="d-flex justify-content-end mt-2">

                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-semibold"
                                    id="submitButton">

                                    <i class="fe fe-save me-2"></i>
                                    Save Jabatan

                                </button>


                                <button type="button" class="btn btn-primary rounded-pill px-5 py-2 fw-semibold"
                                    id="updateButton" style="display: none">

                                    <i class="fe fe-save me-2"></i>
                                    Update Jabatan

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
                                Jabatan List
                            </h3>

                            <p class="text-muted mb-0">
                                Data seluruh jabatan yang terdaftar di sistem
                            </p>

                        </div>

                    </div>

                </div>

                <div class="card-body p-4">

                    <div class="table-responsive">
                        <table class="table align-middle table-hover custom-table" id="jabatanTable" width="100%">

                            <thead>

                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nama</th>
                                    <th>Deskripsi</th>
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

            let table = $('#jabatanTable').DataTable({

                processing: true,
                serverSide: false,

                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search jabatan..."
                },

                ajax: {
                    url: '/jabatan',
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
                        data: 'description'
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
        onclick="editJabatan(${row.id})"
    >
        <i class="fe fe-edit-2"></i>
    </button>

    <button 
        class="btn btn-icon btn-danger rounded-circle"
        onclick="deleteJabatan(${row.id})"
    >
        <i class="fe fe-trash-2"></i>
    </button>
</div>
                            `;
                        }
                    }
                ]

            });


            window.deleteJabatan = function(jabatanId) {

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

                            url: `/jabatan/${jabatanId}`,
                            type: 'delete',
                            success: function(res) {
                                swal({
                                    type: 'success',
                                    title: 'Deleted',
                                    text: res.message
                                });

                                $('#jabatanTable').DataTable().ajax.reload();

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

            window.editJabatan = function(jabatanId) {
                $('#updateButton').attr('data-id', jabatanId);
                $('#submitButton').hide();
                $('#updateButton').show();
                let data = table
                    .rows()
                    .data()
                    .toArray()
                    .find(item => Number(item.id) === Number(jabatanId));

                if (!data) {

                    swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Jabatan data not found.'
                    });

                    return;
                }

                $('#name').val(data.name);
                $('#description').val(data.description);

                // Smooth scroll to form
                $('html, body').animate({
                    scrollTop: $('#jabatanForm').offset().top - 80
                }, 600);
            };

            $('#jabatanForm').on('submit', function(e) {

                e.preventDefault();

                let name = $('#name').val();
                let description = $('#description').val();

                if (
                    name === '' ||
                    description === ''
                ) {
                    swal({
                        type: 'warning',
                        title: 'Oops...',
                        text: 'Semua field wajib diisi'
                    });

                    return;
                }

                $.post('/jabatan', {
                        name: name,
                        description: description,
                    })

                    .done(function(res) {

                        swal({
                            type: 'success',
                            title: 'Berhasil',
                            text: res.message ??
                                'Jabatan berhasil disimpan'
                        });

                        $('#jabatanForm')[0].reset();
                        $('#jabatanTable').DataTable().ajax.reload();
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
                $('#jabatanForm')[0].reset();
                $('#submitButton').show();
                $('#updateButton').hide();
            }

            $('#btnReset').click(function() {
                resetForm();
            });

            $('#updateButton').click(function() {
                let jabatanId = $(this).data('id');
                let name = $('#name').val();
                let description = $('#description').val();

                if (
                    name === '' ||
                    description === ''
                ) {
                    swal({
                        type: 'warning',
                        title: 'Oops...',
                        text: 'Semua field wajib diisi'
                    });

                    return;
                }
                $.ajax({
                        url: '/jabatan/' + jabatanId,
                        type: 'PATCH',
                        data: {
                            name: name,
                            description: description,
                        }
                    })
                    .done(function(res) {
                        resetForm();
                        $('#jabatanTable').DataTable().ajax.reload();
                        swal({
                            type: 'success',
                            title: 'Berhasil',
                            text: res.message ??
                                'Jabatan berhasil diupdate'
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
