@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0 fw-bold text-primary">Laporan & Statistik KPI</h2>
                <p class="text-muted mb-0">Pantau performa dan realisasi KPI karyawan</p>
            </div>
            <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas">
                <i class="fe fe-filter text-white"></i> Filter Data
            </button>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="bg-primary text-white rounded-3 d-flex justify-content-center align-items-center"
                            style="width: 52px; height: 52px;">
                            <i class="fe fe-trending-up fs-4 text-white"></i>
                        </div>
                        <div class="ms-4">
                            <p class="text-muted mb-1 fw-semibold">Rata-Rata Nilai KPI</p>
                            <h3 class="mb-0 fw-bold stat-number" id="stat-avg-score" data-value="0.00" data-is-float="true">
                                0.00</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="bg-success text-white rounded-3 d-flex justify-content-center align-items-center"
                            style="width: 52px; height: 52px;">
                            <i class="fe fe-users fs-4 text-white"></i>
                        </div>
                        <div class="ms-4">
                            <p class="text-muted mb-1 fw-semibold">Total Karyawan (Scope)</p>
                            <h3 class="mb-0 fw-bold stat-number" id="stat-total-users" data-value="0" data-is-float="false">
                                0</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="bg-warning text-white rounded-3 d-flex justify-content-center align-items-center"
                            style="width: 52px; height: 52px;">
                            <i class="fe fe-award fs-4 text-white"></i>
                        </div>
                        <div class="ms-4">
                            <p class="text-muted mb-1 fw-semibold">Nilai Tertinggi</p>
                            <h3 class="mb-0 fw-bold stat-number" id="stat-highest-score" data-value="0.00"
                                data-is-float="true">0.00</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom-0 pt-4 pb-0">
                <h5 class="fw-bold mb-0">Top Performer Realisasi KPI</h5>
                <small class="text-muted" id="table-subtitle">Menampilkan top user keseluruhan (Aktif & Closed). Klik baris
                    untuk detail & mutasi.</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="kpiTable">
                        <thead class="bg-body-secondary text-muted">
                            <tr>
                                <th class="ps-4 border-0" style="width: 80px;">Rank</th>
                                <th class="border-0">Nama Karyawan</th>
                                <th class="border-0 text-center" style="width: 250px;">Pencapaian</th>
                                <th class="border-0 text-center" style="width: 150px;">Rata-rata Nilai</th>
                                <th class="border-0 text-end pe-4" style="width: 80px;"></th>
                            </tr>
                        </thead>
                        <tbody id="kpi-table-body">
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fe fe-box fs-2 mb-3 opacity-50 d-block"></i>
                                    Belum ada data. Silakan terapkan filter.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold" id="filterOffcanvasLabel">
                <i class="fe fe-filter text-primary me-2"></i> Filter Laporan
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="filterForm">
                <div class="mb-4">
                    <label class="form-label fw-bold">Periode KPI</label>
                    <select class="form-select select2-multiple" name="kpi_periods[]" multiple="multiple"
                        data-placeholder="Pilih beberapa periode...">
                        @foreach($kpiPeriods as $period)
                            <option value="{{ $period->id }}">{{ $period->name }} - {{ $period->status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Rentang Waktu Realisasi</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label text-muted small mb-1">Dari Tanggal</label>
                            <input type="date" class="form-control" name="date_from" id="date_from">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted small mb-1">Sampai Tanggal</label>
                            <input type="date" class="form-control" name="date_to" id="date_to">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Scope Unit Organisasi</label>
                    <select class="form-select select2-single" name="org_unit_id" id="sourceOrgUnits"
                        data-placeholder="Pilih scope unit...">
                        <option value=""></option>
                        <option value="all">Semua Keseluruhan</option>
                        @foreach($orgUnits as $unit)
                            <option value="{{ $unit->id }}" class="fw-bold">{{ $unit->name }}</option>
                            @foreach($unit->childrenRecursive as $child)
                                @include('performance.kpireport.partials.org-unit-option', ['child' => $child, 'depth' => 1])
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-primary py-2 fw-bold text-white">Terapkan Filter</button>
                    <button type="reset" class="btn btn-outline-secondary mt-2" id="resetFilter">Reset</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        .expandable-row {
            cursor: pointer;
        }

        .detail-row td {
            padding: 0 !important;
            border: none;
        }

        .expand-icon {
            transition: transform 0.3s ease;
        }

        .expandable-row[aria-expanded="true"] .expand-icon {
            transform: rotate(180deg);
            color: #0d6efd;
        }

        .form-control,
        .form-select {
            border-radius: 6px;
        }

        .select2-container--bootstrap-5 .select2-selection {
            box-shadow: none;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script>
        $(document).ready(function () {
            // Setup Select2
            $('.select2-multiple').select2({ theme: 'bootstrap-5', width: '100%', dropdownParent: $('#filterOffcanvas') });
            $('.select2-single').select2({ theme: 'bootstrap-5', width: '100%', allowClear: true, dropdownParent: $('#filterOffcanvas') });
            let jabatans = @json($jabatan);
            let roles = @json($role);

            function renderGolongan() {
                let golonganRomawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX'];
                let golonganHuruf = ['A', 'B', 'C', 'D', 'E'];
                let optionsHtml = '';

                for (let i = 0; i < golonganRomawi.length; i++) {
                    for (let j = 0; j < golonganHuruf.length; j++) {
                        optionsHtml += `<option
                            value="${golonganRomawi[i]}${golonganHuruf[j]}">${golonganRomawi[i]}${golonganHuruf[j]}</option>`;
                    }
                }
                return optionsHtml;
            }
            const filterOffcanvas = new bootstrap.Offcanvas(document.getElementById('filterOffcanvas'));
            let orgUnitOptionsHtml = $('#sourceOrgUnits').html();

            // Fungsi Animasi Counter Angka
            function animateValue(obj, start, end, duration, isFloat) {
                let startTimestamp = null;
                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    let currentValue = progress * (end - start) + start;
                    obj.innerHTML = isFloat ? currentValue.toFixed(2) : Math.floor(currentValue);
                    if (progress < 1) window.requestAnimationFrame(step);
                };
                window.requestAnimationFrame(step);
            }

            function updateStat(id, newValue) {
                let el = document.getElementById(id);
                let isFloat = el.getAttribute('data-is-float') === 'true';
                let startValue = parseFloat(el.getAttribute('data-value')) || 0;
                let endValue = parseFloat(newValue) || 0;
                el.setAttribute('data-value', endValue);
                animateValue(el, startValue, endValue, 1200, isFloat);
            }

            // Handle Submit Filter
            $('#filterForm').on('submit', function (e) {
                e.preventDefault();
                let formData = $(this).serialize();
                filterOffcanvas.hide();

                Swal.fire({
                    title: 'Memproses Data...',
                    html: 'Sedang menarik statistik dan menghitung nilai KPI.',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                $.ajax({
                    url: "{{ route('reports.kpi.data') }}",
                    type: "GET",
                    data: formData,
                    success: function (response) {
                        Swal.close();

                        updateStat('stat-avg-score', response.stats.avg_score);
                        updateStat('stat-total-users', response.stats.total_users);
                        updateStat('stat-highest-score', response.stats.highest_score);

                        let unitName = $('select[name="org_unit_id"] option:selected').text();
                        $('#table-subtitle').text('Menampilkan data untuk: ' + (unitName.trim() || 'Semua Keseluruhan'));

                        let html = '';
                        if (response.users.length === 0) {
                            html = `<tr><td colspan="5" class="text-center py-5 text-muted"><i class="fe fe-folder fs-3 mb-3 opacity-50 d-block"></i>Tidak ada data ditemukan.</td></tr>`;
                        } else {
                            response.users.forEach((user, index) => {
                                let rankStyle = 'text-muted';
                                if (index === 0) rankStyle = 'text-warning fw-bold fs-5';
                                else if (index === 1) rankStyle = 'text-secondary fw-bold fs-5';
                                else if (index === 2) rankStyle = 'text-danger fw-bold fs-5';

                                let score = parseFloat(user.avg_nilai).toFixed(2);
                                let barColor = score >= 90 ? 'bg-success' : (score >= 70 ? 'bg-primary' : 'bg-warning');

                                // Hitung Lama Kerja menggunakan Moment.js
                                let lamaKerja = '-';
                                if (user.tanggal_masuk) {
                                    let dateIn = moment(user.tanggal_masuk);
                                    let now = moment();
                                    let diffYears = now.diff(dateIn, 'years');
                                    dateIn.add(diffYears, 'years');
                                    let diffMonths = now.diff(dateIn, 'months');

                                    lamaKerja = `${diffYears} Tahun ${diffMonths} Bulan`;
                                }

                                // 1. Baris Utama (NIP ditampilkan di bawah nama sebelum expand)
                                html += `
                                                            <tr class="expandable-row" data-bs-toggle="collapse" data-bs-target="#user-detail-${user.user_id}" aria-expanded="false">
                                                                <td class="ps-4 ${rankStyle}">${index + 1}</td>
                                                                <td>
                                                                    <div class="fw-bold text-primary">${user.user_name}</div>
                                                                    <small class="text-muted">NIP: ${user.nip ?? '-'} | Username: ${user.username ?? '-'}</small>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="d-flex flex-column align-items-center w-100">
                                                                        <div class="progress w-75" style="height: 6px;">
                                                                            <div class="progress-bar ${barColor}" style="width: ${score}%"></div>
                                                                        </div>
                                                                        <small class="text-muted mt-1">${score}%</small>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center fw-bold fs-6">
                                                                    ${score}
                                                                </td>
                                                                <td class="text-end pe-4">
                                                                    <i class="fe fe-chevron-down text-muted expand-icon fs-5"></i>
                                                                </td>
                                                            </tr>
                                                        `;

                                // 2. Baris Expand (Menampilkan NIP sesudah expand & Lama Kerja)
                                html += `
                                                            <tr class="detail-row">
                                                                <td colspan="5">
                                                                    <div id="user-detail-${user.user_id}" class="collapse">
                                                                        <div class="card card-body rounded-0 border-start-0 border-end-0 border-bottom-0 shadow-none bg-body-tertiary p-4 m-0">

                                                                            <div class="row g-3 mb-4">
                                                                                <div class="col-md-2 col-sm-6">
                                                                                    <small class="text-muted d-block fw-semibold mb-1">NIP</small>
                                                                                    <div class="fw-bold text-body">${user.nip ?? '-'}</div>
                                                                                </div>
                                                                                <div class="col-md-2 col-sm-6">
                                                                                    <small class="text-muted d-block fw-semibold mb-1">Lama Kerja</small>
                                                                                    <div class="fw-bold text-body text-success">${lamaKerja}</div>
                                                                                </div>
                                                                                <div class="col-md-2 col-sm-6">
                                                                                    <small class="text-muted d-block fw-semibold mb-1">Unit Organisasi</small>
                                                                                    <div class="fw-bold text-body">${user.unit_name ?? '-'}</div>
                                                                                </div>
                                                                                <div class="col-md-2 col-sm-6">
                                                                                    <small class="text-muted d-block fw-semibold mb-1">Jabatan</small>
                                                                                    <div class="fw-bold text-body">${user.jabatan_name ?? '-'}</div>
                                                                                </div>
                                                                                <div class="col-md-2 col-sm-6">
                                                                                    <small class="text-muted d-block fw-semibold mb-1">Role Sistem</small>
                                                                                    <div class="fw-bold text-body">${user.role_name ?? '-'}</div>
                                                                                </div>
                                                                                <div class="col-md-2 col-sm-6">
                                                                                    <small class="text-muted d-block fw-semibold mb-1">Total Target KPI</small>
                                                                                    <div class="fw-bold text-body">${user.total_kpi} Item</div>
                                                                                </div>
                                                                            </div>

                                                                            <hr class="text-muted opacity-25 my-0 mb-4">

                                                                            <div class="d-flex align-items-center mb-3">
                                                                                <div class="bg-primary text-white p-2 rounded me-3 d-flex align-items-center justify-content-center" style="width:32px; height:32px;">
                                                                                    <i class="fe fe-briefcase text-white"></i>
                                                                                </div>
                                                                                <h6 class="mb-0 fw-bold">Form Perubahan Posisi & Mutasi</h6>
                                                                            </div>
                    <form class="mutation-form" data-user-id="${user.user_id}">
                        <div class="row g-3">
                            <div class="col-md-4">

                                <label class="form-label small text-muted fw-bold">Jabatan Baru</label>
                                <select class="form-select form-select-sm" name="new_jabatan_id" required>
                                    <option value="">-- Pilih Jabatan Baru --</option>
                                    ${Object.entries(jabatans).map(([id, name]) => `<option value="${id}">${name}</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted fw-bold">Pindah Unit Organisasi / Divisi</label>
                                <select class="form-select form-select-sm" name="new_org_unit_id" required>
                                    ${orgUnitOptionsHtml}
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted fw-bold">Pindah Role (Akses Sistem)</label>
                                <select class="form-select form-select-sm" name="new_role_id" required>
                                    <option value="">-- Pilih Role Baru --</option>
                                    ${Object.entries(roles).map(([id, name]) => `<option value="${id}">${name}</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted fw-bold">Golongan Baru</label>
                                <select class="form-select form-select-sm" name="golongan_select" required>
                                    <option value="">-- Pilih Golongan --</option>
                                    ${renderGolongan()}
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted fw-bold">Mulai Aktif Tanggal</label>
                                <input type="date" class="form-control form-control-sm" name="effective_date" required>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-success btn-sm w-100 fw-bold text-white shadow-sm">
                                    <i class="fe fe-save text-white me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>

                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        `;
                            });
                        }

                        $('#kpi-table-body').html(html);
                    },
                    error: function (err) {
                        Swal.fire('Error!', 'Gagal mengambil data.', 'error');
                    }
                });
            });

            // Handle Submit Form Mutasi
            $(document).on('submit', '.mutation-form', function (e) {
                e.preventDefault();
                let form = $(this);
                let userId = form.data('user-id');

                // Gabungkan data form dengan user_id
                let formData = form.serialize() + '&user_id=' + userId;

                Swal.fire({
                    title: 'Simpan Perubahan Posisi?',
                    text: "Sistem akan menyimpan dan menyetujui posisi baru sesuai tanggal yang diisi.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {

                        // Tampilkan loading state
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang menyimpan data mutasi',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        // Jalankan request AJAX
                        $.ajax({
                            url: "{{ route('mutasi.storeDirect') }}", // Panggil route yang baru dibuat
                            type: "POST",
                            data: formData,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message
                                });

                                // Tutup accordion (collapse) detail user
                                $(`#user-detail-${userId}`).collapse('hide');

                                // Reset form setelah berhasil submit
                                form.trigger('reset');
                            },
                            error: function (xhr) {
                                let errorMessage = "Terjadi kesalahan pada sistem.";
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMessage
                                });
                            }
                        });
                    }
                });
            });

            $('#resetFilter').click(function () {
                $('.select2-multiple, .select2-single').val(null).trigger('change');
            });
        });
    </script>
@endpush