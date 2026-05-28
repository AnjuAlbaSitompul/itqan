@extends('layouts.app')

@section('content')
    <div class="row">

        {{-- TOTAL USERS --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card custom-card overflow-hidden border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">

                        <div>
                            <p class="text-muted mb-1 fs-13">Total Users</p>
                            <h2 class="fw-bold mb-1">{{ number_format($totalUsers ?? 0) }}</h2>

                            <span class="badge bg-primary-transparent text-primary">
                                <i class="fe fe-users me-1"></i>
                                Active Users
                            </span>
                        </div>

                        <div class="icon-box bg-primary">
                            <i class="fe fe-users text-white"></i>
                        </div>

                    </div>

                    <div class="progress progress-sm mt-4">
                        <div class="progress-bar bg-primary" style="width: 75%"></div>
                    </div>

                    <small class="text-muted">
                        Registered system users
                    </small>
                </div>
            </div>
        </div>

        {{-- PEGAWAI SIGN IDP --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card custom-card overflow-hidden border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">

                        <div>
                            <p class="text-muted mb-1 fs-13">
                                Pegawai Sign IDP
                            </p>

                            <h2 class="fw-bold mb-1">
                                {{ number_format($pegawaiSignIdp ?? 0) }}
                            </h2>

                            <span class="badge bg-success-transparent text-success">
                                <i class="fe fe-check-circle me-1"></i>
                                {{ $periode ?? 'This Month' }}
                            </span>
                        </div>

                        <div class="icon-box bg-success">
                            <i class="fe fe-file-text text-white"></i>
                        </div>

                    </div>

                    <div class="progress progress-sm mt-4">
                        <div class="progress-bar bg-success" style="width: 60%"></div>
                    </div>

                    <small class="text-muted">
                        Employee signed IDP documents
                    </small>
                </div>
            </div>
        </div>

        {{-- SURAT PERINGATAN --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card custom-card overflow-hidden border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">

                        <div>
                            <p class="text-muted mb-1 fs-13">
                                Surat Peringatan
                            </p>

                            <h2 class="fw-bold mb-1">
                                {{ number_format($suratPeringatan ?? 0) }}
                            </h2>

                            <span class="badge bg-danger-transparent text-danger">
                                <i class="fe fe-alert-triangle me-1"></i>
                                Sent This Period
                            </span>
                        </div>

                        <div class="icon-box bg-danger">
                            <i class="fe fe-alert-circle text-white"></i>
                        </div>

                    </div>

                    <div class="progress progress-sm mt-4">
                        <div class="progress-bar bg-danger" style="width: 45%"></div>
                    </div>

                    <small class="text-muted">
                        Warning letters sent
                    </small>
                </div>
            </div>
        </div>

        {{-- TOTAL PEGAWAI --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card custom-card overflow-hidden border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">

                        <div>
                            <p class="text-muted mb-1 fs-13">
                                Total Pegawai
                            </p>

                            <h2 class="fw-bold mb-1">
                                {{ number_format($totalPegawai ?? 0) }}
                            </h2>

                            <span class="badge bg-warning-transparent text-warning">
                                <i class="fe fe-briefcase me-1"></i>
                                Company Employee
                            </span>
                        </div>

                        <div class="icon-box bg-warning">
                            <i class="fe fe-briefcase text-white"></i>
                        </div>

                    </div>

                    <div class="progress progress-sm mt-4">
                        <div class="progress-bar bg-warning" style="width: 85%"></div>
                    </div>

                    <small class="text-muted">
                        Registered employees
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART & ACTIVITY --}}
    <div class="row">

        {{-- CHART --}}
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0">
                    <h4 class="card-title">
                        Statistik Aktivitas
                    </h4>
                </div>

                <div class="card-body">
                    <canvas id="dashboardChart" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- RECENT ACTIVITY --}}
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0">
                    <h4 class="card-title">
                        Aktivitas Terbaru
                    </h4>
                </div>

                <div class="card-body">

                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <span class="avatar bg-primary-transparent text-primary">
                                <i class="fe fe-user-plus"></i>
                            </span>
                        </div>

                        <div>
                            <h6 class="mb-1">User Baru Ditambahkan</h6>
                            <small class="text-muted">
                                2 menit yang lalu
                            </small>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <span class="avatar bg-success-transparent text-success">
                                <i class="fe fe-file-text"></i>
                            </span>
                        </div>

                        <div>
                            <h6 class="mb-1">IDP Berhasil Ditandatangani</h6>
                            <small class="text-muted">
                                10 menit yang lalu
                            </small>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="me-3">
                            <span class="avatar bg-danger-transparent text-danger">
                                <i class="fe fe-alert-triangle"></i>
                            </span>
                        </div>

                        <div>
                            <h6 class="mb-1">Surat Peringatan Dikirim</h6>
                            <small class="text-muted">
                                1 jam yang lalu
                            </small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- CUSTOM STYLE --}}
    <style>
        .icon-box {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .progress-sm {
            height: 6px;
        }

        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-primary-transparent {
            background: rgba(98, 89, 202, 0.12);
        }

        .bg-success-transparent {
            background: rgba(40, 167, 69, 0.12);
        }

        .bg-danger-transparent {
            background: rgba(220, 53, 69, 0.12);
        }

        .bg-warning-transparent {
            background: rgba(255, 193, 7, 0.12);
        }
    </style>

    {{-- CHART JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('dashboardChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'IDP Sign',
                    data: [12, 19, 10, 25, 22, 30],
                    borderWidth: 3,
                    tension: 0.4
                }, {
                    label: 'Surat Peringatan',
                    data: [2, 5, 3, 7, 4, 6],
                    borderWidth: 3,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
@endsection
