<script>
    $(document).ready(function () {
        let reviewMode = false;
        let selectedKpis = [];

        // Inisialisasi Select2
        $('#team_members').select2({ placeholder: "Pilih anggota tim...", allowClear: true, width: '100%' });
        $('#approver_id').select2({ width: '100%' });

        // Tambahkan inisialisasi Select2 untuk Filter Periode
        $('#filter-period').select2({
            placeholder: "Cari Periode...",
            allowClear: true,
            width: '100%'
        });

        // Karena Select2 menyembunyikan elemen asli, event on change perlu disesuaikan:
        $('#filter-period').on('select2:select select2:unselect', function (e) {
            loadApprovalList();
        });
        $('#filter-status').on('change', function () {
            loadApprovalList();
        });

        function renderFormulaBadges(formulas) {
            if (!formulas || formulas.length === 0) return '<span class="text-muted fst-italic small">Belum ada formula</span>';
            return formulas.map(f =>
                `<span class="badge bg-white text-dark border border-success px-2 py-1 me-1 mb-1 fw-medium shadow-sm" style="font-size: 0.75rem;">
                                                        ${parseFloat(f.from)} <span class="text-muted mx-1">s/d</span> ${parseFloat(f.to)}
                                                    </span>`
            ).join('');
        }

        function renderActionButtons(item) {
            // 1. Jika Approved: Terkunci (Tidak bisa edit/hapus)
            if (item.status === 'approved') {
                return `
                            <button class="btn btn-sm btn-light" disabled title="Terkunci">
                                <i class="fe fe-lock"></i>
                            </button>
                        `;
            }

            // 2. Jika Rejected: Mode Review Saja (Hapus tombol delete)
            if (item.status === 'rejected') {
                return `
                            <button class="btn btn-info btn-sm review-assignment" data-approval='${encodeURIComponent(JSON.stringify(item))}' title="Review & Perbaiki">
                                <i class="fe fe-eye text-white"></i> <span class="text-white">Review</span>
                            </button>
                        `;
            }

            // 3. Jika Pending: Mode Edit & Hapus
            return `
                        <button class="btn btn-warning btn-sm review-assignment" data-approval='${encodeURIComponent(JSON.stringify(item))}' title="Edit">
                            <i class="fe fe-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm delete-assignment" data-id="${item.id}" title="Hapus">
                            <i class="fe fe-trash-2"></i>
                        </button>
                    `;
        }
        function loadApprovalList() {
            let period = $('#filter-period').val();
            let status = $('#filter-status').val();

            $('#approvalList').html('<div class="text-center py-5 text-muted"><div class="spinner-border text-success" role="status"></div><div class="mt-2">Memuat data...</div></div>');

            $.ajax({
                url: '/team/kpi/approval',
                type: 'GET',
                data: {
                    period_id: period,
                    status: status
                },
                success: function (res) {
                    let html = '';

                    if (!res.data || res.data.length === 0) {
                        $('#approvalList').html(`
                                                                <div class="text-center py-5">
                                                                    <i class="fe fe-inbox fs-1 text-muted"></i>
                                                                    <p class="mt-3 text-muted">Belum ada pengajuan KPI</p>
                                                                </div>
                                                            `);
                        return;
                    }

                    res.data.forEach(item => {
                        let statusClass = 'status-pending';
                        let statusText = 'Waiting Approval';

                        if (item.status === 'approved') {
                            statusClass = 'status-approved';
                            statusText = 'Approved';
                        } else if (item.status === 'rejected') {
                            statusClass = 'status-rejected';
                            statusText = 'Rejected';
                        }

                        let memberHtml = item.user_kpis.slice(0, 3).map(x => `<span class="member-chip">${x.user.name}</span>`).join('');
                        let kpiHtml = item.kpi_details.slice(0, 3).map(x => `<span class="kpi-chip">${x.master_kpi.title}</span>`).join('');

                        html += `
                                                                <div class="approval-row ${item.status === 'rejected' ? 'border-danger border-opacity-50' : ''}">
                                                                    <div class="approval-main">
                                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                                            <span class="status-dot ${statusClass}"></span>
                                                                            <div class="approval-title">${item.kpi_period ? item.kpi_period.name : 'Periode KPI'}</div>
                                                                        </div>
                                                                        <div class="approval-meta mb-2">
                                                                            Approver: <strong>${item.approver ? item.approver.name : '-'}</strong> • ${moment(item.created_at).format('DD MMM YYYY')} • <span class="fw-bold">${statusText}</span>
                                                                        </div>
                                                                        <div class="d-flex flex-wrap gap-1 mb-2">${memberHtml}</div>
                                                                        <div class="d-flex flex-wrap gap-1">
                                                                            ${kpiHtml}
                                                                            ${item.kpi_details.length > 3 ? `<span class="kpi-chip">+${item.kpi_details.length - 3}</span>` : ''}
                                                                        </div>
                                                                        ${item.status === 'rejected' && item.notes ? `<div class="mt-2 text-danger small"><i class="fe fe-alert-circle"></i> Catatan Penolakan: <b>${item.notes}</b></div>` : ''}
                                                                    </div>
                                                                    <div class="approval-info">
                                                                        <div class="small text-muted mb-2">${item.kpi_details.length} KPI</div>
                                                                        <div class="approval-actions">
                                                                            ${renderActionButtons(item)}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            `;
                    });

                    $('#approvalList').html(html);
                },
                error: function () {
                    $('#approvalList').html('<div class="text-center py-5 text-danger"><i class="fe fe-alert-triangle fs-1"></i><p class="mt-3">Gagal memuat data.</p></div>');
                }
            });
        }

        // Panggil data pertama kali
        loadApprovalList();


        // ==========================================
        // LOGIKA FORM UTAMA & PEMILIHAN KPI
        // ==========================================
        function addKpiToSelection(kpi) {
            if (selectedKpis.includes(kpi.id)) return;

            selectedKpis.push(kpi.id);
            $('.empty-state').hide();

            let htmlCard = `
                                                    <div class="col-md-6 selected-kpi-item" id="selected-kpi-${kpi.id}">
                                                        <div class="card border-success border-opacity-50 shadow-sm h-100 position-relative overflow-visible">
                                                            <input type="hidden" name="kpi_masters[]" value="${kpi.id}">
                                                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle remove-kpi-btn shadow-sm" data-id="${kpi.id}" style="width:32px;height:32px;padding:0;z-index:1000;">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                            <div class="card-body p-3">
                                                                <h6 class="fw-bold text-dark pe-4 lh-base mb-2" style="font-size:0.95rem;">${kpi.title}</h6>
                                                                <div class="d-flex flex-wrap gap-3 mb-3 small">
                                                                    <span class="text-success fw-medium"><i class="bi bi-bullseye"></i> Target: ${kpi.target} ${kpi.satuan}</span>
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

            let $newCard = $(htmlCard).hide();
            $('#selected-kpis-container').append($newCard);
            $newCard.fadeIn('fast');
        }

        $(document).on('click', '.remove-kpi-btn', function () {
            let kpiId = $(this).data('id');
            selectedKpis = selectedKpis.filter(id => id !== kpiId);

            $(`#selected-kpi-${kpiId}`).fadeOut(200, function () {
                $(this).remove();
                if (selectedKpis.length === 0) {
                    $('.empty-state').fadeIn();
                }
            });
        });

        // ==========================================
        // MODAL CARI & PILIH KPI
        // ==========================================
        function loadModalKpis() {
            let container = $('#available-kpis-list');
            container.html('<div class="col-12 text-center p-4"><div class="spinner-border text-success" role="status"></div><div class="mt-2 text-muted small">Memuat data...</div></div>');

            $.ajax({
                url: '/kpi/master/me',
                type: 'GET',
                success: function (response) {
                    let html = '';
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function (kpi) {
                            let isSelected = selectedKpis.includes(kpi.id);
                            let btnText = isSelected ? '<i class="bi bi-check-lg"></i> Terpilih' : '<i class="bi bi-plus-lg"></i> Pilih';
                            let btnClass = isSelected ? 'btn-success disabled' : 'btn-outline-success btn-select-kpi';
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

        $('#btnOpenKpiModal').on('click', function () {
            loadModalKpis();
        });

        $(document).on('click', '.btn-select-kpi', function () {
            let btn = $(this);
            let kpiData = JSON.parse(btn.attr('data-kpi'));

            btn.removeClass('btn-outline-success btn-select-kpi').addClass('btn-success disabled').html('<i class="bi bi-check-lg"></i> Terpilih');
            addKpiToSelection(kpiData);
        });

        // ==========================================
        // BUAT BARU KPI (OFFCANVAS)
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
                    let bsOffcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasKpiMaster'));
                    bsOffcanvas.hide();

                    $('#formCreateKpiMaster')[0].reset();
                    btn.prop('disabled', false).text('Simpan KPI Master');

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
        // SUBMIT FORM PENUGASAN (CREATE / UPDATE)
        // ==========================================
        $('#assignmentForm').on('submit', function (e) {
            e.preventDefault();

            if ($('input[name="kpi_masters[]"]').length === 0) {
                alert('Silakan pilih minimal 1 Master KPI terlebih dahulu!');
                return;
            }

            let form = $(this);
            let btn = form.find('button[type="submit"]');
            let originalText = btn.html();

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengirim...');

            let approvalId = $('#approval_id').val();
            let url = '/team/kpi/assign';
            let method = 'POST';

            if (approvalId) {
                url = `/team/kpi/assign/${approvalId}`;
                method = 'PUT';
            }

            $.ajax({
                url: url,
                type: method,
                data: form.serialize(),
                success: function (response) {
                    swal({
                        title: 'Berhasil',
                        text: response.message,
                        type: 'success'
                    }, function () {
                        // Reset Form
                        $('#assignmentForm')[0].reset();
                        $('#approval_id').val('');
                        $('#team_members').val(null).trigger('change');
                        $('#approver_id').val(null).trigger('change');

                        selectedKpis = [];
                        $('#selected-kpis-container').html('');
                        $('.empty-state').show();

                        reviewMode = false;
                        $('#submitAssignmentBtn')
                            .removeClass('btn-warning btn-info text-white')
                            .addClass('btn-success')
                            .html('Kirim Penugasan & Minta Approval');

                        // Update list
                        loadApprovalList();

                        // Pindah tab ke list
                        $('.panel-tabs a[href="#tab18"]').tab('show');
                    });
                },
                error: function (xhr) {
                    btn.prop('disabled', false).html(originalText);
                    swal('Error', xhr.responseJSON?.error ?? 'Terjadi kesalahan', 'error');
                }
            });
        });

        // ==========================================
        // EDIT / REVIEW ASSIGNMENT
        // ==========================================
        $(document).on('click', '.review-assignment', function () {
            const data = JSON.parse(decodeURIComponent($(this).attr('data-approval')));

            $('#approval_id').val(data.id);
            reviewMode = true;

            let btnText = '<i class="fe fe-save me-2"></i> Update Assignment';
            let btnClass = 'btn-warning';

            // Jika Rejected, rubah tulisan menjadi Review
            if (data.status === 'rejected') {
                btnText = '<i class="fe fe-refresh-cw me-2"></i> Kirim Ulang (Review)';
                btnClass = 'btn-info text-white';
            }

            $('#submitAssignmentBtn')
                .removeClass('btn-success btn-warning btn-info text-white')
                .addClass(btnClass)
                .html(btnText);

            selectedKpis = [];
            $('#selected-kpis-container').html('');
            $('.empty-state').hide();

            const memberIds = data.user_kpis.map(x => x.user_id);
            $('#team_members').val(memberIds).trigger('change');
            $('#approver_id').val(data.approver_id).trigger('change');
            $('textarea[name="notes"]').val(data.notes);

            data.kpi_details.forEach(detail => {
                addKpiToSelection({
                    id: detail.master_kpi.id,
                    title: detail.master_kpi.title,
                    target: detail.master_kpi.target,
                    bobot: detail.master_kpi.bobot,
                    satuan: detail.master_kpi.satuan,
                    formulas: detail.master_kpi.formulas
                });
            });

            // Pindah tab & Scroll
            $('.panel-tabs a[href="#tab17"]').tab('show');
            $('html,body').animate({ scrollTop: $('#assignmentForm').offset().top - 100 }, 300);
        });

        // ==========================================
        // DELETE ASSIGNMENT
        // ==========================================
        $(document).on('click', '.delete-assignment', function () {
            const id = $(this).data('id');

            swal({
                title: "Apakah Anda Yakin?",
                text: "Data pengajuan yang dihapus tidak dapat dikembalikan!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: `/team/kpi/assign/${id}`, // Pastikan API endpoint delete ini tersedia
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            swal("Berhasil!", "Data pengajuan berhasil dihapus.", "success");
                            loadApprovalList();
                        },
                        error: function (xhr) {
                            swal("Gagal!", xhr.responseJSON?.message || "Gagal menghapus data.", "error");
                        }
                    });
                }
            });
        });

        function renderKpiList(data) {
            let html = '';

            if (!data || data.length === 0) {
                $('#kpiRealizationList').html(`
                    <div class="text-center py-5 bg-light rounded-3 border border-dashed">
                        <i class="fe fe-file-text fs-1 text-muted mb-2"></i>
                        <h6 class="text-secondary fw-medium mb-0">Tidak ada data KPI ditemukan</h6>
                        <small class="text-muted">Silakan sesuaikan kembali filter periode atau anggota tim.</small>
                    </div>
                `);
                return;
            }

            data.forEach(userKpi => {
                let totalBobot = userKpi.kpis.reduce((sum, kpi) => sum + (parseFloat(kpi.bobot) || 0), 0);
                let totalSkor = userKpi.kpis.reduce((sum, kpi) => sum + (kpi.realization ? parseFloat(kpi.realization.nilai) : 0), 0);

                // Indikator status total bobot
                let bobotStatusClass = totalBobot === 100 ? 'text-success' : 'text-warning';

                html += `
                <div class="card border border-light shadow-sm mb-4 rounded-3 bg-white">
                    
                    <div class="card-body p-4 border-bottom border-light">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-1" style="letter-spacing: -0.3px;">${userKpi.member ? userKpi.member.name : '-'}</h5>
                                <div class="text-muted small d-flex align-items-center gap-2">
                                    <span><i class="fe fe-calendar me-1"></i> ${userKpi.period ? userKpi.period.name : '-'}</span>
                                    <span>•</span>
                                    <span>${userKpi.kpis.length} Metrik Ditugaskan</span>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-4 border-start ps-0 ps-sm-4 pt-2 pt-sm-0 border-light">
                                <div>
                                    <small class="text-muted d-block small fw-medium uppercase text-xs">TOTAL BOBOT</small>
                                    <span class="fs-5 fw-bold ${bobotStatusClass}">${totalBobot}%</span>
                                </div>
                                <div>
                                    <small class="text-muted d-block small fw-medium uppercase text-xs">TOTAL SKOR</small>
                                    <span class="fs-5 fw-bold text-primary">${totalSkor.toFixed(2)}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="bg-light bg-opacity-50 text-secondary small" style="border-bottom: 1px solid #f1f5f9;">
                                <tr>
                                    <th class="ps-4 py-3" width="40%">METRIK KPI</th>
                                    <th class="py-3 text-center" width="15%">TARGET & BOBOT</th>
                                    <th class="py-3 text-center" width="15%">FORMULA</th>
                                    <th class="py-3 text-center" width="15%">REALISASI</th>
                                    <th class="pe-4 py-3 text-end" width="15%">PENCAPAIAN</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                userKpi.kpis.forEach(kpi => {
                    let hasReal = kpi.realization !== null;
                    let realVal = hasReal ? kpi.realization.value : '-';
                    let capPercent = hasReal ? parseFloat(kpi.realization.achievement_percent) : 0;
                    let scoreVal = hasReal ? kpi.realization.nilai : '-';
                    let kpiNotes = hasReal && kpi.realization.notes ? kpi.realization.notes : '';

                    // Pewarnaan capaian yang halus (hanya teks/dot, bukan background penuh)
                    let capColor = 'text-secondary';
                    if (hasReal) {
                        capColor = capPercent >= 100 ? 'text-success' : (capPercent >= 50 ? 'text-info' : 'text-danger');
                    }

                    html += `
                                <tr style="border-bottom: 1px solid #f8fafc;">
                                    <td class="ps-4 py-31">
                                        <div class="fw-semibold text-dark mb-1">${kpi.title}</div>
                                        <div class="text-muted small" style="font-size: 0.8rem; line-height: 1.4;">
                                            <span class="d-block"><strong>DoD:</strong> ${kpi.definition_of_done ?? '-'}</span>
                                            ${kpi.guard_rail ? `<span class="d-block text-danger-subtle"><strong>Guard Rail:</strong> ${kpi.guard_rail}</span>` : ''}
                                        </div>
                                    </td>
                                    
                                    <td class="text-center py-3">
                                        <span class="text-dark fw-medium">${kpi.target ?? '-'}</span>
                                        <span class="text-muted small">${kpi.satuan ?? ''}</span>
                                        <small class="text-muted d-block mt-1">Bobot: ${kpi.bobot ?? 0}%</small>
                                    </td>
                                    
                                    <td class="text-center py-3">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center align-items-center">
                                            ${renderFormulaBadges(kpi.formulas)}
                                        </div>
                                    </td>
                                    
                                    <td class="text-center py-3">
                                        <span class="fs-6 fw-bold text-dark">${realVal}</span>
                                        <span class="text-muted small">${realVal !== '-' ? (kpi.satuan ?? '') : ''}</span>
                                        ${kpiNotes ? `
                                            <div class="text-muted mt-1 small text-truncate d-inline-block border-top pt-1 w-100 style="max-width: 140px;" title="${kpiNotes}">
                                                <i class="fe fe-message-square me-1"></i>${kpiNotes}
                                            </div>
                                        ` : ''}
                                    </td>
                                    
                                    <td class="pe-4 text-end py-3">
                                        <div class="fw-bold text-dark fs-6">Skor: ${scoreVal}</div>
                                        <small class="${capColor} fw-medium d-block mt-0.5">
                                            <span style="font-size: 0.75rem;">●</span> ${capPercent}% Capaian
                                        </small>
                                    </td>
                                </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                </div>
                `;
            });

            $('#kpiRealizationList').html(html);
        }
        $('#btnLoadReportKpi').on('click', function () {
            let periodId = $('#periodFilter').val();
            let memberId = $('#memberFilter').val();
            if (!periodId) {
                alert('Silakan pilih periode terlebih dahulu!');
                return;
            }

            $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memuat...');

            $.ajax({
                url: '/team/kpi/report',
                type: 'GET',
                data: { period_id: periodId, member_id: memberId },
                success: function (res) {
                    console.log(res.data)
                    renderKpiList(res.data);
                    $('#btnLoadReportKpi').prop('disabled', false).html('<i class="ti ti-search me-1"></i> Load KPI');
                },
                error: function (xhr, status, error) {
                    console.error(xhr, status, error);
                    alert('Gagal memuat data KPI.');
                    $('#btnLoadReportKpi').prop('disabled', false).html('<i class="ti ti-search me-1"></i> Load KPI');
                }
            });

        });

        // Fungsi untuk merender ulang tampilan list dropdown Select2
        function formatMember(member) {
            // Jika ini adalah placeholder (tidak ada ID), tampilkan teks bawaan
            if (!member.id) {
                return member.text;
            }

            // Ambil data dari atribut HTML data-*
            let nama = $(member.element).data('nama');
            let jabatan = $(member.element).data('jabatan');
            let unit = $(member.element).data('unit');

            // Susun HTML kustom untuk dropdown item
            let $result = $(`
                <div style="line-height: 1.4;">
                    <span style="font-weight: 600; color: #333;">${nama}</span><br>
                    <small style="color: #6c757d; font-size: 0.85em;">
                        <i class="fe fe-briefcase"></i> ${jabatan} | <i class="fe fe-building"></i> ${unit}
                    </small>
                </div>
            `);

            return $result;
        }

        // Fungsi untuk merender teks saat item sudah dipilih
        function formatMemberSelection(member) {
            if (!member.id) {
                return member.text;
            }
            let nama = $(member.element).data('nama');
            return nama; // Hanya tampilkan nama saat sudah dipilih agar input box tidak kepenuhan
        }

        // Inisialisasi Select2
        $('#memberFilter').select2({
            placeholder: "Cari Member...",
            allowClear: true,
            width: '100%',               // Memastikan lebarnya mengikuti container
            templateResult: formatMember, // Gunakan template custom untuk list dropdown
            templateSelection: formatMemberSelection, // Gunakan template custom untuk item yang terpilih
            language: {
                noResults: function () {
                    return "Member tidak ditemukan"; // Teks jika pencarian tidak ada hasil
                }
            }
        });

        // (Opsional) Auto-focus ke kolom search ketika Select2 dibuka
        $('#memberFilter').on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });

    });
</script>