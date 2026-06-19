@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4 mutasi-container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="fw-bolder mb-1"><i class="bi bi-arrow-left-right text-primary me-2"></i>Daftar Mutasi</h2>
                <p class="text-body-secondary mb-0 small">Kelola pengajuan pemindahan atau mutasi karyawan</p>
            </div>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> Tambah Mutasi
            </button>
        </div>

        <div class="mb-4 overflow-auto pb-2 filter-wrapper">
            <div class="nav nav-pills custom-filter-pills flex-nowrap" id="mutasiFilter" role="tablist">
                <button class="nav-link active rounded-pill px-4 fw-medium" data-filter="all">Semua</button>
                <button class="nav-link rounded-pill px-4 fw-medium" data-filter="pending"><span><i
                            class="bi bi-clock me-2"></i></span> Pending</button>
                <button class="nav-link rounded-pill px-4 fw-medium" data-filter="approved"><span><i
                            class="bi bi-check2 me-2"></i></span> Approved</button>
                <button class="nav-link rounded-pill px-4 fw-medium" data-filter="rejected"><span><i
                            class="bi bi-x-lg me-2"></i></span> Rejected</button>
            </div>
        </div>

        <div class="accordion modern-accordion" id="accordionMutasiList">

        </div>
    </div>

    <div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="offcanvasApprove">
        <div class="offcanvas-header bg-body-tertiary border-bottom">
            <h5 class="offcanvas-title fw-bold"><i class="bi bi-check2-circle text-success me-2"></i>Approve Mutasi</h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <div class="mb-4">
                <span class="text-body-secondary small fw-bold text-uppercase d-block mb-1">Karyawan</span>
                <h5 class="fw-bold text-primary" id="approve_employee_name">Nama Karyawan</h5>
            </div>

            <form id="approveMutasiForm" class="flex-grow-1">
                <input type="hidden" name="mutasi_id" id="approve_mutasi_id">

                <div class="mb-4">
                    <label class="form-label fw-bold text-body-secondary">Tgl Efektif Mutasi <span
                            class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-lg rounded-4 bg-body-tertiary" name="effective_date"
                        required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-body-secondary">Catatan HR (Opsional)</label>
                    <textarea class="form-control rounded-4 bg-body-tertiary" name="approval_notes" rows="4"
                        placeholder="Tulis instruksi atau catatan khusus..."></textarea>
                </div>

                <div class="mt-auto pt-3">
                    <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow-sm">
                        Konfirmasi Approval
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Transisi Halus */
        .mutasi-container {
            transition: all 0.3s ease;
        }

        /* Filter Pills Custom */
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

        /* Modern Accordion List */
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
            background-color: rgba(var(--bs-primary-rgb), 0.03);
            border-bottom: 1px solid var(--bs-border-color);
        }

        /* Body & Detail Box */
        .modern-accordion .accordion-collapse {
            background: var(--bs-body-bg);
            border-radius: 0 0 1rem 1rem;
            border: 1px solid var(--bs-border-color);
            border-top: none;
            margin-top: -10px;
            /* Merging seamlessly */
            padding-top: 10px;
        }

        .detail-box {
            border: 1px dashed var(--bs-border-color);
        }

        /* Dark Mode Enhancements (Memastikan bootstrap 5.3 native dark mode tereksekusi dengan baik) */
        [data-bs-theme="dark"] .modern-accordion .accordion-header {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        [data-bs-theme="dark"] .modern-accordion .accordion-item:hover .accordion-header {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // --- SETUP CSRF TOKEN UNTUK AJAX ---
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Fungsi untuk memuat data
            function loadMutasiData(filter = 'all') {
                $.ajax({
                    url: '{{ route("mutasi.data") }}',
                    type: 'GET',
                    success: function (res) {
                        if (res.success) {
                            let container = $('#accordionMutasiList');
                            container.empty();

                            res.data.forEach(function (item, index) {
                                if (filter !== 'all' && item.status !== filter) return;

                                let statusClass = item.status === 'approved' ? 'bg-success' : (item.status === 'rejected' ? 'bg-danger' : 'bg-warning');

                                let html = `
                                    <div class="accordion-item mutasi-item" data-status="${item.status}">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mutasi${item.id}">
                                                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between w-100 pe-3 gap-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="https://ui-avatars.com/api/?name=${item.user_name}&background=random" class="rounded-circle shadow-sm" width="48" height="48">
                                                        <div>
                                                            <h6 class="fw-bold mb-1">${item.user_name}</h6>
                                                            <div class="text-body-secondary small">
                                                                <span class="badge bg-body-secondary text-body border">${item.from_unit_name}</span>
                                                                <i class="bi bi-arrow-right text-primary"></i>
                                                                <span class="badge bg-primary bg-opacity-10 text-primary border">${item.to_unit_name}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="badge ${statusClass} bg-opacity-10 text-white border rounded-pill px-3 py-2">
                                                        ${item.status.toUpperCase()}
                                                    </span>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="mutasi${item.id}" class="accordion-collapse collapse" data-bs-parent="#accordionMutasiList">
                                            <div class="accordion-body">
                                                <div class="row g-4">
                                                    <div class="col-md-6">
                                                        <div class="detail-box p-3 h-100 rounded-4 bg-body-tertiary">
                                                            <small class="text-body-secondary">Jabatan:</small> <span class="fw-medium">${item.jabatan}</span><br>
                                                            <small class="text-body-secondary">Tgl Pengajuan:</small> <span class="fw-medium">${item.created_at_fmt}</span><br>
                                                            <p class="mb-0 mt-2"><small>Alasan: ${item.reason}</small></p>
                                                        </div>
                                                    </div>
                                                    ${item.status === 'pending' ? `
                                                    <div class="col-md-6">
                                                        <button class="btn btn-success action-approve-btn" data-id="${item.id}" data-name="${item.user_name}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasApprove">Approve</button>
                                                    </div>` : ''}
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                                container.append(html);
                            });
                        }
                    }
                });
            }

            // Panggil saat load
            loadMutasiData();

            // Update filter agar memicu reload atau filter ulang
            $('.custom-filter-pills .nav-link').on('click', function () {
                $('.custom-filter-pills .nav-link').removeClass('active');
                $(this).addClass('active');
                loadMutasiData($(this).data('filter'));
            });

            // --- LOGIKA FILTER STATUS ---
            $('.custom-filter-pills .nav-link').on('click', function () {
                $('.custom-filter-pills .nav-link').removeClass('active');
                $(this).addClass('active');

                let filterValue = $(this).data('filter');

                if (filterValue === 'all') {
                    $('.mutasi-item').slideDown(300);
                } else {
                    $('.mutasi-item').each(function () {
                        if ($(this).data('status') === filterValue) {
                            $(this).slideDown(300);
                        } else {
                            $(this).slideUp(300);
                        }
                    });
                }
            });

            // --- MENGISI DATA OFFCANVAS SAAT TOMBOL APPROVE DIKLIK ---
            $(document).on('click', '.action-approve-btn', function () {
                let mutasiId = $(this).data('id');
                let empName = $(this).data('name');

                $('#approve_mutasi_id').val(mutasiId);
                $('#approve_employee_name').text(empName);
                $('#approveMutasiForm')[0].reset();
            });

            // --- SUBMIT OFFCANVAS FORM (APPROVE) ---
            $('#approveMutasiForm').on('submit', function (e) {
                e.preventDefault();

                let submitBtn = $(this).find('button[type="submit"]');
                let btnOriginalText = submitBtn.html();
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...');

                let payload = {
                    mutasi_id: $('#approve_mutasi_id').val(),
                    action: 'approve',
                    effective_date: $('input[name="effective_date"]').val(),
                    approval_notes: $('textarea[name="approval_notes"]').val()
                };

                $.ajax({
                    url: '{{ route("mutasi.approval.hr") }}',
                    type: 'POST',
                    data: payload,
                    success: function (res) {
                        if (res.success) {
                            alert(res.message);
                            let offcanvasEl = document.getElementById('offcanvasApprove');
                            let offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasEl);
                            offcanvasInstance.hide();

                            // Reload halaman atau panggil fungsi load data AJAX di sini
                            location.reload();
                        }
                    },
                    error: function (xhr) {
                        let errMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                        alert(errMsg);
                        submitBtn.prop('disabled', false).html(btnOriginalText);
                    }
                });
            });

            // --- ACTION REJECT ---
            // Tambahkan class 'action-reject-btn' dan 'data-id="1"' pada tombol reject di HTML
            $(document).on('click', '.action-reject-btn', function () {
                let mutasiId = $(this).data('id');

                if (confirm('Apakah Anda yakin ingin menolak pengajuan mutasi ini?')) {
                    let btn = $(this);
                    let btnOriginalText = btn.html();
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                    $.ajax({
                        url: '{{ route("mutasi.approval.hr") }}',
                        type: 'POST',
                        data: {
                            mutasi_id: mutasiId,
                            action: 'reject'
                        },
                        success: function (res) {
                            if (res.success) {
                                alert(res.message);
                                location.reload();
                            }
                        },
                        error: function (xhr) {
                            let errMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                            alert(errMsg);
                            btn.prop('disabled', false).html(btnOriginalText);
                        }
                    });
                }
            });
        });
    </script>
@endpush