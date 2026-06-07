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
                                Unit Management
                            </h3>

                            <p class="text-white-50 mb-0">
                                Kelola data unit yang terdaftar di sistem
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

                    <form id="unitForm">

                        <div class="row">

                            <!-- USERNAME -->
                            <div class="col-xl-4 col-lg-6 mb-4">

                                <label class="form-label fw-semibold mb-2">
                                    Nama unit
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-user"></i>
                                    </span>

                                    <input type="text" id="name" name="name" placeholder="Masukkan nama unit">

                                </div>

                            </div>
                            <div class="col-xl-4 col-lg-6 mb-4">

                                <label class="form-label fw-semibold mb-2">
                                    Outlet terkait, <small class="text-muted">Opsional</small>
                                </label>

                                <select class="form-select modern-select" id="outlet_id" name="outlet_id">
                                    <option value="">Pilih outlet terkait</option>
                                    @foreach ($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                        </div>

                        <!-- USERNAME -->
                        <div class="col-12 mb-4">

                            <label class="form-label fw-semibold mb-2">
                                Deskripsi unit
                            </label>

                            <textarea class="form-control modern-textarea" rows="4" id="description" name="description"
                                placeholder="Masukkan deskripsi unit..."></textarea>

                        </div>
                        <div class="col-12 mb-4">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <label class="form-label fw-semibold mb-0">
                                    Sub Unit
                                </label>

                                <button type="button" class="btn btn-success btn-sm rounded-pill" id="btnAddSubUnit">

                                    <i class="fe fe-plus"></i>
                                    Tambah

                                </button>

                            </div>

                            <div id="subUnitContainer">

                            </div>

                        </div>

                        <!-- ACTION -->
                        <div class="d-flex justify-content-end mt-2">

                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-semibold"
                                id="submitButton">

                                <i class="fe fe-save me-2"></i>
                                Save unit

                            </button>


                            <button type="button" class="btn btn-primary rounded-pill px-5 py-2 fw-semibold"
                                id="updateButton" style="display: none">

                                <i class="fe fe-save me-2"></i>
                                Update unit

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
                                unit List
                            </h3>

                            <p class="text-muted mb-0">
                                Data seluruh unit yang terdaftar di sistem
                            </p>

                        </div>

                    </div>

                </div>

                <div class="card-body p-4">

                    <div class="table-responsive">
                        <table class="table align-middle table-hover custom-table" id="unitTable" width="100%">

                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nama Unit</th>
                                    <th>Outlet</th>
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
        .sub-unit-item {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 12px;
            margin-bottom: 10px;
        }

        .sub-unit-item input {
            border: none;
            background: transparent;
            width: 100%;
            outline: none;
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
        $(document).ready(function () {
            function createSubUnitRow(value = '') {
                return `
                                <div class="sub-unit-item">

                                    <div class="d-flex gap-2 align-items-center">

                                        <input
                                            type="text"
                                            class="form-control sub-unit-name"
                                            value="${value}"
                                            placeholder="Nama Sub Unit">

                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm remove-sub-unit">

                                            <i class="fe fe-trash"></i>

                                        </button>

                                    </div>

                                </div>
                            `;
            }

            function getSubUnits() {

                let subUnits = [];

                $('.sub-unit-name').each(function () {

                    const value = $(this).val().trim();

                    if (value !== '') {

                        subUnits.push({
                            name: value
                        });

                    }

                });

                return subUnits;
            }

            $('#btnAddSubUnit').click(function () {

                $('#subUnitContainer')
                    .append(createSubUnitRow());

            });

            $(document).on(
                'click',
                '.remove-sub-unit',
                function () {

                    $(this)
                        .closest('.sub-unit-item')
                        .remove();

                }
            );


            $('.sub-unit-name').each(function () {

                let value = $(this).val().trim();

                if (value !== '') {
                    subUnits.push({
                        name: value
                    });
                }

            });
            $('#outlet_id').select2({
                width: '100%',
                placeholder: 'Pilih outlet terkait',
                allowClear: true
            });

            let table = $('#unitTable').DataTable({

                processing: true,
                serverSide: false,

                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search unit..."
                },

                ajax: {
                    url: '/unit',
                    type: 'GET',
                    dataSrc: ''
                },

                columns: [{
                    data: 'no',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'name'
                },
                {
                    data: 'outlet.name',
                    defaultContent: '-'
                },
                {
                    data: 'description'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                                  <div class="d-flex gap-2 justify-content-center">
                            <button 
                                class="btn btn-icon btn-primary rounded-circle"
                                onclick="editunit(${row.id})"
                            >
                                <i class="fe fe-edit-2"></i>
                            </button>

                            <button 
                                class="btn btn-icon btn-danger rounded-circle"
                                onclick="deleteunit(${row.id})"
                            >
                                <i class="fe fe-trash-2"></i>
                            </button>
                        </div>
                                                    `;
                    }
                }
                ]

            });


            window.deleteunit = function (unitId) {

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

                            url: `/unit/${unitId}`,
                            type: 'delete',
                            success: function (res) {
                                swal({
                                    type: 'success',
                                    title: 'Deleted',
                                    text: res.message
                                });

                                table.ajax.reload();

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

            window.editunit = function (unitId) {
                $('#updateButton').attr('data-id', unitId);
                $('#submitButton').hide();
                $('#updateButton').show();
                let data = table
                    .rows()
                    .data()
                    .toArray()
                    .find(item => Number(item.id) === Number(unitId));

                if (!data) {

                    swal({
                        type: 'error',
                        title: 'Error',
                        text: 'unit data not found.'
                    });

                    return;
                }

                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#outlet_id').val(data.outlet_id).trigger('change');
                $('#subUnitContainer').empty();

                (data.subunit || []).forEach(function (sub) {

                    $('#subUnitContainer').append(
                        createSubUnitRow(sub.name)
                    );

                });
                // Smooth scroll to form
                $('html, body').animate({
                    scrollTop: $('#unitForm').offset().top - 80
                }, 600);
            };

            $('#unitForm').on('submit', function (e) {

                e.preventDefault();

                let name = $('#name').val();
                let outlet_id = $('#outlet_id').val();
                let description = $('#description').val();
                let subUnits = getSubUnits();
                if (
                    name === ''
                ) {
                    swal({
                        type: 'warning',
                        title: 'Oops...',
                        text: 'Nama unit wajib diisi'
                    });

                    return;
                }

                $.post('/unit', {
                    name: name,
                    description: description,
                    outlet_id: outlet_id,
                    subUnits: subUnits
                })

                    .done(function (res) {

                        swal({
                            type: 'success',
                            title: 'Berhasil',
                            text: res.message ??
                                'unit berhasil disimpan'
                        });

                        $('#unitForm')[0].reset();
                        table.ajax.reload();
                    })

                    .fail(function (err) {

                        swal({
                            type: 'error',
                            title: 'Error',
                            text: err.responseJSON?.error ??
                                'Terjadi kesalahan'
                        });

                    });

            });

            function resetForm() {

                $('#unitForm')[0].reset();

                $('#outlet_id')
                    .val(null)
                    .trigger('change');

                $('#subUnitContainer').empty();

                $('#submitButton').show();
                $('#updateButton')
                    .hide()
                    .removeAttr('data-id');

            }

            $('#btnReset').click(function () {
                resetForm();
            });

            $('#updateButton').click(function () {
                let unitId = $(this).data('id');
                let name = $('#name').val();
                let description = $('#description').val();
                let outlet_id = $('#outlet_id').val();
                let subUnits = getSubUnits();
                if (
                    name === '' ||
                    subUnits.length === 0
                ) {
                    swal({
                        type: 'warning',
                        title: 'Oops...',
                        text: 'Nama unit dan sub-unit wajib diisi'
                    });

                    return;
                }
                $.ajax({
                    url: '/unit/' + unitId,
                    type: 'PATCH',
                    data: {
                        name: name,
                        description: description,
                        outlet_id: outlet_id,
                        subUnits: subUnits
                    }
                })
                    .done(function (res) {
                        resetForm();

                        table.ajax.reload();

                        swal({
                            type: 'success',
                            title: 'Berhasil',
                            text: res.message
                        });
                    })
                    .fail(function (err) {
                        swal({
                            type: 'error',
                            title: 'Error',
                            text: err.responseJSON?.error ??
                                'Terjadi kesalahan'
                        });
                    });
            });
        });
    </script>
@endsection