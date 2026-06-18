@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4 px-3 px-md-4">
        <div class="row g-4">

            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 custom-card">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                        <h5 class="mb-0 fw-bold text-primary" id="formTitle">Tambah Jadwal Sholat</h5>
                        <p class="text-muted small mt-1">Masukkan data jadwal baru atau update data.</p>
                    </div>

                    <div class="card-body px-4 pb-4">
                        <div id="alert-container" class="d-none alert rounded-3 py-2" role="alert"></div>

                        <form id="prayerScheduleForm">
                            @csrf
                            <input type="hidden" name="id" id="schedule_id">

                            <div class="mb-3">
                                <label for="prayer_name" class="form-label fw-semibold text-secondary">Nama Sholat</label>
                                <select class="form-control custom-input" id="prayer_name" name="prayer_name" required>
                                    <option value=""></option>
                                    <option value="Subuh">Subuh</option>
                                    <option value="Tahajjud">Tahajjud</option>
                                </select>
                            </div>

                            <div class="row mb-4 g-3">
                                <div class="col-6">
                                    <label for="start_time" class="form-label fw-semibold text-secondary">Waktu
                                        Mulai</label>
                                    <input type="time" class="form-control custom-input" id="start_time" name="start_time"
                                        required>
                                </div>
                                <div class="col-6">
                                    <label for="end_time" class="form-label fw-semibold text-secondary">Waktu
                                        Selesai</label>
                                    <input type="time" class="form-control custom-input" id="end_time" name="end_time"
                                        required>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100 custom-btn py-2" id="submitBtn">
                                    <span class="btn-text fw-bold">Simpan Data</span>
                                    <span class="spinner-border spinner-border-sm d-none ms-2" role="status"
                                        aria-hidden="true" id="submitSpinner"></span>
                                </button>
                                <button type="button" class="btn btn-light custom-btn py-2 d-none"
                                    id="cancelEditBtn">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 custom-card h-100">
                    <div
                        class="card-header bg-white border-bottom-0 pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">Daftar Jadwal Sholat</h5>
                            <p class="text-muted small mt-1 mb-0">List data sholat yang telah tersimpan.</p>
                        </div>
                        <button class="btn btn-sm btn-light text-primary rounded-pill px-3 fw-semibold" id="refreshListBtn">
                            Refresh
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <div class="scrollable-list-wrapper">
                            <div class="text-center py-5" id="empty-state">
                                <div class="text-muted mb-2" style="font-size: 2rem;">📿</div>
                                <h6 class="text-secondary fw-semibold">Belum ada data jadwal sholat</h6>
                                <p class="text-muted small">Tambahkan jadwal baru melalui form di sebelah.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <style>
        body {
            background-color: #f4f6f9;
        }

        .custom-card {
            overflow: hidden;
        }

        .custom-input {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .custom-input:focus {
            background-color: #ffffff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        /* Menyelaraskan Select2 agar serasi dengan gaya bootstrap kustom */
        .select2-container--bootstrap-5 .select2-selection {
            background-color: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
            border-radius: 0.5rem !important;
            padding: 0.375rem 0.75rem !important;
            height: auto !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            padding-left: 0 !important;
        }

        .custom-btn {
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .custom-btn:hover {
            transform: translateY(-1px);
        }

        /* Scrollable List */
        .scrollable-list-wrapper {
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .scrollable-list-wrapper::-webkit-scrollbar {
            width: 6px;
        }

        .scrollable-list-wrapper::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollable-list-wrapper::-webkit-scrollbar-thumb {
            background: #ced4da;
            border-radius: 10px;
        }

        .scrollable-list-wrapper::-webkit-scrollbar-thumb:hover {
            background: #adb5bd;
        }

        .list-item-hover {}

        .list-item-hover:hover {}

        .transition-all {
            transition: all 0.2s ease-in-out;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {

            // Variabel global untuk menampung data dari database
            let savedSchedules = [];

            let form = $('#prayerScheduleForm');
            let submitBtn = $('#submitBtn');
            let btnText = $('.btn-text');
            let cancelEditBtn = $('#cancelEditBtn');
            let alertContainer = $('#alert-container');
            let formTitle = $('#formTitle');

            // Initialize Select2 dengan Fitur Tags (bisa input manual)
            $('#prayer_name').select2({
                theme: 'bootstrap-5',
                tags: true,
                placeholder: 'Pilih atau ketik nama sholat...',
                allowClear: true,
                width: '100%'
            });

            // Panggil fungsi untuk mengambil data pertama kali
            loadItem();

            // ==== DETEKSI PERUBAHAN DI SELECT2 (AUTO CEK DATA EXIST ATAU TIDAK) ====
            $('#prayer_name').on('change', function () {
                let selectedValue = $(this).val();

                if (!selectedValue) {
                    resetFormWithoutSelect2();
                    return;
                }

                // Cari apakah nama sholat yang dipilih/diketik sudah ada di database array
                let match = savedSchedules.find(item => item.prayer_name.toLowerCase() === selectedValue.toLowerCase());

                if (match) {
                    // JIKA DATA ADA -> OTOMATIS MODE UPDATE
                    $('#schedule_id').val(match.id);
                    $('#start_time').val(match.start_time ? match.start_time.substring(0, 5) : '');
                    $('#end_time').val(match.end_time ? match.end_time.substring(0, 5) : '');

                    formTitle.text('Update Jadwal: ' + match.prayer_name);
                    cancelEditBtn.removeClass('d-none');
                } else {
                    // JIKA TIDAK ADA -> MODE INSERT (Kosongkan ID dan form waktu agar diisi manual)
                    $('#schedule_id').val('');
                    $('#start_time').val('');
                    $('#end_time').val('');

                    formTitle.text('Tambah Jadwal Sholat');
                    cancelEditBtn.addClass('d-none');
                }
            });

            // ==== SUBMIT FORM ====
            form.on('submit', function (e) {
                e.preventDefault();

                submitBtn.prop('disabled', true);
                $('#submitSpinner').removeClass('d-none');
                btnText.text('Menyimpan...');
                alertContainer.addClass('d-none').removeClass('alert-success alert-danger');

                let url = $('#schedule_id').val() ? '/idp/shalat-schedule/' + $('#schedule_id').val() : '/idp/shalat-schedule';
                let method = $('#schedule_id').val() ? 'PUT' : 'POST';
                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        alertContainer.removeClass('d-none').addClass('alert-success');
                        alertContainer.html(`Berhasil: ${response.message || 'Data disimpan!'}`);

                        resetForm();
                        loadItem(); // Refresh daftar data secara langsung tanpa reload halaman
                    },
                    error: function (xhr) {
                        alertContainer.removeClass('d-none').addClass('alert-danger');
                        let errorMsg = 'Terjadi kesalahan.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).map(err => `<div>- ${err}</div>`).join('');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        alertContainer.html(`<strong>Gagal!</strong> ${errorMsg}`);
                    },
                    complete: function () {
                        submitBtn.prop('disabled', false);
                        $('#submitSpinner').addClass('d-none');
                        btnText.text('Simpan Data');
                    }
                });
            });

            // ==== TOMBOL EDIT DI KLIK PADA LIST ====
            $(document).on('click', '.edit-btn', function () {
                let btn = $(this);
                let name = btn.data('name');

                // Set value di Select2 (jika tidak ada di opsi default, Select2 otomatis menambahkan sebagai tag baru)
                if ($('#prayer_name').find("option[value='" + name + "']").length === 0) {
                    let newOption = new Option(name, name, true, true);
                    $('#prayer_name').append(newOption).trigger('change');
                } else {
                    $('#prayer_name').val(name).trigger('change');
                }

                // Auto-scroll ke atas pada mobile view
                if ($(window).width() < 992) {
                    $('html, body').animate({ scrollTop: form.offset().top - 20 }, 300);
                }
            });

            // ==== TOMBOL BATAL EDIT ====
            cancelEditBtn.on('click', function () {
                resetForm();
            });

            function resetForm() {
                form[0].reset();
                $('#prayer_name').val(null).trigger('change'); // Reset pilihan Select2
                $('#schedule_id').val('');
                formTitle.text('Tambah Jadwal Sholat');
                cancelEditBtn.addClass('d-none');
            }

            function resetFormWithoutSelect2() {
                $('#schedule_id').val('');
                $('#start_time').val('');
                $('#end_time').val('');
                formTitle.text('Tambah Jadwal Sholat');
                cancelEditBtn.addClass('d-none');
            }

            // Fungsi render item list data sholat
            function renderItem(item, index) {
                let start = item.start_time ? item.start_time.substring(0, 5) : '';
                let end = item.end_time ? item.end_time.substring(0, 5) : '';

                return `
                                                                    <div class="d-flex align-items-center justify-content-between p-3 px-4 border-bottom list-item-hover transition-all" id="row-${item.id}">
                                                                        <div class="d-flex align-items-center gap-3">
                                                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center text-white justify-content-center fw-bold flex-shrink-0"
                                                                                style="width: 45px; height: 45px; font-size: 1.1rem;">
                                                                                ${index + 1}
                                                                            </div>

                                                                            <div>
                                                                                <h6 class="mb-1 fw-bold text-dark">${item.prayer_name}</h6>
                                                                                <div class="d-flex align-items-center gap-2 small">
                                                                                    <span class="badge bg-success text-white bg-opacity-10 px-2 py-1 rounded-pill">
                                                                                        Mulai: ${start}
                                                                                    </span>
                                                                                    <span class="text-muted">-</span>
                                                                                    <span class="badge bg-danger bg-opacity-10 text-white px-2 py-1 rounded-pill">
                                                                                        Selesai: ${end}
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div>
                                                                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3 edit-btn"
                                                                                data-id="${item.id}" data-name="${item.prayer_name}"
                                                                                data-start="${start}" data-end="${end}">
                                                                                Edit
                                                                            </button>
                                                                        </div>
                                                                    </div>`;
            }

            // Fungsi memuat item dari database
            function loadItem() {
                $.get('/idp/shalat-schedule/list', function (data) {
                    savedSchedules = data; // Simpan ke penampung data global

                    let wrapper = $('.scrollable-list-wrapper');
                    wrapper.empty();

                    // Memperbarui opsi Select2 secara dinamis berdasarkan data database,
                    // namun tetap memastikan opsi 'Subuh' & 'Tahajjud' selalu tersedia paling atas
                    let select = $('#prayer_name');
                    let currentSelection = select.val();

                    select.empty().append('<option value=""></option>');

                    let defaultOptions = ['Subuh', 'Tahajjud'];
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            if (!defaultOptions.includes(item.prayer_name)) {
                                defaultOptions.push(item.prayer_name);
                            }
                        });
                    }

                    defaultOptions.forEach(name => {
                        select.append(new Option(name, name, false, false));
                    });

                    // Kembalikan seleksi jika sebelumnya ada yang terpilih
                    if (currentSelection) {
                        select.val(currentSelection);
                    }
                    select.trigger('change.select2');

                    if (data && data.length > 0) {
                        data.forEach((item, index) => {
                            wrapper.append(renderItem(item, index));
                        });
                    } else {
                        wrapper.append(`
                                                                                <div class="text-center py-5" id="empty-state">
                                                                                    <div class="text-muted mb-2" style="font-size: 2rem;">📿</div>
                                                                                    <h6 class="text-secondary fw-semibold">Belum ada data jadwal sholat</h6>
                                                                                    <p class="text-muted small">Tambahkan jadwal baru melalui form di sebelah.</p>
                                                                                </div>
                                                                            `);
                    }
                }).fail(function () {
                    console.error("Gagal mengambil data jadwal sholat.");
                });
            }

            // ==== ACTION REFRESH LIST ====
            $('#refreshListBtn').on('click', function () {
                let btn = $(this);
                btn.prop('disabled', true).text('Loading...');
                loadItem();
                setTimeout(() => {
                    btn.prop('disabled', false).text('Refresh');
                }, 500);
            });
        });
    </script>
@endpush