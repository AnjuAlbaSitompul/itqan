@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4 hr-approval-page bg-light min-vh-100">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3 bg-white p-4 rounded-4 shadow-sm border-0 position-relative overflow-hidden">
            <div class="position-absolute top-0 end-0 bg-primary bg-opacity-10 w-25 h-100"
                style="clip-path: polygon(25% 0%, 100% 0%, 100% 100%, 0% 100%);"></div>

            <div class="d-flex align-items-center gap-3 position-relative z-1">
                <div class="bg-gradient bg-primary p-3 rounded-circle shadow-sm">
                    <i class="bi bi-shield-check fs-3 text-white"></i>
                </div>
                <div>
                    <h2 class="mb-1 fw-black text-dark tracking-tight">Report & Approval HR</h2>
                    <p class="text-secondary mb-0 small fw-medium">Kelola persetujuan final Mutasi, Peringatan, dan Man
                        Power secara efisien.</p>
                </div>
            </div>
            <button
                class="btn btn-primary bg-gradient d-flex align-items-center gap-2 rounded-pill px-4 py-2.5 shadow position-relative z-1 transition-all hover-lift"
                type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                <i class="bi bi-sliders"></i> Filter Laporan
            </button>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card rounded-4 overflow-hidden">
                    <div class="card-body d-flex align-items-center p-4 position-relative">
                        <div class="position-absolute top-0 end-0 p-3 opacity-10">
                            <i class="bi bi-arrow-left-right" style="font-size: 4rem;"></i>
                        </div>
                        <div class="stat-icon bg-gradient bg-info text-white rounded-circle shadow-sm">
                            <i class="bi bi-arrow-left-right fs-4"></i>
                        </div>
                        <div class="ms-4 position-relative z-1">
                            <p class="text-secondary mb-1 fw-bold text-uppercase small tracking-wide">Menunggu Mutasi</p>
                            <h2 class="mb-0 fw-black text-dark display-6" id="count-mutasi">0</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card rounded-4 overflow-hidden">
                    <div class="card-body d-flex align-items-center p-4 position-relative">
                        <div class="position-absolute top-0 end-0 p-3 opacity-10">
                            <i class="bi bi-exclamation-triangle" style="font-size: 4rem;"></i>
                        </div>
                        <div class="stat-icon bg-gradient bg-warning text-white rounded-circle shadow-sm">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </div>
                        <div class="ms-4 position-relative z-1">
                            <p class="text-secondary mb-1 fw-bold text-uppercase small tracking-wide">Menunggu SP</p>
                            <h2 class="mb-0 fw-black text-dark display-6" id="count-peringatan">0</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card rounded-4 overflow-hidden">
                    <div class="card-body d-flex align-items-center p-4 position-relative">
                        <div class="position-absolute top-0 end-0 p-3 opacity-10">
                            <i class="bi bi-people" style="font-size: 4rem;"></i>
                        </div>
                        <div class="stat-icon bg-gradient bg-primary text-white rounded-circle shadow-sm">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                        <div class="ms-4 position-relative z-1">
                            <p class="text-secondary mb-1 fw-bold text-uppercase small tracking-wide">Req. Man Power</p>
                            <h2 class="mb-0 fw-black text-dark display-6" id="count-manpower">0</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column gap-4">
            <div class="d-flex justify-content-center justify-content-md-start">
                <ul class="nav nav-pills custom-pills p-2 bg-white rounded-pill shadow-sm border border-light"
                    id="approvalTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4 py-2 d-flex align-items-center gap-2"
                            id="mutasi-tab" data-bs-toggle="tab" data-bs-target="#mutasi-pane" type="button" role="tab">
                            <i class="bi bi-arrow-left-right"></i> Mutasi
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 py-2 d-flex align-items-center gap-2" id="peringatan-tab"
                            data-bs-toggle="tab" data-bs-target="#peringatan-pane" type="button" role="tab">
                            <i class="bi bi-shield-exclamation"></i> Peringatan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 py-2 d-flex align-items-center gap-2" id="manpower-tab"
                            data-bs-toggle="tab" data-bs-target="#manpower-pane" type="button" role="tab">
                            <i class="bi bi-person-plus"></i> Man Power
                        </button>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="approvalTabsContent">
                <div class="tab-pane fade show active" id="mutasi-pane" role="tabpanel">
                    <div class="accordion modern-accordion" id="accordionMutasi"></div>
                </div>
                <div class="tab-pane fade" id="peringatan-pane" role="tabpanel">
                    <div class="accordion modern-accordion" id="accordionPeringatan"></div>
                </div>
                <div class="tab-pane fade" id="manpower-pane" role="tabpanel">
                    <div class="accordion modern-accordion" id="accordionManPower"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="filterOffcanvas">
        <div class="offcanvas-header bg-light border-bottom p-4">
            <h5 class="offcanvas-title fw-bolder text-dark d-flex align-items-center gap-2">
                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle">
                    <i class="bi bi-funnel-fill"></i>
                </div>
                Filter Laporan
            </h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4 bg-white">
            <form id="filterForm">
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary small text-uppercase tracking-wide mb-3">Status
                        Approval</label>
                    <select class="form-select form-select-lg shadow-none border-secondary-subtle bg-light" name="status"
                        id="filter_status">
                        <option value="pending" selected>⏳ Menunggu (Pending)</option>
                        <option value="approved">✅ Disetujui (Approved)</option>
                        <option value="completed">🌟 Selesai (Completed)</option>
                        <option value="rejected">❌ Ditolak (Rejected)</option>
                        <option value="all">🌐 Semua Status</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary small text-uppercase tracking-wide mb-3">Rentang
                        Waktu</label>
                    <div class="input-group mb-3 border rounded-3 overflow-hidden focus-ring-group">
                        <span class="input-group-text bg-light border-0"><i
                                class="bi bi-calendar-minus text-muted"></i></span>
                        <input type="date" class="form-control border-0 shadow-none bg-light" name="start_date"
                            id="filter_start">
                    </div>
                    <div class="input-group border rounded-3 overflow-hidden focus-ring-group">
                        <span class="input-group-text bg-light border-0"><i
                                class="bi bi-calendar-plus text-muted"></i></span>
                        <input type="date" class="form-control border-0 shadow-none bg-light" name="end_date"
                            id="filter_end">
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit" class="btn btn-primary bg-gradient w-100 rounded-pill py-3 fw-bold shadow">
                        <i class="bi bi-check2-all me-2"></i> Terapkan Filter
                    </button>
                    <button type="reset" class="btn btn-light w-100 rounded-pill py-3 fw-bold mt-2 border"
                        onclick="setTimeout(()=>$('#filterForm').submit(), 100)">
                        Reset Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .hr-approval-page {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .tracking-wide {
            letter-spacing: 0.05em;
        }

        .tracking-tight {
            letter-spacing: -0.02em;
        }

        .fw-black {
            font-weight: 800;
        }

        /* Stat Cards */
        .stat-card {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }

        .stat-icon {
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Nav Pills */
        .custom-pills .nav-link {
            color: #64748b;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .custom-pills .nav-link:hover:not(.active) {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .custom-pills .nav-link.active {
            background-color: var(--bs-primary);
            color: white;
            box-shadow: 0 10px 15px -3px rgba(13, 110, 253, 0.3);
            transform: translateY(-2px);
        }

        /* Accordion Enhancements */
        .modern-accordion .accordion-item {
            border: 1px solid #e2e8f0;
            background: #fff;
            margin-bottom: 1.5rem;
            border-radius: 1rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .modern-accordion .accordion-item:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Status Borders */
        .modern-accordion .accordion-item.status-pending {
            border-left: 6px solid var(--bs-warning);
        }

        .modern-accordion .accordion-item.status-approved {
            border-left: 6px solid var(--bs-success);
        }

        .modern-accordion .accordion-item.status-completed {
            border-left: 6px solid var(--bs-info);
        }

        .modern-accordion .accordion-item.status-rejected {
            border-left: 6px solid var(--bs-danger);
        }

        .modern-accordion .accordion-header {
            background: transparent;
        }

        .modern-accordion .accordion-button {
            background-color: transparent;
            padding: 1.25rem 1.5rem;
            box-shadow: none !important;
            font-weight: 600;
        }

        .modern-accordion .accordion-button:not(.collapsed) {
            background-color: #f8fafc;
            color: var(--bs-primary);
            border-bottom: 1px solid #e2e8f0;
        }

        .modern-accordion .accordion-body {
            padding: 1.5rem;
            background-color: #fcfcfd;
        }

        /* Detail Boxes */
        .info-card {
            background: #ffffff;
            border-radius: 0.75rem;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .info-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 800;
            color: #94a3b8;
            margin-bottom: 0.5rem;
            display: block;
            letter-spacing: 0.05em;
        }

        .info-value {
            font-weight: 600;
            color: #1e293b;
            font-size: 1rem;
            line-height: 1.5;
        }

        /* Action Area */
        .action-area {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            border: 1px solid #e2e8f0;
            position: relative;
        }

        .action-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--bs-primary), var(--bs-info));
            border-radius: 4px 4px 0 0;
        }

        .focus-ring-group:focus-within {
            border-color: #86b7fe !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // Initial Load
            loadApprovalData();

            // Filter Handler
            $('#filterForm').on('submit', function (e) {
                e.preventDefault();
                let offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('filterOffcanvas'));
                if (offcanvas) offcanvas.hide();

                // Skeleton loading state
                $('.modern-accordion').html(`
                        <div class="text-center py-5 bg-white rounded-4 border">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
                            <h5 class="mt-4 fw-bold text-dark">Menyelaraskan Data...</h5>
                            <p class="text-muted">Mohon tunggu sebentar.</p>
                        </div>
                    `);

                loadApprovalData();
            });

            function loadApprovalData() {
                let params = {
                    status: $('#filter_status').val(),
                    start_date: $('#filter_start').val(),
                    end_date: $('#filter_end').val()
                };

                $.get('{{ route("approval.data") }}', params)
                    .done(function (res) {
                        if (res.success) {
                            renderMutasi(res.data.mutasi);
                            renderPeringatan(res.data.peringatan);
                            renderManPower(res.data.man_power);

                            // Update counts based on 'pending' items
                            let st = params.status;
                            if (st === 'pending' || st === 'all') {
                                animateValue('count-mutasi', res.data.mutasi.filter(i => i.status === 'pending').length);
                                animateValue('count-peringatan', res.data.peringatan.filter(i => i.status === 'pending').length);
                                animateValue('count-manpower', res.data.man_power.filter(i => i.status === 'pending').length);
                            }
                        }
                    })
                    .fail(function (err) {
                        console.error("Gagal mengambil data", err);
                        $('.modern-accordion').html(renderEmpty("Terjadi kesalahan sistem saat memuat data. Silakan muat ulang halaman.", "bi-exclamation-octagon", "text-danger"));
                    });
            }

            function animateValue(id, end) {
                $(`#${id}`).prop('Counter', $(`#${id}`).text()).animate({
                    Counter: end
                }, {
                    duration: 800,
                    easing: 'swing',
                    step: function (now) { $(this).text(Math.ceil(now)); }
                });
            }

            function getStatusBadge(status) {
                const badges = {
                    'approved': '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-check2-all me-2"></i>Approved</span>',
                    'completed': '<span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-patch-check-fill me-2"></i>Completed</span>',
                    'rejected': '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-x-circle-fill me-2"></i>Rejected</span>',
                    'pending': '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-hourglass-split me-2"></i>Pending</span>'
                };
                return badges[status] || badges['pending'];
            }

            function formatDate(dateString) {
                if (!dateString) return '-';
                return new Date(dateString).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
            }

            function getAvatar(name) {
                return `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=random&color=fff&rounded=true&bold=true`;
            }

            function renderEmpty(message, icon = "bi-inbox", colorClass = "text-secondary") {
                return `
                    <div class="text-center py-5 bg-white rounded-4 border mt-2 shadow-sm d-flex flex-column align-items-center justify-content-center" style="min-height: 300px;">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 80px; height: 80px;">
                            <i class="bi ${icon} fs-1 ${colorClass}"></i>
                        </div>
                        <h4 class="fw-bolder text-dark mb-2">Data Tidak Ditemukan</h4>
                        <p class="text-muted mb-0 w-75">${message}</p>
                    </div>`;
            }

            // ================== M U T A S I ==================
            function renderMutasi(data) {
                const container = $('#accordionMutasi');
                container.empty();

                if (data.length === 0) {
                    container.html(renderEmpty("Belum ada pengajuan mutasi yang sesuai dengan filter Anda.", "bi-folder-x"));
                    return;
                }

                let html = '';
                data.forEach((item) => {
                    let accId = `mutasi_${item.id}`;
                    let isPending = item.status === 'pending';
                    let empName = item.user ? item.user.name : 'Unknown';
                    let statusClass = `status-${item.status}`;

                    html += `
                        <div class="accordion-item ${statusClass}">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${accId}">
                                    <div class="d-flex align-items-center justify-content-between w-100 pe-3 flex-wrap gap-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="${getAvatar(empName)}" alt="Avatar" width="50" height="50" class="rounded-circle shadow-sm border border-2 border-white">
                                            <div>
                                                <div class="fw-black text-dark mb-1 fs-5">${empName}</div>
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <span class="badge bg-light text-secondary border border-secondary-subtle px-2 py-1"><i class="bi bi-building me-1"></i>${item.from_unit ? item.from_unit.name : '-'}</span>
                                                    <i class="bi bi-arrow-right text-primary"></i>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-2 py-1"><i class="bi bi-geo-alt-fill me-1"></i>${item.to_unit ? item.to_unit.name : '-'}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>${getStatusBadge(item.status)}</div>
                                    </div>
                                </button>
                            </h2>
                            <div id="${accId}" class="accordion-collapse collapse" data-bs-parent="#accordionMutasi">
                                <div class="accordion-body">
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-6">
                                            <div class="info-card h-100 position-relative overflow-hidden">
                                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-light opacity-50" style="z-index: 0;"></div>
                                                <div class="position-relative z-1">
                                                    <span class="info-label"><i class="bi bi-info-circle me-1"></i> Info Pengajuan</span>
                                                    <div class="mb-3">
                                                        <span class="text-muted small d-block mb-1">Diajukan Oleh:</span> 
                                                        <span class="info-value fw-bold text-dark d-flex align-items-center gap-2"><i class="bi bi-person-circle text-primary"></i> ${item.requested_by ? item.requested_by.name : '-'}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-muted small d-block mb-1">Tanggal Diajukan:</span> 
                                                        <span class="info-value"><i class="bi bi-calendar-event me-1 text-secondary"></i> ${formatDate(item.created_at)}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-card h-100">
                                                <span class="info-label"><i class="bi bi-chat-left-text me-1"></i> Alasan Mutasi</span>
                                                <span class="info-value text-wrap text-secondary lh-lg">${item.reason || '<i class="text-muted fw-normal">Tidak ada alasan spesifik yang dilampirkan.</i>'}</span>
                                            </div>
                                        </div>
                                    </div>

                                    ${isPending ? `
                                    <form class="action-area p-4 rounded-4 shadow-sm" data-type="mutasi" data-id="${item.id}">
                                        <h6 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2"><i class="bi bi-person-check fs-4 text-primary"></i> Formulir Keputusan HR</h6>
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-secondary text-uppercase tracking-wide">Tgl Efektif Mutasi <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control form-control-lg bg-white shadow-none" name="effective_date" required>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label small fw-bold text-secondary text-uppercase tracking-wide">Catatan Keputusan (Opsional)</label>
                                                <input type="text" class="form-control form-control-lg bg-white shadow-none" name="approval_notes" placeholder="Berikan instruksi atau catatan khusus...">
                                            </div>
                                            <div class="col-12 d-flex gap-3 pt-2">
                                                <button type="button" class="btn btn-success px-5 py-2.5 fw-bold rounded-pill shadow-sm btn-approve transition-all"><i class="bi bi-check-lg me-2"></i> Setujui Pengajuan</button>
                                                <button type="button" class="btn btn-outline-danger px-5 py-2.5 fw-bold rounded-pill btn-reject transition-all"><i class="bi bi-x-lg me-2"></i> Tolak Pengajuan</button>
                                            </div>
                                        </div>
                                    </form>` : `
                                    <div class="alert alert-light border border-secondary-subtle d-flex align-items-start gap-3 mb-0 rounded-4">
                                        <i class="bi bi-info-circle-fill text-${item.status === 'approved' ? 'success' : (item.status === 'completed' ? 'info' : 'danger')} fs-3 mt-1"></i>
                                        <div>
                                            <h6 class="mb-1 fw-black text-dark">Status Pengajuan Terkini</h6>
                                            <p class="text-secondary mb-0">Permintaan ini telah diproses dan masuk dalam kategori <strong>${item.status.toUpperCase()}</strong>. ${item.effective_date ? `<br>Tanggal Berlaku: <strong class="text-dark">${formatDate(item.effective_date)}</strong>` : ''}</p>
                                        </div>
                                    </div>
                                    `}
                                </div>
                            </div>
                        </div>`;
                });
                container.html(html);
            }

            // ================== P E R I N G A T A N ==================
            function renderPeringatan(data) {
                const container = $('#accordionPeringatan');
                container.empty();

                if (data.length === 0) {
                    container.html(renderEmpty("Tidak ada data pengajuan Surat Peringatan.", "bi-shield-check"));
                    return;
                }

                const typeLabels = { 'peringatan_1': 'Surat Peringatan 1', 'peringatan_2': 'Surat Peringatan 2', 'peringatan_3': 'Surat Peringatan 3' };
                const typeColors = { 'peringatan_1': 'warning', 'peringatan_2': 'orange', 'peringatan_3': 'danger' };

                let html = '';
                data.forEach((item) => {
                    let accId = `peringatan_${item.id}`;
                    let isPending = item.status === 'pending';
                    let typeName = typeLabels[item.type] || item.type;
                    let colorCode = typeColors[item.type] || 'danger';
                    let empName = item.user ? item.user.name : 'Unknown';
                    let statusClass = `status-${item.status}`;

                    html += `
                        <div class="accordion-item ${statusClass}">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${accId}">
                                    <div class="d-flex align-items-center justify-content-between w-100 pe-3 flex-wrap gap-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-gradient bg-${colorCode} rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px">
                                                <i class="bi bi-exclamation-triangle-fill fs-4 text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-black text-dark mb-1 fs-5">${empName}</div>
                                                <div class="small fw-bold text-${colorCode} bg-${colorCode} bg-opacity-10 px-2 py-1 rounded-pill d-inline-block"><i class="bi bi-envelope-exclamation me-1"></i> ${typeName}</div>
                                            </div>
                                        </div>
                                        <div>${getStatusBadge(item.status)}</div>
                                    </div>
                                </button>
                            </h2>
                            <div id="${accId}" class="accordion-collapse collapse" data-bs-parent="#accordionPeringatan">
                                <div class="accordion-body">
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-5">
                                            <div class="info-card h-100 border-start border-4 border-${colorCode}">
                                                <span class="info-label">Detail Penerbitan</span>
                                                <div class="mb-3">
                                                    <span class="text-muted small d-block mb-1">Diajukan Oleh:</span> 
                                                    <span class="info-value fw-bold"><i class="bi bi-person me-1"></i> ${item.requested_by ? item.requested_by.name : '-'}</span>
                                                </div>
                                                <div>
                                                    <span class="text-muted small d-block mb-1">Tanggal Issued:</span> 
                                                    <span class="info-value"><i class="bi bi-calendar-x me-1"></i> ${formatDate(item.issued_date)}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="info-card h-100 bg-${colorCode} bg-opacity-10 border-0">
                                                <span class="info-label text-${colorCode}"><i class="bi bi-journal-x me-1"></i> Alasan / Pelanggaran</span>
                                                <span class="info-value text-dark fw-medium lh-lg">${item.reason || '-'}</span>
                                            </div>
                                        </div>
                                    </div>

                                    ${isPending ? `
                                    <form class="action-area p-4 rounded-4 shadow-sm" data-type="peringatan" data-id="${item.id}">
                                        <h6 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2"><i class="bi bi-shield-lock fs-4 text-primary"></i> Otorisasi Surat Peringatan</h6>
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-secondary text-uppercase tracking-wide">Berlaku Sampai <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control form-control-lg bg-white shadow-none" name="due_date" required>
                                                <div class="form-text small mt-2"><i class="bi bi-info-circle me-1"></i> Default SP: 3 Bulan.</div>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label small fw-bold text-secondary text-uppercase tracking-wide">Ketentuan Tambahan (HR)</label>
                                                <textarea class="form-control form-control-lg bg-white shadow-none" name="approval_notes" rows="2" placeholder="Cth: Karyawan wajib mengikuti program coaching..."></textarea>
                                            </div>
                                            <div class="col-12 d-flex gap-3 pt-2">
                                                <button type="button" class="btn btn-success px-5 py-2.5 fw-bold rounded-pill shadow-sm btn-approve"><i class="bi bi-check-lg me-2"></i> Sahkan SP</button>
                                                <button type="button" class="btn btn-outline-danger px-5 py-2.5 fw-bold rounded-pill btn-reject"><i class="bi bi-x-lg me-2"></i> Tolak SP</button>
                                            </div>
                                        </div>
                                    </form>` : `
                                    <div class="alert alert-light border border-secondary-subtle d-flex align-items-start gap-3 mb-0 rounded-4">
                                        <i class="bi bi-info-circle-fill text-secondary fs-3 mt-1"></i>
                                        <div>
                                            <h6 class="mb-1 fw-black text-dark">Validasi Selesai</h6>
                                            <p class="text-secondary mb-0">Status validasi dokumen ini adalah <strong>${item.status.toUpperCase()}</strong>. ${item.due_date ? `Masa berlaku s/d <strong>${formatDate(item.due_date)}</strong>.` : ''}</p>
                                        </div>
                                    </div>
                                    `}
                                </div>
                            </div>
                        </div>`;
                });
                container.html(html);
            }

            // ================== M A N  P O W E R ==================
            function renderManPower(data) {
                const container = $('#accordionManPower');
                container.empty();

                if (data.length === 0) {
                    container.html(renderEmpty("Tidak ada permintaan penambahan Man Power saat ini.", "bi-person-badge"));
                    return;
                }

                let html = '';
                data.forEach((item) => {
                    let accId = `manpower_${item.id}`;
                    let isPending = item.status === 'pending';
                    let statusClass = `status-${item.status}`;

                    let candidateOptions = '<option value="">🌐 Buka Lowongan Baru (Eksternal)</option>';
                    if (item.candidates && item.candidates.length > 0) {
                        candidateOptions += '<optgroup label="Kandidat Mutasi Silang (Internal)">';
                        item.candidates.forEach(cand => {
                            candidateOptions += `<option value="${cand.id}">🔄 ${cand.name} (${cand.jabatan} | ${cand.unit_name})</option>`;
                        });
                        candidateOptions += '</optgroup>';
                    }

                    let projectedCount = item.current_headcount + item.jumlah_manpower;

                    html += `
                        <div class="accordion-item ${statusClass}">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${accId}">
                                    <div class="d-flex align-items-center justify-content-between w-100 pe-3 flex-wrap gap-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-gradient bg-primary text-white rounded-4 shadow-sm d-flex align-items-center justify-content-center" style="width:55px; height:55px">
                                                <i class="bi bi-building-add fs-3"></i>
                                            </div>
                                            <div>
                                                <div class="fw-black text-dark mb-1 fs-5">${item.unit_name}</div>
                                                <div class="small fw-bold text-primary bg-primary bg-opacity-10 px-2 py-1 rounded-pill d-inline-block"><i class="bi bi-person-plus-fill me-1"></i> Membutuhkan +${item.jumlah_manpower} Personil</div>
                                            </div>
                                        </div>
                                        <div>${getStatusBadge(item.status)}</div>
                                    </div>
                                </button>
                            </h2>
                            <div id="${accId}" class="accordion-collapse collapse" data-bs-parent="#accordionManPower">
                                <div class="accordion-body">
                                    <div class="row g-4 mb-4">
                                        <div class="col-4">
                                            <div class="p-4 bg-white border rounded-4 text-center shadow-sm h-100 d-flex flex-column justify-content-center">
                                                <h2 class="fw-black text-secondary mb-0 display-6">${item.current_headcount}</h2>
                                                <span class="small text-muted fw-bold text-uppercase mt-2 tracking-wide" style="font-size:0.7rem">Headcount Saat Ini</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-4 bg-gradient bg-primary border-0 rounded-4 text-center shadow h-100 d-flex flex-column justify-content-center">
                                                <h2 class="fw-black text-white mb-0 display-6">+${item.jumlah_manpower}</h2>
                                                <span class="small text-white opacity-75 fw-bold text-uppercase mt-2 tracking-wide" style="font-size:0.7rem">Diminta</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-4 bg-dark border-0 rounded-4 text-center shadow-sm h-100 d-flex flex-column justify-content-center position-relative overflow-hidden">
                                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-10" style="clip-path: polygon(0 0, 100% 0, 100% 30%, 0 100%);"></div>
                                                <h2 class="fw-black text-white mb-0 display-6 position-relative z-1">${projectedCount}</h2>
                                                <span class="small text-white-50 fw-bold text-uppercase mt-2 tracking-wide position-relative z-1" style="font-size:0.7rem">Proyeksi Final</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-card mb-4 border-0 bg-light">
                                        <div class="row g-4">
                                            <div class="col-md-4 border-end-md">
                                                <span class="info-label">Info Dokumen</span>
                                                <span class="info-value d-block"><i class="bi bi-person text-secondary me-1"></i> ${item.requested_by}</span>
                                                <small class="text-muted fw-medium d-block mt-1"><i class="bi bi-clock me-1"></i> ${item.created_at}</small>
                                            </div>
                                            <div class="col-md-8 ps-md-4">
                                                <span class="info-label">Justifikasi Pemintaan</span>
                                                <span class="info-value fw-medium text-secondary lh-lg">${item.reason || '<i class="text-muted fw-normal">Tidak ada penjelasan spesifik.</i>'}</span>
                                            </div>
                                        </div>
                                    </div>

                                    ${isPending ? `
                                    <form class="action-area p-4 rounded-4 shadow-sm border border-primary border-opacity-25" data-type="manpower" data-id="${item.id}">
                                        <h6 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2"><i class="bi bi-gear-wide-connected fs-4 text-primary"></i> Panel Eksekusi HR</h6>

                                        <div class="alert alert-primary bg-opacity-10 border-0 d-flex gap-3 align-items-start rounded-4 mb-4">
                                            <i class="bi bi-lightbulb-fill text-primary mt-1 fs-5"></i>
                                            <div class="small text-dark lh-lg">
                                                Anda memiliki opsi untuk membuka rekrutmen eksternal, atau efisiensi dengan melakukan <strong>Mutasi Silang</strong> dari personil aktif di unit operasional serupa.
                                            </div>
                                        </div>

                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-secondary text-uppercase tracking-wide">Tindakan Pemenuhan <span class="text-danger">*</span></label>
                                                <select class="form-select form-select-lg rounded-3 shadow-none bg-white" name="assigned_user_id">
                                                    ${candidateOptions}
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-secondary text-uppercase tracking-wide">Tgl Efektif (Jika Mutasi)</label>
                                                <input type="date" class="form-control form-control-lg rounded-3 shadow-none bg-white" name="effective_date_mp">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-bold text-secondary text-uppercase tracking-wide">Instruksi Tambahan</label>
                                                <input type="text" class="form-control form-control-lg rounded-3 shadow-none bg-white" name="approval_notes" placeholder="Catatan untuk requester atau file referensi...">
                                            </div>
                                            <div class="col-12 d-flex gap-3 pt-3">
                                                <button type="button" class="btn btn-primary bg-gradient px-5 py-3 fw-bold rounded-pill shadow btn-approve flex-grow-1 transition-all"><i class="bi bi-check-circle-fill me-2"></i> Eksekusi & Setujui</button>
                                                <button type="button" class="btn btn-outline-danger px-5 py-3 fw-bold rounded-pill btn-reject transition-all"><i class="bi bi-x-circle-fill me-2"></i> Tolak</button>
                                            </div>
                                        </div>
                                    </form>` : `
                                    <div class="alert alert-light border border-secondary-subtle d-flex align-items-start gap-3 mb-0 rounded-4">
                                        <i class="bi bi-info-circle-fill text-secondary fs-3 mt-1"></i>
                                        <div>
                                            <h6 class="mb-1 fw-black text-dark">Request Ditutup</h6>
                                            <p class="text-secondary mb-0">Keputusan akhir formasi Man Power: <strong>${item.status.toUpperCase()}</strong>.</p>
                                        </div>
                                    </div>
                                    `}
                                </div>
                            </div>
                        </div>`;
                });
                container.html(html);
            }

            // ================== A C T I O N  S U B M I T ==================
            $(document).on('click', '.btn-approve, .btn-reject', function () {
                const action = $(this).hasClass('btn-approve') ? 'approve' : 'reject';
                const form = $(this).closest('form');

                // Validasi manual HTML5
                if (action === 'approve' && !form[0].checkValidity()) {
                    form[0].reportValidity();
                    return;
                }

                const id = form.data('id');
                const type = form.data('type');

                let formData = {
                    action: action,
                    type: type,
                    id: id
                };

                form.serializeArray().forEach(item => {
                    formData[item.name] = item.value;
                });

                const btn = $(this);
                const btnHtml = btn.html();

                // State Loading
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Memproses...');
                form.find('button').prop('disabled', true);
                form.css('opacity', '0.6');

                $.ajax({
                    url: '{{ route("approval.action") }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res.success) {
                            // Jika kamu menggunakan library SweetAlert, panggil di sini
                            alert(res.message);
                            loadApprovalData();
                        } else {
                            alert('Gagal: ' + res.message);
                            form.css('opacity', '1');
                            form.find('button').prop('disabled', false);
                            btn.html(btnHtml);
                        }
                    },
                    error: function (xhr) {
                        let errorMsg = 'Terjadi kesalahan sistem saat memproses request.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        alert(errorMsg);

                        // Restore button
                        form.css('opacity', '1');
                        form.find('button').prop('disabled', false);
                        btn.html(btnHtml);
                    }
                });
            });
        });
    </script>
@endpush