@extends('layouts.app')

@section('content')
    <div class="row">

        <!-- FORM -->
        <div class="col-12">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- HEADER -->
                <div class="card-header border-0 p-4 joblevel-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>

                            <h3 class="fw-bold text-white mb-1">
                                IDP Job Level Master
                            </h3>

                            <p class="text-white-50 mb-0">
                                Kelola daftar job level yang akan dipilih oleh karyawan
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

                    <form id="jobLevelForm">

                        <div class="row">

                            <!-- JOB LEVEL -->
                            <div class="col-xl-4 col-lg-6 mb-4">

                                <label class="form-label fw-semibold mb-2">
                                    Job Level Name
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-briefcase"></i>
                                    </span>

                                    <input type="text" id="name" name="name" placeholder="Contoh: Leadership">

                                </div>

                            </div>

                            <!-- START PERIOD -->
                            <div class="col-xl-3 col-lg-6 mb-4">

                                <label class="form-label fw-semibold mb-2">
                                    Start Period
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-calendar"></i>
                                    </span>

                                    <input type="date" id="start_period" name="start_period">

                                </div>

                            </div>

                            <!-- END PERIOD -->
                            <div class="col-xl-3 col-lg-6 mb-4">

                                <label class="form-label fw-semibold mb-2">
                                    End Period
                                </label>

                                <div class="modern-input">

                                    <span>
                                        <i class="fe fe-calendar"></i>
                                    </span>

                                    <input type="date" id="end_period" name="end_period">

                                </div>

                            </div>

                            <!-- STATUS -->
                            <div class="col-xl-2 col-lg-6 mb-4">

                                <label class="form-label fw-semibold mb-2">
                                    Status
                                </label>

                                <select class="form-select modern-select select2" id="status" name="status">

                                    <option value="active">
                                        Active
                                    </option>

                                    <option value="inactive">
                                        Inactive
                                    </option>

                                </select>

                            </div>

                            <!-- DESCRIPTION -->
                            <div class="col-12 mb-4">

                                <label class="form-label fw-semibold mb-2">
                                    Description
                                </label>

                                <textarea class="form-control modern-textarea" rows="4" id="description" name="description"
                                    placeholder="Deskripsi job level..."></textarea>

                            </div>

                        </div>

                        <!-- BUTTON -->
                        <div class="d-flex justify-content-end gap-2">

                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" id="btnCancel">

                                Cancel

                            </button>

                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-semibold">

                                <i class="fe fe-save me-2"></i>
                                Save Job Level

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- TABLE -->
        <div class="col-12 mt-4">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- TABS -->
                <div class="tab-menu-heading border-bottom">

                    <div class="tabs-menu px-4 pt-3">

                        <ul class="nav panel-tabs panel-secondary gap-2">

                            <li>
                                <a href="#tab1" class="active rounded-pill px-4 py-2" data-bs-toggle="tab">

                                    <i class="fe fe-list me-1"></i>
                                    Job Level List

                                </a>
                            </li>

                            <li>
                                <a href="#tab2" class="rounded-pill px-4 py-2" data-bs-toggle="tab">

                                    <i class="fe fe-clock me-1"></i>
                                    History

                                </a>
                            </li>

                        </ul>

                    </div>

                </div>

                <!-- CONTENT -->
                <div class="tab-content">

                    <!-- LIST -->
                    <div class="tab-pane active" id="tab1">

                        <div class="card-body p-4">

                            <div class="table-responsive">

                                <table class="table align-middle custom-table" id="jobLevelTable" width="100%">

                                    <thead>

                                        <tr>
                                            <th width="5%">#</th>
                                            <th>Job Level</th>
                                            <th>Periode</th>
                                            <th>Status</th>
                                            <th>Created By</th>
                                            <th width="15%">Action</th>
                                        </tr>

                                    </thead>

                                    <tbody></tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                    <!-- HISTORY -->
                    <div class="tab-pane" id="tab2">

                        <div class="card-body p-4">

                            <div class="timeline">

                                <div class="timeline-item">

                                    <div class="timeline-badge bg-primary"></div>

                                    <div class="timeline-content">

                                        <h6 class="mb-1 fw-semibold">
                                            Leadership Updated
                                        </h6>

                                        <p class="text-muted mb-1">
                                            Periode diubah menjadi Januari 2026
                                        </p>

                                        <small class="text-muted">
                                            Oleh Admin • 2 jam lalu
                                        </small>

                                    </div>

                                </div>

                                <div class="timeline-item">

                                    <div class="timeline-badge bg-success"></div>

                                    <div class="timeline-content">

                                        <h6 class="mb-1 fw-semibold">
                                            Communication Created
                                        </h6>

                                        <p class="text-muted mb-1">
                                            Job level baru berhasil dibuat
                                        </p>

                                        <small class="text-muted">
                                            Oleh HRD • Kemarin
                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- STYLE -->
    <style>
        .joblevel-header {
            background: linear-gradient(135deg, #6259ca 0%, #867efc 100%);
        }

        .modern-input {
            height: 54px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            display: flex;
            align-items: center;
            overflow: hidden;
            background: #fff;
            transition: .3s;
        }

        .modern-input:focus-within {
            border-color: #6259ca;
            box-shadow: 0 0 0 4px rgba(98, 89, 202, .12);
        }

        .modern-input span {
            width: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6259ca;
        }

        .modern-input input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            padding-right: 16px;
        }

        .modern-textarea {
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            padding: 14px;
        }

        .modern-textarea:focus {
            border-color: #6259ca;
            box-shadow: 0 0 0 4px rgba(98, 89, 202, .12);
        }

        .modern-select {
            height: 54px;
            border-radius: 14px !important;
        }

        .custom-table thead th {
            background: #f8fafc;
            padding: 16px;
            font-size: 13px;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: none;
        }

        .custom-table tbody td {
            padding: 16px;
            vertical-align: middle;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 9px;
            top: 0;
            width: 2px;
            height: 100%;
            background: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-badge {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            position: absolute;
            left: -30px;
            top: 3px;
        }

        .timeline-content {
            background: #f8fafc;
            padding: 16px;
            border-radius: 16px;
        }
    </style>

    <!-- SCRIPT -->
    <script>
        $(document).ready(function() {

            $('.select2').select2({
                width: '100%'
            });

            $('#jobLevelTable').DataTable({

                processing: true,
                serverSide: false,

                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search job level..."
                },

                data: [],

                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'period'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'created_by'
                    },
                    {
                        data: 'action'
                    }
                ]

            });

            $('#jobLevelForm').submit(function(e) {

                e.preventDefault();

                let formData = {

                    name: $('#name').val(),
                    description: $('#description').val(),
                    start_period: $('#start_period').val(),
                    end_period: $('#end_period').val(),
                    status: $('#status').val(),
                    _token: '{{ csrf_token() }}'

                };

                if (
                    formData.name === '' ||
                    formData.start_period === '' ||
                    formData.end_period === ''
                ) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops',
                        text: 'Semua field wajib diisi'
                    });

                    return;

                }

                $.post('/job-level/store', formData)

                    .done(function(res) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message ??
                                'Job level berhasil disimpan'
                        });

                        $('#jobLevelForm')[0].reset();

                    })

                    .fail(function(err) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: err.responseJSON?.message ??
                                'Terjadi kesalahan'
                        });

                    });

            });

            $('#btnReset, #btnCancel').click(function() {

                $('#jobLevelForm')[0].reset();

            });

        });
    </script>
@endsection
