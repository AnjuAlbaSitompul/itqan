@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 mt-2">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h3 class="fw-bold mb-1" style="color: #6259ca;">
                                <i class="fe fe-map-pin me-2"></i>Outlet List
                            </h3>
                            <p class="text-muted mb-0">
                                Data seluruh outlet yang terdaftar di sistem
                            </p>
                        </div>

                        <button type="button" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm"
                            onclick="showAddForm()">
                            <i class="fe fe-plus me-2"></i> Tambah Outlet
                        </button>
                    </div>
                </div>

                <div class="card-body p-4">
                    <table class="table align-middle table-hover custom-table dt-responsive nowrap" id="outletTable"
                        width="100%">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th width="15%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end shadow-lg" tabindex="-1" id="outletOffcanvas" aria-labelledby="outletOffcanvasLabel"
        style="width: 400px;">
        <div class="offcanvas-header user-header border-0 p-4">
            <div>
                <h4 class="fw-bold text-white mb-1" id="offcanvasTitle">Tambah Outlet</h4>
                <p class="text-white-50 mb-0" style="font-size: 0.875rem;">Kelola detail outlet</p>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-4 bg-light">
            <form id="outletForm">

                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">Nama Outlet</label>
                    <div class="modern-input">
                        <span><i class="fe fe-home"></i></span>
                        <input type="text" id="name" name="name" placeholder="Masukkan nama outlet" required>
                    </div>
                </div>


                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">Alamat Outlet</label>
                    <textarea class="form-control modern-textarea shadow-sm border-0" rows="4" id="alamat" name="alamat"
                        placeholder="Masukkan alamat outlet..." style="border-radius: 14px;" required></textarea>
                </div>

                <div class="d-flex gap-2 mt-5">
                    <button type="button" class="btn btn-light rounded-pill flex-fill fw-semibold" id="btnReset">
                        Reset
                    </button>

                    <button type="submit" class="btn btn-primary rounded-pill flex-fill fw-semibold shadow-sm"
                        id="submitButton">
                        <i class="fe fe-save me-1"></i> Save
                    </button>

                    <button type="button" class="btn btn-primary rounded-pill flex-fill fw-semibold shadow-sm"
                        id="updateButton" style="display: none;">
                        <i class="fe fe-save me-1"></i> Update
                    </button>
                </div>

            </form>
        </div>
    </div>

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

        .custom-table thead th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0 !important;
            padding: 16px;
            font-size: 13px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
        }

        .custom-table tbody td {
            padding: 16px;
            vertical-align: middle;
            color: #334155;
        }

        .custom-table tbody tr {
            transition: .2s;
        }

        .custom-table tbody tr:hover {
            background: #f1f5f9;
        }

        /* Responsive DataTables adjustments */
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {
            background-color: #6259ca;
            border-radius: 50%;
        }
    </style>

    <script>
        $(document).ready(function () {

            // Initialize DataTable with responsive extension
            let table = $('#outletTable').DataTable({
                processing: true,
                serverSide: false,
                responsive: true, // <-- Make table expandable on mobile
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search outlet..."
                },
                ajax: {
                    url: '/outlet',
                    type: 'GET',
                    dataSrc: ''
                },
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'name' },
                    { data: 'alamat' },
                    // { data: 'man_power' }, // Pastikan kolom ini ada direturn oleh backend Anda
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `
                                                                                <div class="d-flex gap-2 justify-content-center">
                                                                                    <button class="btn btn-icon btn-sm btn-soft-primary rounded-circle" style="background: #eff2fb; color:#6259ca; border:none;" onclick="editOutlet(${row.id})">
                                                                                        <i class="fe fe-edit-2"></i>
                                                                                    </button>
                                                                                    <button class="btn btn-icon btn-sm btn-soft-danger rounded-circle" style="background: #fdefee; color:#e82646; border:none;" onclick="deleteOutlet(${row.id})">
                                                                                        <i class="fe fe-trash-2"></i>
                                                                                    </button>
                                                                                </div>
                                                                                `;
                        }
                    }
                ]
            });

            // Global functions
            window.resetForm = function () {
                $('#outletForm')[0].reset();
                $('#submitButton').show();
                $('#updateButton').hide();
                $('#offcanvasTitle').text('Tambah Outlet');
            };

            window.showAddForm = function () {
                resetForm();
                var myOffcanvas = new bootstrap.Offcanvas(document.getElementById('outletOffcanvas'));
                myOffcanvas.show();
            };

            window.editOutlet = function (outletId) {
                resetForm(); // Reset before filling

                // Set data-id untuk update
                $('#updateButton').attr('data-id', outletId);
                $('#submitButton').hide();
                $('#updateButton').show();
                $('#offcanvasTitle').text('Edit Outlet');

                // Cari data berdasarkan row DataTables
                let data = table.rows().data().toArray().find(item => Number(item.id) === Number(outletId));

                if (!data) {
                    swal({ type: 'error', title: 'Error', text: 'Outlet data not found.' });
                    return;
                }

                // Fill form
                $('#name').val(data.name);
                $('#man_power').val(data.man_power);
                $('#alamat').val(data.alamat);

                // Tampilkan Offcanvas
                var myOffcanvas = new bootstrap.Offcanvas(document.getElementById('outletOffcanvas'));
                myOffcanvas.show();
            };

            window.deleteOutlet = function (outletId) {
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
                            url: `/outlet/${outletId}`,
                            type: 'delete',
                            success: function (res) {
                                swal({ type: 'success', title: 'Deleted', text: res.message });
                                $('#outletTable').DataTable().ajax.reload(null, false);
                            },
                            error: function (err) {
                                swal({ type: 'error', title: 'Error', text: err.responseJSON?.message ?? 'An error occurred.' });
                            }
                        });
                    }
                });
            };

            // Event Listeners
            $('#btnReset').click(function () {
                resetForm();
            });

            // CREATE Data
            $('#outletForm').on('submit', function (e) {
                e.preventDefault();

                let name = $('#name').val();
                let alamat = $('#alamat').val();

                $.post('/outlet', { name: name, alamat: alamat })
                    .done(function (res) {
                        swal({ type: 'success', title: 'Berhasil', text: res.message ?? 'Outlet berhasil disimpan' });
                        $('#outletTable').DataTable().ajax.reload(null, false);

                        // Tutup Offcanvas
                        bootstrap.Offcanvas.getInstance(document.getElementById('outletOffcanvas')).hide();
                    })
                    .fail(function (err) {
                        swal({ type: 'error', title: 'Error', text: err.responseJSON?.message ?? 'Terjadi kesalahan' });
                    });
            });

            // UPDATE Data
            $('#updateButton').click(function () {
                let outletId = $(this).attr('data-id'); // Gunakan .attr() untuk memastikan mengambil val terbaru
                let name = $('#name').val();
                let alamat = $('#alamat').val();

                if (name === '' || alamat === '') {
                    swal({ type: 'warning', title: 'Oops...', text: 'Semua field wajib diisi' });
                    return;
                }

                $.ajax({
                    url: '/outlet/' + outletId,
                    type: 'PATCH',
                    data: { name: name, alamat: alamat }
                })
                    .done(function (res) {
                        $('#outletTable').DataTable().ajax.reload(null, false); // false mencegah pagination reset
                        swal({ type: 'success', title: 'Berhasil', text: res.message ?? 'Outlet berhasil diupdate' });

                        // Tutup Offcanvas
                        bootstrap.Offcanvas.getInstance(document.getElementById('outletOffcanvas')).hide();
                    })
                    .fail(function (err) {
                        swal({ type: 'error', title: 'Error', text: err.responseJSON?.message ?? 'Terjadi kesalahan' });
                    });
            });

        });
    </script>
@endsection