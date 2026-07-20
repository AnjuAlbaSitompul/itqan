@php
    $roleGroup = $roleGroup ?? ($isManagerGroup ?? false ? 'approver' : 'employee');
    $summaryCards = $summaryCards ?? [];
    $announcementItems = collect($announcementItems ?? []);
    $latestWarnings = collect($latestWarnings ?? []);
    $chartLabels = ($yearKpiSummary['monthly'] ?? collect())->pluck('month_key')->map(function ($monthKey) {
        return \Carbon\Carbon::parse($monthKey . '-01')->format('M Y');
    })->values();
    $chartValues = ($yearKpiSummary['monthly'] ?? collect())->pluck('total_nilai')->map(fn($value) => (float) $value)->values();
@endphp

<div class="dashboard-shell">
    <div class="dashboard-hero card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-body p-4 p-lg-5 position-relative">
            <div class="dashboard-glow dashboard-glow--one"></div>
            <div class="dashboard-glow dashboard-glow--two"></div>

            <div class="row g-4 align-items-center position-relative">
                <div class="col-12 col-xl-8">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge rounded-pill dashboard-pill">{{ $roleLabel ?? 'Dashboard' }}</span>
                        <span
                            class="badge rounded-pill dashboard-pill dashboard-pill--soft">{{ $currentPeriod?->name ?? $periodName ?? 'Periode KPI' }}</span>
                    </div>

                    <h1 class="dashboard-title fw-bold mb-3">{{ $welcomeTitle ?? 'Dashboard ringkas dan responsif.' }}
                    </h1>
                    <p class="dashboard-lead mb-0">
                        {{ $welcomeSubtitle ?? 'Pantau KPI, aktivitas, dan approval penting dalam satu tampilan.' }}</p>
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
                                        {{ auth()->user()?->profile?->jabatan?->name ?? 'Belum ada jabatan' }}</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted">Periode aktif</span>
                                <span class="fw-semibold">{{ $periodRange ?? '-' }}</span>
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

    <div class="row g-4 mb-4">
        @foreach($summaryCards as $card)
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="metric-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="metric-label">{{ $card['label'] }}</div>
                                <div class="metric-value">{{ number_format($card['value'], 2) }}</div>
                                <div class="metric-unit">{{ $card['unit'] }}</div>
                            </div>
                            <div class="metric-icon bg-{{ $card['tone'] }}">
                                <i class="bi {{ $card['icon'] }}"></i>
                            </div>
                        </div>
                        <div class="metric-hint mt-3">{{ $card['hint'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card dashboard-card border-0 shadow-sm mb-4">
                <div class="card-header border-0 bg-transparent p-4 pb-0">
                    <h4 class="fw-bold mb-1">Tren KPI 1 Tahun</h4>
                    <p class="text-muted mb-0">Akumulasi nilai KPI berdasarkan realisasi 12 bulan terakhir.</p>
                </div>
                <div class="card-body p-4">
                    <canvas id="dashboardChart" height="120"></canvas>
                </div>
            </div>

            <div class="card dashboard-card border-0 shadow-sm mb-4">
                <div
                    class="card-header border-0 bg-transparent p-4 pb-0 d-flex justify-content-between align-items-center gap-3 flex-wrap">
                    <div>
                        <h4 class="fw-bold mb-1">Warning Aktif</h4>
                        <p class="text-muted mb-0">Surat peringatan dan mutasi yang sudah di-approve.</p>
                    </div>
                    <div class="dashboard-chip">SP approved: {{ number_format($warningSummary['peringatan'] ?? 0) }}
                    </div>
                    <div class="dashboard-chip">Mutasi approved: {{ number_format($warningSummary['mutasi'] ?? 0) }}
                    </div>
                </div>
                <div class="card-body p-4 pt-3">
                    <div class="row g-3">
                        @forelse($latestWarnings as $item)
                            <div class="col-12 col-md-6">
                                <div class="timeline-card">
                                    <div class="d-flex align-items-start justify-content-between gap-3">
                                        <div>
                                            <div class="fw-bold">{{ $item['label'] }}</div>
                                            <div class="text-muted small">{{ $item['name'] }} · {{ $item['unit'] }}</div>
                                            <div class="mt-2 small">{{ $item['title'] }}</div>
                                        </div>
                                        <div class="text-muted small text-nowrap">{{ $item['date'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="empty-box">Belum ada warning yang disetujui.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card dashboard-card border-0 shadow-sm mb-4">
                <div
                    class="card-header border-0 bg-transparent p-4 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">Pengumuman</h4>
                        <p class="text-muted mb-0">Auto scroll notifikasi terbaru.</p>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="announcement-marquee">
                        <div class="announcement-track">
                            @foreach($announcementLoopItems as $announcement)
                                <a href="{{ $announcement['url'] ?? '#' }}" class="announcement-card text-decoration-none">
                                    <div class="announcement-dot"></div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-body">{{ $announcement['title'] }}</div>
                                        <div class="small text-muted">{{ $announcement['message'] }}</div>
                                        <div class="small text-muted mt-1" data-utc-time="{{ $announcement['created_at'] ?? '' }}">{{ $announcement['created_at_label'] }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-header border-0 bg-transparent p-4 pb-0">
                    <h4 class="fw-bold mb-1">Ringkasan Singkat</h4>
                </div>
                <div class="card-body p-4 pt-3">
                    <div class="quick-stat mb-3">
                        <span>Aktif Users</span>
                        <strong>{{ number_format($activeUsers ?? 0) }}</strong>
                    </div>
                    <div class="quick-stat mb-3">
                        <span>Aktif Pegawai</span>
                        <strong>{{ number_format($activeEmployees ?? 0) }}</strong>
                    </div>
                    <div class="quick-stat mb-3">
                        <span>Jogging</span>
                        <strong>{{ number_format($joggingSummary['total_distance'] ?? 0, 2) }} km</strong>
                    </div>
                    <div class="quick-stat mb-3">
                        <span>Buku Hari Ini</span>
                        <strong>{{ number_format($bookReadingSummary['total_logs'] ?? 0) }} log</strong>
                    </div>
                    <div class="quick-stat">
                        <span>Sholat Hari Ini</span>
                        <strong>{{ number_format($prayerSummary['total_reports'] ?? 0) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .dashboard-shell {
            --shell-surface: rgba(255, 255, 255, .9);
            --shell-border: rgba(15, 23, 42, .08);
            --shell-shadow: 0 18px 42px rgba(15, 23, 42, .08);
            --shell-muted: #64748b;
        }

        .dashboard-hero,
        .dashboard-card,
        .metric-card,
        .dashboard-spotlight {
            background: var(--shell-surface);
            border: 1px solid var(--shell-border);
            border-radius: 1.5rem;
            box-shadow: var(--shell-shadow);
            backdrop-filter: blur(12px);
        }

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

        .metric-card {
            overflow: hidden;
        }

        .metric-label {
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--shell-muted);
            margin-bottom: .5rem;
        }

        .metric-value {
            font-size: clamp(1.6rem, 4vw, 2.3rem);
            line-height: 1;
            font-weight: 800;
        }

        .metric-unit {
            color: var(--shell-muted);
            font-size: .92rem;
            margin-top: .2rem;
        }

        .metric-icon {
            width: 3.15rem;
            height: 3.15rem;
            border-radius: 1rem;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 1.2rem;
        }

        .metric-icon.bg-primary {
            background: linear-gradient(135deg, var(--bs-primary), #7c3aed);
        }

        .metric-icon.bg-success {
            background: linear-gradient(135deg, var(--bs-success), #16a34a);
        }

        .metric-icon.bg-info {
            background: linear-gradient(135deg, var(--bs-info), #0ea5e9);
        }

        .metric-icon.bg-warning {
            background: linear-gradient(135deg, var(--bs-warning), #f59e0b);
        }

        .metric-icon.bg-secondary {
            background: linear-gradient(135deg, #64748b, #334155);
        }

        .metric-icon.bg-danger {
            background: linear-gradient(135deg, var(--bs-danger), #ef4444);
        }

        .metric-icon.bg-dark {
            background: linear-gradient(135deg, #0f172a, #334155);
        }

        .metric-hint,
        .text-muted {
            color: var(--shell-muted) !important;
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

        [data-bs-theme="dark"] .dashboard-shell {
            --shell-surface: rgba(17, 24, 39, .92);
            --shell-border: rgba(255, 255, 255, .08);
            --shell-shadow: 0 20px 50px rgba(0, 0, 0, .35);
            --shell-muted: rgba(226, 232, 240, .72);
        }

        [data-bs-theme="dark"] .dashboard-card,
        [data-bs-theme="dark"] .metric-card,
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

        @media (max-width: 575.98px) {
            .announcement-marquee {
                max-height: 360px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('dashboardChart');
            if (!canvas) return;

            const labels = @json($chartLabels ?? []);
            const values = @json($chartValues ?? []);

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels.length ? labels : ['-'],
                    datasets: [{
                        label: 'Nilai KPI',
                        data: values.length ? values : [0],
                        borderWidth: 3,
                        tension: 0.35,
                        fill: true,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13,110,253,.12)',
                        pointRadius: 3,
                        pointBackgroundColor: '#0d6efd'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,.15)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
@endpush