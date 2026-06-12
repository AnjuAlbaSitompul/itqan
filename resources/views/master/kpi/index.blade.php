@extends('layouts.app')

@section('content')

    <div class="row g-4">

        <!-- LEFT PANEL -->
        <div class="col-xl-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="period-filter">

                    <div class="row g-2">

                        <div class="col-5">
                            <label class="form-label small text-muted">
                                From
                            </label>
                            <input type="date" id="filterStart" class="form-control">
                        </div>

                        <div class="col-5">
                            <label class="form-label small text-muted">
                                Until
                            </label>
                            <input type="date" id="filterEnd" class="form-control">
                        </div>

                        <div class="col-2 d-flex align-items-center">
                            <button class="btn btn-light" id="resetFilter">
                                <i class="fe fe-refresh-cw"></i>
                            </button>
                        </div>

                    </div>

                </div>

                <div class="period-list">

                    <small class="text-muted">
                        No KPI period available.
                    </small>

                </div>

            </div>

        </div>

        <!-- RIGHT PANEL -->
        <div class="col-xl-8">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start mb-4">

                        <div>
                            <h4 class="fw-semibold mb-1" id="periodName">
                                KPI Januari 2026
                            </h4>

                            <p class="text-muted mb-0">
                                Registration:
                                01 Jan 2026 - 05 Jan 2026
                            </p>
                        </div>

                        <span class="badge bg-success">
                            OPEN
                        </span>

                    </div>

                    <!-- KPI STATS -->
                    <div class="row g-3 mb-4">

                        <div class="col-md-3">
                            <div class="mini-stat">
                                <div class="mini-stat-icon bg-primary text-white">
                                    <i class="fe fe-users"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">
                                        Employee
                                    </small>
                                    <h5 class="mb-0">
                                        120
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mini-stat">
                                <div class="mini-stat-icon bg-success text-white">
                                    <i class="fe fe-user-check"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">
                                        Supervisor
                                    </small>
                                    <h5 class="mb-0">
                                        12
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mini-stat">
                                <div class="mini-stat-icon bg-warning text-white">
                                    <i class="fe fe-briefcase"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">
                                        Manager
                                    </small>
                                    <h5 class="mb-0">
                                        8
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mini-stat">
                                <div class="mini-stat-icon bg-danger text-white">
                                    <i class="fe fe-user-x"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">
                                        Pending
                                    </small>
                                    <h5 class="mb-0">
                                        24
                                    </h5>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- DETAIL -->
                    <div class="row g-3 mb-4">

                        <div class="col-md-6">
                            <small class="text-muted d-block">
                                KPI Period
                            </small>
                            <div class="fw-semibold">
                                01 Jan 2026 - 31 Jan 2026
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">
                                Registration Period
                            </small>
                            <div class="fw-semibold">
                                01 Jan 2026 - 05 Jan 2026
                            </div>
                        </div>

                    </div>

                    <!-- ACTION -->
                    <div class="d-flex flex-wrap gap-2">

                        <button class="btn btn-primary">
                            Edit
                        </button>

                        <button class="btn btn-outline-secondary">
                            View KPI
                        </button>

                        <button class="btn btn-success" id="btnCreatePeriod">
                            New Period
                        </button>

                        <button class="btn btn-danger">
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

        <div class="offcanvas-footer border-top p-3 d-flex justify-content-end gap-2">

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