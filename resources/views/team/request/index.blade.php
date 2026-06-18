@extends('layouts.app')

@section('content')
    <div class="container-fluid request-page py-4 py-lg-5">
        <div class="request-hero card border-0 overflow-hidden mb-4 mb-lg-5">
            <div class="card-body p-4 p-lg-5 position-relative">
                <div class="request-hero__glow request-hero__glow--one"></div>
                <div class="request-hero__glow request-hero__glow--two"></div>

                <div class="row align-items-center g-4 position-relative">
                    <div class="col-12">
                        <h1 class="display-6 fw-bold mb-3 request-title">
                            Permintaan Approve Peringatan, Mutasi, dan Man Power
                        </h1>

                        <p class="request-lead mb-0">
                            Kirim permintaan approval peringatan, mutasi, atau kebutuhan man power.
                            Field mengikuti struktur migration terbaru.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 align-items-start">
            <div class="col-12 col-xl-8">
                <div class="card request-surface border-0 shadow-sm">
                    <div class="card-header border-0 bg-transparent p-4 pb-0">
                        <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center">
                            <div>
                                <h4 class="fw-bold mb-1">Buat Permintaan</h4>
                                <p class="text-muted mb-0">Pilih jenis request, isi detail, lalu kirim ke atasan.</p>
                            </div>

                            <div class="btn-group request-switcher" role="group" aria-label="Tipe permintaan">
                                <button type="button" class="btn request-switcher__btn active"
                                    data-target="peringatan-panel">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Peringatan
                                </button>
                                <button type="button" class="btn request-switcher__btn" data-target="mutasi-panel">
                                    <i class="bi bi-arrow-left-right me-2"></i>Mutasi
                                </button>
                                <button type="button" class="btn request-switcher__btn" data-target="manpower-panel">
                                    <i class="bi bi-people me-2"></i>Man Power
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content">
                            <div class="request-panel active" id="peringatan-panel">
                                <div class="row g-4">
                                    <div class="col-12 col-lg-7">
                                        <form id="peringatanForm" class="request-form" autocomplete="off"
                                            action="{{ route('team.request.peringatan.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="pending">

                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <div class="form-floating modern-floating">
                                                        <select class="form-select" id="warning_user_id" name="user_id">
                                                            <option value="">Pilih karyawan</option>
                                                            @foreach(($users ?? []) as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="warning_user_id">Karyawan</label>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-floating modern-floating">
                                                        <select class="form-select" id="warning_type" name="type">
                                                            <option value="peringatan_1">Peringatan 1</option>
                                                            <option value="peringatan_2">Peringatan 2</option>
                                                            <option value="peringatan_3">Peringatan 3</option>
                                                        </select>
                                                        <label for="warning_type">Jenis</label>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-floating modern-floating">
                                                        <input type="date" class="form-control" id="warning_issued_date"
                                                            name="issued_date" value="{{ old('issued_date') }}">
                                                        <label for="warning_issued_date">Tanggal</label>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-floating modern-floating">
                                                        <input type="date" class="form-control" id="warning_due_date"
                                                            name="due_date" value="{{ old('due_date') }}">
                                                        <label for="warning_due_date">Tenggat</label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-floating modern-floating">
                                                        <textarea class="form-control" placeholder="Alasan peringatan"
                                                            id="warning_reason" name="reason"
                                                            style="height: 140px">{{ old('reason') }}</textarea>
                                                        <label for="warning_reason">Alasan</label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div
                                                        class="d-flex flex-column flex-sm-row gap-2 justify-content-between align-items-sm-center pt-2">
                                                        <div class="small text-muted">
                                                            Status request akan tersimpan sebagai <span
                                                                class="fw-semibold text-uppercase">pending</span>.
                                                        </div>

                                                        <button type="submit"
                                                            class="btn btn-primary px-4 request-submit-btn"
                                                            data-mode="peringatan">
                                                            <i class="bi bi-send me-2"></i>Kirim Permintaan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-12 col-lg-5">
                                        <div class="request-preview card border-0 h-100">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                                                    <div>
                                                        <p class="text-muted small mb-1">Preview</p>
                                                        <h5 class="fw-bold mb-0">Ringkasan Peringatan</h5>
                                                    </div>
                                                    <span class="badge rounded-pill bg-warning text-dark"
                                                        id="warningPreviewType">Peringatan 1</span>
                                                </div>

                                                <div class="preview-list">
                                                    <div class="preview-item">
                                                        <span class="preview-label">Karyawan</span>
                                                        <span class="preview-value" id="warningPreviewUser">Belum
                                                            dipilih</span>
                                                    </div>
                                                    <div class="preview-item">
                                                        <span class="preview-label">Jenis</span>
                                                        <span class="preview-value"
                                                            id="warningPreviewTypeText">peringatan_1</span>
                                                    </div>
                                                    <div class="preview-item">
                                                        <span class="preview-label">Diterbitkan</span>
                                                        <span class="preview-value" id="warningPreviewIssued">-</span>
                                                    </div>
                                                    <div class="preview-item">
                                                        <span class="preview-label">Tenggat</span>
                                                        <span class="preview-value" id="warningPreviewDue">-</span>
                                                    </div>
                                                    <div class="preview-item preview-item--stacked">
                                                        <span class="preview-label">Alasan</span>
                                                        <span class="preview-value" id="warningPreviewReason">Isi alasan
                                                            peringatan untuk ditampilkan di sini.</span>
                                                    </div>
                                                </div>

                                                <div class="approval-note mt-4">
                                                    <i class="bi bi-shield-check me-2"></i>
                                                    <span>Request mengikuti approval atasan dari struktur organisasi.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="request-panel" id="mutasi-panel">
                                <div class="row g-4">
                                    <div class="col-12 col-lg-7">
                                        <form id="mutasiForm" class="request-form" autocomplete="off"
                                            action="{{ route('team.request.mutasi.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="pending">

                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <div class="form-floating modern-floating">
                                                        <select class="form-select" id="mutasi_user_id" name="user_id">
                                                            <option value="">Pilih karyawan</option>
                                                            @foreach(($users ?? []) as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="mutasi_user_id">Karyawan</label>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-floating modern-floating">
                                                        <select class="form-select" id="mutasi_from_id" name="from_id">
                                                            <option value="">Pilih unit asal</option>
                                                            @foreach(($organizationalUnits ?? []) as $organizationalUnit)
                                                                <option value="{{ $organizationalUnit->id }}">
                                                                    {{ $organizationalUnit->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label for="mutasi_from_id">Dari Unit</label>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-floating modern-floating">
                                                        <select class="form-select" id="mutasi_to_id" name="to_id">
                                                            <option value="">Pilih unit tujuan</option>
                                                            @foreach(($organizationalUnits ?? []) as $organizationalUnit)
                                                                <option value="{{ $organizationalUnit->id }}">
                                                                    {{ $organizationalUnit->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label for="mutasi_to_id">Ke Unit</label>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-floating modern-floating">
                                                        <select class="form-select" id="mutasi_jabatan_id"
                                                            name="jabatan_id">
                                                            <option value="">Pilih jabatan</option>
                                                            @foreach(($jabatans ?? []) as $jabatan)
                                                                <option value="{{ $jabatan->id }}">{{ $jabatan->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="mutasi_jabatan_id">Jabatan</label>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-floating modern-floating">
                                                        <input type="text" class="form-control" id="mutasi_golongan"
                                                            name="golongan" value="{{ old('golongan') }}"
                                                            placeholder="Golongan">
                                                        <label for="mutasi_golongan">Golongan</label>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-floating modern-floating">
                                                        <input type="date" class="form-control" id="mutasi_effective_date"
                                                            name="effective_date" value="{{ old('effective_date') }}">
                                                        <label for="mutasi_effective_date">Efektif</label>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="modern-switch-wrap h-100 d-flex align-items-center">
                                                        <div class="form-check form-switch m-0">
                                                            <input class="form-check-input" type="checkbox" role="switch"
                                                                id="mutasi_is_head" name="is_head" value="1">
                                                            <label class="form-check-label fw-semibold"
                                                                for="mutasi_is_head">Posisi kepala unit</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-floating modern-floating">
                                                        <textarea class="form-control" placeholder="Alasan mutasi"
                                                            id="mutasi_reason" name="reason"
                                                            style="height: 140px">{{ old('reason') }}</textarea>
                                                        <label for="mutasi_reason">Alasan Mutasi</label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div
                                                        class="d-flex flex-column flex-sm-row gap-2 justify-content-between align-items-sm-center pt-2">
                                                        <div class="small text-muted">
                                                            Status request akan tersimpan sebagai <span
                                                                class="fw-semibold text-uppercase">pending</span>.
                                                        </div>

                                                        <button type="submit"
                                                            class="btn btn-primary px-4 request-submit-btn"
                                                            data-mode="mutasi">
                                                            <i class="bi bi-send me-2"></i>Kirim Permintaan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-12 col-lg-5">
                                        <div class="request-preview card border-0 h-100">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                                                    <div>
                                                        <p class="text-muted small mb-1">Preview</p>
                                                        <h5 class="fw-bold mb-0">Ringkasan Mutasi</h5>
                                                    </div>
                                                    <span class="badge rounded-pill bg-info text-dark"
                                                        id="mutasiPreviewHead">Staff</span>
                                                </div>

                                                <div class="preview-list">
                                                    <div class="preview-item">
                                                        <span class="preview-label">Karyawan</span>
                                                        <span class="preview-value" id="mutasiPreviewUser">Belum
                                                            dipilih</span>
                                                    </div>
                                                    <div class="preview-item">
                                                        <span class="preview-label">Asal</span>
                                                        <span class="preview-value" id="mutasiPreviewFrom">-</span>
                                                    </div>
                                                    <div class="preview-item">
                                                        <span class="preview-label">Tujuan</span>
                                                        <span class="preview-value" id="mutasiPreviewTo">-</span>
                                                    </div>
                                                    <div class="preview-item">
                                                        <span class="preview-label">Jabatan</span>
                                                        <span class="preview-value" id="mutasiPreviewJabatan">-</span>
                                                    </div>
                                                    <div class="preview-item">
                                                        <span class="preview-label">Golongan</span>
                                                        <span class="preview-value" id="mutasiPreviewGolongan">-</span>
                                                    </div>
                                                    <div class="preview-item">
                                                        <span class="preview-label">Efektif</span>
                                                        <span class="preview-value" id="mutasiPreviewEffective">-</span>
                                                    </div>
                                                    <div class="preview-item preview-item--stacked">
                                                        <span class="preview-label">Alasan</span>
                                                        <span class="preview-value" id="mutasiPreviewReason">Isi alasan
                                                            mutasi untuk ditampilkan di sini.</span>
                                                    </div>
                                                </div>

                                                <div class="approval-note mt-4">
                                                    <i class="bi bi-arrow-right-circle me-2"></i>
                                                    <span>Request mutasi mengikuti approval organisasi.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="request-panel" id="manpower-panel">
                                <div class="row g-4">
                                    <div class="col-12 col-lg-7">
                                        <form id="manpowerForm" class="request-form" autocomplete="off"
                                            action="{{ route('team.request.manpower.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="pending">
                                            <input type="hidden" name="superior_approval_status" value="pending">

                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <div class="form-floating modern-floating">
                                                        <select class="form-select" id="manpower_unit_id"
                                                            name="organizational_unit_id">
                                                            <option value="">Pilih unit organisasi</option>
                                                            @foreach(($organizationalUnits ?? []) as $organizationalUnit)
                                                                <option value="{{ $organizationalUnit->id }}">
                                                                    {{ $organizationalUnit->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label for="manpower_unit_id">Unit</label>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-floating modern-floating">
                                                        <input type="number" class="form-control" id="manpower_amount"
                                                            name="jumlah_manpower" min="1" step="1"
                                                            placeholder="Jumlah kebutuhan">
                                                        <label for="manpower_amount">Jumlah Kebutuhan</label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-floating modern-floating">
                                                        <textarea class="form-control"
                                                            placeholder="Alasan kebutuhan man power" id="manpower_reason"
                                                            name="reason" style="height: 170px"></textarea>
                                                        <label for="manpower_reason">Alasan</label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div
                                                        class="d-flex flex-column flex-sm-row gap-2 justify-content-between align-items-sm-center pt-2">
                                                        <div class="small text-muted">
                                                            Status request akan tersimpan sebagai <span
                                                                class="fw-semibold text-uppercase">pending</span>.
                                                        </div>

                                                        <button type="submit"
                                                            class="btn btn-primary px-4 request-submit-btn"
                                                            data-mode="manpower">
                                                            <i class="bi bi-send me-2"></i>Kirim Permintaan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-12 col-lg-5">
                                        <div class="request-preview card border-0 h-100">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                                                    <div>
                                                        <p class="text-muted small mb-1">Preview</p>
                                                        <h5 class="fw-bold mb-0">Ringkasan Man Power</h5>
                                                    </div>
                                                    <span class="badge rounded-pill bg-primary text-white"
                                                        id="manpowerPreviewAmount">-</span>
                                                </div>

                                                <div class="preview-list">
                                                    <div class="preview-item">
                                                        <span class="preview-label">Unit</span>
                                                        <span class="preview-value" id="manpowerPreviewUnit">Belum
                                                            dipilih</span>
                                                    </div>
                                                    <div class="preview-item">
                                                        <span class="preview-label">Jumlah</span>
                                                        <span class="preview-value" id="manpowerPreviewAmountText">-</span>
                                                    </div>
                                                    <div class="preview-item preview-item--stacked">
                                                        <span class="preview-label">Alasan</span>
                                                        <span class="preview-value" id="manpowerPreviewReason">Isi alasan
                                                            kebutuhan man power untuk ditampilkan di sini.</span>
                                                    </div>
                                                </div>

                                                <div class="approval-note mt-4">
                                                    <i class="bi bi-diagram-3 me-2"></i>
                                                    <span>Approval mengikuti struktur organizational unit yang
                                                        dipilih.</span>
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
                <div class="card request-surface border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Atasan Approver</h5>

                        @forelse ($approvers as $approver)
                            <div class="soft-info-box mb-3">
                                <i class="bi bi-person-badge me-2"></i>
                                <div>
                                    <div class="fw-semibold">{{ $approver->name }}</div>
                                    <div class="small text-muted">
                                        {{ $approver->profile?->jabatan?->name ?? 'Atasan unit' }}
                                        @if ($approver->profile?->organizationalUnit)
                                            · {{ $approver->profile->organizationalUnit->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="soft-info-box">
                                <i class="bi bi-info-circle me-2"></i>
                                <span>Atasan tidak ditemukan dari struktur organisasi saat ini.</span>
                            </div>
                        @endforelse
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
            --request-bg: linear-gradient(180deg, rgba(var(--bs-primary-rgb), .08), rgba(var(--bs-info-rgb), .03));
            --request-surface: var(--bs-body-bg);
            --request-border: var(--bs-border-color-translucent);
            --request-shadow: 0 12px 28px rgba(15, 23, 42, .06);
        }

        .request-page .text-body,
        .request-page h1,
        .request-page h2,
        .request-page h3,
        .request-page h4,
        .request-page h5,
        .request-page h6,
        .request-page .form-label,
        .request-page .form-check-label,
        .request-page .preview-value,
        .request-page .preview-label,
        .request-page .soft-info-box,
        .request-page .approval-note,
        .request-page .request-lead,
        .request-page .request-switcher__btn {
            color: var(--bs-body-color);
        }

        .request-page .text-muted {
            color: var(--bs-secondary-color) !important;
        }

        .request-hero,
        .request-surface,
        .request-preview {
            background: var(--request-surface);
            border: 1px solid var(--request-border);
            box-shadow: var(--request-shadow);
            border-radius: 1.25rem;
        }

        .request-hero {
            background: var(--request-bg);
        }

        .request-hero__glow {
            position: absolute;
            border-radius: 999px;
            filter: blur(10px);
            pointer-events: none;
            opacity: .8;
        }

        .request-hero__glow--one {
            width: 220px;
            height: 220px;
            top: -80px;
            right: -40px;
            background: rgba(var(--bs-primary-rgb), .16);
        }

        .request-hero__glow--two {
            width: 160px;
            height: 160px;
            bottom: -50px;
            left: 10%;
            background: rgba(var(--bs-info-rgb), .12);
        }

        .request-title {
            max-width: 12ch;
            line-height: 1.05;
        }

        .request-lead {
            max-width: 60ch;
            font-size: 1rem;
        }

        .request-switcher {
            background: var(--bs-tertiary-bg);
            padding: .25rem;
            border-radius: .95rem;
            border: 1px solid var(--bs-border-color);
            flex-wrap: wrap;
        }

        .request-switcher__btn {
            border: 0;
            border-radius: .8rem !important;
            font-weight: 700;
            color: var(--bs-body-color) !important;
            background: transparent;
            padding: .65rem 1rem;
        }

        .request-switcher__btn.active {
            color: #fff !important;
            background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
            box-shadow: 0 10px 22px rgba(var(--bs-primary-rgb), .25);
        }

        .request-panel {
            display: none;
        }

        .request-panel.active {
            display: block;
        }

        .modern-floating .form-control,
        .modern-floating .form-select {
            border-radius: .95rem;
            border-color: var(--bs-border-color);
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            min-height: 54px;
        }

        .modern-floating .form-control:focus,
        .modern-floating .form-select:focus {
            box-shadow: 0 0 0 .2rem rgba(var(--bs-primary-rgb), .12);
            border-color: rgba(var(--bs-primary-rgb), .35);
        }

        .modern-floating textarea.form-control {
            padding-top: 1.6rem;
        }

        .modern-switch-wrap {
            padding: .85rem .95rem;
            border-radius: .95rem;
            border: 1px solid var(--bs-border-color);
            background: var(--bs-tertiary-bg);
        }

        .request-preview {
            position: sticky;
            top: 1rem;
        }

        .preview-list {
            display: grid;
            gap: .85rem;
        }

        .preview-item {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding-bottom: .8rem;
            border-bottom: 1px dashed var(--bs-border-color);
        }

        .preview-item--stacked {
            flex-direction: column;
        }

        .preview-item--stacked .preview-value {
            text-align: left;
        }

        .preview-label {
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--bs-secondary-color);
            flex-shrink: 0;
        }

        .preview-value {
            color: var(--bs-body-color);
            font-weight: 600;
            text-align: right;
        }

        .approval-note,
        .soft-info-box {
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            padding: .85rem .95rem;
            border-radius: .95rem;
            background: rgba(var(--bs-primary-rgb), .06);
            border: 1px solid rgba(var(--bs-primary-rgb), .1);
        }

        .request-page .text-muted,
        .request-page .small,
        .request-page .preview-label,
        .request-page .metric-label {
            color: var(--bs-secondary-color) !important;
        }

        [data-bs-theme="dark"] .request-page {
            --request-bg: linear-gradient(135deg, rgba(13, 110, 253, .22), rgba(13, 202, 240, .12));
            --request-surface: rgba(17, 24, 39, .92);
            --request-border: rgba(255, 255, 255, .08);
            --request-shadow: 0 18px 45px rgba(0, 0, 0, .35);
        }

        [data-bs-theme="dark"] .request-pill,
        [data-bs-theme="dark"] .request-badge-soft {
            background: rgba(15, 23, 42, .72);
            border-color: rgba(255, 255, 255, .08);
            color: var(--bs-body-color);
        }

        [data-bs-theme="dark"] .request-switcher,
        [data-bs-theme="dark"] .modern-switch-wrap,
        [data-bs-theme="dark"] .approval-note,
        [data-bs-theme="dark"] .soft-info-box {
            background: rgba(15, 23, 42, .72);
            border-color: rgba(255, 255, 255, .08);
        }

        [data-bs-theme="dark"] .modern-floating .form-control,
        [data-bs-theme="dark"] .modern-floating .form-select {
            background-color: rgba(15, 23, 42, .86);
        }

        [data-bs-theme="dark"] .request-switcher__btn.active {
            color: #fff !important;
        }

        [data-bs-theme="dark"] .request-page .text-muted,
        [data-bs-theme="dark"] .request-page .small,
        [data-bs-theme="dark"] .request-page .preview-label {
            color: var(--bs-secondary-color) !important;
        }

        @media (max-width: 991.98px) {
            .request-title {
                max-width: none;
                font-size: clamp(2rem, 5vw, 3rem);
            }

            .request-preview {
                position: static;
            }
        }

        @media (max-width: 575.98px) {
            .request-switcher {
                width: 100%;
            }

            .request-switcher__btn {
                flex: 1 1 50%;
                font-size: .9rem;
                padding: .65rem .8rem;
            }

            .preview-item {
                flex-direction: column;
                gap: .25rem;
            }

            .preview-value {
                text-align: left;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function () {
            const typeLabelMap = {
                peringatan_1: 'Peringatan 1',
                peringatan_2: 'Peringatan 2',
                peringatan_3: 'Peringatan 3'
            };

            const textOrDash = value => value && value.trim() ? value : '-';

            const resolveSelectedText = $select => {
                const label = $select.find('option:selected').text().trim();
                return label && !label.toLowerCase().includes('pilih') ? label : 'Belum dipilih';
            };

            const updateWarningPreview = () => {
                const userText = resolveSelectedText($('#warning_user_id'));
                const typeValue = $('#warning_type').val();
                const issuedDate = $('#warning_issued_date').val();
                const dueDate = $('#warning_due_date').val();
                const reason = $('#warning_reason').val();

                $('#warningPreviewUser').text(userText);
                $('#warningPreviewType').text(typeLabelMap[typeValue] || 'Peringatan');
                $('#warningPreviewTypeText').text(typeValue || '-');
                $('#warningPreviewIssued').text(textOrDash(issuedDate));
                $('#warningPreviewDue').text(textOrDash(dueDate));
                $('#warningPreviewReason').text(reason.trim() ? reason : 'Isi alasan peringatan untuk ditampilkan di sini.');
            };

            const updateMutasiPreview = () => {
                const userText = resolveSelectedText($('#mutasi_user_id'));
                const fromText = resolveSelectedText($('#mutasi_from_id'));
                const toText = resolveSelectedText($('#mutasi_to_id'));
                const jabatanText = resolveSelectedText($('#mutasi_jabatan_id'));
                const golongan = $('#mutasi_golongan').val();
                const effectiveDate = $('#mutasi_effective_date').val();
                const reason = $('#mutasi_reason').val();
                const isHead = $('#mutasi_is_head').is(':checked');

                $('#mutasiPreviewUser').text(userText);
                $('#mutasiPreviewFrom').text(fromText);
                $('#mutasiPreviewTo').text(toText);
                $('#mutasiPreviewJabatan').text(jabatanText);
                $('#mutasiPreviewGolongan').text(textOrDash(golongan));
                $('#mutasiPreviewEffective').text(textOrDash(effectiveDate));
                $('#mutasiPreviewReason').text(reason.trim() ? reason : 'Isi alasan mutasi untuk ditampilkan di sini.');
                $('#mutasiPreviewHead').text(isHead ? 'Kepala Unit' : 'Staff');
            };

            const updateManPowerPreview = () => {
                const unitText = resolveSelectedText($('#manpower_unit_id'));
                const amount = $('#manpower_amount').val();
                const reason = $('#manpower_reason').val();

                $('#manpowerPreviewUnit').text(unitText);
                $('#manpowerPreviewAmount').text(amount ? `${amount} orang` : '-');
                $('#manpowerPreviewAmountText').text(amount ? `${amount} orang` : '-');
                $('#manpowerPreviewReason').text(reason.trim() ? reason : 'Isi alasan kebutuhan man power untuk ditampilkan di sini.');
            };

            const setActivePanel = targetId => {
                $('.request-switcher__btn').removeClass('active');
                $(`.request-switcher__btn[data-target="${targetId}"]`).addClass('active');
                $('.request-panel').removeClass('active');
                $(`#${targetId}`).addClass('active');
            };

            $('.request-switcher__btn').on('click', function () {
                setActivePanel($(this).data('target'));
            });

            $('#peringatanForm, #mutasiForm').on('submit', function (e) {
                e.preventDefault();

                const form = $(this);
                const submitButton = form.find('.request-submit-btn');
                const originalHtml = submitButton.html();

                submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...');

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function (response) {
                        alert(response.message || 'Permintaan berhasil dikirim.');
                        form[0].reset();

                        if (form.attr('id') === 'peringatanForm') {
                            updateWarningPreview();
                        } else {
                            updateMutasiPreview();
                        }
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message
                            || xhr.responseJSON?.error
                            || 'Gagal mengirim permintaan.';
                        alert(message);
                    },
                    complete: function () {
                        submitButton.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            $('#warning_user_id, #warning_type, #warning_issued_date, #warning_due_date, #warning_reason').on('input change', updateWarningPreview);
            $('#mutasi_user_id, #mutasi_from_id, #mutasi_to_id, #mutasi_jabatan_id, #mutasi_golongan, #mutasi_effective_date, #mutasi_reason, #mutasi_is_head').on('input change', updateMutasiPreview);
            $('#manpower_unit_id, #manpower_amount, #manpower_reason').on('input change', updateManPowerPreview);

            updateWarningPreview();
            updateMutasiPreview();
            updateManPowerPreview();
        });
    </script>
@endpush