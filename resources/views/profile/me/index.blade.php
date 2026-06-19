@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Desain Cover Background */
        .profile-cover-modern {
            height: 250px;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            border-radius: 10px 10px 0 0;
            position: relative;
        }

        /* Overlay gelap agar terlihat elegan jika background tidak ada */
        .profile-cover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.5) 100%);
            border-radius: 10px 10px 0 0;
        }

        /* Box Informasi di bawah cover */
        .profile-info-bar {
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: flex-end;
            margin-top: -60px;
            /* Membuat konten naik menimpa cover */
            position: relative;
            z-index: 2;
        }

        /* Foto Profil yang presisi dan bulat sempurna */
        .profile-avatar-modern {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #ffffff;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 3;
        }

        /* Detail Teks */
        .profile-details {
            margin-left: 25px;
            padding-bottom: 10px;
            flex-grow: 1;
        }

        /* Responsiveness untuk Layar HP */
        @media (max-width: 768px) {
            .profile-info-bar {
                flex-direction: column;
                align-items: center;
                text-align: center;
                margin-top: -80px;
            }

            .profile-details {
                margin-left: 0;
                margin-top: 15px;
                padding-bottom: 0;
            }

            .btn-profile {
                margin-top: 20px;
                width: 100%;
            }

            .btn-profile button {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row mb-4">
        <div class="col-lg-12">

            <div class="card border-0 shadow-sm rounded-3">
                @php
                    $hasBg = $user->ownProfile && $user->ownProfile->background;
                    $bgUrl = $hasBg ? asset('storage/' . $user->ownProfile->background) : '';
                    $bgColor = $hasBg ? 'transparent' : '#2c3e50'; // Warna dasar jika foto kosong
                @endphp

                <div class="profile-cover-modern"
                    style="background-image: url('{{ $bgUrl }}'); background-color: {{ $bgColor }};">
                    <div class="profile-cover-overlay"></div>
                </div>

                <div class="profile-info-bar d-flex flex-column flex-md-row">
                    <img src="{{ $user->ownProfile?->avatar ? asset('storage/' . $user->ownProfile->avatar) : "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&background=random" }}"
                        alt="Avatar" class="profile-avatar-modern">

                    <div class="profile-details">
                        <h3 class="fw-bold mb-1 text-dark">{{ $user->name }}</h3>
                        <p class="text-muted mb-0 fs-15">
                            <i class="fe fe-award text-primary me-1"></i> <span
                                class="fw-semibold">{{ strtoupper($user->role?->name) }}</span>
                            @if($user->profile?->jabatan)
                                <span class="mx-2">|</span>
                                <i class="fe fe-briefcase text-primary me-1"></i> {{ $user->profile?->jabatan->name }}
                            @endif
                        </p>
                    </div>

                    <div class="btn-profile ms-md-auto align-self-md-center">
                        @if ($user->id === auth()->id())
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#editProfileModal">
                                <i class="fe fe-edit-2 me-1"></i> Edit Profile
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0 fw-bold">Informasi Pekerjaan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4 mt-2">
                        <div class="me-4 text-center text-primary bg-primary-transparent p-2 rounded">
                            <i class="fe fe-briefcase fs-20"></i>
                        </div>
                        <div>
                            <strong
                                class="text-dark">{{ $user->profile?->jabatan->name ?? "Belum Ditentukan" }}</strong><br>
                            <span class="text-muted fs-13">Jabatan</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-4 text-center text-success bg-success-transparent p-2 rounded">
                            <i class="fe fe-map-pin fs-20"></i>
                        </div>
                        <div>
                            <strong class="text-dark">{{ $user->profile?->outlet->name ?? "Belum Ditentukan" }}</strong><br>
                            <span class="text-muted fs-13">Outlet / Lokasi</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-4 text-center text-info bg-info-transparent p-2 rounded">
                            <i class="fe fe-box fs-20"></i>
                        </div>
                        <div>
                            <strong
                                class="text-dark">{{ $user->profile?->organizationalUnit->name ?? "Belum Ditentukan" }}</strong><br>
                            <span class="text-muted fs-13">Unit Organisasi</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="me-4 text-center text-warning bg-warning-transparent p-2 rounded">
                            <i class="fe fe-calendar fs-20"></i>
                        </div>
                        <div>
                            <strong
                                class="text-dark">{{ $user->profile?->tanggal_masuk?->format('d M Y') ?? "Belum Ditentukan" }}</strong><br>
                            <span class="text-muted fs-13">Tanggal Masuk</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0 fw-bold">Aktivitas Saya</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center p-4">
                            <span class="fw-semibold"><i class="fe fe-activity text-danger me-2 fs-16"></i> Jogging
                                Distance</span>
                            <span class="badge bg-danger rounded-pill px-3 py-2 fs-12">{{ $joggingCount }} Km</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-4">
                            <span class="fw-semibold"><i class="fe fe-user-check text-secondary me-2 fs-16"></i> Prayer
                                Reports</span>
                            <span class="badge bg-secondary rounded-pill px-3 py-2 fs-12">{{ $prayerCount }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-4">
                            <span class="fw-semibold"><i class="fe fe-book text-primary me-2 fs-16"></i> Books Read</span>
                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-12">{{ $bookLogs }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Informasi Pribadi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped text-nowrap mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-3 py-3 text-muted" width="250">Nama Lengkap</th>
                                    <td class="py-3"><span class="fw-bold text-dark">{{ $user->name }}</span></td>
                                </tr>
                                <tr>
                                    <th class="ps-3 py-3 text-muted">Nomor Induk Pegawai (NIP)</th>
                                    <td class="py-3"><span
                                            class="fw-semibold text-dark">{{ $user->ownProfile?->nip ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <th class="ps-3 py-3 text-muted">Jenis Kelamin</th>
                                    <td class="py-3"><span class="fw-semibold text-dark">
                                            @if($user->ownProfile?->jenis_kelamin == 'L') Laki-Laki
                                            @elseif($user->ownProfile?->jenis_kelamin == 'P') Perempuan
                                            @else - @endif
                                        </span></td>
                                </tr>
                                <tr>
                                    <th class="ps-3 py-3 text-muted">Tanggal Lahir</th>
                                    <td class="py-3"><span
                                            class="fw-semibold text-dark">{{ $user->ownProfile?->tanggal_lahir ? \Carbon\Carbon::parse($user->ownProfile->tanggal_lahir)->format('d F Y') : '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-3 py-3 text-muted">Pendidikan Terakhir</th>
                                    <td class="py-3"><span
                                            class="fw-semibold text-dark">{{ $user->ownProfile?->tamatan ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-3 py-3 text-muted">Tipe BPJS</th>
                                    <td class="py-3"><span
                                            class="fw-semibold text-dark">{{ $user->ownProfile?->tipe_bpjs ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-3 py-3 align-top text-muted">Alamat (KTP)</th>
                                    <td class="py-3 text-wrap"><span
                                            class="fw-semibold text-dark">{{ $user->ownProfile?->alamat ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-3 py-3 align-top text-muted">Domisili Saat Ini</th>
                                    <td class="py-3 text-wrap"><span
                                            class="fw-semibold text-dark">{{ $user->ownProfile?->domisili ?? '-' }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="form-edit-profile" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="editProfileModalLabel">Edit Data Pribadi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Pengaturan Tampilan</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Foto Profil (Avatar)</label>
                                        <input class="form-control" type="file" name="avatar"
                                            accept="image/jpeg, image/png, image/jpg">
                                        <small class="text-muted">Abaikan jika tidak ingin mengubah foto.</small>
                                        <div class="text-danger error-text avatar_error mt-1 fs-12"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Foto Sampul (Background)</label>
                                        <input class="form-control" type="file" name="background"
                                            accept="image/jpeg, image/png, image/jpg">
                                        <small class="text-muted">Abaikan jika tidak ingin mengubah cover.</small>
                                        <div class="text-danger error-text background_error mt-1 fs-12"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Informasi Personal</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-semibold">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="name" value="{{ $user->name }}"
                                            placeholder="Masukkan Nama Lengkap">
                                        <div class="text-danger error-text name_error mt-1 fs-12"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">NIP</label>
                                        <input type="text" class="form-control" name="nip"
                                            value="{{ $user->ownProfile?->nip }}" placeholder="Masukkan NIP">
                                        <div class="text-danger error-text nip_error mt-1 fs-12"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Tipe BPJS</label>
                                        <input type="text" class="form-control" name="tipe_bpjs"
                                            value="{{ $user->ownProfile?->tipe_bpjs }}"
                                            placeholder="Kesehatan / Ketenagakerjaan">
                                        <div class="text-danger error-text tipe_bpjs_error mt-1 fs-12"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-select form-control">
                                            <option value="">-- Pilih --</option>
                                            <option value="L" {{ $user->ownProfile?->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ $user->ownProfile?->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        <div class="text-danger error-text jenis_kelamin_error mt-1 fs-12"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="tanggal_lahir"
                                            value="{{ $user->ownProfile?->tanggal_lahir }}">
                                        <div class="text-danger error-text tanggal_lahir_error mt-1 fs-12"></div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-semibold">Pendidikan Terakhir (Tamatan)</label>
                                        <input type="text" class="form-control" name="tamatan"
                                            value="{{ $user->ownProfile?->tamatan }}"
                                            placeholder="Contoh: S1 Teknik Informatika">
                                        <div class="text-danger error-text tamatan_error mt-1 fs-12"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Alamat Sesuai KTP</label>
                                        <textarea class="form-control" name="alamat" rows="3"
                                            placeholder="Alamat KTP">{{ $user->ownProfile?->alamat }}</textarea>
                                        <div class="text-danger error-text alamat_error mt-1 fs-12"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Domisili Saat Ini</label>
                                        <textarea class="form-control" name="domisili" rows="3"
                                            placeholder="Alamat Domisili">{{ $user->ownProfile?->domisili }}</textarea>
                                        <div class="text-danger error-text domisili_error mt-1 fs-12"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary px-4" id="btn-save-profile">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('#form-edit-profile').on('submit', function (e) {
                e.preventDefault();

                let form = this;
                let formData = new FormData(form);
                let submitBtn = $('#btn-save-profile');

                $(form).find('.error-text').text('');
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...');

                $.ajax({
                    url: "{{ route('profile.me.update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        submitBtn.prop('disabled', false).text('Simpan Perubahan');

                        if (response.status === 'success') {
                            $('#editProfileModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false,
                                backdrop: `rgba(0,0,0,0.4)`
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function (xhr) {
                        submitBtn.prop('disabled', false).text('Simpan Perubahan');

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (prefix, val) {
                                $(form).find('div.' + prefix + '_error').text(val[0]);
                            });

                            Swal.fire({
                                icon: 'warning',
                                title: 'Validasi Gagal',
                                text: 'Periksa kembali kolom yang berwarna merah.',
                                confirmButtonColor: '#3085d6'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON.message || 'Gagal menyimpan data ke server.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush