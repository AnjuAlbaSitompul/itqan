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

                        <button class="btn btn-primary rounded-pill btn-sm">

                            <i class="fe fe-plus"></i>

                        </button>

                    </div>

                </div>

                <div class="card-body p-0">

                    <div class="period-list">

                        <div class="period-item active">

                            <div class="period-content">

                                <div class="period-icon">
                                    <i class="fe fe-calendar"></i>
                                </div>

                                <div>

                                    <div class="fw-semibold">
                                        KPI Januari 2026
                                    </div>

                                    <small class="text-muted">
                                        01 Jan - 31 Jan 2026
                                    </small>

                                </div>

                            </div>

                            <span class="badge bg-success-subtle text-success">
                                Open
                            </span>

                        </div>

                        <div class="period-item">

                            <div class="period-content">

                                <div class="period-icon">
                                    <i class="fe fe-calendar"></i>
                                </div>

                                <div>

                                    <div class="fw-semibold">
                                        KPI Februari 2026
                                    </div>

                                    <small class="text-muted">
                                        01 Feb - 28 Feb 2026
                                    </small>

                                </div>

                            </div>

                            <span class="badge bg-warning-subtle text-warning">
                                Draft
                            </span>

                        </div>

                        <div class="period-item">

                            <div class="period-content">

                                <div class="period-icon">
                                    <i class="fe fe-calendar"></i>
                                </div>

                                <div>

                                    <div class="fw-semibold">
                                        KPI Maret 2026
                                    </div>

                                    <small class="text-muted">
                                        01 Mar - 31 Mar 2026
                                    </small>

                                </div>

                            </div>

                            <span class="badge bg-danger-subtle text-danger">
                                Closed
                            </span>

                        </div>

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


    <!-- SCRIPT -->

@endsection
@push('styles')
    @include('master.kpi.components.style')
@endpush
@push('scripts')
    @include('master.kpi.components.script')
@endpush