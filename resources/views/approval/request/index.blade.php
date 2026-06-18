@extends('layouts.app')

@section('content')
    <div class="approval-page container-fluid py-4 py-lg-5">
        <div class="approval-hero card border-0 shadow-sm overflow-hidden mb-4">
            <div class="card-body p-4 p-lg-5 position-relative">
                <div class="approval-hero__glow approval-hero__glow--one"></div>
                <div class="approval-hero__glow approval-hero__glow--two"></div>

                <div class="row align-items-center g-4 position-relative">
                    <div class="col-12 col-xl-8">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge rounded-pill approval-pill">Approval Request</span>
                            <span class="badge rounded-pill approval-pill approval-pill--soft">
                                {{ auth()->user()?->role?->name ? strtoupper(auth()->user()->role->name) : 'ROLE' }}
                            </span>
                        </div>

                        <h1 class="display-6 fw-bold mb-3 approval-title">
                            Approval request atau permintaan.
                        </h1>

                        <p class="approval-lead mb-0">
                            Semua mutasi, peringatan, dan man power request ditampilkan disini. Anda dapat menyetujui atau menolak request sesuai dengan kebijakan perusahaan.
                        </p>
                    </div>

                    <div class="col-12 col-xl-4">
                        <div class="approval-hero__panel card border-0 shadow-none">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="approval-avatar">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ auth()->user()?->name ?? 'Approval Officer' }}</div>
                                        <div class="small text-muted">
                                            {{ auth()->user()?->profile?->jabatan?->name ?? 'Belum ada jabatan' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between gap-3 small text-muted mb-2">
                                    <span>Unit</span>
                                    <span class="text-end fw-semibold text-body">
                                        {{ auth()->user()?->profile?->organizationalUnit?->name ?? '-' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between gap-3 small text-muted">
                                    <span>Mode</span>
                                    <span class="text-end fw-semibold text-body">Pengawasan approval</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="approvalAlert" class="mb-4"></div>

        <div class="row g-4 mb-4">
            <div class="col-6 col-lg-3">
                <div class="approval-metric card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="metric-label">Total</div>
                        <div class="metric-value" id="metricTotal">0</div>
                        <div class="metric-subtitle">request yang ditemukan</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="approval-metric card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="metric-label">Pending</div>
                        <div class="metric-value text-warning" id="metricPending">0</div>
                        <div class="metric-subtitle">siap diproses</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="approval-metric card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="metric-label">Approved</div>
                        <div class="metric-value text-success" id="metricApproved">0</div>
                        <div class="metric-subtitle">sudah disetujui</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="approval-metric card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="metric-label">Rejected</div>
                        <div class="metric-value text-danger" id="metricRejected">0</div>
                        <div class="metric-subtitle">sudah ditolak</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card approval-filter-card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-lg-5">
                        <label for="approvalSearch" class="form-label small text-muted mb-2">Cari</label>
                        <div class="input-group approval-input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="search" class="form-control" id="approvalSearch"
                                placeholder="Nama, unit, alasan, jabatan">
                        </div>
                    </div>

                    <div class="col-12 col-lg-3">
                        <label for="approvalType" class="form-label small text-muted mb-2">Jenis</label>
                        <select id="approvalType" class="form-select approval-select">
                            <option value="all">Semua jenis</option>
                            <option value="mutasi">Mutasi</option>
                            <option value="peringatan">Peringatan</option>
                            <option value="manpower">Man Power</option>
                        </select>
                    </div>

                    <div class="col-12 col-lg-2">
                        <label for="approvalStatus" class="form-label small text-muted mb-2">Status</label>
                        <select id="approvalStatus" class="form-select approval-select">
                            <option value="pending">Pending</option>
                            <option value="all">Semua</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="col-12 col-lg-2 d-grid">
                        <button type="button" class="btn btn-outline-secondary approval-reset-btn" id="approvalReset">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card approval-list-card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 p-4 pb-0">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                    <div>
                        <h4 class="fw-bold mb-1">Daftar Request</h4>
                        <p class="text-muted mb-0">Klik item untuk membuka detail dan lakukan keputusan.</p>
                    </div>
                    <div class="approval-list-hint">
                        <i class="bi bi-lightning-charge me-2"></i>
                        <span>Semua aksi dikirim Ke SPV / Manager HR.</span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 pt-3">
                <div id="approvalLoading" class="approval-empty text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3 mb-0 text-muted">Memuat data approval...</p>
                </div>

                <div id="approvalEmpty" class="approval-empty d-none text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted"></i>
                    <h5 class="mt-3 mb-2">Tidak ada request</h5>
                    <p class="text-muted mb-0">Coba ubah filter atau cek kembali data bawahan Anda.</p>
                </div>

                <div id="approvalList" class="approval-stack d-none"></div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .approval-page {
            --approval-surface: rgba(255, 255, 255, .9);
            --approval-surface-strong: #fff;
            --approval-border: rgba(15, 23, 42, .08);
            --approval-shadow: 0 18px 40px rgba(15, 23, 42, .08);
            --approval-muted: #64748b;
            --approval-bg: linear-gradient(180deg, rgba(13, 110, 253, .08), rgba(20, 184, 166, .04));
        }

        .approval-hero,
        .approval-filter-card,
        .approval-list-card,
        .approval-metric,
        .approval-hero__panel {
            background: var(--approval-surface);
            border: 1px solid var(--approval-border);
            box-shadow: var(--approval-shadow);
            border-radius: 1.4rem;
            backdrop-filter: blur(12px);
        }

        .approval-hero {
            background: var(--approval-bg);
        }

        .approval-hero__glow {
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
            filter: blur(10px);
            opacity: .85;
        }

        .approval-hero__glow--one {
            width: 240px;
            height: 240px;
            top: -90px;
            right: -70px;
            background: rgba(var(--bs-primary-rgb), .18);
        }

        .approval-hero__glow--two {
            width: 180px;
            height: 180px;
            left: 8%;
            bottom: -70px;
            background: rgba(var(--bs-info-rgb), .12);
        }

        .approval-pill {
            padding: .55rem .85rem;
            background: rgba(var(--bs-primary-rgb), .1);
            color: var(--bs-primary);
            border: 1px solid rgba(var(--bs-primary-rgb), .15);
            letter-spacing: .04em;
        }

        .approval-pill--soft {
            background: rgba(var(--bs-dark-rgb), .06);
            color: var(--bs-body-color);
        }

        .approval-title {
            line-height: 1.05;
            max-width: 13ch;
        }

        .approval-lead {
            max-width: 64ch;
            color: var(--approval-muted);
        }

        .approval-avatar {
            width: 3.1rem;
            height: 3.1rem;
            border-radius: 1rem;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), .18), rgba(var(--bs-info-rgb), .18));
            color: var(--bs-primary);
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .approval-metric .metric-label {
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--approval-muted);
            margin-bottom: .65rem;
        }

        .approval-metric .metric-value {
            font-size: clamp(1.75rem, 4vw, 2.4rem);
            line-height: 1;
            font-weight: 800;
        }

        .approval-metric .metric-subtitle {
            margin-top: .5rem;
            color: var(--approval-muted);
            font-size: .92rem;
        }

        .approval-input-group .input-group-text,
        .approval-select {
            border-radius: 1rem;
            border-color: var(--approval-border);
            background-color: var(--approval-surface-strong);
            color: var(--bs-body-color);
        }

        .approval-input-group .input-group-text {
            border-right: 0;
        }

        .approval-input-group .form-control {
            border-left: 0;
            border-radius: 0 1rem 1rem 0;
            background: var(--approval-surface-strong);
            color: var(--bs-body-color);
        }

        .approval-input-group .form-control:focus,
        .approval-select:focus {
            box-shadow: 0 0 0 .22rem rgba(var(--bs-primary-rgb), .12);
            border-color: rgba(var(--bs-primary-rgb), .35);
        }

        .approval-reset-btn {
            border-radius: 1rem;
            min-height: 48px;
        }

        .approval-list-hint {
            padding: .8rem 1rem;
            border-radius: 1rem;
            background: rgba(var(--bs-primary-rgb), .08);
            color: var(--bs-body-color);
            border: 1px solid rgba(var(--bs-primary-rgb), .1);
        }

        .approval-stack {
            display: grid;
            gap: 1rem;
        }

        .approval-item {
            border: 1px solid var(--approval-border);
            border-radius: 1.35rem;
            overflow: hidden;
            background: var(--approval-surface-strong);
            box-shadow: 0 12px 24px rgba(15, 23, 42, .05);
        }

        .approval-item__header {
            padding: 1.15rem 1.15rem 1rem;
        }

        .approval-item__title {
            font-size: 1.08rem;
            font-weight: 800;
            margin-bottom: .35rem;
        }

        .approval-item__subtitle,
        .approval-item__meta,
        .approval-item__info,
        .approval-item__detail-label,
        .approval-item__detail-value,
        .approval-empty,
        .approval-loading {
            color: var(--bs-body-color);
        }

        .approval-item__meta,
        .approval-item__info,
        .approval-item__detail-label {
            color: var(--approval-muted);
        }

        .approval-item__toggle {
            border: 0;
            background: transparent;
            color: var(--bs-primary);
            font-weight: 700;
            padding: 0;
        }

        .approval-item__toggle i {
            transition: transform .2s ease;
        }

        .approval-item__toggle[aria-expanded="true"] i {
            transform: rotate(180deg);
        }

        .approval-item__body {
            border-top: 1px solid var(--approval-border);
            background: linear-gradient(180deg, rgba(var(--bs-primary-rgb), .02), transparent);
        }

        .approval-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .85rem;
        }

        .approval-detail {
            padding: .9rem 1rem;
            border-radius: 1rem;
            background: rgba(var(--bs-secondary-rgb), .04);
            border: 1px solid var(--approval-border);
        }

        .approval-detail--stacked {
            grid-column: 1 / -1;
        }

        .approval-detail__label {
            display: block;
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--approval-muted);
            margin-bottom: .3rem;
        }

        .approval-detail__value {
            font-weight: 700;
        }

        .approval-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .approval-actions .btn {
            border-radius: .95rem;
            min-width: 8rem;
        }

        .approval-empty,
        .approval-loading {
            border: 1px dashed var(--approval-border);
            border-radius: 1.2rem;
            background: rgba(var(--bs-secondary-rgb), .02);
        }

        .approval-page .text-muted {
            color: var(--approval-muted) !important;
        }

        [data-bs-theme="dark"] .approval-page {
            --approval-surface: rgba(17, 24, 39, .92);
            --approval-surface-strong: rgba(17, 24, 39, .98);
            --approval-border: rgba(255, 255, 255, .08);
            --approval-shadow: 0 20px 50px rgba(0, 0, 0, .35);
            --approval-muted: rgba(226, 232, 240, .72);
            --approval-bg: linear-gradient(180deg, rgba(37, 99, 235, .2), rgba(14, 165, 233, .08));
        }

        [data-bs-theme="dark"] .approval-list-hint,
        [data-bs-theme="dark"] .approval-pill--soft,
        [data-bs-theme="dark"] .approval-detail,
        [data-bs-theme="dark"] .approval-empty,
        [data-bs-theme="dark"] .approval-loading,
        [data-bs-theme="dark"] .approval-input-group .input-group-text,
        [data-bs-theme="dark"] .approval-input-group .form-control,
        [data-bs-theme="dark"] .approval-select {
            background: rgba(15, 23, 42, .82);
            border-color: rgba(255, 255, 255, .08);
        }

        @media (max-width: 991.98px) {
            .approval-title {
                max-width: none;
                font-size: clamp(2rem, 5vw, 3rem);
            }

            .approval-detail-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575.98px) {
            .approval-item__header {
                padding: 1rem;
            }

            .approval-actions .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function () {
            const listUrl = "{{ route('approval.request.list') }}";
            const defaultFilters = {
                type: 'all',
                status: 'pending',
                search: ''
            };

            let currentFilters = { ...defaultFilters };
            let searchTimer = null;

            loadApprovals();

            $('#approvalType, #approvalStatus').on('change', function () {
                currentFilters.type = $('#approvalType').val();
                currentFilters.status = $('#approvalStatus').val();
                loadApprovals();
            });

            $('#approvalSearch').on('input', function () {
                clearTimeout(searchTimer);

                searchTimer = setTimeout(function () {
                    currentFilters.search = $('#approvalSearch').val();
                    loadApprovals();
                }, 250);
            });

            $('#approvalReset').on('click', function () {
                $('#approvalType').val(defaultFilters.type);
                $('#approvalStatus').val(defaultFilters.status);
                $('#approvalSearch').val('');
                currentFilters = { ...defaultFilters };
                loadApprovals();
            });

            $(document).on('click', '.approval-action-btn', function () {
                const button = $(this);
                const action = button.data('action');
                const endpoint = button.data('endpoint');
                const card = button.closest('.approval-item');

                if (action === 'rejected' && !confirm('Tolak request ini?')) {
                    return;
                }

                setButtonBusy(button, true);

                $.ajax({
                    url: endpoint,
                    method: 'POST',
                    data: {
                        action: action,
                    },
                    success: function (response) {
                        showAlert(response.message || 'Request berhasil diproses.', 'success');
                        card.addClass('border-success border-opacity-50');
                        loadApprovals();
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || xhr.responseJSON?.error || 'Gagal memproses request.';
                        showAlert(message, 'danger');
                    },
                    complete: function () {
                        setButtonBusy(button, false);
                    }
                });
            });

            function loadApprovals() {
                $('#approvalLoading').removeClass('d-none');
                $('#approvalEmpty').addClass('d-none');
                $('#approvalList').addClass('d-none').empty();

                $.ajax({
                    url: listUrl,
                    method: 'GET',
                    data: currentFilters,
                    success: function (response) {
                        const data = response.data || [];
                        const stats = response.stats || {};

                        $('#metricTotal').text(stats.total || 0);
                        $('#metricPending').text(stats.pending || 0);
                        $('#metricApproved').text(stats.approved || 0);
                        $('#metricRejected').text(stats.rejected || 0);

                        if (!data.length) {
                            $('#approvalEmpty').removeClass('d-none');
                            return;
                        }

                        $('#approvalList').html(data.map(renderItem).join('')).removeClass('d-none');
                    },
                    error: function () {
                        $('#approvalEmpty').removeClass('d-none').html(`
                                            <i class="bi bi-exclamation-triangle display-4 text-danger"></i>
                                            <h5 class="mt-3 mb-2">Gagal memuat data</h5>
                                            <p class="text-muted mb-0">Silakan coba lagi beberapa saat.</p>
                                        `);
                    },
                    complete: function () {
                        $('#approvalLoading').addClass('d-none');
                    }
                });
            }

            function renderItem(item) {
                const detailHtml = (item.details || []).map(detail => {
                    const stacked = String(detail.value || '').length > 90 ? ' approval-detail--stacked' : '';
                    return `
                                        <div class="approval-detail${stacked}">
                                            <span class="approval-detail__label">${escapeHtml(detail.label || '-')}</span>
                                            <div class="approval-detail__value">${escapeHtml(detail.value || '-').replace(/\n/g, '<br>')}</div>
                                        </div>
                                    `;
                }).join('');

                const disabled = item.status !== 'pending' ? 'disabled' : '';

                return `
                                    <div class="approval-item" id="approval-item-${item.type}-${item.id}">
                                        <div class="approval-item__header">
                                            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                                                <div class="flex-grow-1">
                                                    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                                        <span class="badge rounded-pill ${item.type_badge_class || 'bg-secondary'}">${escapeHtml(item.type_label || item.type)}</span>
                                                        <span class="badge rounded-pill ${item.status_badge_class || 'bg-secondary'}">${escapeHtml(item.status_label || item.status)}</span>
                                                    </div>
                                                    <div class="approval-item__title">${escapeHtml(item.title || '-')}</div>
                                                    <div class="approval-item__subtitle">${escapeHtml(item.subtitle || '-')}</div>
                                                    <div class="approval-item__meta mt-2">
                                                        <i class="bi bi-person me-1"></i>${escapeHtml(item.requested_by || '-')}
                                                        <span class="mx-2">•</span>
                                                        <i class="bi bi-clock-history me-1"></i>${escapeHtml(item.created_at_label || '-')}
                                                    </div>
                                                </div>

                                                <div class="text-lg-end">
                                                    <button class="approval-item__toggle" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-${item.type}-${item.id}" aria-expanded="false">
                                                        Detail <i class="bi bi-chevron-down ms-1"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="collapse approval-item__body" id="collapse-${item.type}-${item.id}">
                                            <div class="p-4 pt-0">
                                                <div class="approval-detail-grid mb-4">
                                                    ${detailHtml}
                                                </div>

                                                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-center">
                                                    <div class="approval-item__info small">
                                                        ${item.status === 'pending' ? 'Siap diproses.' : 'Request sudah diproses sebelumnya.'}
                                                    </div>

                                                    <div class="approval-actions">
                                                        <button type="button" class="btn btn-outline-danger approval-action-btn" data-action="rejected"
                                                            data-endpoint="${item.reject_url}" ${disabled}>
                                                            <i class="bi bi-x-circle me-2"></i>Tolak
                                                        </button>
                                                        <button type="button" class="btn btn-success approval-action-btn" data-action="approved"
                                                            data-endpoint="${item.action_url}" ${disabled}>
                                                            <i class="bi bi-check2-circle me-2"></i>Setujui
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
            }

            function setButtonBusy(button, isBusy) {
                if (isBusy) {
                    button.data('original-html', button.html());
                    button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
                    return;
                }

                const originalHtml = button.data('original-html');
                if (originalHtml) {
                    button.prop('disabled', false).html(originalHtml);
                }
            }

            function showAlert(message, type) {
                const icon = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
                const className = type === 'success' ? 'alert-success' : 'alert-danger';
                const alertId = 'approval-alert-item';

                $('#approvalAlert').html(`
                                    <div id="${alertId}" class="alert ${className} alert-dismissible fade show border-0 shadow-sm" role="alert">
                                        <i class="bi ${icon} me-2"></i>${escapeHtml(message)}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                `);

                window.setTimeout(function () {
                    $('#' + alertId).remove();
                }, 3500);
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }
        });
    </script>
@endpush