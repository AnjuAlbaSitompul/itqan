@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4 peringatan-container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="fw-bolder mb-1"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Daftar Peringatan
                </h2>
                <p class="text-body-secondary mb-0 small">Kelola persetujuan Surat Peringatan (SP) karyawan</p>
            </div>
            <button class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-sm d-flex align-items-center gap-2"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddSp">
                <i class="bi bi-plus-lg"></i> Tambah SP
            </button>
        </div>

        <div class="mb-4 overflow-auto pb-2 filter-wrapper">
            <div class="nav nav-pills custom-filter-pills flex-nowrap" id="peringatanFilter" role="tablist">
                <button class="nav-link active rounded-pill px-4 fw-medium" data-filter="all">Semua</button>
                <button class="nav-link rounded-pill px-4 fw-medium" data-filter="pending"><span class="me-2"><i
                            class="bi bi-clock"></i></span> Pending</button>
                <button class="nav-link rounded-pill px-4 fw-medium" data-filter="approved"><span class="me-2"><i
                            class="bi bi-check-circle"></i></span> Approved</button>
                <button class="nav-link rounded-pill px-4 fw-medium" data-filter="rejected"><span class="me-2"><i
                            class="bi bi-x-circle"></i></span> Rejected</button>
            </div>
        </div>

        <!-- Kontainer Data Dinamis -->
        <div class="accordion modern-accordion" id="accordionPeringatanList">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-body-secondary">Memuat data peringatan...</p>
            </div>
        </div>
    </div>

    <!-- Offcanvas Approve -->
    <div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="offcanvasApproveSP">
        <div class="offcanvas-header bg-body-tertiary border-bottom">
            <h5 class="offcanvas-title fw-bold"><i class="bi bi-shield-check text-success me-2"></i>Sahkan Peringatan</h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <div class="mb-4 bg-warning bg-opacity-10 p-3 rounded-4 border border-warning-subtle">
                <span class="text-warning-emphasis small fw-bold text-uppercase d-block mb-1">Karyawan</span>
                <h5 class="fw-bold text-dark mb-0" id="approve_employee_name">Nama Karyawan</h5>
                <span class="badge bg-danger mt-2" id="approve_sp_type">Tipe SP</span>
            </div>

            <form id="approvePeringatanForm" class="flex-grow-1">
                <input type="hidden" name="peringatan_id" id="approve_peringatan_id">

                <div class="mb-4">
                    <label class="form-label fw-bold text-body-secondary">Berlaku Sampai (Due Date) <span
                            class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-lg rounded-4 bg-body-tertiary" name="due_date"
                        required>
                    <div class="form-text small mt-2 text-muted"><i class="bi bi-info-circle me-1"></i> Default
                        masa berlaku SP umumnya adalah 3 hingga 6 bulan sejak diterbitkan.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-body-secondary">Catatan HR (Opsional)</label>
                    <textarea class="form-control rounded-4 bg-body-tertiary" name="approval_notes" rows="4"
                        placeholder="Cth: Karyawan wajib mengikuti sesi konseling HR..."></textarea>
                </div>

                <div class="mt-auto pt-3">
                    <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow-sm"
                        id="btnSubmitApprove">
                        Sahkan SP Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="offcanvasAddSp" style="width: 450px;">
        <div class="offcanvas-header bg-body-tertiary border-bottom">
            <h5 class="offcanvas-title fw-bold"><i class="bi bi-plus-circle text-warning me-2"></i>Buat SP Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form id="formAddSP">
                <div class="mb-4">
                    <label class="form-label fw-bold">Pilih Karyawan</label>
                    <select class="form-select select2-karyawan" id="selectKaryawan" name="user_ids[]" multiple="multiple"
                        style="width: 100%;" required>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Tipe Surat Peringatan</label>
                    <select class="form-select form-select-lg rounded-4" name="sp_type" required>
                        <option value="peringatan_1">SP 1</option>
                        <option value="peringatan_2">SP 2</option>
                        <option value="peringatan_3">SP 3</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Berlaku Sampai (Due Date) <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-lg rounded-4 bg-body-tertiary" name="due_date"
                        required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Alasan/Kronologi</label>
                    <textarea class="form-control rounded-4" name="reason" rows="4"
                        placeholder="Jelaskan kronologi pelanggaran..." required></textarea>
                </div>

                <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold py-3" id="btnSaveSP">
                    Simpan & Terbitkan
                </button>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .peringatan-container {
            transition: all 0.3s ease;
        }

        .filter-wrapper::-webkit-scrollbar {
            display: none;
        }

        .custom-filter-pills .nav-link {
            color: var(--bs-body-color);
            border: 1px solid var(--bs-border-color);
            margin-right: 0.5rem;
            background: var(--bs-body-bg);
            transition: all 0.2s;
        }

        .custom-filter-pills .nav-link:hover {
            background: var(--bs-tertiary-bg);
        }

        .custom-filter-pills .nav-link.active {
            background-color: var(--bs-primary);
            color: #fff;
            border-color: var(--bs-primary);
            box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.2);
        }

        .modern-accordion .accordion-item {
            border: none;
            background: transparent;
            margin-bottom: 1rem;
        }

        .modern-accordion .accordion-header {
            background: var(--bs-body-bg);
            border-radius: 1rem;
            border: 1px solid var(--bs-border-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }

        .modern-accordion .accordion-item:hover .accordion-header {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }

        .modern-accordion .accordion-button {
            background: transparent;
            padding: 1.25rem;
            box-shadow: none !important;
            color: var(--bs-body-color);
        }

        .modern-accordion .accordion-button:not(.collapsed) {
            background-color: rgba(var(--bs-warning-rgb), 0.05);
            border-bottom: 1px solid var(--bs-border-color);
        }

        .modern-accordion .accordion-collapse {
            background: var(--bs-body-bg);
            border-radius: 0 0 1rem 1rem;
            border: 1px solid var(--bs-border-color);
            border-top: none;
            margin-top: -10px;
            padding-top: 10px;
        }

        .detail-box {
            border: 1px dashed var(--bs-border-color);
        }

        [data-bs-theme="dark"] .modern-accordion .accordion-header {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        [data-bs-theme="dark"] .modern-accordion .accordion-item:hover .accordion-header {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
        }

        /* Styling Select2 untuk tampilan yang lebih modern */
        .select2-container--bootstrap-5 .select2-selection--multiple {
            border: 1px solid var(--bs-border-color) !important;
            border-radius: 0.75rem !important;
            /* Disesuaikan dengan rounded-4 */
            padding: 0.4rem !important;
            min-height: 50px !important;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
            background-color: var(--bs-warning) !important;
            border: none !important;
            color: var(--bs-dark) !important;
            font-weight: 600 !important;
            border-radius: 20px !important;
            padding: 4px 12px !important;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
            color: var(--bs-dark) !important;
            border-right: none !important;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // Setup CSRF

            let employees = @json($karyawans);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // 1. Initial Data Load
            loadPeringatanData();

            $('.select2-karyawan').select2({
                placeholder: "Cari dan pilih karyawan...",
                allowClear: true,// Pastikan sudah menggunakan bootstrap 5 theme untuk select2
                dropdownParent: $('#offcanvasAddSp'),
                data: employees.map(emp => ({
                    id: emp.id,
                    text: emp.name
                }))
            });

            // 2. Filter Action
            $('.custom-filter-pills .nav-link').on('click', function () {
                $('.custom-filter-pills .nav-link').removeClass('active');
                $(this).addClass('active');
                applyFilter();
            });

            function applyFilter() {
                let filterValue = $('.custom-filter-pills .nav-link.active').data('filter');
                if (filterValue === 'all') {
                    $('.peringatan-item').slideDown(300);
                } else {
                    $('.peringatan-item').each(function () {
                        if ($(this).data('status') === filterValue) {
                            $(this).slideDown(300);
                        } else {
                            $(this).slideUp(300);
                        }
                    });
                }
            }

            // 3. Load Data Via AJAX (Route: peringatan.data)
            function loadPeringatanData() {
                $.ajax({
                    url: '{{ route("peringatan.data") }}',
                    type: 'GET',
                    success: function (res) {
                        if (res.success) {
                            let html = '';
                            if (res.data.length === 0) {
                                html = '<div class="text-center py-5 text-muted">Belum ada data peringatan.</div>';
                            } else {
                                res.data.forEach(function (item) {
                                    html += buildAccordionItem(item);
                                });
                            }
                            $('#accordionPeringatanList').html(html);
                            applyFilter(); // Terapkan filter yang sedang aktif
                        }
                    },
                    error: function () {
                        $('#accordionPeringatanList').html('<div class="text-center py-4 text-danger">Gagal memuat data dari server.</div>');
                    }
                });
            }

            // 4. HTML Builder untuk Data SP
            function buildAccordionItem(item) {
                let statusBadge = '';
                let iconClass = '';
                let iconBg = '';
                let actionContent = '';
                let collapseId = 'peringatan_' + item.id;

                // Pengaturan Styling berdasarkan Status
                if (item.status === 'pending') {
                    iconBg = 'bg-warning bg-opacity-10 text-white';
                    iconClass = 'bi-shield-exclamation';
                    statusBadge = `<span class="badge bg-warning bg-opacity-10 text-white border border-warning-subtle rounded-pill px-3 py-2"><i class="bi bi-hourglass-split me-1"></i> Pending</span>`;
                    actionContent = `
                                                                                                                                                                                    <div class="col-md-6">
                                                                                                                                                                                        <div class="detail-box p-3 h-100 rounded-4 bg-body-tertiary border-warning">
                                                                                                                                                                                            <span class="d-block text-uppercase text-body-secondary small fw-bold mb-2">Aksi Cepat (Validasi HR)</span>
                                                                                                                                                                                            <p class="small text-body-secondary mb-3">Tentukan masa berlaku SP dan sahkan surat peringatan ini.</p>
                                                                                                                                                                                            <div class="d-flex gap-2 flex-wrap">
                                                                                                                                                                                                <button class="btn btn-success rounded-pill px-4 fw-medium flex-grow-1 action-approve-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasApproveSP" data-id="${item.id}" data-name="${item.user_name}" data-sp="${item.type_label}">
                                                                                                                                                                                                    <i class="bi bi-check2-circle me-1"></i> Sahkan SP
                                                                                                                                                                                                </button>
                                                                                                                                                                                                <button class="btn btn-outline-danger rounded-pill px-4 fw-medium flex-grow-1 action-reject-btn" data-id="${item.id}">
                                                                                                                                                                                                    <i class="bi bi-x-circle me-1"></i> Tolak SP
                                                                                                                                                                                                </button>
                                                                                                                                                                                            </div>
                                                                                                                                                                                        </div>
                                                                                                                                                                                    </div>`;
                } else if (item.status === 'approved') {
                    iconBg = 'bg-success bg-opacity-10 text-white';
                    iconClass = 'bi-check-circle';
                    statusBadge = `<span class="badge bg-success bg-opacity-10 text-white border border-success-subtle rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i> Approved</span>`;
                    actionContent = `
                                                                                                                                                                                    <div class="col-12 mt-3">
                                                                                                                                                                                        <div class="alert border-success-subtle bg-success bg-opacity-10 text-success rounded-4 d-flex align-items-center gap-3 mb-0">
                                                                                                                                                                                            <i class="bi bi-info-circle-fill fs-4 text-white"></i>
                                                                                                                                                                                            <div>
                                                                                                                                                                                                <strong class="text-white">SP Telah Disahkan!</strong>
                                                                                                                                                                                                <div class="small text-white">Masa Berlaku SP sampai dengan: ${item.due_date || '-'}.</div>
                                                                                                                                                                                            </div>
                                                                                                                                                                                        </div>
                                                                                                                                                                                    </div>`;
                } else {
                    iconBg = 'bg-danger bg-opacity-10 text-white';
                    iconClass = 'bi-x-octagon';
                    statusBadge = `<span class="badge bg-danger bg-opacity-10 text-white border border-danger-subtle rounded-pill px-3 py-2"><i class="bi bi-x-circle me-1"></i> Rejected</span>`;
                    actionContent = `
                                                                                                                                                                                    <div class="col-12 mt-3">
                                                                                                                                                                                        <div class="alert border-danger-subtle bg-danger bg-opacity-10 text-danger rounded-4 d-flex align-items-center gap-3 mb-0">
                                                                                                                                                                                            <i class="bi bi-x-circle-fill fs-4 text-white"></i>
                                                                                                                                                                                            <div>
                                                                                                                                                                                                <strong class="text-white">SP Ditolak</strong>
                                                                                                                                                                                                <div class="small text-white">Pengajuan SP ini telah ditolak.</div>
                                                                                                                                                                                            </div>
                                                                                                                                                                                        </div>
                                                                                                                                                                                    </div>`;
                }

                return `
                                                                                                                                                                            <div class="accordion-item peringatan-item" data-status="${item.status}">
                                                                                                                                                                                <h2 class="accordion-header">
                                                                                                                                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                                                                                                                                                                                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between w-100 pe-3 gap-3">
                                                                                                                                                                                            <div class="d-flex align-items-center gap-3">
                                                                                                                                                                                                <div class="${iconBg} rounded-circle p-3 d-flex align-items-center justify-content-center shadow-sm" style="width:48px; height:48px">
                                                                                                                                                                                                    <i class="bi ${iconClass} fs-5"></i>
                                                                                                                                                                                                </div>
                                                                                                                                                                                                <div>
                                                                                                                                                                                                    <h6 class="fw-bold mb-1">${item.user_name}</h6>
                                                                                                                                                                                                    <div class="text-body-secondary small d-flex align-items-center flex-wrap gap-2">
                                                                                                                                                                                                        <span class="badge bg-danger text-white border border-danger-subtle">${item.type_label}</span>
                                                                                                                                                                                                        <i class="bi bi-circle-fill text-muted" style="font-size: 4px;"></i>
                                                                                                                                                                                                        <span>${item.unit_name}</span>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                </div>
                                                                                                                                                                                            </div>
                                                                                                                                                                                            <div class="text-start text-md-end">
                                                                                                                                                                                                ${statusBadge}
                                                                                                                                                                                            </div>
                                                                                                                                                                                        </div>
                                                                                                                                                                                    </button>
                                                                                                                                                                                </h2>
                                                                                                                                                                                <div id="${collapseId}" class="accordion-collapse collapse" data-bs-parent="#accordionPeringatanList">
                                                                                                                                                                                    <div class="accordion-body">
                                                                                                                                                                                        <div class="row g-4 mb-2">
                                                                                                                                                                                            <div class="${item.status === 'pending' ? 'col-md-6' : 'col-md-12'}">
                                                                                                                                                                                                <div class="detail-box p-3 h-100 rounded-4 bg-body-tertiary">
                                                                                                                                                                                                    <span class="d-block text-uppercase text-body-secondary small fw-bold mb-2">Detail Pelanggaran</span>
                                                                                                                                                                                                    <div class="mb-2"><small class="text-body-secondary">Tgl Kejadian/Issued:</small> <span class="fw-medium">${item.issued_date}</span></div>
                                                                                                                                                                                                    <div class="mb-2"><small class="text-body-secondary">Diajukan Oleh:</small> <span class="fw-medium">${item.requested_by}</span></div>
                                                                                                                                                                                                    <div><small class="text-body-secondary">Alasan/Kronologi:</small>
                                                                                                                                                                                                        <p class="mb-0 fw-medium text-danger">${item.reason}</p>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                </div>
                                                                                                                                                                                            </div>
                                                                                                                                                                                            ${actionContent}
                                                                                                                                                                                        </div>
                                                                                                                                                                                    </div>
                                                                                                                                                                                </div>
                                                                                                                                                                            </div>`;
            }

            // 5. Trigger Offcanvas Approve (Event Delegation Karena DOM Dinamis)
            $(document).on('click', '.action-approve-btn', function () {
                let peringatanId = $(this).data('id');
                let empName = $(this).data('name');
                let spType = $(this).data('sp');

                $('#approve_peringatan_id').val(peringatanId);
                $('#approve_employee_name').text(empName);
                $('#approve_sp_type').text(spType);
                $('#approvePeringatanForm')[0].reset();
            });

            // 6. Submit Approve Form (Route: peringatan.approval.hr)
            $('#approvePeringatanForm').on('submit', function (e) {
                e.preventDefault();

                let submitBtn = $('#btnSubmitApprove');
                let btnOriginalText = submitBtn.html();
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Memproses...');

                let payload = {
                    peringatan_id: $('#approve_peringatan_id').val(),
                    action: 'approve',
                    due_date: $('input[name="due_date"]').val(),
                    approval_notes: $('textarea[name="approval_notes"]').val()
                };

                $.ajax({
                    url: '{{ route("peringatan.approval.hr") }}',
                    type: 'POST',
                    data: payload,
                    success: function (res) {
                        if (res.success) {
                            swal({
                                title: "Berhasil!",
                                text: res.message,
                                icon: "success"
                            })
                            // Tutup Offcanvas
                            let offcanvasEl = document.getElementById('offcanvasApproveSP');
                            let offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasEl);
                            offcanvasInstance.hide();

                            // Reload Data via AJAX (Tanpa Refresh)
                            loadPeringatanData();
                        }
                    },
                    error: function (xhr) {
                        let errMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                        swal({
                            title: "Gagal!",
                            text: errMsg,
                            icon: "error"
                        });
                    },
                    complete: function () {
                        submitBtn.prop('disabled', false).html(btnOriginalText);
                    }
                });
            });

            // 7. Trigger Reject Action (Route: peringatan.approval.hr)
            $(document).on('click', '.action-reject-btn', function () {
                let peringatanId = $(this).data('id');

                if (confirm('Apakah Anda yakin ingin menolak persetujuan Surat Peringatan ini?')) {
                    let btn = $(this);
                    let btnOriginalText = btn.html();
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                    $.ajax({
                        url: '{{ route("peringatan.approval.hr") }}',
                        type: 'POST',
                        data: {
                            peringatan_id: peringatanId,
                            action: 'reject'
                        },
                        success: function (res) {
                            if (res.success) {
                                swal({
                                    title: "Berhasil!",
                                    text: res.message,
                                    icon: "success"
                                });
                                // Reload Data via AJAX (Tanpa Refresh)
                                loadPeringatanData();
                            }
                        },
                        error: function (xhr) {
                            let errMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                            swal({
                                title: "Gagal!",
                                text: errMsg,
                                icon: "error"
                            });
                            btn.prop('disabled', false).html(btnOriginalText);
                        }
                    });
                }
            });

            // 1. Inisialisasi Select2


            // 2. Submit Form Add SP
            // Submit Form Add SP
            $('#formAddSP').on('submit', function (e) {
                e.preventDefault();
                let btn = $('#btnSaveSP');

                // Pastikan data select2 masuk ke form
                let formData = $(this).serialize();

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...');

                $.ajax({
                    url: '{{ route("peringatan.store") }}',
                    type: 'POST',
                    data: formData, // Mengirim user_ids[] dan field lainnya
                    success: function (res) {
                        swal({
                            title: "Berhasil!",
                            text: res.message,
                            icon: "success"
                        });
                        $('#offcanvasAddSp').offcanvas('hide');
                        $('#formAddSP')[0].reset();
                        $('.select2-karyawan').val(null).trigger('change');
                        loadPeringatanData();
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menyimpan data';
                        swal("Gagal!", msg, "error");
                    },
                    complete: function () {
                        btn.prop('disabled', false).text('Simpan & Terbitkan');
                    }
                });
            });
        });
    </script>
@endpush