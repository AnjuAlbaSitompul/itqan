@extends('layouts.app')

@section('content')
    <div class="container-fluid request-page py-4 py-lg-5">
        <div class="request-hero card border-0 overflow-hidden mb-4 mb-lg-5 shadow-sm">
            <div class="request-hero__bg"></div>
            <div class="card-body p-4 p-lg-5 position-relative z-1">
                <div class="row align-items-center g-4">
                    <div class="col-12 col-md-8">
                        <div class="d-flex align-items-center mb-3 gap-3">
                            <div class="hero-icon-box bg-white text-primary shadow-sm">
                                <i class="bi bi-send-plus fs-3"></i>
                            </div>
                            <h1 class="display-6 fw-bolder mb-0 request-title text-dark">
                                Formulir Permintaan
                            </h1>
                        </div>
                        <p class="request-lead mb-0 text-secondary fs-5">
                            Kirim pengajuan Peringatan, Mutasi, atau Man Power dengan cepat.
                            Pilih tipe permintaan di bawah dan lengkapi detailnya.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 align-items-start">
            <div class="col-12 col-xl-8">
                <div class="card request-surface border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header border-bottom bg-white p-4">
                        <div class="d-flex flex-column flex-md-row gap-4 justify-content-between align-items-md-center">
                            <div>
                                <h5 class="fw-bold mb-1">Pilih Jenis Request</h5>
                                <p class="text-muted small mb-0">Formulir akan menyesuaikan dengan pilihan Anda.</p>
                            </div>

                            <div class="request-switcher" role="group" aria-label="Tipe permintaan">
                                <button type="button" class="request-switcher__btn active" data-target="peringatan-panel">
                                    <i class="bi bi-exclamation-triangle"></i> Peringatan
                                </button>
                                <button type="button" class="request-switcher__btn" data-target="mutasi-panel">
                                    <i class="bi bi-arrow-left-right"></i> Mutasi
                                </button>
                                <button type="button" class="request-switcher__btn" data-target="manpower-panel">
                                    <i class="bi bi-people"></i> Man Power
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="tab-content p-4 p-lg-5">

                            <div class="request-panel active" id="peringatan-panel">
                                <div class="row g-5">
                                    <div class="col-12 col-lg-7">
                                        <h6 class="fw-bold text-primary mb-4"><i class="bi bi-card-text me-2"></i>Detail
                                            Peringatan</h6>
                                        <form id="peringatanForm" class="request-form" autocomplete="off"
                                            action="{{ route('team.request.peringatan.store') }}" method="POST">
                                            @csrf
                                            <div class="row g-4">
                                                <div class="col-12">
                                                    <div class="form-floating modern-floating">
                                                        <select class="form-select" id="warning_user_id" name="user_id">
                                                            <option value="">Pilih karyawan...</option>
                                                            @foreach(($users ?? []) as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="warning_user_id">Karyawan Tertuju</label>
                                                    </div>
                                                </div>

                                                <div class="col-12" id="warning_type_container" style="display: none;">
                                                    <div class="form-floating modern-floating">
                                                        <select class="form-select" id="warning_type" name="type"
                                                            required></select>
                                                        <label for="warning_type">Tingkat Peringatan (Diizinkan)</label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-floating modern-floating">
                                                        <textarea class="form-control" placeholder="Tuliskan alasan lengkap"
                                                            id="warning_reason" name="reason" style="height: 120px"
                                                            required>{{ old('reason') }}</textarea>
                                                        <label for="warning_reason">Alasan / Kronologi</label>
                                                    </div>
                                                </div>

                                                <div class="col-12 pt-2">
                                                    <button type="submit"
                                                        class="btn btn-primary w-100 py-3 rounded-3 fw-bold request-submit-btn shadow-sm"
                                                        data-mode="peringatan" disabled>
                                                        <i class="bi bi-send-fill me-2"></i> Ajukan Peringatan
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-12 col-lg-5">
                                        <div class="request-preview h-100 rounded-4">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-eye me-2"></i>Live
                                                    Preview</h6>
                                                <span
                                                    class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm fw-bold"
                                                    id="warningPreviewType">SP</span>
                                            </div>
                                            <div class="preview-list">
                                                <div class="preview-item">
                                                    <span class="preview-label">Karyawan</span>
                                                    <span class="preview-value text-dark" id="warningPreviewUser">-</span>
                                                </div>
                                                <div class="preview-item preview-item--stacked border-0 mt-2">
                                                    <span class="preview-label">Alasan</span>
                                                    <div class="preview-value-box mt-2" id="warningPreviewReason">
                                                        <span class="text-muted fw-normal fst-italic">Isi alasan untuk
                                                            menampilkan preview.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="request-panel" id="mutasi-panel">
                                <div class="row g-5">
                                    <div class="col-12 col-lg-7">
                                        <h6 class="fw-bold text-primary mb-4"><i
                                                class="bi bi-signpost-split me-2"></i>Detail Mutasi</h6>
                                        <form id="mutasiForm" class="request-form" autocomplete="off"
                                            action="{{ route('team.request.mutasi.store') }}" method="POST">
                                            @csrf
                                            <div class="row g-4">
                                                <div class="col-12">
                                                    <div class="form-floating modern-floating">
                                                        <select class="form-select" id="mutasi_user_id" name="user_id">
                                                            <option value="">Pilih karyawan...</option>
                                                            @foreach(($users ?? []) as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="mutasi_user_id">Karyawan Terpilih</label>
                                                    </div>
                                                </div>

                                                <div class="col-12" id="mutasi_to_container" style="display: none;">
                                                    <div class="form-floating modern-floating">
                                                        <select class="form-select" id="mutasi_to_id" name="to_id"
                                                            required></select>
                                                        <label for="mutasi_to_id">Mutasi Ke Unit (Tipe Sejenis)</label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-floating modern-floating">
                                                        <textarea class="form-control" placeholder="Alasan mutasi"
                                                            id="mutasi_reason" name="reason" style="height: 120px"
                                                            required>{{ old('reason') }}</textarea>
                                                        <label for="mutasi_reason">Alasan Mutasi</label>
                                                    </div>
                                                </div>

                                                <div class="col-12 pt-2">
                                                    <button type="submit"
                                                        class="btn btn-primary w-100 py-3 rounded-3 fw-bold request-submit-btn shadow-sm"
                                                        data-mode="mutasi" disabled>
                                                        <i class="bi bi-send-fill me-2"></i> Ajukan Mutasi
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-12 col-lg-5">
                                        <div class="request-preview h-100 rounded-4">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-eye me-2"></i>Live
                                                    Preview</h6>
                                                <span
                                                    class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm fw-bold">Mutasi</span>
                                            </div>
                                            <div class="preview-list">
                                                <div class="preview-item">
                                                    <span class="preview-label">Karyawan</span>
                                                    <span class="preview-value text-dark" id="mutasiPreviewUser">-</span>
                                                </div>
                                                <div class="preview-item">
                                                    <span class="preview-label">Tujuan</span>
                                                    <span class="preview-value text-primary" id="mutasiPreviewTo">-</span>
                                                </div>
                                                <div class="preview-item preview-item--stacked border-0 mt-2">
                                                    <span class="preview-label">Alasan</span>
                                                    <div class="preview-value-box mt-2" id="mutasiPreviewReason">
                                                        <span class="text-muted fw-normal fst-italic">Isi alasan mutasi
                                                            untuk menampilkan preview.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="request-panel" id="manpower-panel">
                                <div class="row g-5">
                                    <div class="col-12 col-lg-7">
                                        <h6 class="fw-bold text-primary mb-4"><i
                                                class="bi bi-person-plus me-2"></i>Kebutuhan Man Power</h6>
                                        <form id="manpowerForm" class="request-form" autocomplete="off"
                                            action="{{ route('team.request.manpower.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="pending">
                                            <input type="hidden" name="superior_approval_status" value="pending">

                                            <div class="row g-4">
                                                <div class="col-12">
                                                    <div class="form-floating modern-floating">
                                                        <select class="form-select" id="manpower_unit_id"
                                                            name="organizational_unit_id">
                                                            <option value="">Pilih unit organisasi...</option>
                                                            @foreach(($organizationalUnits ?? []) as $organizationalUnit)
                                                                <option value="{{ $organizationalUnit->id }}">
                                                                    {{ $organizationalUnit->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label for="manpower_unit_id">Unit Penempatan</label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-floating modern-floating">
                                                        <input type="number" class="form-control fw-bold text-primary fs-5"
                                                            id="manpower_amount" name="jumlah_manpower" min="1" step="1"
                                                            placeholder="0">
                                                        <label for="manpower_amount">Total Kebutuhan (Orang)</label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-floating modern-floating">
                                                        <textarea class="form-control"
                                                            placeholder="Alasan kebutuhan man power" id="manpower_reason"
                                                            name="reason" style="height: 120px"></textarea>
                                                        <label for="manpower_reason">Justifikasi Penambahan</label>
                                                    </div>
                                                </div>

                                                <div class="col-12 pt-2">
                                                    <button type="submit"
                                                        class="btn btn-primary w-100 py-3 rounded-3 fw-bold request-submit-btn shadow-sm"
                                                        data-mode="manpower">
                                                        <i class="bi bi-send-fill me-2"></i> Ajukan Man Power
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-12 col-lg-5">
                                        <div class="request-preview h-100 rounded-4">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-eye me-2"></i>Live
                                                    Preview</h6>
                                                <span
                                                    class="badge bg-primary text-white px-3 py-2 rounded-pill shadow-sm fw-bold"
                                                    id="manpowerPreviewAmount">0 Org</span>
                                            </div>
                                            <div class="preview-list">
                                                <div class="preview-item">
                                                    <span class="preview-label">Unit Tujuan</span>
                                                    <span class="preview-value text-dark" id="manpowerPreviewUnit">-</span>
                                                </div>
                                                <div class="preview-item preview-item--stacked border-0 mt-2">
                                                    <span class="preview-label">Justifikasi</span>
                                                    <div class="preview-value-box mt-2" id="manpowerPreviewReason">
                                                        <span class="text-muted fw-normal fst-italic">Isi alasan kebutuhan
                                                            man power untuk menampilkan preview.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card request-surface border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 45px; height: 45px;">
                                <i class="bi bi-shield-lock-fill fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Alur Persetujuan</h5>
                                <span class="text-muted small">Atasan Approver Anda</span>
                            </div>
                        </div>

                        <div class="approver-list">
                            @forelse ($approvers as $approver)
                                <div
                                    class="approver-card mb-3 p-3 rounded-4 border bg-white shadow-sm d-flex align-items-center transition-hover">
                                    <div
                                        class="approver-avatar bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold border">
                                        {{ substr($approver->name, 0, 2) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark">{{ $approver->name }}</div>
                                        <div class="small text-muted d-flex flex-column gap-1 mt-1">
                                            <span><i class="bi bi-briefcase me-1"></i>
                                                {{ $approver->profile?->jabatan?->name ?? 'Atasan unit' }}</span>
                                            @if ($approver->profile?->organizationalUnit)
                                                <span><i class="bi bi-building me-1"></i>
                                                    {{ $approver->profile->organizationalUnit->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <i class="bi bi-check-circle-fill text-success fs-5 ms-2 opacity-50"></i>
                                </div>
                            @empty
                                <div class="alert alert-light border text-center p-4 rounded-4">
                                    <i class="bi bi-exclamation-circle text-warning fs-1 mb-2 d-block"></i>
                                    <span class="text-muted">Atasan approver tidak terdeteksi dalam struktur organisasi
                                        Anda.</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div
                    class="alert alert-primary bg-primary-subtle border-0 rounded-4 p-4 d-flex align-items-start shadow-sm">
                    <i class="bi bi-info-circle-fill text-primary fs-4 me-3 mt-1"></i>
                    <div>
                        <h6 class="fw-bold text-primary mb-1">Informasi Status</h6>
                        <p class="mb-0 small text-primary opacity-75">
                            Setiap permintaan yang dikirim akan secara default berstatus <strong
                                class="text-uppercase">Pending</strong> hingga disetujui oleh atasan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .request-page {
            background-color: var(--bs-body-bg);
            --req-primary: var(--bs-primary);
            --req-surface: #ffffff;
            --req-border: var(--bs-border-color);
        }

        /* Hero Section */
        .request-hero {
            background: linear-gradient(120deg, var(--bs-primary-bg-subtle) 0%, var(--bs-body-bg) 100%);
            border: 1px solid var(--req-border) !important;
            border-radius: 1.5rem !important;
        }

        .request-hero__bg {
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(var(--bs-primary-rgb), 0.1) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }

        .hero-icon-box {
            width: 55px;
            height: 55px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--req-border);
        }

        /* Segmented Control (Tabs) */
        .request-switcher {
            display: inline-flex;
            background-color: var(--bs-secondary-bg);
            padding: 0.35rem;
            border-radius: 50rem;
            gap: 0.25rem;
            border: 1px solid var(--req-border);
        }

        .request-switcher__btn {
            background: transparent;
            border: none;
            padding: 0.6rem 1.25rem;
            border-radius: 50rem;
            font-weight: 600;
            color: var(--bs-secondary-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .request-switcher__btn:hover {
            color: var(--bs-body-color);
        }

        .request-switcher__btn.active {
            background-color: var(--req-surface);
            color: var(--bs-primary);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Form Inputs */
        .modern-floating .form-control,
        .modern-floating .form-select {
            border-radius: 1rem;
            border: 1.5px solid var(--req-border);
            background-color: var(--req-surface);
            padding: 1rem 1.25rem;
            transition: all 0.2s ease-in-out;
            font-weight: 500;
        }

        .modern-floating .form-select {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }

        .modern-floating .form-control:focus,
        .modern-floating .form-select:focus {
            border-color: var(--req-primary);
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.15);
            background-color: var(--req-surface);
        }

        .modern-floating label {
            padding: 1rem 1.25rem;
            color: var(--bs-secondary-color);
            font-weight: 500;
        }

        /* Preview Container */
        .request-preview {
            background: var(--bs-light);
            border: 1px solid var(--req-border);
            padding: 1.75rem;
        }

        .preview-list {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .preview-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px dashed var(--bs-border-color);
            padding-bottom: 1rem;
        }

        .preview-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            color: var(--bs-secondary-color);
        }

        .preview-value {
            font-weight: 700;
            text-align: right;
            word-break: break-word;
        }

        .preview-value-box {
            background: var(--bs-white);
            border: 1px solid var(--req-border);
            border-radius: 0.75rem;
            padding: 1rem;
            width: 100%;
            min-height: 80px;
            font-size: 0.95rem;
            color: var(--bs-body-color);
        }

        /* Sidebar Elements */
        .approver-avatar {
            width: 48px;
            height: 48px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .transition-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .transition-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05) !important;
        }

        /* Panel Visibility */
        .request-panel {
            display: none;
            animation: fadeIn 0.4s ease forwards;
        }

        .request-panel.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Dark Mode Adjustments */
        [data-bs-theme="dark"] .request-page {
            --req-surface: #1e2125;
            --req-border: #373b3e;
        }

        [data-bs-theme="dark"] .request-hero {
            background: linear-gradient(120deg, rgba(var(--bs-primary-rgb), 0.15) 0%, #212529 100%);
        }

        [data-bs-theme="dark"] .request-preview {
            background: #1a1d20;
        }

        [data-bs-theme="dark"] .preview-value-box,
        [data-bs-theme="dark"] .approver-card {
            background-color: #212529 !important;
        }

        [data-bs-theme="dark"] .bg-white {
            background-color: var(--req-surface) !important;
        }

        [data-bs-theme="dark"] .text-dark {
            color: #f8f9fa !important;
        }

        /* Responsive Details */
        @media (max-width: 767.98px) {
            .request-switcher {
                width: 100%;
                flex-direction: column;
                border-radius: 1rem;
            }

            .request-switcher__btn {
                border-radius: 0.75rem;
                justify-content: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function () {
            const resolveSelectedText = $select => {
                const label = $select.find('option:selected').text().trim();
                return label && !label.toLowerCase().includes('pilih') ? label : '-';
            };

            // --- Peringatan Logic ---
            const updateWarningPreview = () => {
                const user = resolveSelectedText($('#warning_user_id'));
                $('#warningPreviewUser').text(user).toggleClass('text-muted', user === '-');

                const typeValue = $('#warning_type').val();
                const typeLabels = { 'peringatan_1': 'SP1', 'peringatan_2': 'SP2', 'peringatan_3': 'SP3' };
                $('#warningPreviewType').text(typeValue ? typeLabels[typeValue] : 'SP');

                const reason = $('#warning_reason').val().trim();
                if (reason) {
                    $('#warningPreviewReason').html(`<span class="text-dark">${reason.replace(/\n/g, '<br>')}</span>`);
                } else {
                    $('#warningPreviewReason').html(`<span class="text-muted fw-normal fst-italic">Isi alasan untuk menampilkan preview.</span>`);
                }
            };

            $('#warning_user_id').on('change', function () {
                let userId = $(this).val();
                let container = $('#warning_type_container');
                let select = $('#warning_type');
                let submitBtn = $('.request-submit-btn[data-mode="peringatan"]');

                if (!userId) {
                    container.slideUp(200);
                    select.empty();
                    submitBtn.prop('disabled', true);
                    updateWarningPreview();
                    return;
                }

                $.get('/team/request/detail', { user_id: userId, type: 'peringatan' })
                    .done(function (res) {
                        if (res.success) {
                            select.empty();
                            if (res.data.available_types.length === 0) {
                                select.append('<option value="">Karyawan sudah mencapai batas maksimal SP</option>');
                                submitBtn.prop('disabled', true);
                            } else {
                                const labels = {
                                    'peringatan_1': 'Peringatan 1 (SP1)',
                                    'peringatan_2': 'Peringatan 2 (SP2)',
                                    'peringatan_3': 'Peringatan 3 (SP3)'
                                };
                                res.data.available_types.forEach(function (type) {
                                    select.append(`<option value="${type}">${labels[type] || type}</option>`);
                                });
                                submitBtn.prop('disabled', false);
                            }
                            container.slideDown(200);
                            updateWarningPreview();
                        }
                    });
            });

            $('#warning_type, #warning_reason').on('input change', updateWarningPreview);

            // --- Mutasi Logic ---
            const updateMutasiPreview = () => {
                const user = resolveSelectedText($('#mutasi_user_id'));
                const toUnit = $('#mutasi_to_id').val() ? resolveSelectedText($('#mutasi_to_id')) : '-';

                $('#mutasiPreviewUser').text(user).toggleClass('text-muted', user === '-');
                $('#mutasiPreviewTo').text(toUnit).toggleClass('text-muted', toUnit === '-');

                const reason = $('#mutasi_reason').val().trim();
                if (reason) {
                    $('#mutasiPreviewReason').html(`<span class="text-dark">${reason.replace(/\n/g, '<br>')}</span>`);
                } else {
                    $('#mutasiPreviewReason').html(`<span class="text-muted fw-normal fst-italic">Isi alasan mutasi untuk menampilkan preview.</span>`);
                }
            };

            $('#mutasi_user_id').on('change', function () {
                let userId = $(this).val();
                let container = $('#mutasi_to_container');
                let select = $('#mutasi_to_id');
                let submitBtn = $('.request-submit-btn[data-mode="mutasi"]');

                if (!userId) {
                    container.slideUp(200);
                    select.empty();
                    submitBtn.prop('disabled', true);
                    updateMutasiPreview();
                    return;
                }

                $.get('/team/request/detail', { user_id: userId, type: 'mutasi' })
                    .done(function (res) {
                        if (res.success) {
                            select.empty();
                            if (res.data.available_units.length === 0) {
                                select.append('<option value="">Tidak ada unit tujuan dengan tipe yang sama</option>');
                                submitBtn.prop('disabled', true);
                            } else {
                                select.append('<option value="">Pilih Unit Tujuan...</option>');
                                res.data.available_units.forEach(function (unit) {
                                    select.append(`<option value="${unit.id}">${unit.name}</option>`);
                                });
                                submitBtn.prop('disabled', false);
                            }
                            container.slideDown(200);
                            updateMutasiPreview();
                        }
                    });
            });

            $('#mutasi_to_id, #mutasi_reason').on('input change', updateMutasiPreview);

            // --- Man Power Logic ---
            const updateManPowerPreview = () => {
                const unit = resolveSelectedText($('#manpower_unit_id'));
                $('#manpowerPreviewUnit').text(unit).toggleClass('text-muted', unit === '-');

                const amount = $('#manpower_amount').val();
                $('#manpowerPreviewAmount').text(amount ? `${amount} Org` : '0 Org');

                const reason = $('#manpower_reason').val().trim();
                if (reason) {
                    $('#manpowerPreviewReason').html(`<span class="text-dark">${reason.replace(/\n/g, '<br>')}</span>`);
                } else {
                    $('#manpowerPreviewReason').html(`<span class="text-muted fw-normal fst-italic">Isi alasan kebutuhan man power untuk menampilkan preview.</span>`);
                }
            };
            $('#manpower_unit_id, #manpower_amount, #manpower_reason').on('input change', updateManPowerPreview);

            // --- Tab Switcher Logic ---
            $('.request-switcher__btn').on('click', function () {
                const targetId = $(this).data('target');

                // Update Buttons
                $('.request-switcher__btn').removeClass('active');
                $(this).addClass('active');

                // Update Panels
                $('.request-panel').removeClass('active');
                $(`#${targetId}`).addClass('active');
            });

            // --- Form Submission Logic ---
            $('.request-form').on('submit', function (e) {
                e.preventDefault();
                const form = $(this);
                const submitButton = form.find('.request-submit-btn');
                const originalHtml = submitButton.html();

                submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
                console.log(form.serialize());
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function (response) {
                        alert(response.message || 'Permintaan berhasil dikirim.');
                        form[0].reset();

                        // Reset UI
                        $('#warning_type_container, #mutasi_to_container').slideUp(200);
                        updateWarningPreview();
                        updateMutasiPreview();
                        updateManPowerPreview();
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || xhr.responseJSON?.error || 'Gagal mengirim permintaan.';

                        if (xhr.responseJSON?.errors) {
                            let errorStr = '';
                            $.each(xhr.responseJSON.errors, function (key, val) {
                                errorStr += val[0] + '\n';
                            });
                            alert(errorStr);
                        } else {
                            alert(message);
                        }
                    },
                    complete: function () {
                        // Restore button HTML (tapi disable kembali jika form kosong setelah direset)
                        submitButton.html(originalHtml);
                        const isManPower = form.attr('id') === 'manpowerForm';
                        if (!isManPower) { submitButton.prop('disabled', true); }
                        else { submitButton.prop('disabled', false); }
                    }
                });
            });
        });
    </script>
@endpush