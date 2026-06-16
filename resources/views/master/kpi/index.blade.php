@extends('layouts.app')

@section('content')

    <div class="row g-4">
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm h-100 overflow-hidden rounded-4">
                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">Daftar Periode</h5>
                    <button class="btn btn-primary btn-sm d-flex align-items-center gap-2 rounded-3 shadow-sm fw-medium"
                        id="btnCreatePeriod">
                        <i class="fe fe-plus"></i> Buat Baru
                    </button>
                </div>
                <div class="period-filter bg-white border-bottom p-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-5">
                            <label class="form-label small text-muted fw-semibold mb-1">From Date</label>
                            <input type="date" id="filterStart" class="form-control form-control-sm input-modern">
                        </div>
                        <div class="col-5">
                            <label class="form-label small text-muted fw-semibold mb-1">Until Date</label>
                            <input type="date" id="filterEnd" class="form-control form-control-sm input-modern">
                        </div>
                        <div class="col-2">
                            <button class="btn btn-light btn-sm w-100 h-100 rounded-3 shadow-sm" id="resetFilter"
                                title="Reset">
                                <i class="fe fe-refresh-cw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="period-list p-2" id="periodListContainer">
                    <div class="text-center py-5 text-muted">
                        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                        <small class="d-block">Memuat daftar periode...</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm h-100 rounded-4">

                <div id="periodDetailLoader" class="card-body d-flex flex-column align-items-center justify-content-center"
                    style="min-height: 450px;">
                    <div id="loaderPlaceholder" class="text-center transition-fade">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="fe fe-layers fs-1 text-muted opacity-50"></i>
                        </div>
                        <h5 class="text-dark fw-bold mb-1">Pilih Periode KPI</h5>
                        <p class="text-muted small">Klik salah satu periode di panel kiri untuk melihat detail statistik.
                        </p>
                    </div>

                    <div id="loaderSpinner" class="text-center d-none transition-fade">
                        <div class="spinner-border text-primary border-3" style="width: 3rem; height: 3rem;" role="status">
                        </div>
                        <h6 class="mt-3 text-muted fw-medium">Menarik data detail...</h6>
                    </div>
                </div>

                <div id="periodDetailContent" class="card-body d-none p-4 p-md-5">

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-5 gap-3">
                        <div>
                            <h3 class="fw-bold mb-2 text-dark" id="detailPeriodName">-</h3>
                            <p class="text-muted small mb-0 d-flex align-items-center gap-2">
                                <span class="bg-light text-secondary px-2 py-1 rounded border"><i
                                        class="fe fe-calendar me-1"></i> Registrasi: <span
                                        id="detailRegDate">-</span></span>
                            </p>
                        </div>
                        <div>
                            <span class="badge px-4 py-2 fs-6 rounded-pill shadow-sm text-uppercase tracking-wide"
                                id="detailStatusBadge">-</span>
                        </div>
                    </div>

                    <div class="row g-3 mb-5">
                        <div class="col-sm-6 col-md-3">
                            <div class="mini-stat">
                                <div class="mini-stat-icon bg-primary bg-opacity-10 text-primary"><i
                                        class="fe fe-users text-white"></i></div>
                                <div>
                                    <small class="text-muted d-block text-uppercase fw-bold tracking-wide"
                                        style="font-size: 0.65rem;">Employee</small>
                                    <h3 class="mb-0 fw-bold text-dark" id="statEmployee">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="mini-stat">
                                <div class="mini-stat-icon bg-success bg-opacity-10 text-success"><i
                                        class="fe fe-user-check text-white"></i></div>
                                <div>
                                    <small class="text-muted d-block text-uppercase fw-bold tracking-wide"
                                        style="font-size: 0.65rem;">Supervisor</small>
                                    <h3 class="mb-0 fw-bold text-dark" id="statSupervisor">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="mini-stat">
                                <div class="mini-stat-icon bg-warning bg-opacity-10 text-warning"><i
                                        class="fe fe-briefcase text-white"></i></div>
                                <div>
                                    <small class="text-muted d-block text-uppercase fw-bold tracking-wide"
                                        style="font-size: 0.65rem;">Manager</small>
                                    <h3 class="mb-0 fw-bold text-dark" id="statManager">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="mini-stat">
                                <div class="mini-stat-icon bg-danger bg-opacity-10 text-danger"><i
                                        class="fe fe-clock text-white"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block text-uppercase fw-bold tracking-wide"
                                        style="font-size: 0.65rem;">Pending Approval</small>
                                    <h3 class="mb-0 fw-bold text-dark" id="statPending">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-uppercase text-muted fw-bold mb-3 tracking-wide" style="font-size: 0.75rem;">Timeline
                        Configuration</h6>
                    <div class="bg-light border border-light-subtle rounded-4 p-4 mb-4">
                        <div class="row g-4">
                            <div class="col-md-6 border-end border-light-subtle">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="p-3 bg-white rounded-3 shadow-sm border border-light"><i
                                            class="fe fe-target text-primary fs-4"></i></div>
                                    <div>
                                        <small class="text-muted text-uppercase fw-semibold d-block mb-1"
                                            style="font-size: 0.65rem;">Active KPI Period</small>
                                        <div class="fw-bold text-dark fs-6" id="detailPeriodDate">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="p-3 bg-white rounded-3 shadow-sm border border-light"><i
                                            class="fe fe-edit-3 text-info fs-4"></i></div>
                                    <div>
                                        <small class="text-muted text-uppercase fw-semibold d-block mb-1"
                                            style="font-size: 0.65rem;">Registration Window</small>
                                        <div class="fw-bold text-dark fs-6" id="detailRegDateBox">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 pt-4 mt-2">
                        <!-- Added id="btnEditPeriod" and starting with d-none -->
                        <button id="btnEditPeriod"
                            class="btn btn-primary d-none align-items-center gap-2 px-4 rounded-3 shadow-sm fw-medium">
                            <i class="fe fe-edit"></i> Edit Periode
                        </button>
                        <div class="ms-auto d-flex gap-2">
                            <button
                                class="btn btn-danger bg-opacity-10 text-white d-flex align-items-center gap-2 border-0 rounded-3 fw-medium transition-hover">
                                <i class="fe fe-lock"></i> Tutup Periode
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end kpi-offcanvas shadow-lg border-0" tabindex="-1" id="periodCanvas">

        <div class="offcanvas-header bg-white border-bottom px-4 py-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary">
                    <i class="fe fe-sliders fs-4 text-white"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark" id="canvasTitle">Create KPI Period</h5>
                    <small class="text-muted">Konfigurasi pengaturan periode baru</small>
                </div>
            </div>
            <button type="button" class="btn-close text-reset shadow-none" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body p-4 custom-scrollbar">
            <form id="periodForm">
                <!-- Added hidden ID field -->
                <input type="hidden" id="period_id">

                <div class="form-section modern-section mb-4">
                    <h6 class="section-title text-uppercase tracking-wide text-primary fw-bold mb-3"><i
                            class="fe fe-info me-2"></i>General Info</h6>
                    <div class="bg-white p-3 rounded-4 border shadow-sm">
                        <label class="form-label fw-semibold text-dark small mb-2">Nama Periode</label>
                        <input type="text" id="name" class="form-control form-control-lg input-modern"
                            placeholder="Cth: KPI Januari 2026">
                    </div>
                </div>

                <div class="form-section modern-section mb-4">
                    <h6 class="section-title text-uppercase tracking-wide text-info fw-bold mb-3"><i
                            class="fe fe-edit-3 me-2"></i>Registration Window</h6>
                    <div class="bg-white p-3 rounded-4 border shadow-sm row g-3 m-0">
                        <div class="col-md-6 px-2">
                            <label class="form-label fw-semibold text-dark small mb-2">Waktu Buka</label>
                            <input type="datetime-local" id="registration_start" class="form-control input-modern">
                        </div>
                        <div class="col-md-6 px-2">
                            <label class="form-label fw-semibold text-dark small mb-2">Waktu Tutup</label>
                            <input type="datetime-local" id="registration_end" class="form-control input-modern">
                        </div>
                    </div>
                </div>

                <div class="form-section modern-section mb-4">
                    <h6 class="section-title text-uppercase tracking-wide text-success fw-bold mb-3"><i
                            class="fe fe-target me-2"></i>Execution Period</h6>
                    <div class="bg-white p-3 rounded-4 border shadow-sm row g-3 m-0">
                        <div class="col-md-6 px-2">
                            <label class="form-label fw-semibold text-dark small mb-2">Tanggal Mulai</label>
                            <input type="date" id="period_start" class="form-control input-modern">
                        </div>
                        <div class="col-md-6 px-2">
                            <label class="form-label fw-semibold text-dark small mb-2">Tanggal Berakhir</label>
                            <input type="date" id="period_end" class="form-control input-modern">
                        </div>
                    </div>
                </div>

                <div class="form-section modern-section mb-4">
                    <h6 class="section-title text-uppercase tracking-wide text-warning fw-bold mb-3"><i
                            class="fe fe-activity me-2"></i>Initial Status</h6>
                    <div class="bg-white p-3 rounded-4 border shadow-sm">
                        <select id="status" class="form-select form-select-lg input-modern">
                            <option value="draft">Draft (Disembunyikan)</option>
                            <option value="open">Open (Terbuka untuk diisi)</option>
                            <option value="closed">Closed (Ditutup)</option>
                        </select>
                    </div>
                </div>

            </form>
        </div>

        <div class="offcanvas-footer bg-white border-top p-4 d-flex justify-content-end gap-3 shadow-lg">
            <button type="button" class="btn btn-light border px-4 rounded-3 fw-medium"
                data-bs-dismiss="offcanvas">Batal</button>
            <button type="button" id="btnSavePeriod"
                class="btn btn-primary px-5 rounded-3 fw-bold d-flex align-items-center gap-2">
                <i class="fe fe-save"></i> Simpan Periode
            </button>
        </div>

    </div>

@endsection

@push('styles')
    <style>
        /* Panel List Kiri */
        .period-list {
            max-height: calc(100vh - 200px);
            min-height: 400px;
            overflow-y: auto;
            background: #f8fafc;
        }

        .period-list::-webkit-scrollbar,
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .period-list::-webkit-scrollbar-thumb,
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        /* Period Item Card Modern */
        .period-item {
            margin-bottom: 10px;
            padding: 16px;
            border: 1px solid transparent;
            border-radius: 14px;
            background: #fff;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .period-item:hover {
            transform: translateY(-2px);
            border-color: #cbd5e1;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .period-item.active {
            border-color: #3b82f6;
            background: #eff6ff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .period-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #f1f5f9;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: 0.3s;
        }

        .period-item.active .period-icon {
            background: #3b82f6;
            color: #fff;
            transform: scale(1.05);
        }

        /* Panel Kanan Stats */
        .mini-stat {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            transition: 0.2s;
        }

        .mini-stat:hover {
            border-color: #e2e8f0;
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .mini-stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.4rem;
        }

        /* Input Form & Offcanvas */
        .kpi-offcanvas {
            width: 500px !important;
            background: #f8fafc;
        }

        .input-modern {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            transition: all 0.2s ease;
            box-shadow: none;
        }

        .input-modern:focus {
            background-color: #fff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .modern-section .section-title {
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        /* Utilitas Tampilan */
        .transition-hover:hover {
            filter: brightness(0.95);
        }

        .tracking-wide {
            letter-spacing: 0.5px;
        }

        .transition-fade {
            transition: opacity 0.3s ease;
        }

        .btn-delete-period {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 10px;
            background: transparent;
            transition: 0.2s;
        }

        .btn-delete-period:hover {
            background: #fee2e2;
            color: #dc2626 !important;
        }

        /* Badges */
        .status-open {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .status-closed {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .status-draft {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {

            let periodCanvasElement = document.getElementById('periodCanvas');
            let periodCanvas = null;
            if (periodCanvasElement) {
                periodCanvas = new bootstrap.Offcanvas(periodCanvasElement);
            }

            // Variable to hold the data of the currently viewed period
            let currentPeriodData = null;

            // Helper format for datetime-local (requires YYYY-MM-DDTHH:mm)
            function formatDateTimeLocal(dateStr) {
                if (!dateStr) return '';
                if (dateStr.includes(' ')) {
                    return dateStr.replace(' ', 'T').substring(0, 16);
                }
                return dateStr.substring(0, 16);
            }

            // Format for <input type="datetime-local"> (Needs YYYY-MM-DDTHH:mm)
            function formatDateTimeLocal(dateStr) {
                if (!dateStr) return '';
                // Converts "2026-01-15 14:30:00" or "2026-01-15T14:30:00.000Z" to "2026-01-15T14:30"
                return dateStr.replace(' ', 'T').substring(0, 16);
            }

            // Format for <input type="date"> (Needs exactly YYYY-MM-DD)
            function formatDate(dateStr) {
                if (!dateStr) return '';
                return dateStr.substring(0, 10);
            }

            // Helper to format date for the UI display (e.g., "16 Jun 2026 • 16:44")
            function formatUIDate(dateString) {
                if (!dateString) return '-';

                let parts = dateString.split(' ');
                let datePart = parts[0];
                let timePart = parts[1];

                let dateElements = datePart.split('-');
                if (dateElements.length !== 3) return dateString; // fallback if format is unknown

                let year = dateElements[0];
                let monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                let month = monthNames[parseInt(dateElements[1], 10) - 1];
                let day = parseInt(dateElements[2], 10);

                let formatted = `${day} ${month} ${year}`;

                // If time exists, append it (Hours:Minutes)
                if (timePart) {
                    let timeElements = timePart.split(':');
                    if (timeElements.length >= 2) {
                        formatted += ` <span class="text-primary fw-bold ms-1">${timeElements[0]}:${timeElements[1]}</span>`;
                    }
                }

                return formatted;
            }

            // ==========================================
            // 1. INIT DATA
            // ==========================================
            loadPeriods();

            function getStatusClass(status) {
                status = (status || '').toLowerCase();
                if (status === 'open') return 'status-open';
                if (status === 'closed') return 'status-closed';
                return 'status-draft';
            }

            // ==========================================
            // 2. FETCH LIST (LEFT PANEL)
            // ==========================================
            function loadPeriods() {
                $.get('/kpi/period', function (res) {
                    let html = '';

                    if (!res.data || res.data.length === 0) {
                        $('#periodListContainer').html(`
                                                                <div class="text-center py-5 text-muted">
                                                                    <div class="bg-white d-inline-flex p-3 rounded-circle shadow-sm mb-3 border">
                                                                        <i class="fe fe-inbox fs-2"></i>
                                                                    </div>
                                                                    <h6 class="fw-bold text-dark">Belum Ada Periode</h6>
                                                                    <small class="d-block">Silakan buat periode KPI baru terlebih dahulu.</small>
                                                                </div>
                                                            `);
                        return;
                    }

                    res.data.forEach(period => {
                        let stClass = getStatusClass(period.status);
                        html += `
                                                                <div class="period-item" data-id="${period.id}">
                                                                    <div class="d-flex align-items-center justify-content-between w-100">
                                                                        <div class="d-flex align-items-center gap-3">
                                                                            <div class="period-icon shadow-sm border border-light"><i class="fe fe-calendar"></i></div>
                                                                            <div>
                                                                                <div class="fw-bold text-dark lh-sm mb-1">${period.name}</div>
                                                                                <div class="d-flex align-items-center gap-2">
                                                                                    <span class="badge ${stClass} tracking-wide" style="font-size: 0.6rem;">${period.status.toUpperCase()}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <button type="button" class="btn-delete-period text-muted" data-id="${period.id}" title="Hapus Periode">
                                                                                <i class="fe fe-trash-2"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            `;
                    });

                    $('#periodListContainer').html(html);

                    let firstOpen = res.data.find(p => p.status.toLowerCase() === 'open');
                    let targetId = firstOpen ? firstOpen.id : res.data[0].id;

                    if (targetId) {
                        loadPeriodDetails(targetId);
                    }
                }).fail(function () {
                    $('#periodListContainer').html('<div class="text-center text-danger py-4 fw-medium"><i class="fe fe-alert-triangle me-2"></i>Gagal memuat daftar periode.</div>');
                });
            }

            // ==========================================
            // 3. FETCH DETAIL (RIGHT PANEL)
            // ==========================================
            function loadPeriodDetails(id) {
                $('.period-item').removeClass('active');
                $(`.period-item[data-id="${id}"]`).addClass('active');

                $('#periodDetailContent').addClass('d-none');
                $('#periodDetailLoader').removeClass('d-none').addClass('d-flex');
                $('#loaderPlaceholder').addClass('d-none');
                $('#loaderSpinner').removeClass('d-none');

                $.ajax({
                    url: `/kpi/period/${id}`,
                    method: 'GET',
                    success: function (res) {
                        let data = res.data || {};
                        let stats = data.stats || {};
                        currentPeriodData = data; // Cache data for editing

                        $('#detailPeriodName').text(data.name || '-');

                        // Using the new helper for Registration Dates
                        $('#detailRegDate').html(`${formatUIDate(data.registration_start)} <span class="text-muted mx-1">s/d</span> ${formatUIDate(data.registration_end)}`);

                        $('#detailStatusBadge')
                            .text((data.status || 'draft').toUpperCase())
                            .removeClass('status-open status-closed status-draft')
                            .addClass(getStatusClass(data.status));

                        $('#statEmployee').text(stats.employee || 0);
                        $('#statSupervisor').text(stats.supervisor || 0);
                        $('#statManager').text(stats.manager || 0);
                        $('#statPending').text(stats.pending || 0);

                        // Using the new helper for Period Dates inside the Timeline Configuration boxes
                        $('#detailPeriodDate').html(`${formatUIDate(data.period_start)} <br> <span class="text-muted small">s/d</span> <br> ${formatUIDate(data.period_end)}`);
                        $('#detailRegDateBox').html(`${formatUIDate(data.registration_start)} <br> <span class="text-muted small">s/d</span> <br> ${formatUIDate(data.registration_end)}`);

                        // Logic to Show/Hide Edit Button based on Status
                        if (data.status && data.status.toLowerCase() === 'closed') {
                            $('#btnEditPeriod').removeClass('d-flex').addClass('d-none');
                        } else {
                            $('#btnEditPeriod').removeClass('d-none').addClass('d-flex');
                        }
                    },
                    error: function () {
                        $('#loaderSpinner').addClass('d-none');
                        $('#loaderPlaceholder').removeClass('d-none').html(`
                                                                <div class="text-danger">
                                                                    <i class="fe fe-alert-circle fs-1 mb-3"></i>
                                                                    <h5 class="fw-bold mb-1">Gagal Memuat Detail</h5>
                                                                    <p class="small">Koneksi terputus atau data tidak ditemukan.</p>
                                                                </div>
                                                            `);
                    },
                    complete: function () {
                        $('#periodDetailLoader').removeClass('d-flex').addClass('d-none');
                        $('#periodDetailContent').removeClass('d-none').hide().fadeIn(300);
                    }
                });
            }

            // ==========================================
            // 4. EVENT LISTENERS
            // ==========================================

            $(document).on('click', '.period-item', function (e) {
                if ($(e.target).closest('.btn-delete-period').length) return;
                let id = $(this).data('id');
                loadPeriodDetails(id);
            });

            // Action: Click "Buat Baru"
            $('#btnCreatePeriod').on('click', function () {
                $('#periodForm')[0].reset();
                $('#period_id').val(''); // Clear hidden ID for creation
                $('#canvasTitle').text('Create KPI Period');
                if (periodCanvas) periodCanvas.show();
            });

            // Action: Click "Edit Periode"
            // Action: Click "Edit Periode"
            $('#btnEditPeriod').on('click', function () {
                if (!currentPeriodData) return;
                console.log(currentPeriodData)
                // Populate the form fields with cached data
                $('#period_id').val(currentPeriodData.id);
                $('#name').val(currentPeriodData.name);

                // Use formatDateTimeLocal for datetime inputs
                $('#registration_start').val(formatDateTimeLocal(currentPeriodData.registration_start));
                $('#registration_end').val(formatDateTimeLocal(currentPeriodData.registration_end));

                // Use formatDate for date-only inputs
                $('#period_start').val(formatDate(currentPeriodData.period_start));
                $('#period_end').val(formatDate(currentPeriodData.period_end));

                // Ensure status is lowercase to match exactly with the <option value="...">
                $('#status').val((currentPeriodData.status || 'draft').toLowerCase());

                $('#canvasTitle').text('Edit KPI Period');
                if (periodCanvas) periodCanvas.show();
            });

            $('#period_start').on('change', function () {
                let date = $(this).val();
                if (!date) return;
                let d = new Date(date);
                let month = d.toLocaleString('en-US', { month: 'long' });
                let year = d.getFullYear();

                if ($('#name').val() === '') {
                    $('#name').val(`KPI ${month} ${year}`);
                }
            });

            // Action: Save Period (Handle Create & Update)
            $('#btnSavePeriod').on('click', function () {
                let btn = $(this);
                let originalText = btn.html();
                let periodId = $('#period_id').val();

                // Decide Method and URL based on presence of period_id
                let url = periodId ? `/kpi/period/${periodId}/update` : "/kpi/period";
                let method = periodId ? "PUT" : "POST";

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        name: $('#name').val(),
                        registration_start: $('#registration_start').val(),
                        registration_end: $('#registration_end').val(),
                        period_start: $('#period_start').val(),
                        period_end: $('#period_end').val(),
                        status: $('#status').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        swal({ type: 'success', title: 'Berhasil', text: res.message });
                        if (periodCanvas) periodCanvas.hide();
                        loadPeriods();
                    },
                    error: function (xhr) {
                        swal({ type: 'error', title: 'Gagal', text: xhr.responseJSON?.message ?? 'Validasi form gagal. Periksa kembali inputan Anda.' });
                    },
                    complete: function () {
                        btn.prop('disabled', false).html(originalText);
                    }
                });
            });

            $(document).on('click', '.btn-delete-period', function (e) {
                e.stopPropagation();
                let id = $(this).data('id');

                swal({
                    title: 'Apakah Anda Yakin?',
                    text: 'Data periode KPI ini akan dihapus secara permanen.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'btn-danger',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    closeOnConfirm: false
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: `/kpi/period/${id}`,
                            type: 'DELETE',
                            data: { _token: "{{ csrf_token() }}" },
                            success: function (res) {
                                swal('Berhasil!', res.message, 'success');

                                $('#periodDetailContent').addClass('d-none');
                                $('#periodDetailLoader').removeClass('d-none').addClass('d-flex');
                                $('#loaderSpinner').addClass('d-none');
                                $('#loaderPlaceholder').removeClass('d-none');

                                loadPeriods();
                            },
                            error: function (xhr) {
                                swal('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan sistem', 'error');
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush