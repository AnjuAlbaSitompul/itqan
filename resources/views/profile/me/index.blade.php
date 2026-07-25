@extends('layouts.app')

@push('styles')
    <style>
        .profile-page {
            --profile-surface: #ffffff;
            --profile-surface-alt: #f8fafc;
            --profile-border: #e2e8f0;
            --profile-text: #0f172a;
            --profile-muted: #64748b;
            padding: clamp(.5rem, 1.5vw, 1.5rem);
        }

        .profile-hero,
        .profile-section,
        .profile-quick-card {
            background: var(--profile-surface);
            border: 1px solid var(--profile-border);
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, .05);
            border-radius: 1.25rem;
        }

        /* --- HERO & COVER --- */
        .profile-hero {
            overflow: hidden;
        }

        .profile-cover-modern {
            min-height: 220px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            background-color: #334155;
        }

        .profile-cover-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, .1) 0%, rgba(15, 23, 42, .65) 100%);
        }

        .profile-info-bar {
            display: flex;
            align-items: flex-end;
            gap: 1.5rem;
            margin-top: -70px;
            padding: 0 2rem 2rem;
            position: relative;
            z-index: 1;
        }

        .profile-avatar-modern {
            width: 140px;
            height: 140px;
            border-radius: 1.25rem;
            object-fit: cover;
            border: 5px solid var(--profile-surface);
            background: var(--profile-surface);
            box-shadow: 0 10px 25px -3px rgba(15, 23, 42, .2);
            flex-shrink: 0;
        }

        .profile-details {
            flex: 1 1 auto;
            padding-bottom: .25rem;
            min-width: 0;
        }

        .profile-name {
            margin: 0;
            color: var(--profile-text);
            font-size: clamp(1.5rem, 2.2vw, 2.25rem);
            font-weight: 800;
            letter-spacing: -.025em;
            line-height: 1.2;
        }

        .profile-chips {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin-top: .75rem;
        }

        .profile-chip {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .4rem .85rem;
            border-radius: 9999px;
            border: 1px solid rgba(var(--bs-primary-rgb), .2);
            background: rgba(var(--bs-primary-rgb), .08);
            color: var(--bs-primary);
            font-size: .825rem;
            font-weight: 600;
        }

        .profile-chip-soft {
            background: var(--profile-surface-alt);
            border-color: var(--profile-border);
            color: var(--profile-text);
        }

        .profile-actions {
            margin-left: auto;
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            padding-bottom: .25rem;
        }

        .profile-actions .btn {
            border-radius: .75rem;
            padding: .65rem 1.25rem;
            font-weight: 600;
            font-size: .875rem;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: all .2s ease;
        }

        /* --- SECTIONS --- */
        .profile-section {
            overflow: hidden;
            height: 100%;
        }

        .profile-section .card-header {
            background: transparent;
            border-bottom: 1px solid var(--profile-border);
            padding: 1.25rem 1.5rem;
        }

        .profile-section .card-body {
            padding: 1.5rem;
        }

        .profile-section-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--profile-text);
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        /* --- INFORMASI PEKERJAAN (LIST STYLE) --- */
        .info-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: .85rem 0;
            border-bottom: 1px dashed var(--profile-border);
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-row:first-child {
            padding-top: 0;
        }

        .info-icon {
            width: 44px;
            height: 44px;
            border-radius: .75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.15rem;
            color: var(--bs-primary);
            background: rgba(var(--bs-primary-rgb), .1);
        }

        .info-icon.success {
            background: rgba(25, 135, 84, .1);
            color: #198754;
        }

        .info-icon.warning {
            background: rgba(255, 193, 7, .12);
            color: #d97706;
        }

        .info-icon.info {
            background: rgba(13, 202, 240, .1);
            color: #0891b2;
        }

        /* --- INFORMASI PRIBADI (MODERN GRID CARDS) --- */
        .info-grid-card {
            background: var(--profile-surface-alt);
            border: 1px solid var(--profile-border);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: border-color .2s ease;
        }

        .info-grid-card:hover {
            border-color: rgba(var(--bs-primary-rgb), .4);
        }

        .info-label {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 600;
            color: var(--profile-muted);
            margin-bottom: .25rem;
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        .info-value {
            font-size: .95rem;
            font-weight: 700;
            color: var(--profile-text);
            word-break: break-word;
        }

        .info-value.empty {
            color: var(--profile-muted);
            font-weight: 400;
            font-style: italic;
        }

        .address-card {
            background: var(--profile-surface-alt);
            border: 1px solid var(--profile-border);
            border-left: 4px solid var(--bs-primary);
            border-radius: .75rem;
            padding: 1rem 1.25rem;
        }

        .address-card.domisili {
            border-left-color: #0891b2;
        }

        /* --- ACTIVITY CHIPS --- */
        .activity-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: .85rem 1.25rem;
            border-radius: .85rem;
            border: 1px solid var(--profile-border);
            background: var(--profile-surface-alt);
            margin-bottom: .75rem;
        }

        .activity-item:last-child {
            margin-bottom: 0;
        }

        /* --- MODAL FIXES --- */
        /* .profile-modal .modal-content {
                background: var(--profile-surface);
                border: 1px solid var(--profile-border);
                border-radius: 1.25rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, .25);
                overflow: hidden;
            }

            .profile-modal .modal-header {
                background: var(--profile-surface);
                border-bottom: 1px solid var(--profile-border);
                padding: 1.25rem 1.5rem;
                color: var(--profile-text);
            }

            .profile-modal .modal-header .btn-close {
                margin: -0.5rem -0.5rem -0.5rem auto;
            }

            .profile-modal .modal-body {
                background: var(--profile-surface);
                padding: 1.5rem;
                max-height: calc(100vh - 210px);
                overflow-y: auto;
            }

            .profile-modal .modal-footer {
                background: var(--profile-surface-alt);
                border-top: 1px solid var(--profile-border);
                padding: 1rem 1.5rem;
            }

            .profile-modal .form-control,
            .profile-modal .form-select {
                border-radius: .65rem;
                border-color: var(--profile-border);
                padding: .6rem .85rem;
                background-color: var(--profile-surface);
                color: var(--profile-text);
            }

            .profile-modal .form-control:focus,
            .profile-modal .form-select:focus {
                border-color: var(--bs-primary);
                box-shadow: 0 0 0 .2rem rgba(var(--bs-primary-rgb), .15);
            } */

        .form-section-title {
            margin: 0 0 1rem;
            padding-bottom: .5rem;
            border-bottom: 2px solid rgba(var(--bs-primary-rgb), .15);
            color: var(--bs-primary);
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        /* --- DARK THEME SUPPORT --- */
        [data-bs-theme="dark"] .profile-page {
            --profile-surface: #1e293b;
            --profile-surface-alt: #0f172a;
            --profile-border: #334155;
            --profile-text: #f8fafc;
            --profile-muted: #94a3b8;
        }

        [data-bs-theme="dark"] .profile-hero,
        [data-bs-theme="dark"] .profile-section,
        [data-bs-theme="dark"] .profile-quick-card,
        /* [data-bs-theme="dark"] .profile-modal .modal-content {
                box-shadow: 0 10px 30px -5px rgba(0, 0, 0, .5);
            }

            [data-bs-theme="dark"] .profile-modal .modal-header .btn-close {
                filter: invert(1) grayscale(100%) brightness(200%);
            } */

        /* --- RESPONSIVE --- */
        @media (max-width: 991.98px) {
            .profile-info-bar {
                align-items: center;
                flex-direction: column;
                text-align: center;
                padding-bottom: 1.5rem;
            }

            .profile-actions {
                margin-left: 0;
                width: 100%;
                justify-content: center;
                margin-top: .5rem;
            }

            .profile-actions .btn {
                flex: 1 1 200px;
                justify-content: center;
            }
        }

        @media (max-width: 767.98px) {
            .profile-info-bar {
                margin-top: -50px;
                padding: 0 1rem 1.25rem;
            }

            .profile-avatar-modern {
                width: 110px;
                height: 110px;
                border-radius: 1rem;
            }

            .profile-chips {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $hasBg = $user->ownProfile && $user->ownProfile->background;
        $bgUrl = $hasBg ? asset('storage/' . $user->ownProfile->background) : '';
        $bgColor = $hasBg ? 'transparent' : '#334155';
    @endphp

    <div class="profile-page">
        {{-- HERO SECTION --}}
        <div class="card profile-hero mb-4 border-0">
            <div class="profile-cover-modern"
                style="background-image: url('{{ $bgUrl }}'); background-color: {{ $bgColor }};">
                <div class="profile-cover-overlay"></div>
            </div>

            <div class="profile-info-bar">
                <img src="{{ $user->ownProfile?->avatar ? asset('storage/' . $user->ownProfile->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}"
                    alt="Avatar" class="profile-avatar-modern">

                <div class="profile-details">
                    <h1 class="profile-name">{{ $user->name }}</h1>

                    <div class="profile-chips">
                        <span class="profile-chip">
                            <i class="fe fe-award"></i> {{ strtoupper($user->role?->name ?? 'USER') }}
                        </span>
                        @if($user->profile?->jabatan)
                            <span class="profile-chip profile-chip-soft">
                                <i class="fe fe-briefcase"></i> {{ $user->profile->jabatan->name }}
                            </span>
                        @endif
                        @if($user->profile?->outlet)
                            <span class="profile-chip profile-chip-soft">
                                <i class="fe fe-map-pin"></i> {{ $user->profile->outlet->name }}
                            </span>
                        @endif
                    </div>
                </div>

                @if ($user->id === auth()->id())
                    <div class="profile-actions">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#editProfileModal">
                            <i class="fe fe-edit-2"></i> Edit Profile
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#changePasswordModal">
                            <i class="fe fe-lock"></i> Ubah Password
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="row g-4">
            {{-- KOLOM KIRI (INFORMASI PEKERJAAN & AKTIVITAS) --}}
            <div class="col-xl-4 col-lg-5 d-flex flex-column gap-4">
                <div class="profile-section card border-0">
                    <div class="card-header">
                        <h5 class="profile-section-title">
                            <i class="fe fe-briefcase text-primary"></i> Informasi Pekerjaan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fe fe-award"></i>
                            </div>
                            <div>
                                <div class="info-label mb-0">Jabatan</div>
                                <div class="info-value">{{ $user->profile?->jabatan->name ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon success">
                                <i class="fe fe-map-pin"></i>
                            </div>
                            <div>
                                <div class="info-label mb-0">Outlet / Lokasi</div>
                                <div class="info-value">{{ $user->profile?->outlet->name ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon info">
                                <i class="fe fe-box"></i>
                            </div>
                            <div>
                                <div class="info-label mb-0">Unit Organisasi</div>
                                <div class="info-value">{{ $user->profile?->organizationalUnit->name ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon warning">
                                <i class="fe fe-calendar"></i>
                            </div>
                            <div>
                                <div class="info-label mb-0">Tanggal Masuk</div>
                                <div class="info-value">{{ $user->profile?->tanggal_masuk?->format('d M Y') ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-section card border-0 profile-quick-card">
                    <div class="card-header">
                        <h5 class="profile-section-title">
                            <i class="fe fe-activity text-danger"></i> Aktivitas Saya
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-item">
                            <span class="fw-semibold text-body"><i class="fe fe-heart text-danger me-2"></i> Jogging
                                Distance</span>
                            <span class="badge bg-danger rounded-pill px-3 py-2">{{ $joggingCount }} Km</span>
                        </div>
                        <div class="activity-item">
                            <span class="fw-semibold text-body"><i class="fe fe-moon text-secondary me-2"></i> Prayer
                                Reports</span>
                            <span class="badge bg-secondary rounded-pill px-3 py-2">{{ $prayerCount }}</span>
                        </div>
                        <div class="activity-item">
                            <span class="fw-semibold text-body"><i class="fe fe-book text-primary me-2"></i> Books
                                Read</span>
                            <span class="badge bg-primary rounded-pill px-3 py-2">{{ $bookLogs }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (INFORMASI PRIBADI - TATA LETAK GRID TERSTRUKTUR) --}}
            <div class="col-xl-8 col-lg-7">
                <div class="profile-section card border-0">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="profile-section-title">
                            <i class="fe fe-user text-primary"></i> Informasi Pribadi
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- GRID DATA UTAMA --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="info-grid-card">
                                    <span class="info-label"><i class="fe fe-user text-primary"></i> Nama Lengkap</span>
                                    <span class="info-value">{{ $user->name }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-grid-card">
                                    <span class="info-label"><i class="fe fe-hash text-info"></i> NIP</span>
                                    <span class="info-value {{ !$user->ownProfile?->nip ? 'empty' : '' }}">
                                        {{ $user->ownProfile?->nip ?? 'Belum dilengkapi' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-grid-card">
                                    <span class="info-label"><i class="fe fe-users text-warning"></i> Jenis Kelamin</span>
                                    <span class="info-value {{ !$user->ownProfile?->jenis_kelamin ? 'empty' : '' }}">
                                        @if($user->ownProfile?->jenis_kelamin == 'L') Laki-Laki
                                        @elseif($user->ownProfile?->jenis_kelamin == 'P') Perempuan
                                        @else Belum dilengkapi @endif
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-grid-card">
                                    <span class="info-label"><i class="fe fe-gift text-success"></i> Tanggal Lahir</span>
                                    <span class="info-value {{ !$user->ownProfile?->tanggal_lahir ? 'empty' : '' }}">
                                        {{ $user->ownProfile?->tanggal_lahir ? \Carbon\Carbon::parse($user->ownProfile->tanggal_lahir)->format('d F Y') : 'Belum dilengkapi' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-grid-card">
                                    <span class="info-label"><i class="fe fe-book-open text-primary"></i> Pendidikan
                                        Terakhir</span>
                                    <span class="info-value {{ !$user->ownProfile?->tamatan ? 'empty' : '' }}">
                                        {{ $user->ownProfile?->tamatan ?? 'Belum dilengkapi' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-grid-card">
                                    <span class="info-label"><i class="fe fe-shield text-info"></i> Tipe BPJS</span>
                                    <span class="info-value {{ !$user->ownProfile?->tipe_bpjs ? 'empty' : '' }}">
                                        {{ $user->ownProfile?->tipe_bpjs ?? 'Belum dilengkapi' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- ALAMAT & DOMISILI --}}
                        <h6 class="text-uppercase text-muted fw-bold mb-3"
                            style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            Lokasi & Alamat
                        </h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="address-card">
                                    <span class="info-label"><i class="fe fe-home text-primary"></i> Alamat Sesuai
                                        KTP</span>
                                    <div class="info-value mt-1 {{ !$user->ownProfile?->alamat ? 'empty' : '' }}">
                                        {{ $user->ownProfile?->alamat ?? 'Alamat KTP belum dilengkapi' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="address-card domisili">
                                    <span class="info-label"><i class="fe fe-map text-info"></i> Domisili Saat Ini</span>
                                    <div class="info-value mt-1 {{ !$user->ownProfile?->domisili ? 'empty' : '' }}">
                                        {{ $user->ownProfile?->domisili ?? 'Alamat domisili belum dilengkapi' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT PROFILE --}}
    <form id="form-edit-profile" enctype="multipart/form-data" data-action="{{ route('profile.me.update') }}"
        data-modal="editProfileModal">
        @csrf
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="editProfileModalLabel">
                            <i class="fe fe-edit-2 me-2 text-primary"></i> Edit Profile
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-section-title">Foto Profil</div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Foto Avatar</label>
                                <input class="form-control" type="file" name="avatar"
                                    accept="image/jpeg, image/png, image/jpg">
                                <small class="text-muted d-block mt-1">Abaikan jika tidak ingin mengubah foto.</small>
                                <div class="text-danger error-text avatar_error mt-1 small"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Foto Sampul</label>
                                <input class="form-control" type="file" name="background"
                                    accept="image/jpeg, image/png, image/jpg">
                                <small class="text-muted d-block mt-1">Abaikan jika tidak ingin mengubah cover.</small>
                                <div class="text-danger error-text background_error mt-1 small"></div>
                            </div>
                        </div>
                        <div class="form-section-title">Informasi Personal</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control" name="name" value="{{ $user->name }}"
                                    placeholder="Masukkan nama lengkap">
                                <div class="text-danger error-text name_error mt-1 small"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NIP</label>
                                <input type="text" class="form-control" name="nip" value="{{ $user->ownProfile?->nip }}"
                                    placeholder="Masukkan NIP">
                                <div class="text-danger error-text nip_error mt-1 small"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tipe BPJS</label>
                                <input type="text" class="form-control" name="tipe_bpjs"
                                    value="{{ $user->ownProfile?->tipe_bpjs }}" placeholder="Kesehatan / Ketenagakerjaan">
                                <div class="text-danger error-text tipe_bpjs_error mt-1 small"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ $user->ownProfile?->jenis_kelamin == 'L' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="P" {{ $user->ownProfile?->jenis_kelamin == 'P' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                <div class="text-danger error-text jenis_kelamin_error mt-1 small"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tanggal_lahir"
                                    value="{{ $user->ownProfile?->tanggal_lahir ? \Carbon\Carbon::parse($user->ownProfile->tanggal_lahir)->format('Y-m-d') : '' }}">
                                <div class="text-danger error-text tanggal_lahir_error mt-1 small"></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Pendidikan Terakhir</label>
                                <input type="text" class="form-control" name="tamatan"
                                    value="{{ $user->ownProfile?->tamatan }}" placeholder="Contoh: S1 Teknik Informatika">
                                <div class="text-danger error-text tamatan_error mt-1 small"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Alamat Sesuai KTP</label>
                                <textarea class="form-control" name="alamat" rows="3"
                                    placeholder="Alamat KTP">{{ $user->ownProfile?->alamat }}</textarea>
                                <div class="text-danger error-text alamat_error mt-1 small"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Domisili Saat Ini</label>
                                <textarea class="form-control" name="domisili" rows="3"
                                    placeholder="Alamat domisili">{{ $user->ownProfile?->domisili }}</textarea>
                                <div class="text-danger error-text domisili_error mt-1 small"></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary px-4" id="btn-save-profile">
                            <i class="fe fe-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    {{-- MODAL UBAH PASSWORD --}}
    @if ($user->id === auth()->id())
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="form-change-password" data-action="{{ route('profile.me.change-password') }}"
                        data-modal="changePasswordModal">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="changePasswordModalLabel">
                                <i class="fe fe-lock me-2 text-primary"></i> Ubah Password
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning border-0 d-flex align-items-center gap-2 mb-4" role="alert">
                                <i class="fe fe-alert-triangle fs-5"></i>
                                <div>Pastikan password baru minimal 8 karakter dan berbeda dari password lama.</div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Password Saat Ini</label>
                                    <input type="password" class="form-control" name="current_password"
                                        placeholder="Masukkan password saat ini">
                                    <div class="text-danger error-text current_password_error mt-1 small"></div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Password Baru</label>
                                    <input type="password" class="form-control" name="new_password"
                                        placeholder="Minimal 8 karakter">
                                    <div class="text-danger error-text new_password_error mt-1 small"></div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                                    <input type="password" class="form-control" name="new_password_confirmation"
                                        placeholder="Ulangi password baru">
                                    <div class="text-danger error-text new_password_confirmation_error mt-1 small"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4" id="btn-change-password">
                                <i class="fe fe-check-circle me-1"></i> Simpan Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const editForm = document.getElementById('form-edit-profile');
            const passwordForm = document.getElementById('form-change-password');
            const defaultEditLabel = '<i class="fe fe-save me-1"></i> Simpan Perubahan';
            const defaultPasswordLabel = '<i class="fe fe-check-circle me-1"></i> Simpan Password';

            const isDarkTheme = () => document.documentElement.getAttribute('data-bs-theme') === 'dark';

            const swalTheme = () => ({
                background: isDarkTheme() ? '#1e293b' : '#ffffff',
                color: isDarkTheme() ? '#f8fafc' : '#0f172a'
            });

            const showAlert = (icon, title, text) => {
                return Swal.fire({
                    icon,
                    title,
                    text,
                    confirmButtonColor: '#0d6efd',
                    ...swalTheme(),
                });
            };

            const clearErrors = (form) => {
                form.querySelectorAll('.error-text').forEach((node) => node.textContent = '');
            };

            const setButtonState = (button, loading, loadingText, idleHtml) => {
                if (loading) {
                    button.disabled = true;
                    button.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${loadingText}`;
                    return;
                }
                button.disabled = false;
                button.innerHTML = idleHtml;
            };

            const applyErrors = (form, errors) => {
                Object.entries(errors || {}).forEach(([field, messages]) => {
                    const errorNode = form.querySelector(`.${field}_error`);
                    if (errorNode) {
                        errorNode.textContent = messages[0];
                    }
                });
            };

            const hideModal = (modalId) => {
                const modalEl = document.getElementById(modalId);
                if (!modalEl || !window.bootstrap) return;
                const modal = bootstrap.Modal.getInstance(modalEl) || bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.hide();
            };

            const submitForm = async (form, button, idleHtml, loadingText, reloadPage = true) => {
                clearErrors(form);
                setButtonState(button, true, loadingText, idleHtml);

                try {
                    const response = await fetch(form.dataset.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: new FormData(form)
                    });

                    const payload = await response.json().catch(() => ({}));

                    if (response.ok && payload.status === 'success') {
                        setButtonState(button, false, loadingText, idleHtml);
                        hideModal(form.dataset.modal);
                        form.reset();

                        await showAlert('success', 'Berhasil', payload.message || 'Perubahan berhasil disimpan.');

                        if (reloadPage) {
                            window.location.reload();
                        }
                        return;
                    }

                    if (response.status === 422 && payload.errors) {
                        setButtonState(button, false, loadingText, idleHtml);
                        applyErrors(form, payload.errors);
                        await showAlert('warning', 'Validasi Gagal', 'Periksa kembali kolom yang ditandai merah.');
                        return;
                    }

                    setButtonState(button, false, loadingText, idleHtml);
                    await showAlert('error', 'Oops...', payload.message || 'Gagal menyimpan data ke server.');
                } catch (error) {
                    setButtonState(button, false, loadingText, idleHtml);
                    await showAlert('error', 'Oops...', 'Terjadi kesalahan jaringan atau server.');
                }
            };

            if (editForm) {
                editForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const button = document.getElementById('btn-save-profile');
                    submitForm(editForm, button, defaultEditLabel, 'Menyimpan...');
                });
            }

            if (passwordForm) {
                passwordForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const button = document.getElementById('btn-change-password');
                    submitForm(passwordForm, button, defaultPasswordLabel, 'Menyimpan...', false);
                });
            }
        });
    </script>
@endpush