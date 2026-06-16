@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="container mt-4 mb-5 max-w-4xl">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">Penugasan KPI Tim</h4>
                        <p class="text-muted mb-0">Kelola target dan metrik KPI untuk bawahan Anda.</p>
                    </div>
                </div>

                @if(!$kpiperiod)
                    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
                        <div>
                            <strong>Periode KPI Belum Dibuka!</strong><br>
                            Saat ini tidak ada periode KPI yang berstatus "Open". Anda belum bisa melakukan penugasan.
                        </div>
                    </div>
                @else
                    @php
                        $isExpired = false;
                        if (isset($kpiperiod->registration_end)) {
                            $isExpired = \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($kpiperiod->registration_end));
                        }
                    @endphp

                    @if($isExpired)
                        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center" role="alert">
                            <i class="bi bi-hourglass-bottom fs-4 me-3 text-danger"></i>
                            <div>
                                <strong>Waktu Pengisian Telah Habis!</strong><br>
                                Batas waktu untuk periode <b>{{ $kpiperiod->name }}</b> telah berlalu.
                            </div>
                        </div>
                    @elseif($unregisteredMembers->count() > 0)
                        @php
                            $names = $unregisteredMembers->pluck('name')->toArray();
                            $count = count($names);
                            if ($count > 3) {
                                $displayNames = implode(', ', array_slice($names, 0, 3)) . ', ...';
                            } else {
                                $displayNames = implode(', ', $names);
                            }
                        @endphp
                        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
                            <div>
                                <strong>Beberapa Anggota Belum Terdaftar!</strong><br>
                                Terdapat {{ $unregisteredMembers->count() }} anggota tim yang belum terdaftar dalam sistem:
                                <strong>{{ $displayNames }}</strong>.
                                Pastikan semua anggota terdaftar untuk melakukan penugasan KPI.
                            </div>
                        </div>
                    @elseif($isTeamSet)
                        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                                <div>
                                    <strong>Penugasan Selesai!</strong><br>
                                    Anda sudah mengatur KPI tim untuk periode <b>{{ $kpiperiod->name }}</b>.
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-info-circle-fill fs-4 me-3 text-success"></i>
                            <div>
                                <strong>Periode Aktif: {{ $kpiperiod->name }}</strong><br>
                                Silakan buat penugasan sebelum batas waktu: <span
                                    class="text-danger fw-semibold">{{ \Carbon\Carbon::parse($kpiperiod->registration_end ?? now())->format('d M Y, H:i') }}</span>.
                            </div>
                        </div>
                    @endif
                @endif

                <div class="card border-0 shadow-sm rounded-2 mb-4">
                    <div class="card-body p-0">
                        <div class="panel panel-success">
                            <div class="tab-menu-heading">
                                <div class="tabs-menu">
                                    <ul class="nav panel-tabs panel-success">
                                        <li><a href="#tab17" class="active" data-bs-toggle="tab"><span><i
                                                        class="fe fe-list me-1"></i></span>Daftarkan KPI</a></li>
                                        <li><a href="#tab18" data-bs-toggle="tab"><span><i
                                                        class="fe fe-calendar me-1"></i></span>Daftar Approval</a></li>
                                        <li><a href="#tab19" data-bs-toggle="tab"><span><i
                                                        class="fe fe-file-text me-1"></i></span>Laporan KPI Team</a></li>
                                        <li><a href="#tab20" data-bs-toggle="tab"><span><i
                                                        class="fe fe-bell me-1"></i></span>Tab 4</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">

                                    <!-- TAB 1: FORM PENUGASAN KPI -->
                                    <div class="tab-pane active" id="tab17">
                                        <form id="assignmentForm" action="#" method="POST">
                                            @csrf
                                            <div class="row mb-4">
                                                <div class="col-md-12 mb-4">
                                                    <label class="form-label fw-semibold">Pilih Anggota Tim (Bawahan) <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-select select2-multiple" id="team_members"
                                                        name="team_members[]" multiple="multiple" required>
                                                        @foreach($subordinates ?? [] as $member)
                                                            <option value="{{ $member->id }}">{{ $member->name }}
                                                                ({{ $member->role->name ?? '-' }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input type="hidden" id="approval_id" name="approval_id">
                                                <div class="col-md-12 mb-4">
                                                    <div
                                                        class="d-flex justify-content-between align-items-end mb-3 pb-2 border-bottom">
                                                        <label class="form-label fw-semibold mb-0">KPI Master Terpilih <span
                                                                class="text-danger">*</span></label>
                                                        <div class="btn-group shadow-sm">
                                                            <button type="button" class="btn btn-sm btn-success"
                                                                data-bs-toggle="modal" data-bs-target="#modalKpiList"
                                                                id="btnOpenKpiModal">
                                                                <i class="bi bi-search"></i> Cari & Pilih KPI
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                                data-bs-toggle="offcanvas"
                                                                data-bs-target="#offcanvasKpiMaster">
                                                                <i class="bi bi-plus-lg"></i> Buat Baru
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div id="selected-kpis-container" class="row g-3">
                                                        <div
                                                            class="col-12 text-center p-5 border border-dashed rounded bg-light empty-state">
                                                            <i
                                                                class="bi bi-clipboard-data text-muted fs-1 mb-2 d-block"></i>
                                                            <h6 class="text-muted fw-normal">Belum ada metrik KPI yang
                                                                dipilih.</h6>
                                                            <small class="text-muted">Klik "Cari & Pilih KPI" atau "Buat
                                                                Baru" untuk menambahkan.</small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mb-4">
                                                    <label class="form-label fw-semibold">Atasan Pengecek (Approver) <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-select select2-single" id="approver_id"
                                                        name="approver_id" required>
                                                        <option value="" disabled selected>Cari atasan...</option>
                                                        @foreach($superior ?? [] as $approver)
                                                            <option value="{{ $approver->id }}">{{ $approver->name }}
                                                                ({{ $approver->role->name ?? '-' }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="form-label fw-semibold">Catatan Tambahan
                                                        (Opsional)</label>
                                                    <textarea class="form-control rounded-3" name="notes" rows="2"
                                                        placeholder="Tuliskan pesan untuk tim atau approver..."></textarea>
                                                </div>
                                            </div>

                                            <div class="d-grid mt-4">
                                                <button type="submit" id="submitAssignmentBtn"
                                                    class="btn btn-success py-2 fw-semibold">
                                                    Kirim Penugasan & Minta Approval
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- TAB 2: LIST APPROVAL -->
                                    <div class="tab-pane" id="tab18">
                                        <div class="row mb-4 align-items-center">
                                            <div class="col-md-6">
                                                <h5 class="mb-1 fw-bold">KPI Approval</h5>
                                                <small class="text-muted">Daftar KPI yang sudah diajukan untuk
                                                    approval.</small>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex gap-2 justify-content-md-end mt-3 mt-md-0">
                                                    <!-- Pilih Filter Periode -->
                                                    <div style="width: 200px;">
                                                        <select id="filter-period" class="form-select shadow-sm border-0">
                                                            <option value="">Semua Periode</option>
                                                            @foreach($periods ?? [] as $id => $name)
                                                                <option value="{{ $id }}">{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <!-- Pilih Filter Status -->
                                                    <select id="filter-status"
                                                        class="form-select form-select-sm w-auto shadow-sm border-0">
                                                        <option value="">Semua Status</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="approved">Approved</option>
                                                        <option value="rejected">Rejected</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="approval-list-vertical" id="approvalList">
                                            <div class="text-center py-5 text-muted">
                                                <div class="spinner-border text-success" role="status"></div>
                                                <div class="mt-2">Memuat data...</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TAB 3 -->
                                    <div class="tab-pane" id="tab19">


                                        <!-- Filter -->
                                        <div class="card border-0 shadow-sm mb-3">
                                            <div class="card-body">
                                                <div class="row g-3">

                                                    <div class="col-md-5">
                                                        <label class="form-label fw-semibold">Member</label>
                                                        <select class="form-select select2" id="memberFilter"
                                                            style="width: 100%;">
                                                            <option value="" disabled selected>Cari Member...</option>

                                                            @foreach($allSubordinate ?? [] as $member)
                                                                <option value="{{ $member->id }}"
                                                                    data-nama="{{ $member->name }}"
                                                                    data-jabatan="{{ $member->profile?->jabatan?->name ?? 'Tanpa Jabatan' }}"
                                                                    data-unit="{{ $member->profile?->organizationalUnit?->name ?? 'Tanpa Unit' }}">
                                                                    {{ $member->name }} -
                                                                    {{ $member->profile?->jabatan?->name ?? 'Tanpa Jabatan' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold">Periode</label>
                                                        <select class="form-select select2" id="periodFilter">
                                                            <option value="">Semua Periode</option>
                                                            @foreach($periods ?? [] as $id => $name)
                                                                <option value="{{ $id }}">{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3 d-flex align-items-end">
                                                        <button class="btn btn-success w-100" id="btnLoadReportKpi">
                                                            <i class="ti ti-search me-1"></i>
                                                            Load KPI
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- KPI List -->
                                        <div id="kpiRealizationList">
                                            <div class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center justify-content-center">
                                                    <i class="ti ti-list-check fs-1 text-muted mb-3"></i>
                                                    <h5 class="mb-1 fw-semibold">Belum ada KPI</h5>
                                                    <p class="text-muted mb-0">Tidak ada data KPI yang ditampilkan untuk
                                                        periode ini.</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- TAB 4 -->
                                    <div class="tab-pane" id="tab20">
                                        <p>Konten Tab 4...</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PILIH KPI MASTER -->
    <div class="modal fade" id="modalKpiList" tabindex="-1" aria-labelledby="modalKpiListLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="modalKpiListLabel">Pilih Master KPI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light bg-opacity-50">
                    <div id="available-kpis-list" class="row g-3"></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- OFFCANVAS BUAT KPI MASTER -->
    <div class="offcanvas offcanvas-end shadow" tabindex="-1" id="offcanvasKpiMaster"
        aria-labelledby="offcanvasKpiMasterLabel">
        <div class="offcanvas-header border-bottom bg-light">
            <h5 class="offcanvas-title fw-bold" id="offcanvasKpiMasterLabel">Buat Master KPI Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form id="formCreateKpiMaster">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-medium small text-muted">Judul KPI</label>
                    <input type="text" class="form-control" name="title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium small text-muted">Definition of Done</label>
                    <textarea class="form-control" name="definition_of_done" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium small text-muted">Guard Rail</label>
                    <textarea class="form-control" name="guard_rail" rows="2"></textarea>
                </div>
                <div class="row mb-4">
                    <div class="col-4">
                        <label class="form-label fw-medium small text-muted">Satuan</label>
                        <select class="form-select" name="satuan">
                            <option value="percentage">% (Persentase)</option>
                            <option value="number">Angka</option>
                            <option value="currency">Mata Uang</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-medium small text-muted">Bobot</label>
                        <input type="number" step="0.01" class="form-control" name="bobot" value="0">
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-medium small text-muted">Target</label>
                        <input type="number" step="0.01" class="form-control" name="target" value="0">
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Formula (Penilaian)</h6>
                <div id="formula-container-offcanvas">
                    <div class="row g-2 mb-2 formula-row">
                        <div class="col-5">
                            <input type="number" step="0.01" class="form-control" name="formulas[0][from]"
                                placeholder="From">
                        </div>
                        <div class="col-5">
                            <input type="number" step="0.01" class="form-control" name="formulas[0][to]" placeholder="To">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-outline-success w-100 add-formula-btn">+</button>
                        </div>
                    </div>
                </div>

                <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-success" id="btnSaveMaster">Simpan KPI Master</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    @include('team.kpi.components.style')
@endpush

@push('scripts')
    @include('team.kpi.components.script')
@endpush