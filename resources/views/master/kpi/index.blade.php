@extends('layouts.app')

@section('content')
    <div class="row mb-4">

        <!-- PERIOD LIST -->
        <div class="col-xl-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-white border-0 p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-1">
                                KPI Period
                            </h5>

                            <small class="text-muted">
                                Available periods
                            </small>

                        </div>

                        <button class="btn btn-primary rounded-pill btn-smoff-canvas" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#periodCanvas" aria-controls="periodCanvas">

                            <i class="fe fe-plus"></i>

                        </button>

                    </div>

                </div>

                <div class="card-body p-0">

                    <div class="period-list">

                    </div>

                </div>

            </div>

        </div>

        <!-- DETAIL PANEL -->
        <div class="col-xl-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-4">

                        <div>

                            <h4 class="fw-bold mb-1">
                                KPI Januari 2026
                            </h4>

                            <p class="text-muted mb-0">
                                Registration: 01 Jan 2026 - 05 Jan 2026
                            </p>

                        </div>

                        <span class="badge bg-success-subtle text-success px-3 py-2">
                            OPEN
                        </span>

                    </div>

                    <div class="row g-3">

                        <div class="col-md-3">

                            <div class="mini-stat">

                                <div class="mini-stat-icon bg-primary">
                                    <i class="fe fe-users"></i>
                                </div>

                                <div>

                                    <small class="text-muted">
                                        Employee
                                    </small>

                                    <h5 class="mb-0 fw-bold">
                                        120
                                    </h5>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="mini-stat">

                                <div class="mini-stat-icon bg-success">
                                    <i class="fe fe-user-check"></i>
                                </div>

                                <div>

                                    <small class="text-muted">
                                        Supervisor
                                    </small>

                                    <h5 class="mb-0 fw-bold">
                                        12
                                    </h5>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="mini-stat">

                                <div class="mini-stat-icon bg-warning">
                                    <i class="fe fe-briefcase"></i>
                                </div>

                                <div>

                                    <small class="text-muted">
                                        Manager
                                    </small>

                                    <h5 class="mb-0 fw-bold">
                                        8
                                    </h5>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="mini-stat">

                                <div class="mini-stat-icon bg-danger">
                                    <i class="fe fe-user-x"></i>
                                </div>

                                <div>

                                    <small class="text-muted">
                                        Pending
                                    </small>

                                    <h5 class="mb-0 fw-bold">
                                        24
                                    </h5>

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="row">

                        <div class="col-md-6">

                            <label class="text-muted small">
                                KPI Period
                            </label>

                            <div class="fw-semibold">
                                01 Jan 2026 - 31 Jan 2026
                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Registration Period
                            </label>

                            <div class="fw-semibold">
                                01 Jan 2026 - 05 Jan 2026
                            </div>

                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">

                        <button class="btn btn-primary rounded-pill">
                            Edit Period
                        </button>

                        <button class="btn btn-light rounded-pill">
                            View KPI
                        </button>

                        <button class="btn btn-danger rounded-pill">
                            Close Period
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="offcanvas offcanvas-end kpi-offcanvas" tabindex="-1" id="periodCanvas">

        <div class="offcanvas-header border-bottom">

            <div>
                <h5 class="mb-0 fw-bold" id="canvasTitle">
                    Create KPI Period
                </h5>

                <small class="text-muted">
                    Configure KPI period settings
                </small>
            </div>

            <button type="button" class="btn-close" data-bs-dismiss="offcanvas">
            </button>

        </div>

        <div class="offcanvas-body">

            <form id="periodForm">

                <div class="form-section">

                    <h6 class="section-title">
                        General Information
                    </h6>

                    <div class="mb-3">

                        <label class="form-label">
                            Period Name
                        </label>

                        <input type="text" id="name" class="form-control" placeholder="Ex: KPI January 2026">

                    </div>

                </div>

                <div class="form-section">

                    <h6 class="section-title">
                        Registration Period
                    </h6>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Start
                            </label>

                            <input type="datetime-local" id="registration_start" class="form-control">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                End
                            </label>

                            <input type="datetime-local" id="registration_end" class="form-control">

                        </div>

                    </div>

                </div>

                <div class="form-section">

                    <h6 class="section-title">
                        KPI Period
                    </h6>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Start Date
                            </label>

                            <input type="date" id="period_start" class="form-control">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                End Date
                            </label>

                            <input type="date" id="period_end" class="form-control">

                        </div>

                    </div>

                </div>

                <div class="form-section">

                    <h6 class="section-title">
                        Status
                    </h6>

                    <select id="status" class="form-select">

                        <option value="draft">
                            Draft
                        </option>

                        <option value="open">
                            Open
                        </option>

                        <option value="closed">
                            Closed
                        </option>

                    </select>

                </div>

            </form>

        </div>

        <div class="offcanvas-footer border-top">

            <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">

                Cancel

            </button>

            <button type="button" id="btnSavePeriod" class="btn btn-primary">

                Save Period

            </button>

        </div>

    </div>


    <!-- SCRIPT -->

@endsection
@push('styles')
    @include('master.kpi.components.style')
@endpush
@push('scripts')
    @include('master.kpi.components.script')
@endpush