@extends('layouts.app')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="fw-bold mb-1">Jabatan Management</h2>
                        <p class="mb-0" style="opacity: 0.7;">Kelola data jabatan dan level yang terdaftar di sistem</p>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-primary shadow-sm rounded-pill px-4 fw-semibold"
                            id="btnAddJabatan" data-bs-toggle="offcanvas" data-bs-target="#jabatanOffcanvas">
                            <i class="fe fe-plus me-2"></i> Add New Jabatan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle custom-table dt-responsive nowrap" id="jabatanTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Jabatan</th>
                                    <th>Level</th>
                                    <th width="15%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="jabatanOffcanvas"
        aria-labelledby="jabatanOffcanvasLabel" style="width: 400px;">
        <div class="offcanvas-header user-header border-0 p-4">
            <h4 class="offcanvas-title fw-bold text-white mb-0" id="offcanvasTitle">Add New Jabatan</h4>
            <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-4 d-flex flex-column">
            <p class="small mb-4" style="opacity: 0.7;">Silakan isi formulir di bawah ini untuk mengelola data jabatan.</p>

            <form id="jabatanForm" class="flex-grow-1 d-flex flex-column">
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <label class="form-label fw-semibold mb-2">Nama Jabatan</label>
                        <div class="modern-input">
                            <span><i class="fe fe-briefcase"></i></span>
                            <input type="text" id="name" name="name" placeholder="Masukkan nama jabatan">
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold mb-2">Level Jabatan</label>
                        <div class="modern-input">
                            <span><i class="fe fe-layers"></i></span>
                            <input type="number" id="level" name="level" placeholder="Masukkan level jabatan">
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-4 border-top d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold"
                        data-bs-dismiss="offcanvas">Batal</button>
                    <button type="submit" class="btn btn-success shadow-sm rounded-pill px-4 fw-semibold" id="submitButton">
                        <i class="fe fe-save me-2"></i> Save
                    </button>
                    <button type="button" class="btn btn-success shadow-sm rounded-pill px-4 fw-semibold" id="updateButton"
                        style="display: none;">
                        <i class="fe fe-check-circle me-2"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .user-header {
            background: linear-gradient(135deg, #6259ca 0%, #867efc 100%);
        }

        /* MEMAKSA WARNA TEXT MENGIKUTI TEMPLATE (INHERIT) */
        body,
        label,
        h2,
        h6,
        .offcanvas {
            color: inherit;
        }

        /* Override paksa warna DataTables */
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

        /* INPUT STYLES */
        .modern-input {
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

        /* ACTION BUTTONS IN TABLE */
        .btn-action {
            background-color: var(--bs-tertiary-bg);
            border: 1px solid var(--bs-border-color);
            color: inherit;
            transition: .2s;
        }

        .btn-action.text-primary:hover {
            color: #0d6efd !important;
            border-color: #0d6efd;
        }

        .btn-action.text-danger:hover {
            color: #dc3545 !important;
            border-color: #dc3545;
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
    </style>

    <script>
        $(document).ready(function () {
            // Setup CSRF untuk request AJAX
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            // Initialize DataTables
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
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, meta) { return meta.row + 1; }
                    },
                    { data: 'name' },
                    { data: 'level' },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button type="button" class="btn btn-action text-primary btn-sm rounded-circle p-2" onclick="editJabatan(${row.id})" title="Edit Jabatan">
                                                    <i class="fe fe-edit" style="font-size: 16px;"></i>
                                                </button>
                                                <button type="button" class="btn btn-action text-danger btn-sm rounded-circle p-2" onclick="deleteJabatan(${row.id})" title="Delete Jabatan">
                                                    <i class="fe fe-trash-2" style="font-size: 16px;"></i>
                                                </button>
                                            </div>
                                        `;
                        }
                    }
                ]
            });

            // ================== OFFCANVAS & FORM LOGIC ==================
            // Fungsi untuk mereset form ke mode "Add"
            window.resetForm = function () {
                $('#jabatanForm')[0].reset();
                $('#offcanvasTitle').text('Add New Jabatan');
                $('#submitButton').show();
                $('#updateButton').hide().removeData('id');
            };

            // Event listener ketika tombol "Add New Jabatan" diklik
            $('#btnAddJabatan').click(function () {
                resetForm();
            });

            // Fungsi Edit - Mengambil data, mengisi form, dan membuka Offcanvas
            window.editJabatan = function (jabatanId) {
                let data = table.rows().data().toArray().find(item => Number(item.id) === Number(jabatanId));

                if (!data) {
                    return swal({ type: 'error', title: 'Error', text: 'Data jabatan tidak ditemukan.' });
                }

                resetForm();
                $('#offcanvasTitle').text('Edit Jabatan');
                $('#submitButton').hide();
                $('#updateButton').show().data('id', jabatanId); // Simpan ID di tombol update

                // Populate form
                $('#name').val(data.name);
                $('#level').val(data.level);

                // Tampilkan offcanvas menggunakan API Bootstrap
                let offcanvasEl = document.getElementById('jabatanOffcanvas');
                let bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
                bsOffcanvas.show();
            };

            // ================== AJAX CRUD ==================
            // POST - Buat Jabatan Baru
            $('#jabatanForm').on('submit', function (e) {
                e.preventDefault();

                let name = $('#name').val();
                let level = $('#level').val();

                if (name === '' || level === '') {
                    return swal({ type: 'warning', title: 'Oops...', text: 'Semua field wajib diisi' });
                }

                $.post('/jabatan', { name: name, level: level })
                    .done(function (res) {
                        // Tutup offcanvas
                        bootstrap.Offcanvas.getInstance(document.getElementById('jabatanOffcanvas')).hide();

                        setTimeout(() => {
                            swal({ type: 'success', title: 'Berhasil', text: res.message ?? 'Jabatan berhasil disimpan' });
                            // Gunakan inisiasi langsung agar tabel tidak berkedip
                            $('#jabatanTable').DataTable().ajax.reload(null, false);
                        }, 300);
                    })
                    .fail(function (err) {
                        swal({ type: 'error', title: 'Error', text: err.responseJSON?.message ?? 'Terjadi kesalahan' });
                    });
            });

            // PATCH - Update Jabatan
            $('#updateButton').click(function () {
                let jabatanId = $(this).data('id');
                let name = $('#name').val();
                let level = $('#level').val();

                if (name === '' || level === '') {
                    return swal({ type: 'warning', title: 'Oops...', text: 'Semua field wajib diisi' });
                }

                $.ajax({
                    url: '/jabatan/' + jabatanId,
                    type: 'PATCH',
                    data: { name: name, level: level }
                })
                    .done(function (res) {
                        bootstrap.Offcanvas.getInstance(document.getElementById('jabatanOffcanvas')).hide();

                        setTimeout(() => {
                            swal({ type: 'success', title: 'Berhasil', text: res.message ?? 'Jabatan berhasil diupdate' });
                            $('#jabatanTable').DataTable().ajax.reload(null, false);
                        }, 300);
                    })
                    .fail(function (err) {
                        swal({ type: 'error', title: 'Error', text: err.responseJSON?.message ?? 'Terjadi kesalahan' });
                    });
            });

            // DELETE - Hapus Jabatan
            window.deleteJabatan = function (jabatanId) {
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
                            url: `/jabatan/${jabatanId}`,
                            type: 'delete',
                            success: function (res) {
                                swal({ type: 'success', title: 'Deleted', text: res.message });
                                $('#jabatanTable').DataTable().ajax.reload(null, false);
                            },
                            error: function (err) {
                                swal({ type: 'error', title: 'Error', text: err.responseJSON?.message ?? 'Gagal menghapus data' });
                            }
                        });
                    }
                });
            };
        });
    </script>
@endsection