@extends('layouts.adminlte', [
    'title' => 'Leaderboard',
])

@section('head')
    <link rel="stylesheet" href="{{ asset('adminlte-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('adminlte-3.2.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte-3.2.0/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select.dataTables.min.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp">
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <x-adminlte.session-notifications />
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <h1 class="m-0">Leaderboard {{ \Carbon\Carbon::parse($kpi->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($kpi->end_date)->format('d/m/Y') }}</h1>
                    {{-- @can('kpi-create')
                        <a href="{{ route('admin.kpi.create') }}" class="btn btn-sm btn-primary ml-2">Add New</a>
                    @endcan --}}
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">KPI Leaderboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content pb-3">
        <div class="container-fluid">
            <a href="{{ route('admin.kpi.leaderboard', ['kpi_id' => $kpi_id]) }}" class="btn btn-sm {{ request()->show != 'tendik' ? 'btn-primary' : 'btn-light border' }} mb-2">Karyawan</a>
            <a href="{{ route('admin.kpi.leaderboard', ['kpi_id' => $kpi_id, 'show' => 'tendik']) }}" class="btn btn-sm {{ request()->show == 'tendik' ? 'btn-primary' : 'btn-light border' }} mb-2">Kategori Tendik</a>
            <div class="card m-0">
                <div class="card-body table-responsive">
                    <table id="datatable" class="table-striped table-bordered table" style="width:100%">
                        <thead>
                            <tr>
                                <th style="cursor: pointer" id="selector">
                                    <input type="checkbox" class="w-100" style="cursor: pointer">
                                    <span style="display: none;">Selector</span>
                                </th>
                                <th>Nama</th>
                                <th>Point</th>
                                <th>Point Kehadiran</th>
                                <th>Point Feedback</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/jquery.dataTables.1.12.1.min.js') }}"></script>
    {{-- <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script> --}}
    <script src="{{ asset('adminlte-3.2.0/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte-3.2.0/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte-3.2.0/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte-3.2.0/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('adminlte-3.2.0/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte-3.2.0/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('adminlte-3.2.0/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('adminlte-3.2.0/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('adminlte-3.2.0/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('adminlte-3.2.0/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('adminlte-3.2.0/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.select.1.3.3.min.js') }}"></script>
    <script src="{{ asset('js/datatables/select-deselect-all.js') }}"></script>
    <script src="{{ asset('js/datatables/delete-button-init.js') }}"></script>
    <script src="{{ asset('js/datatables/bulk-delete.js') }}"></script>
    <script>
        $(document).ready(function() {
            const exportOption = [1, 2, 3, 4];
            const buttons = [{
                extend: 'copy',
                className: 'btn btn-sm rounded-0 btn-secondary',
                exportOptions: {
                    columns: exportOption
                }
            }, {
                extend: 'csv',
                className: 'btn btn-sm rounded-0 btn-secondary',
                exportOptions: {
                    columns: exportOption
                }
            }, {
                extend: 'excel',
                className: 'btn btn-sm rounded-0 btn-secondary',
                exportOptions: {
                    columns: exportOption
                }
            }, {
                extend: 'pdf',
                className: 'btn btn-sm rounded-0 btn-secondary',
                exportOptions: {
                    columns: exportOption
                }
            }, {
                extend: 'print',
                className: 'btn btn-sm rounded-0 btn-secondary',
                exportOptions: {
                    columns: exportOption
                }
            }, {
                extend: 'colvis',
                className: 'btn btn-sm rounded-0 btn-secondary'
            }];

            const datatable = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                search: {
                    return: true,
                },
                language: {
                    processing: 'Loading...'
                },
                ajax: '{!! route('admin.kpi.leaderboard', ['kpi_id' => $kpi_id, 'show' => request()->show]) !!}',
                lengthMenu: [
                    [10, 50, 100, 500, 1000],
                    [10, 50, 100, 500, 1000]
                ],
                columns: [{
                    defaultContent: ''
                }
                , {
                    data: 'name',
                    name: 'name',
                    searchable: false
                }
                , {
                    data: 'points',
                    name: 'points'
                }
                , {
                    data: 'presence_points',
                    name: 'presence_points'
                }
                , {
                    data: 'feedback_points',
                    name: 'feedback_points'
                }
            ],
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: buttons,
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }, {
                    orderable: false,
                    targets: []
                }],
                order: [
                    [2, 'desc']
                ]
            });

            datatable.on('draw', () => {
                deleteButtonInit('{{ csrf_token() }}');
            });
        });
    </script>
@endsection
