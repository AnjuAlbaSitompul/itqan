@extends('layouts.app')

@section('content')
    <div class="container mt-4 mb-5 max-w-4xl">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Penugasan KPI Tim</h4>
                <p class="text-muted mb-0">Kelola target dan metrik KPI untuk bawahan Anda.</p>
            </div>
        </div>

        @if(!$kpiperiod)
            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
                <div>
                    <strong>Periode KPI Belum Dibuka!</strong><br>
                    Saat ini tidak ada periode KPI yang berstatus "Open". Anda belum bisa melakukan penugasan.
                </div>
            </div>
        @else
            @php
                $isExpired = false;
                if (isset($kpiperiod->end_date)) {
                    $isExpired = \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($kpiperiod->end_date));
                }
            @endphp

            @if($isExpired)
                <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center" role="alert">
                    <i class="bi bi-hourglass-bottom fs-4 me-3 text-danger"></i>
                    <div>
                        <strong>Waktu Pengisian Telah Habis!</strong><br>
                        Batas waktu untuk periode <b>{{ $kpiperiod->name }}</b> telah berlalu.
                    </div>
                </div>
            @elseif($isTeamSet)
                <div class="alert alert-success border-0 shadow-sm d-flex justify-content-between align-items-center" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                        <div>
                            <strong>Penugasan Selesai!</strong><br>
                            Anda sudah mengatur KPI tim untuk periode <b>{{ $kpiperiod->name }}</b>.
                        </div>
                    </div>
                    <a href="#" class="btn btn-sm btn-success fw-semibold">Lihat Data</a>
                </div>
            @else
                <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-info-circle-fill fs-4 me-3 text-primary"></i>
                    <div>
                        <strong>Periode Aktif: {{ $kpiperiod->name }}</strong><br>
                        Silakan buat penugasan sebelum batas waktu: <span
                            class="text-danger fw-semibold">{{ \Carbon\Carbon::parse($kpiperiod->end_date ?? now())->format('d M Y, H:i') }}</span>.
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form id="assignmentForm" action="#" method="POST">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-12 mb-4">
                                    <label class="form-label fw-semibold">Pilih Anggota Tim (Bawahan) <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select select2-multiple" id="team_members" name="team_members[]"
                                        multiple="multiple" required>
                                        @foreach($subordinate ?? [] as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->role->name ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <div class="d-flex justify-content-between align-items-end mb-3 pb-2 border-bottom">
                                        <label class="form-label fw-semibold mb-0">KPI Master Terpilih <span
                                                class="text-danger">*</span></label>
                                        <div class="btn-group shadow-sm">
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modalKpiList" id="btnOpenKpiModal">
                                                <i class="bi bi-search"></i> Cari & Pilih KPI
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="offcanvas"
                                                data-bs-target="#offcanvasKpiMaster">
                                                <i class="bi bi-plus-lg"></i> Buat Baru
                                            </button>
                                        </div>
                                    </div>

                                    <div id="selected-kpis-container" class="row g-3">
                                        <div class="col-12 text-center p-5 border border-dashed rounded bg-light empty-state">
                                            <i class="bi bi-clipboard-data text-muted fs-1 mb-2 d-block"></i>
                                            <h6 class="text-muted fw-normal">Belum ada metrik KPI yang dipilih.</h6>
                                            <small class="text-muted">Klik "Cari & Pilih KPI" atau "Buat Baru" untuk
                                                menambahkan.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <label class="form-label fw-semibold">Atasan Pengecek (Approver) <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select select2-single" id="approver_id" name="approver_id" required>
                                        <option value="" disabled selected>Cari atasan...</option>
                                        @foreach($superior ?? [] as $approver)
                                            <option value="{{ $approver->id }}">{{ $approver->name }}
                                                ({{ $approver->role->name ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Catatan Tambahan (Opsional)</label>
                                    <textarea class="form-control rounded-3" name="notes" rows="2"
                                        placeholder="Tuliskan pesan untuk tim atau approver..."></textarea>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary py-2 fw-semibold">Kirim Penugasan & Minta
                                    Approval</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <div class="modal fade" id="modalKpiList" tabindex="-1" aria-labelledby="modalKpiListLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="modalKpiListLabel">Pilih Master KPI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light bg-opacity-50">
                    <div id="available-kpis-list" class="row g-3">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end shadow" tabindex="-1" id="offcanvasKpiMaster"
        aria-labelledby="offcanvasKpiMasterLabel">
        <div class="offcanvas-header border-bottom bg-light">
            <h5 class="offcanvas-title fw-bold" id="offcanvasKpiMasterLabel">Buat Master KPI Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form id="formCreateKpiMaster">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-medium small text-muted">Judul KPI</label>
                    <input type="text" class="form-control" name="title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium small text-muted">Definition of Done</label>
                    <textarea class="form-control" name="definition_of_done" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium small text-muted">Guard Rail</label>
                    <textarea class="form-control" name="guard_rail" rows="2"></textarea>
                </div>
                <div class="row mb-4">
                    <div class="col-4">
                        <label class="form-label fw-medium small text-muted">Satuan</label>
                        <select class="form-select" name="satuan">
                            <option value="percentage">% (Persentase)</option>
                            <option value="number">Angka</option>
                            <option value="currency">Mata Uang</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-medium small text-muted">Bobot</label>
                        <input type="number" step="0.01" class="form-control" name="bobot" value="0">
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-medium small text-muted">Target</label>
                        <input type="number" step="0.01" class="form-control" name="target" value="0">
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Formula (Penilaian)</h6>
                <div id="formula-container-offcanvas">
                    <div class="row g-2 mb-2 formula-row">
                        <div class="col-5">
                            <input type="number" step="0.01" class="form-control" name="formulas[0][from]"
                                placeholder="From">
                        </div>
                        <div class="col-5">
                            <input type="number" step="0.01" class="form-control" name="formulas[0][to]" placeholder="To">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-outline-primary w-100 add-formula-btn">+</button>
                        </div>
                    </div>
                </div>

                <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-dark" id="btnSaveMaster">Simpan KPI Master</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .select2-container .select2-selection--single,
        .select2-container .select2-selection--multiple {
            min-height: 42px;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding-top: 2px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 4px 10px;
            margin-top: 6px;
            font-size: 0.85rem;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 8px;
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            padding-right: 8px;
        }

        .offcanvas-end {
            width: 500px;
        }

        @media (max-width: 576px) {
            .offcanvas-end {
                width: 100%;
            }
        }

        /* Utility untuk custom border */
        .border-dashed {
            border-style: dashed !important;
            border-width: 2px !important;
        }

        /* Animasi penghapusan kartu */
        .fade-out {
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.3s ease;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // 1. Inisialisasi Select2 untuk user/approver
            $('#team_members').select2({ placeholder: "Pilih anggota tim...", allowClear: true, width: '100%' });
            $('#approver_id').select2({ width: '100%' });

            // Array untuk melacak ID KPI yang sudah dipilih
            let selectedKpis = [];

            // Helper: Render HTML Badge Formula
            function renderFormulaBadges(formulas) {
                if (!formulas || formulas.length === 0) return '<span class="text-muted fst-italic small">Belum ada formula</span>';
                return formulas.map(f =>
                    // Perubahan di baris ini: Menggunakan background putih, teks gelap, border tegas, dan bayangan kecil
                    `<span class="badge bg-white text-dark border border-secondary px-2 py-1 me-1 mb-1 fw-medium shadow-sm" style="font-size: 0.75rem;">
                                                            ${parseFloat(f.from)} <span class="text-muted mx-1">s/d</span> ${parseFloat(f.to)}
                                                        </span>`
                ).join('');

            }

            // Helper: Tambahkan KPI ke dalam Form UI Utama
            function addKpiToSelection(kpi) {
                // Jangan tambah jika sudah ada
                if (selectedKpis.includes(kpi.id)) return;

                selectedKpis.push(kpi.id);
                $('.empty-state').hide(); // Sembunyikan state kosong

                // Bikin form input hidden array agar backend bisa memprosesnya (name="kpi_masters[]")
                let htmlCard = `
                                                            <div class="col-md-6 selected-kpi-item" id="selected-kpi-${kpi.id}">
                                                                <div class="card border-primary border-opacity-50 shadow-sm h-100 position-relative">
                                                                    <input type="hidden" name="kpi_masters[]" value="${kpi.id}">

                                                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle remove-kpi-btn shadow-sm" data-id="${kpi.id}" style="width: 32px; height: 32px; padding: 0;">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>

                                                                    <div class="card-body p-3">
                                                                        <h6 class="fw-bold text-dark pe-4 lh-base mb-2" style="font-size:0.95rem;">${kpi.title}</h6>
                                                                        <div class="d-flex flex-wrap gap-3 mb-3 small">
                                                                            <span class="text-primary fw-medium"><i class="bi bi-bullseye"></i> Target: ${kpi.target} ${kpi.satuan}</span>
                                                                            <span class="text-success fw-medium"><i class="bi bi-percent"></i> Bobot: ${kpi.bobot ?? 0}</span>
                                                                        </div>
                                                                        <div class="pt-2 border-top">
                                                                            <div class="text-muted mb-1" style="font-size: 0.7rem; font-weight: 600;">FORMULA:</div>
                                                                            <div class="d-flex flex-wrap">${renderFormulaBadges(kpi.formulas)}</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `;

                // Animasi masuk
                let $newCard = $(htmlCard).hide();
                $('#selected-kpis-container').append($newCard);
                $newCard.fadeIn('fast');
            }

            // 2. FUNGSI LOAD DATA AJAX KE DALAM MODAL
            function loadModalKpis() {
                let container = $('#available-kpis-list');
                container.html('<div class="col-12 text-center p-4"><div class="spinner-border text-primary" role="status"></div><div class="mt-2 text-muted small">Memuat data...</div></div>');

                $.ajax({
                    url: '/kpi/master/me',
                    type: 'GET',
                    success: function (response) {
                        let html = '';
                        if (response.data && response.data.length > 0) {
                            response.data.forEach(function (kpi) {
                                let isSelected = selectedKpis.includes(kpi.id);
                                let btnText = isSelected ? '<i class="bi bi-check-lg"></i> Terpilih' : '<i class="bi bi-plus-lg"></i> Pilih';
                                let btnClass = isSelected ? 'btn-success disabled' : 'btn-outline-primary btn-select-kpi';

                                // Escape JSON data untuk disimpan di attribute button
                                let kpiJson = JSON.stringify(kpi).replace(/'/g, "&apos;");

                                html += `
                                                                            <div class="col-md-6">
                                                                                <div class="card h-100 border shadow-sm">
                                                                                    <div class="card-body p-3">
                                                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                                                            <h6 class="fw-bold mb-0 lh-base pe-2" style="font-size:0.95rem;">${kpi.title}</h6>
                                                                                            <button type="button" class="btn btn-sm ${btnClass}" id="modal-btn-${kpi.id}" data-kpi='${kpiJson}'>
                                                                                                ${btnText}
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="small text-muted mb-2">
                                                                                            <span class="me-2">Target: <strong class="text-dark">${kpi.target} ${kpi.satuan}</strong></span>
                                                                                            <span>Bobot: <strong class="text-dark">${kpi.bobot ?? 0}</strong></span>
                                                                                        </div>
                                                                                        <div class="mt-2 pt-2 border-top">
                                                                                            ${renderFormulaBadges(kpi.formulas)}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        `;
                            });
                        } else {
                            html = '<div class="col-12 text-center p-4 text-muted">Anda belum memiliki Master KPI. Silakan Buat Baru.</div>';
                        }
                        container.html(html);
                    },
                    error: function () {
                        container.html('<div class="col-12 text-center p-4 text-danger">Gagal memuat data.</div>');
                    }
                });
            }

            // Saat Modal dibuka, load/refresh data
            $('#btnOpenKpiModal').on('click', function () {
                loadModalKpis();
            });

            // 3. EVENT: Klik tombol "Pilih" di dalam Modal
            $(document).on('click', '.btn-select-kpi', function () {
                let btn = $(this);
                let kpiData = JSON.parse(btn.attr('data-kpi'));

                // Ubah status tombol di modal menjadi hijau "Terpilih"
                btn.removeClass('btn-outline-primary btn-select-kpi').addClass('btn-success disabled').html('<i class="bi bi-check-lg"></i> Terpilih');

                // Tambahkan ke UI Utama
                addKpiToSelection(kpiData);
            });

            // 4. EVENT: Klik tombol "Trash / Hapus" pada Kartu Terpilih di UI Utama
            $(document).on('click', '.remove-kpi-btn', function () {
                let idToRemove = $(this).data('id');

                // Hapus dari array tracking
                selectedKpis = selectedKpis.filter(id => id !== idToRemove);

                // Animasi Hapus DOM
                let card = $(`#selected-kpi-${idToRemove}`);
                card.addClass('fade-out');
                setTimeout(() => {
                    card.remove();
                    // Jika habis, tampilkan empty state lagi
                    if (selectedKpis.length === 0) {
                        $('.empty-state').fadeIn();
                    }
                }, 300);
            });

            // ==========================================
            // LOGIKA FORM OFFCANVAS (BUAT BARU) Tetap Sama
            // ==========================================
            let fIndex = 0;
            $(document).on('click', '.add-formula-btn', function () {
                fIndex++;
                let newRow = `
                                                            <div class="row g-2 mb-2 formula-row" style="display:none;">
                                                                <div class="col-5"><input type="number" step="0.01" class="form-control" name="formulas[${fIndex}][from]" placeholder="From"></div>
                                                                <div class="col-5"><input type="number" step="0.01" class="form-control" name="formulas[${fIndex}][to]" placeholder="To"></div>
                                                                <div class="col-2"><button type="button" class="btn btn-outline-danger w-100 remove-formula-btn"><i class="bi bi-trash"></i> X</button></div>
                                                            </div>`;
                $('#formula-container-offcanvas').append(newRow);
                $('#formula-container-offcanvas .formula-row:last').slideDown('fast');
            });

            $(document).on('click', '.remove-formula-btn', function () {
                $(this).closest('.formula-row').slideUp('fast', function () { $(this).remove(); });
            });

            $('#formCreateKpiMaster').on('submit', function (e) {
                e.preventDefault();
                let btn = $('#btnSaveMaster');
                let formData = $(this).serialize();
                btn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: '/kpi/master/me',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        // Tutup Offcanvas
                        let bsOffcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasKpiMaster'));
                        bsOffcanvas.hide();

                        // Reset Form
                        $('#formCreateKpiMaster')[0].reset();
                        btn.prop('disabled', false).text('Simpan KPI Master');

                        // LANGSUNG MASUKKAN KPI BARU KE DAFTAR TERPILIH DI LAYAR
                        addKpiToSelection(response.data);

                        alert('KPI Master berhasil dibuat dan otomatis ditambahkan ke daftar!');
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).text('Simpan KPI Master');
                        alert('Terjadi kesalahan saat menyimpan KPI.');
                    }
                });
            });

            // ==========================================
            // SUBMIT FORM PENUGASAN UTAMA (AJAX)
            // ==========================================
            $('#assignmentForm').on('submit', function (e) {
                e.preventDefault();

                // Validasi manual: Pastikan ada KPI Card yang dipilih (karena hidden input diletakkan di dalam card)
                if ($('input[name="kpi_masters[]"]').length === 0) {
                    alert('Silakan pilih minimal 1 Master KPI terlebih dahulu!');
                    return;
                }

                let form = $(this);
                let btn = form.find('button[type="submit"]');
                let originalText = btn.html();

                // Ubah status tombol menjadi loading
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengirim...');

                $.ajax({
                    url: '/team/kpi/assign', // Sesuaikan dengan route POST Anda di web.php
                    type: 'POST',
                    data: form.serialize(),
                    success: function (response) {
                        alert(response.message);

                        // Redirect ke halaman index setelah sukses
                        // Ganti '/team/kpi' dengan URL halaman dashboard tim Anda
                        window.location.href = '/team/kpi';
                    },
                    error: function (xhr) {
                        // Kembalikan status tombol jika gagal
                        btn.prop('disabled', false).html(originalText);

                        let res = xhr.responseJSON;
                        if (res && res.message) {
                            alert(res.message);
                        } else {
                            alert('Terjadi kesalahan pada server saat mengirim penugasan.');
                        }
                    }
                });
            });
        });
    </script>
@endpush