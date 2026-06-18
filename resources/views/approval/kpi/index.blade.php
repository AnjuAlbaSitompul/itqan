@extends('layouts.app')

@section('content')
    <div class="kpi-layout-wrapper">
        <div class="row h-100 g-4">

            <div class="col-12 col-lg-8 kpi-list-column custom-scrollbar" id="center-details-container">
                <div class="text-center text-muted mt-5 pt-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Memuat data pengajuan...</p>
                </div>
            </div>

            <div class="col-12 col-lg-4 kpi-sidebar-column d-none d-lg-flex flex-column">

                <div class="card border-0 shadow-sm rounded-4 mb-4 w-100">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0">
                        <h5 class="fw-bold mb-0">
                            <i class="fe fe-pie-chart me-2 text-primary"></i>
                            Overview Periode
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="periode-filter" class="form-label fw-semibold small text-muted mb-2">Pilih
                                Periode</label>
                            <div class="periode-select-wrap position-relative">
                                <i class="fe fe-calendar periode-select-icon"></i>
                                <select class="form-select periode-select" id="periode-filter">
                                    <option value="">Pilih Periode</option>
                                    @foreach($periods as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="periode-filter" class="form-label fw-semibold small text-muted mb-2">Status</label>
                            <div class="periode-select-wrap position-relative">
                                <i class="fe fe-activity periode-select-icon"></i>
                                <select class="form-select periode-select" id="status-filter">
                                    <option value="">Pilih Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Disetujui</option>
                                    <option value="rejected">Ditolak</option>
                                    <option value="review">Dalam Review</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <span class="text-muted">Belum Mendaftar</span>
                            <span class="badge rounded-pill bg-warning text-dark px-3 py-2" id="stat-unregistered">0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <span class="text-muted">Ditolak</span>
                            <span class="badge rounded-pill bg-danger px-3 py-2" id="stat-rejected">0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Disetujui</span>
                            <span class="badge rounded-pill bg-success px-3 py-2" id="stat-approved">0</span>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 w-100 flex-grow-1 overflow-hidden d-flex flex-column">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0">
                        <h5 class="fw-bold mb-0">
                            <i class="fe fe-list me-2 text-primary"></i>
                            Antrean Pengajuan
                        </h5>
                    </div>
                    <div class="card-body p-0 mt-3 d-flex flex-column position-relative h-100">
                        <div class="ticker-container">
                            <div class="ticker-content" id="ticker-list">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="kpi-bottom-bar custom-scrollbar shadow-lg" id="bottom-bar-list">
        <div class="bottom-chip active" id="creator-chip-all" onclick="filterByCreator('all')">
            <i class="fe fe-users me-1"></i> Semua Pengajuan
        </div>
    </div>

    <style>
        /* KUNCI SCROLL LOKAL */
        .kpi-layout-wrapper {
            height: calc(100vh - 180px);
            overflow: hidden;
        }

        .kpi-list-column {
            height: 100%;
            overflow-y: auto;
            padding-bottom: 80px;
            /* Ruang untuk bottom bar */
            padding-right: 10px;
        }

        .kpi-sidebar-column {
            height: 100%;
            overflow: hidden;
        }

        .kpi-details-scroll-area {
            max-height: 350px;
            overflow-y: auto;
            padding-right: 12px;
            margin-bottom: 16px;
        }

        /* Scrollbar minimalis */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        /* --- ADAPTASI CLASS DENGAN WARNA PRIMARY BAWAAN BOOTSTRAP --- */
        .card {
            border-radius: 24px !important;
        }

        .request-avatar {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
        }

        .modern-textarea {
            border-radius: 16px !important;
            border: 1px solid #e5e7eb !important;
            min-height: 90px;
            padding: 12px 16px;
            transition: .3s;
            background: #fff;
            resize: none;
        }

        .modern-textarea:focus {
            /* Memanfaatkan CSS Variable Primary Bawaan Bootstrap 5 */
            border-color: var(--bs-primary) !important;
            box-shadow: 0 0 0 4px rgba(var(--bs-primary-rgb, 13, 110, 253), .15) !important;
            outline: none;
        }

        .form-label {
            margin-bottom: 8px;
            color: #374151;
        }

        .periode-select-wrap {
            position: relative;
        }

        .periode-select-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            pointer-events: none;
            z-index: 2;
        }

        .periode-select {
            height: 48px;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            padding-left: 42px;
            padding-right: 16px;
            background-color: #f8fafc;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, .03);
            transition: .25s ease;
        }

        .periode-select:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 4px rgba(var(--bs-primary-rgb, 13, 110, 253), .12);
            background-color: #fff;
        }

        /* Ticker Animasi */
        .ticker-container {
            flex: 1;
            overflow: hidden;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 0 24px;
        }

        .ticker-content {
            position: absolute;
            width: calc(100% - 48px);
            animation: scroll-up 20s linear infinite;
        }

        .ticker-content:hover {
            animation-play-state: paused;
        }

        @keyframes scroll-up {
            0% {
                top: 100%;
            }

            100% {
                top: -150%;
            }
        }

        .ticker-item {
            padding: 16px;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: .2s;
        }

        .ticker-item:hover {
            border-color: var(--bs-primary);
            background: #f8fafc;
        }

        /* Bottom Bar Fixed */
        .kpi-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 75px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            overflow-x: auto;
            white-space: nowrap;
            gap: 12px;
            padding: 0 24px;
            border-top: 1px solid #f3f4f6;
            z-index: 1030;
        }

        .bottom-chip {
            padding: 10px 24px;
            border-radius: 30px;
            cursor: pointer;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            color: #6b7280;
            font-weight: 600;
            transition: .3s;
        }

        .bottom-chip:hover {
            border-color: var(--bs-primary);
            color: var(--bs-primary);
        }

        .bottom-chip.active {
            background: var(--bs-primary);
            border-color: var(--bs-primary);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(var(--bs-primary-rgb, 13, 110, 253), .25);
        }

        /* Item KPI List */
        .kpi-item-box {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 16px;
            background: #fdfdfd;
        }

        .kpi-item-box:last-child {
            margin-bottom: 0;
        }
    </style>
@endsection

@push('scripts')
    <script>
        let kpiDataList = [];

        $(document).ready(function () {
            fetchKpiList();
        });

        $('#periode-filter').on('change', function () {
            fetchKpiList();
        });

        $('#status-filter').on('change', function () {
            fetchKpiList();
        });

        function fetchKpiList() {
            let period = $('#periode-filter').val();
            let filter = $('#status-filter').val();
            let container = $('#center-details-container');

            // Tampilkan loading spinner & nonaktifkan filter sementara
            container.html(`
                    <div class="text-center text-muted mt-5 pt-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Memuat data pengajuan...</p>
                    </div>
                `);
            $('#periode-filter, #status-filter').prop('disabled', true);

            $.ajax({
                url: "{{ route('approval.kpi.list') }}",
                method: 'GET',
                data: {
                    period_id: period,
                    status: filter
                },
                success: function (response) {
                    kpiDataList = response.list || [];

                    const creatorsMap = new Map();
                    kpiDataList.forEach(item => {
                        if (item.creator && !creatorsMap.has(item.creator.id)) {
                            creatorsMap.set(item.creator.id, item.creator);
                        }
                    });

                    $('#stat-unregistered').text(response.unregistered || 0);
                    $('#stat-rejected').text(response.reject || 0);
                    $('#stat-approved').text(response.approve || 0);

                    renderCenterCards(kpiDataList);
                    renderBottomCreators(Array.from(creatorsMap.values()));
                    renderSideTickerCards(kpiDataList);
                },
                error: function (error) {
                    console.error('Error fetching data:', error);
                    container.html('<div class="alert alert-danger mt-4 text-center">Gagal memuat data.</div>');
                },
                complete: function () {
                    // Aktifkan kembali filter setelah request selesai
                    $('#periode-filter, #status-filter').prop('disabled', false);
                }
            });
        }
        function renderCenterCards(dataList) {
            const container = $('#center-details-container');
            container.empty();

            if (dataList.length === 0) {
                container.html('<div class="text-center text-muted mt-5"><i class="fe fe-inbox" style="font-size: 3rem;"></i><br><p class="mt-2">Tidak ada data persetujuan.</p></div>');
                return;
            }

            dataList.forEach(item => {
                const subordinateName = item.user_kpis?.[0]?.user?.name || 'Tidak Diketahui';
                const creatorName = item.creator?.name || 'Sistem';
                const createdAt = new Date(item.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });

                let kpiDetailsHTML = '';
                if (item.kpi_details && item.kpi_details.length > 0) {
                    item.kpi_details.forEach(detail => {
                        const master = detail.master_kpi;

                        let formulaText = [];
                        if (master.formulas && master.formulas.length > 0) {
                            master.formulas.forEach(f => {
                                formulaText.push(`${parseFloat(f.from)} - ${parseFloat(f.to)} (${parseFloat(f.progress)}%)`);
                            });
                        }
                        const formulaString = formulaText.length > 0 ? formulaText.join(', ') : '-';
                        const satuanText = master.satuan === 'percentage' ? '%' : (master.satuan === 'number' ? 'Poin' : master.satuan);

                        kpiDetailsHTML += `
                                                <div class="kpi-item-box">
                                                    <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
                                                        <div class="fw-bold text-dark" style="font-size: 1.1rem;">${master.title}</div>
                                                        <div class="text-end small">
                                                            <span class="badge bg-light text-dark border px-2 py-1 me-1">Target: ${parseFloat(master.target)}${satuanText}</span>
                                                            <span class="badge bg-light text-dark border px-2 py-1">Bobot: ${parseFloat(master.bobot)}</span>
                                                        </div>
                                                    </div>
                                                    <div class="small text-muted mb-2 lh-base">
                                                        <strong class="text-dark">Definition of Done:</strong><br> ${master.definition_of_done} <br>
                                                        <strong class="text-dark">Guard Rail:</strong><br> ${master.guard_rail || '-'}
                                                    </div>
                                                    <div class="small text-muted border-top pt-2 mt-2" style="font-size: 0.8rem;">
                                                        <strong>Skala:</strong> ${formulaString}
                                                    </div>
                                                </div>
                                            `;
                    });
                } else {
                    kpiDetailsHTML = '<div class="text-muted small py-3 text-center">Tidak ada detail KPI.</div>';
                }

                const statuscardColor = item.status === 'approved' ? 'success' : (item.status === 'rejected' ? 'danger' : 'warning');

                const card = `
                                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4" id="card-${item.id}">
                                            <div class="card-header border-0 p-4 bg-primary">
                                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="request-avatar">
                                                            <i class="fe fe-target"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="fw-bold text-white mb-1">${subordinateName}</h4>
                                                            <p class="text-white-50 mb-0">Diajukan oleh: ${creatorName}</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="fw-bold text-white mb-1">#REQ-${item.id}</div>
                                                        <span class="badge rounded-pill bg-${statuscardColor} text-dark px-3 py-1 text-uppercase">${item.status}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-body p-4">
                                                <div class="kpi-details-scroll-area custom-scrollbar">
                                                    ${kpiDetailsHTML}
                                                </div>

                                                <div class="mt-2 pt-3 border-top">
                                                    <label class="form-label fw-semibold">Catatan Evaluasi</label>
                                                    <textarea id="notes-${item.id}" class="form-control modern-textarea mb-3" value="${item.notes || ''}" placeholder="Tambahkan catatan (wajib diisi jika menolak pengajuan)..." ${item.status === 'rejected' ? 'disabled' : ''}></textarea>

                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button type="button" class="btn btn-danger rounded-pill px-4" onclick="processAction(${item.id}, 'reject')">
                                                            <i class="fe fe-slash me-1"></i> Reject
                                                        </button>
                                                        <button type="button" class="btn btn-success rounded-pill px-4" onclick="processAction(${item.id}, 'approve')">
                                                            <i class="fe fe-check me-1"></i> Approve
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                container.append(card);
            });
        }

        function renderBottomCreators(creators) {
            const container = $('#bottom-bar-list');

            // Kosongkan container terlebih dahulu
            container.empty();

            // Render ulang chip 'Semua Pengajuan' agar tidak hilang setelah di-empty
            container.append(`
                                    <div class="bottom-chip active" id="creator-chip-all" onclick="filterByCreator('all')">
                                        <i class="fe fe-users me-1"></i> Semua Pengajuan
                                    </div>
                                `);

            // Looping dan append daftar creator unik yang baru didapatkan
            creators.forEach(creator => {
                const chip = `
                                        <div class="bottom-chip" id="creator-chip-${creator.id}" onclick="filterByCreator(${creator.id})">
                                            <i class="fe fe-user me-1"></i> ${creator.name}
                                        </div>
                                    `;
                container.append(chip);
            });
        }

        function renderSideTickerCards(dataList) {
            const container = $('#ticker-list');
            container.empty();

            dataList.forEach(item => {
                const subName = item.user_kpis?.[0]?.user?.name || 'Unknown';
                const kpiCount = item.kpi_details ? item.kpi_details.length : 0;

                const tickerItem = `
                                        <div class="ticker-item" onclick="filterByApprovalId(${item.id})">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">${subName}</span>
                                                <span class="badge bg-light text-primary border">#REQ-${item.id}</span>
                                            </div>
                                            <div class="text-muted small">
                                                <i class="fe fe-layers me-1"></i> ${kpiCount} Item KPI
                                            </div>
                                        </div>
                                    `;
                container.append(tickerItem);
            });
        }

        function filterByCreator(creatorId) {
            $('.bottom-chip').removeClass('active');
            $(`#creator-chip-${creatorId}`).addClass('active');

            if (creatorId === 'all') {
                renderCenterCards(kpiDataList);
            } else {
                const filtered = kpiDataList.filter(item => item.creator && item.creator.id === creatorId);
                renderCenterCards(filtered);
            }
        }

        function filterByApprovalId(appId) {
            $('.bottom-chip').removeClass('active');
            $('#creator-chip-all').addClass('active');

            const filtered = kpiDataList.filter(x => x.id === appId);
            renderCenterCards(filtered);
        }

        function processAction(id, action) {
            const notes = $(`#notes-${id}`).val();

            if (action === 'reject' && notes.trim() === '') {
                swal({
                    type: 'warning',
                    title: 'Perhatian',
                    text: 'Catatan evaluasi wajib diisi jika Anda menolak pengajuan.'
                });
                return;
            }

            const actionText = action === 'approve' ? 'menyetujui' : 'menolak';

            swal({
                title: 'Apakah Anda Yakin?',
                text: `Anda akan ${actionText} pengajuan ini.`,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: `Ya, ${actionText}!`,
                cancelButtonText: 'Batal'
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: `/approval/kpi/${id}/${action}`,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}', notes: notes },
                        success: function (res) {
                            swal({ type: 'success', title: 'Berhasil', text: res.message });
                            fetchKpiList();
                        },
                        error: function (err) {
                            swal({ type: 'error', title: err.responseJSON?.message || 'Gagal memproses tindakan.' });
                        }
                    });
                }
            });
        }
    </script>
@endpush