@extends('layouts.app')

@section('content')
    <div class="row">

        <!-- FORM CARD -->
        <div class="col-12">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- HEADER -->
                <div class="card-header border-0 p-4 user-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>
                            <h3 class="fw-bold text-white mb-1">
                                Supervisor
                            </h3>

                            <p class="text-white-50 mb-0">
                                Kelola data supervisor user
                            </p>
                        </div>

                    </div>

                </div>

                <!-- BODY -->
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover custom-table" id="unitTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nama</th>
                                    <th>Deskripsi</th>
                                    <th width="15%">Action</th>
                                </tr>

                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STYLE -->
    <style>
        .user-header {
            background: linear-gradient(135deg, #6259ca 0%, #867efc 100%);
        }

        .modern-input {
            height: 52px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            display: flex;
            align-items: center;
            overflow: hidden;
            transition: .3s;
            background: #fff;
        }

        .modern-input:focus-within {
            border-color: #6259ca;
            box-shadow: 0 0 0 4px rgba(98, 89, 202, .12);
        }

        .modern-input span {
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6259ca;
            font-size: 16px;
        }

        .modern-input input {
            border: none;
            outline: none;
            width: 100%;
            height: 100%;
            padding-right: 16px;
            background: transparent;
        }

        .modern-select {
            height: 52px;
            border-radius: 14px !important;
            border: 1px solid #e5e7eb !important;
        }

        .select2-container--default .select2-selection--single {
            height: 52px !important;
            border-radius: 14px !important;
            border: 1px solid #e5e7eb !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 52px !important;
            padding-left: 14px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 52px !important;
        }

        .custom-table thead th {
            background: #f8fafc;
            border-bottom: none !important;
            padding: 16px;
            font-size: 13px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
        }

        .custom-table tbody td {
            padding: 16px;
            vertical-align: middle;
        }

        .custom-table tbody tr {
            transition: .2s;
        }

        .custom-table tbody tr:hover {
            background: #f8fafc;
        }

        .dataTables_filter input {
            border-radius: 12px !important;
            border: 1px solid #e5e7eb !important;
            padding: 8px 14px !important;
        }

        .dataTables_length select {
            border-radius: 10px !important;
            border: 1px solid #e5e7eb !important;
        }
    </style>

    <!-- SCRIPT -->
    <script>
        $(document).ready(function() {

            let table = $('#unitTable').DataTable({

                processing: true,
                serverSide: false,

                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search..."
                },

                ajax: {
                    url: '/supervisor/data',
                    type: 'GET',
                    dataSrc: ''
                },

                columns: [{
                        data: 'no',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
          <div class="d-flex gap-2 justify-content-center">
    <button 
        class="btn btn-icon btn-primary rounded-circle"
        onclick="editunit(${row.id})"
    >
        <i class="fe fe-edit-2"></i>
    </button>

    <button 
        class="btn btn-icon btn-danger rounded-circle"
        onclick="deleteunit(${row.id})"
    >
        <i class="fe fe-trash-2"></i>
    </button>
</div>
                            `;
                        }
                    }
                ]

            });
        });
    </script>
@endsection
