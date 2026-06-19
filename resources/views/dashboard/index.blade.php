@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* --- CORE SHELL --- */
        .dashboard-shell {
            --shell-surface: rgba(255, 255, 255, .9);
            --shell-border: rgba(15, 23, 42, .08);
            --shell-shadow: 0 18px 42px rgba(15, 23, 42, .08);
            --shell-muted: #64748b;
        }

        .dashboard-hero,
        .dashboard-card,
        .timeline-card,
        .custom-card,
        .dashboard-spotlight {
            background: var(--shell-surface);
            border: 1px solid var(--shell-border);
            border-radius: 1.5rem;
            box-shadow: var(--shell-shadow);
            backdrop-filter: blur(12px);
        }

        /* --- HERO & GLOW --- */
        .dashboard-hero {
            background: linear-gradient(180deg, rgba(var(--bs-primary-rgb), .08), rgba(var(--bs-info-rgb), .04));
        }

        .dashboard-glow {
            position: absolute;
            border-radius: 999px;
            filter: blur(10px);
            pointer-events: none;
            opacity: .8;
        }

        .dashboard-glow--one {
            width: 240px;
            height: 240px;
            right: -90px;
            top: -90px;
            background: rgba(var(--bs-primary-rgb), .16);
        }

        .dashboard-glow--two {
            width: 180px;
            height: 180px;
            left: 10%;
            bottom: -70px;
            background: rgba(var(--bs-info-rgb), .12);
        }

        .dashboard-pill {
            background: rgba(var(--bs-primary-rgb), .1);
            color: var(--bs-primary);
            border: 1px solid rgba(var(--bs-primary-rgb), .15);
            padding: .55rem .9rem;
        }

        .dashboard-pill--soft {
            background: rgba(var(--bs-dark-rgb), .06);
            color: var(--bs-body-color);
        }

        .dashboard-title {
            line-height: 1.05;
            max-width: 14ch;
        }

        .dashboard-lead {
            color: var(--shell-muted);
            max-width: 60ch;
        }

        .dashboard-avatar {
            width: 3rem;
            height: 3rem;
            display: grid;
            place-items: center;
            border-radius: 1rem;
            background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), .18), rgba(var(--bs-info-rgb), .18));
            color: var(--bs-primary);
            font-size: 1.15rem;
        }

        /* --- METRICS & CARDS --- */
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

        .dashboard-chip {
            padding: .6rem .85rem;
            border-radius: 999px;
            background: rgba(var(--bs-primary-rgb), .08);
            border: 1px solid rgba(var(--bs-primary-rgb), .1);
            font-size: .9rem;
        }

        .timeline-card {
            padding: 1rem 1.05rem;
            border-radius: 1.1rem;
            border: 1px solid var(--shell-border);
            background: rgba(var(--bs-secondary-rgb), .03);
            margin-bottom: 1rem;
        }

        .empty-box {
            border: 1px dashed var(--shell-border);
            border-radius: 1.1rem;
            padding: 1.25rem;
            text-align: center;
            color: var(--shell-muted);
        }

        .quick-stat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: .9rem 1rem;
            border-radius: 1rem;
            background: rgba(var(--bs-secondary-rgb), .04);
            border: 1px solid var(--shell-border);
        }

        /* --- ANNOUNCEMENTS MARQUEE --- */
        .announcement-marquee {
            max-height: 420px;
            overflow: hidden;
            padding: 0 1rem 1rem;
        }

        .announcement-track {
            display: flex;
            flex-direction: column;
            gap: .75rem;
            animation: marquee-up 18s linear infinite;
        }

        .announcement-track:hover {
            animation-play-state: paused;
        }

        .announcement-card {
            display: flex;
            gap: .75rem;
            align-items: flex-start;
            padding: .95rem 1rem;
            border-radius: 1rem;
            border: 1px solid var(--shell-border);
            background: rgba(var(--bs-secondary-rgb), .03);
        }

        .announcement-card:hover {
            border-color: rgba(var(--bs-primary-rgb), .25);
        }

        .announcement-dot {
            width: .65rem;
            height: .65rem;
            border-radius: 999px;
            margin-top: .35rem;
            background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
            flex-shrink: 0;
        }

        @keyframes marquee-up {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(-50%);
            }
        }

        /* --- DARK MODE FIXES --- */
        [data-bs-theme="dark"] .dashboard-shell {
            --shell-surface: rgba(17, 24, 39, .92);
            --shell-border: rgba(255, 255, 255, .08);
            --shell-shadow: 0 20px 50px rgba(0, 0, 0, .35);
            --shell-muted: rgba(226, 232, 240, .72);
        }

        [data-bs-theme="dark"] .dashboard-card,
        [data-bs-theme="dark"] .custom-card,
        [data-bs-theme="dark"] .dashboard-spotlight,
        [data-bs-theme="dark"] .timeline-card,
        [data-bs-theme="dark"] .announcement-card,
        [data-bs-theme="dark"] .quick-stat,
        [data-bs-theme="dark"] .empty-box {
            background: rgba(15, 23, 42, .8);
        }

        @media (max-width: 991.98px) {
            .dashboard-title {
                max-width: none;
                font-size: clamp(2rem, 5vw, 3rem);
            }
        }
    </style>
@endpush

@section('content')
    @php
        // Pemetaan data Controller agar sesuai dengan kebutuhan view
        $roleGroup = $isManagerGroup ?? false ? 'approver' : 'employee';
        $roleLabel = ucfirst($role ?? 'Pegawai');
        $periodName = $currentPeriod?->name ?? 'Periode KPI';
        $periodRange = $currentPeriod ? \Carbon\Carbon::parse($currentPeriod->period_start)->format('M Y') . ' - ' . \Carbon\Carbon::parse($currentPeriod->period_end)->format('M Y') : '-';

        // Data Chart dinamis
        $chartData = collect($yearKpiSummary['monthly'] ?? []);
        $chartLabels = $chartData->pluck('month_key')->map(function ($monthKey) {
            return \Carbon\Carbon::parse($monthKey . '-01')->format('M Y');
        })->values();
        $chartValues = $chartData->pluck('total_nilai')->map(fn($value) => (float) $value)->values();

        // Pemetaan metrik untuk Card Top
        $totalUsersVal = $currentKpiSummary['total_users'] ?? 0;
        $pegawaiSignIdpVal = $currentKpiSummary['total_realization'] ?? 0;
        $suratPeringatanVal = $warningSummary['peringatan'] ?? 0;
    @endphp

    <div class="dashboard-shell">
        {{-- 1. HERO SECTION --}}
        <div class="dashboard-hero card border-0 shadow-sm mb-4 overflow-hidden">
            <div class="card-body p-4 p-lg-5 position-relative">
                <div class="dashboard-glow dashboard-glow--one"></div>
                <div class="dashboard-glow dashboard-glow--two"></div>

                <div class="row g-4 align-items-center position-relative">
                    <div class="col-12 col-xl-8">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge rounded-pill dashboard-pill">{{ $roleLabel }}</span>
                            <span class="badge rounded-pill dashboard-pill dashboard-pill--soft">{{ $periodName }}</span>
                        </div>
                        <h1 class="dashboard-title fw-bold mb-3">Dashboard.</h1>
                        <p class="dashboard-lead mb-0">Pantau KPI, aktivitas, dan approval penting dalam satu tampilan.</p>
                    </div>

                    <div class="col-12 col-xl-4">
                        <div class="dashboard-spotlight card border-0 shadow-none">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="dashboard-avatar">
                                        <i class="bi bi-speedometer2"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ auth()->user()?->name ?? 'User' }}</div>
                                        <div class="small text-muted">
                                            {{ auth()->user()?->profile?->jabatan?->name ?? 'Belum ada jabatan' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between small mb-2">
                                    <span class="text-muted">Periode aktif</span>
                                    <span class="fw-semibold">{{ $periodRange }}</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Scope</span>
                                    <span
                                        class="fw-semibold">{{ $roleGroup === 'employee' ? 'Personal' : 'Tim & Organisasi' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        {{-- 2. TOP METRIC CARDS --}}
        @php
            // Dinamis Kolom: Jika manajer, bagi 4 (col-xl-3). Jika pegawai biasa, bagi 3 (col-xl-4)
            $colClass = $isManagerGroup ? 'col-xl-3 col-lg-6 col-md-6' : 'col-xl-4 col-lg-4 col-md-12';
        @endphp

        <div class="row g-4 mb-4">

            {{-- CARD TOTAL USERS (HANYA MUNCUL JIKA MANAGER/ADMIN) --}}
            @if($isManagerGroup)
                <div class="{{ $colClass }}">
                    <div class="card custom-card overflow-hidden border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 fs-13">Total Users / Subordinates</p>
                                    <h2 class="fw-bold mb-1">{{ number_format($totalUsersVal) }}</h2>
                                    <span class="badge bg-primary-transparent text-primary mt-1">
                                        <i class="bi bi-people me-1"></i> Active Scope
                                    </span>
                                </div>
                                <div class="icon-box bg-primary"><i class="bi bi-people-fill text-white"></i></div>
                            </div>
                            <div class="progress progress-sm mt-4">
                                <div class="progress-bar bg-primary" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- CARD TOTAL REALISASI --}}
            <div class="{{ $colClass }}">
                <div class="card custom-card overflow-hidden border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 fs-13">Total Realisasi KPI</p>
                                <h2 class="fw-bold mb-1">{{ number_format($pegawaiSignIdpVal) }}</h2>
                                <span class="badge bg-success-transparent text-success mt-1">
                                    <i class="bi bi-check-circle me-1"></i> {{ $periodName }}
                                </span>
                            </div>
                            <div class="icon-box bg-success"><i class="bi bi-file-earmark-text-fill text-white"></i></div>
                        </div>
                        <div class="progress progress-sm mt-4">
                            <div class="progress-bar bg-success" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD SURAT PERINGATAN --}}
            <div class="{{ $colClass }}">
                <div class="card custom-card overflow-hidden border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 fs-13">Surat Peringatan</p>
                                <h2 class="fw-bold mb-1">{{ number_format($suratPeringatanVal) }}</h2>
                                <span class="badge bg-danger-transparent text-danger mt-1">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Sent This Period
                                </span>
                            </div>
                            <div class="icon-box bg-danger"><i class="bi bi-exclamation-circle-fill text-white"></i></div>
                        </div>
                        <div class="progress progress-sm mt-4">
                            <div class="progress-bar bg-danger" style="width: 45%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD MUTASI PENDING --}}
            <div class="{{ $colClass }}">
                <div class="card custom-card overflow-hidden border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 fs-13">Mutasi Pending</p>
                                <h2 class="fw-bold mb-1">{{ number_format($warningSummary['manpower_pending'] ?? 0) }}</h2>
                                <span class="badge bg-warning-transparent text-warning mt-1">
                                    <i class="bi bi-briefcase me-1"></i> Needs Approval
                                </span>
                            </div>
                            <div class="icon-box bg-warning"><i class="bi bi-briefcase-fill text-white"></i></div>
                        </div>
                        <div class="progress progress-sm mt-4">
                            <div class="progress-bar bg-warning" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- 3. MAIN DASHBOARD CONTENT (CHART & WIDGETS) --}}
        <div class="row g-4">
            <div class="col-12 col-xl-8">
                {{-- KPI CHART --}}
                <div class="card dashboard-card border-0 shadow-sm mb-4">
                    <div class="card-header border-0 bg-transparent p-4 pb-0">
                        <h4 class="fw-bold mb-1">Tren Nilai KPI</h4>
                        <p class="text-muted mb-0">Akumulasi nilai realisasi bulanan.</p>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="dashboardChart" height="120"></canvas>
                    </div>
                </div>

                {{-- RECENT WARNINGS & ACTIVITY --}}
                <div class="card dashboard-card border-0 shadow-sm mb-4">
                    <div
                        class="card-header border-0 bg-transparent p-4 pb-0 d-flex justify-content-between align-items-center gap-3 flex-wrap">
                        <div>
                            <h4 class="fw-bold mb-1">Aktivitas & Warning Terbaru</h4>
                            <p class="text-muted mb-0">SP dan mutasi yang telah di-approve.</p>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-3">
                        <div class="row g-3">
                            @forelse($latestWarnings as $item)
                                <div class="col-12 col-md-6">
                                    <div class="timeline-card mb-0">
                                        <div class="d-flex align-items-start justify-content-between gap-3">
                                            <div>
                                                <div
                                                    class="fw-bold text-{{ $item['type'] === 'peringatan' ? 'danger' : 'primary' }}">
                                                    {{ $item['label'] }}
                                                </div>
                                                <div class="text-muted small">{{ $item['name'] }} · {{ $item['unit'] }}</div>
                                                <div class="mt-2 small">{{ $item['title'] }}</div>
                                            </div>
                                            <div class="text-muted small text-nowrap">{{ $item['date'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="empty-box">Belum ada aktivitas persetujuan terbaru.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                {{-- FORM BUAT PENGUMUMAN (Khusus HC/Admin) --}}
                @if(in_array($role ?? '', ['admin', 'admin_hc', 'manager_hc', 'spv_hc']))
                    <div class="card dashboard-card border-0 shadow-sm mb-4 border-primary">
                        <div class="card-header border-0 bg-transparent p-4 pb-0">
                            <h4 class="fw-bold mb-1 text-primary"><i class="bi bi-megaphone me-2"></i>Buat Pengumuman</h4>
                            <p class="text-muted mb-0 small">Kirim broadcast notifikasi ke seluruh user.</p>
                        </div>
                        <div class="card-body p-4 pt-3">
                            {{-- Sesuaikan action route dengan yang ada di web.php Anda --}}
                            <form id="announcementForm">
                                @csrf
                                <div class="mb-3">
                                    <input type="text" name="title" class="form-control bg-light border-0"
                                        placeholder="Judul Pengumuman" required>
                                </div>
                                <div class="mb-3">
                                    <textarea name="message" class="form-control bg-light border-0" rows="3"
                                        placeholder="Tulis pesan di sini..." required></textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary rounded-pill fw-semibold">
                                        <i class="bi bi-send me-1"></i> Broadcast
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- ANNOUNCEMENTS MARQUEE --}}
                <div class="card dashboard-card border-0 shadow-sm mb-4">
                    <div class="card-header border-0 bg-transparent p-4 pb-0">
                        <h4 class="fw-bold mb-1">Pengumuman Terbaru</h4>
                        <p class="text-muted mb-0">Auto scroll notifikasi sistem.</p>
                    </div>
                    <div class="card-body p-0 mt-3">
                        <div class="announcement-marquee">
                            <div class="announcement-track">
                                @forelse($announcements as $announcement)
                                    <a href="{{ $announcement['url'] ?? '#' }}" class="announcement-card text-decoration-none">
                                        <div class="announcement-dot"></div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold text-body">{{ $announcement['title'] }}</div>
                                            <div class="small text-muted">{{ $announcement['message'] }}</div>
                                            <div class="small text-muted mt-1">{{ $announcement['created_at'] }}</div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-center p-3 text-muted">Belum ada pengumuman.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- QUICK SUMMARY (JOGGING, SHOLAT, BUKU) --}}
                <div class="card dashboard-card border-0 shadow-sm">
                    <div class="card-header border-0 bg-transparent p-4 pb-0">
                        <h4 class="fw-bold mb-1">Ringkasan Aktivitas Subordinate</h4>
                    </div>
                    <div class="card-body p-4 pt-3">
                        <div class="quick-stat mb-3">
                            <span>Total Jarak Jogging</span>
                            <strong>{{ number_format($joggingSummary['total_distance'] ?? 0, 2) }} km</strong>
                        </div>
                        <div class="quick-stat mb-3">
                            <span>Sesi Jogging</span>
                            <strong>{{ number_format($joggingSummary['total_sessions'] ?? 0) }} Sesi</strong>
                        </div>
                        <div class="quick-stat mb-3">
                            <span>Laporan Baca Buku</span>
                            <strong>{{ number_format($bookReadingSummary['total_logs'] ?? 0) }} Log</strong>
                        </div>
                        <div class="quick-stat">
                            <span>Laporan Sholat</span>
                            <strong>{{ number_format($prayerSummary['total_reports'] ?? 0) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('dashboardChart');
            if (!canvas) return;

            // Mapping PHP Arrays ke JavaScript JS Array secara dinamis
            const labels = @json($chartLabels ?? []);
            const values = @json($chartValues ?? []);

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels.length ? labels : ['Belum ada data'],
                    datasets: [{
                        label: 'Total Nilai KPI',
                        data: values.length ? values : [0],
                        borderWidth: 3,
                        tension: 0.35,
                        fill: true,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13,110,253,.12)',
                        pointRadius: 4,
                        pointBackgroundColor: '#0d6efd',
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(148,163,184,.15)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

        });
    </script>
    <script>
        $(document).ready(function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $('#announcementForm').on('submit', function (e) {
                e.preventDefault();
                const form = $(this);
                const data = form.serialize();

                $.post('/announcement/store', data)
                    .done(function (response) {
                        swal({
                            title: 'Sukses!',
                            text: response.message || 'Pengumuman berhasil dibuat.',
                            icon: 'success',
                            timer: 2000,
                            buttons: false
                        })
                        form[0].reset();
                    })
                    .fail(function (xhr) {
                        console.error('Error:', xhr.responseText);
                        swal({
                            title: 'Error!',
                            text: xhr.responseJSON?.error || 'Gagal membuat pengumuman.',
                            icon: 'error',
                            timer: 2000,
                            buttons: false
                        });
                    });
            });
        })
    </script>
@endpush