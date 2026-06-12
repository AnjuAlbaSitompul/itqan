<script>
    $(function () {

        console.log({{ $detail }})
        loadPeriods();
        let periodCanvas = new bootstrap.Offcanvas(
            document.getElementById('periodCanvas')
        );

        /*
        |--------------------------------------------------------------------------
        | OPEN CREATE
        |--------------------------------------------------------------------------
        */

        $('#btnCreatePeriod').on('click', function () {

            $('#periodForm')[0].reset();

            $('#period_id').val('');

            $('#canvasTitle').text('Create KPI Period');

            periodCanvas.show();

        });

        /*
        |--------------------------------------------------------------------------
        | AUTO GENERATE NAME
        |--------------------------------------------------------------------------
        */

        $('#period_start').on('change', function () {

            let date = $(this).val();

            if (!date) return;

            let d = new Date(date);

            let month = d.toLocaleString(
                'en-US',
                {
                    month: 'long'
                }
            );

            let year = d.getFullYear();

            if ($('#name').val() === '') {

                $('#name').val(
                    `KPI ${month} ${year}`
                );

            }

        });

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        function validateForm() {
            let registrationStart =
                $('#registration_start').val();

            let registrationEnd =
                $('#registration_end').val();

            let periodStart =
                $('#period_start').val();

            let periodEnd =
                $('#period_end').val();

            if (
                registrationStart > registrationEnd
            ) {

                swal({
                    type: 'warning',
                    title: 'Validation',
                    text: 'Registration end must be greater than registration start'
                });

                return false;
            }

            if (
                periodStart > periodEnd
            ) {

                swal({
                    type: 'warning',
                    title: 'Validation',
                    text: 'Period end must be greater than period start'
                });

                return false;
            }

            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE
        |--------------------------------------------------------------------------
        */

        $('#btnSavePeriod').on('click', function () {

            if (!validateForm()) {
                return;
            }

            let btn = $(this);

            btn.prop('disabled', true);

            $.ajax({

                url: "/kpi/period",

                method: "POST",

                data: {

                    name: $('#name').val(),

                    registration_start:
                        $('#registration_start').val(),

                    registration_end:
                        $('#registration_end').val(),

                    period_start:
                        $('#period_start').val(),

                    period_end:
                        $('#period_end').val(),

                    status:
                        $('#status').val(),

                    _token:
                        "{{ csrf_token() }}"
                },

                success: function (res) {

                    swal({
                        type: 'success',
                        title: 'Success',
                        text: res.message
                    });

                    periodCanvas.hide();

                    loadPeriods();

                },

                error: function (xhr) {

                    let message =
                        xhr.responseJSON?.message ??
                        'Something went wrong';

                    swal({
                        type: 'error',
                        title: 'Error',
                        text: message
                    });

                },

                complete: function () {

                    btn.prop(
                        'disabled',
                        false
                    );

                }

            });

        });

        /*
        |--------------------------------------------------------------------------
        | RESET WHEN CLOSED
        |--------------------------------------------------------------------------
        */

        $('#periodCanvas').on(
            'hidden.bs.offcanvas',
            function () {

                $('#periodForm')[0].reset();

            }
        );

        /*
        |--------------------------------------------------------------------------
        | LOAD PERIODS
        |--------------------------------------------------------------------------
        */
        function loadPeriods() {

            $.get('/kpi/period', function (res) {

                let html = '';

                res.data.forEach(period => {

                    let statusClass = '';

                    switch (period.status.toLowerCase()) {

                        case 'open':
                            statusClass = 'bg-success-subtle text-success';
                            break;

                        case 'closed':
                            statusClass = 'bg-danger-subtle text-danger';
                            break;

                        default:
                            statusClass = 'bg-warning-subtle text-warning';
                            break;
                    }
                    html += `
<div class="period-item" data-id="${period.id}">

    <div class="period-content">

        <div class="period-icon">
            <i class="fe fe-calendar"></i>
        </div>

        <div>

            <div class="fw-semibold">
                ${period.name}
            </div>

            <small class="text-muted">
                ${period.period_start} / ${period.period_end}
            </small>

        </div>

    </div>

    <div class="d-flex align-items-center gap-2">

        <span class="badge ${statusClass}">
            ${period.status.toUpperCase()}
        </span>

        <button
            type="button"
            class="btn btn-sm btn-icon btn-delete-period"
            data-id="${period.id}"
            title="Delete">

            <i class="fe fe-trash-2 text-danger"></i>

        </button>

    </div>

</div>
`;

                });

                $('.period-list').html(html);

            });

        }
        $(document).on('click', '.btn-delete-period', function (e) {

            e.stopPropagation();

            let id = $(this).data('id');

            swal({
                title: 'Are you sure?',
                text: 'KPI Period akan dihapus.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya Hapus!',
                cancelButtonText: 'Cancel',
                closeOnConfirm: false
            }, function (isConfirm) {

                if (!isConfirm) {
                    return;
                }

                $.ajax({

                    url: `/kpi/period/${id}`,

                    type: 'DELETE',

                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function (res) {

                        swal(
                            'Berhasil!',
                            res.message,
                            'success'
                        );

                        loadPeriods();

                    },

                    error: function (xhr) {

                        swal(
                            'Error!',
                            xhr.responseJSON?.message || 'Terjadi kesalahan',
                            'error'
                        );

                    }

                });

            });

        });

        function kpiDetail(id) {
            $.get(`/kpi/detail/${id}`, function (res) {
                $('#detailTitle').text(res.data.title);
                $('#detailDefinitionOfDone').text(res.data.definition_of_done);
                $('#detailGuardRail').text(res.data.guard_rail);
                $('#detailCreator').text(res.data.creator.name);
                $('#detailCreatedAt').text(new Date(res.data.created_at).toLocaleString());
                $('#kpiDetailCanvas').offcanvas('show');
            });
        }
    });
</script>