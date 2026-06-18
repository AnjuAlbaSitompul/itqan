@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4 dashboard-container">
        <div class="row mb-4 align-items-center">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
                <h2 class="fw-bold mb-0 dashboard-title">Monitoring Aktivitas Tim</h2>
                <p class="text-muted mb-0">Pantau progres jogging, ibadah, dan literasi bawahan Anda.</p>
            </div>

            <div class="col-12 col-md-6">
                <div class="d-flex justify-content-md-end">
                    <div class="input-group dashboard-filter shadow-sm rounded-pill overflow-hidden">
                        <span class="input-group-text bg-transparent border-0 ps-4">
                            <i class="bi bi-person-lines-fill text-primary"></i>
                        </span>
                        <select id="user-filter-select" name="user_id"
                            class="form-select border-0 bg-transparent shadow-none">
                            <option value="">-- Pilih Bawahan --</option>
                            @foreach($subordinates as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->profile->position ?? 'Staff' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div id="data-container">
            <div id="empty-state" class="card glass-card border-0 text-center py-5 mt-4">
                <div class="card-body py-5">
                    <i class="bi bi-people-fill text-muted opacity-50" style="font-size: 5rem;"></i>
                    <h4 class="mt-4 text-muted fw-bold">Pilih Bawahan</h4>
                    <p class="text-muted mb-0">Silakan pilih bawahan pada filter di atas untuk melihat detail aktivitasnya.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            transition: background-color 0.3s ease;
        }

        .dashboard-filter {
            background-color: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            max-width: 350px;
            width: 100%;
        }

        .dashboard-filter select:focus {
            box-shadow: none;
            cursor: pointer;
        }

        .glass-card {
            background: var(--bs-body-bg);
            border-radius: 1.25rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--bs-border-color-translucent);
        }

        .custom-pills {
            gap: 0.5rem;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .custom-pills::-webkit-scrollbar {
            display: none;
        }

        .custom-pills .nav-link {
            border-radius: 50px;
            color: var(--bs-secondary-color);
            background: var(--bs-tertiary-bg);
            border: 1px solid var(--bs-border-color);
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .custom-pills .nav-link:hover {
            background: var(--bs-secondary-bg);
        }

        .custom-pills .nav-link.active {
            background: var(--bs-primary);
            color: #fff;
            border-color: var(--bs-primary);
            box-shadow: 0 4px 15px rgba(var(--bs-primary-rgb), 0.4);
        }

        .table-light-custom th {
            background-color: var(--bs-tertiary-bg);
            color: var(--bs-secondary-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.5rem;
        }

        .custom-accordion .accordion-button:not(.collapsed) {
            background-color: var(--bs-primary-bg-subtle);
            color: var(--bs-primary-text-emphasis);
            box-shadow: none;
        }

        .custom-accordion .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="dark"] .glass-card {
            background: var(--bs-gray-900);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        }

        [data-bs-theme="dark"] .dashboard-filter {
            background: var(--bs-gray-900);
        }

        [data-bs-theme="dark"] .custom-pills .nav-link {
            background: var(--bs-gray-800);
            border-color: var(--bs-gray-700);
        }

        [data-bs-theme="dark"] .table-light-custom th {
            background-color: var(--bs-gray-800);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // Setup CSRF Token
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // 1. AJAX LOAD DATA SAAT DROPDOWN BERUBAH
            $('#user-filter-select').change(function () {
                let userId = $(this).val();
                let container = $('#data-container');

                // Template Empty State
                const emptyState = `
                                <div id="empty-state" class="card glass-card border-0 text-center py-5 mt-4">
                                    <div class="card-body py-5">
                                        <i class="bi bi-people-fill text-muted opacity-50" style="font-size: 5rem;"></i>
                                        <h4 class="mt-4 text-muted fw-bold">Pilih Bawahan</h4>
                                        <p class="text-muted mb-0">Silakan pilih bawahan pada filter di atas untuk melihat detail aktivitasnya.</p>
                                    </div>
                                </div>`;

                if (!userId) {
                    container.html(emptyState);
                    return;
                }

                // Tampilkan loading animation
                container.html(`
                                <div class="d-flex justify-content-center py-5 my-5">
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            `);

                // Fetch Data
                $.ajax({
                    url: "{{ route('team.idp.get-data') }}",
                    type: "GET",
                    data: { user_id: userId },
                    success: function (response) {
                        if (response.html) {
                            container.html(response.html);
                        } else {
                            container.html('<div class="alert alert-warning rounded-pill mt-4">Data bawahan tidak ditemukan.</div>');
                        }
                    },
                    error: function () {
                        container.html('<div class="alert alert-danger rounded-pill mt-4">Gagal memuat data dari server. Silakan coba lagi.</div>');
                    }
                });
            });

            // 2. AJAX APPROVAL BUKU
            // 2. AJAX APPROVAL BUKU
            $(document).on('click', '.btn-action-book', function (e) {
                e.preventDefault();

                let button = $(this);
                let proposalId = button.data('id');
                let actionStatus = button.data('status');
                let actionText = actionStatus === 'approved' ? 'menyetujui' : 'menolak';

                // AMBIL VALUE DARI INPUT TANGGAL
                let dueDateInput = $(`#due-date-${proposalId}`);
                let dueDateVal = dueDateInput.val();

                // Validasi Client-Side: Wajib isi tanggal jika disetujui
                if (actionStatus === 'approved' && !dueDateVal) {
                    alert('Silakan pilih Tenggat Waktu (Due Date) terlebih dahulu sebelum menyetujui proposal buku.');
                    dueDateInput.focus();
                    return;
                }

                if (!confirm(`Apakah Anda yakin ingin ${actionText} proposal buku ini?`)) {
                    return;
                }

                let actionContainer = $(`#action-container-${proposalId}`);
                let statusBadge = $(`#badge-book-${proposalId}`);

                // Nonaktifkan tombol saat loading
                button.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);
                button.siblings().prop('disabled', true);
                dueDateInput.prop('disabled', true);

                $.ajax({
                    url: `/team/idp/book-proposal/${proposalId}/approve`,
                    type: 'POST',
                    data: {
                        status: actionStatus,
                        due_date: dueDateVal // KIRIM DATA TANGGAL KE SERVER
                    },
                    success: function (response) {
                        if (response.success) {
                            // Hilangkan kotak input
                            actionContainer.slideUp('fast', function () { $(this).remove(); });

                            // Update badge
                            statusBadge.text(response.new_status);
                            statusBadge.removeClass('bg-warning text-dark bg-success bg-danger');

                            if (actionStatus === 'approved') {
                                statusBadge.addClass('bg-success text-white');
                            } else {
                                statusBadge.addClass('bg-danger text-white');
                            }

                            // Jika Anda mau, di sini Anda bisa me-reload data AJAX 
                            // $('#user-filter-select').trigger('change');
                        }
                    },
                    error: function (xhr) {
                        let errorMsg = 'Terjadi kesalahan saat memproses data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            // Jika validasi Laravel (422) gagal
                            if (xhr.responseJSON.errors && xhr.responseJSON.errors.due_date) {
                                errorMsg = xhr.responseJSON.errors.due_date[0];
                            } else {
                                errorMsg = xhr.responseJSON.message;
                            }
                        }
                        alert(errorMsg);

                        // Kembalikan tombol jika gagal
                        button.text(actionStatus === 'approved' ? 'Setujui' : 'Tolak').prop('disabled', false);
                        button.siblings().prop('disabled', false);
                        dueDateInput.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush