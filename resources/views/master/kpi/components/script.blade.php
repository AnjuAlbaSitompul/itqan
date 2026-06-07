<script>
    $(document).ready(function () {

        $('.select2').select2({
            width: '100%'
        });

        $('#jobLevelTable').DataTable({

            processing: true,
            serverSide: false,

            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search job level..."
            },

            data: [],

            columns: [{
                data: 'no'
            },
            {
                data: 'name'
            },
            {
                data: 'period'
            },
            {
                data: 'status'
            },
            {
                data: 'created_by'
            },
            {
                data: 'action'
            }
            ]

        });

        $('#jobLevelForm').submit(function (e) {

            e.preventDefault();

            let formData = {

                name: $('#name').val(),
                description: $('#description').val(),
                start_period: $('#start_period').val(),
                end_period: $('#end_period').val(),
                status: $('#status').val(),
                _token: '{{ csrf_token() }}'

            };

            if (
                formData.name === '' ||
                formData.start_period === '' ||
                formData.end_period === ''
            ) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Oops',
                    text: 'Semua field wajib diisi'
                });

                return;

            }

            $.post('/job-level/store', formData)

                .done(function (res) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message ??
                            'Job level berhasil disimpan'
                    });

                    $('#jobLevelForm')[0].reset();

                })

                .fail(function (err) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.responseJSON?.message ??
                            'Terjadi kesalahan'
                    });

                });

        });

        $('#btnReset, #btnCancel').click(function () {

            $('#jobLevelForm')[0].reset();

        });

    });
</script>