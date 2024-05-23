@extends('layouts.adminlte', [
    'title' => 'Umpan Balik',
])

@section('head')
    <link rel="stylesheet" href="{{ asset('adminlte-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('adminlte-3.2.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte-3.2.0/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select.dataTables.min.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp">
    <style>
        #builtin-filter {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        @media (min-width: 768px) {
            #builtin-filter {
                flex-direction: row;
                width: 50%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <x-adminlte.session-notifications />
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <h1 class="m-0">Umpan Balik</h1>
                    {{-- @can('presence-scopes-create')
                        <a href="{{ route('admin.feedbacks.create') }}" class="btn btn-sm btn-primary ml-2">Add New</a>
                    @endcan --}}
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Umpan Balik</li>
                    </ol>
                </div>
            </div>
            <form id="builtin-filter">
                <select name="user_id" id="user_id" class="custom-select">
                    <option value="">Semua Karyawan</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request()->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}</option>
                    @endforeach
                </select>
                <select name="question_id" id="question_id" class="custom-select">
                    <option value="">Semua Pertanyaan</option>
                    @foreach ($questions as $question)
                        <option value="{{ $question->id }}" {{ request()->question_id == $question->id ? 'selected' : '' }}>
                            {{ $question->question }}</option>
                    @endforeach
                </select>
                <select name="kpi_period_id" id="kpi_period_id" class="custom-select">
                    <option value="">Semua Periode KPI</option>
                    @foreach ($kpis as $kpi)
                        <option value="{{ $kpi->id }}" {{ request()->kpi_period_id == $kpi->id ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($kpi->start_date)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($kpi->end_date)->format('d/m/Y') }}
                            {{ $kpi->is_active ? '(Aktif)' : '' }}</option>
                    @endforeach
                </select>
                <select name="course_id" id="course_id" class="custom-select">
                    <option value="">Semua Mata Kuliah</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" {{ request()->course_id == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary" type="submit">Filter</button>
            </form>
        </div>
    </div>

    <div class="content pb-3">
        <div class="container-fluid">
            <div class="card m-0">
                <div class="card-body table-responsive">
                    <table id="datatable" class="table-striped table-bordered table" style="width:100%">
                        <thead>
                            <tr>
                                <th style="cursor: pointer" id="selector">
                                    <input type="checkbox" class="w-100" style="cursor: pointer">
                                    <span style="display: none;">Selector</span>
                                </th>
                                <th>Pertanyaan</th>
                                <th>Pesan/Jawaban</th>
                                <th>Point</th>
                                <th>Karyawan</th>
                                <th>Mata Kuliah</th>
                                <th>Pengirim</th>
                                <th class="d-print-none">Options</th>
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
            const exportOption = [1, 2, 3, 4, 5, 6];
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
            }, {
                text: 'Bulk Delete',
                className: 'btn btn-sm rounded-0 btn-danger',
                action: function() {
                    startBulkDelete('{{ csrf_token() }}',
                        '{{ route('admin.feedbacks.massDestroy') }}')
                }
            }, ];

            const datatable = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                search: {
                    return: true,
                },
                language: {
                    processing: 'Loading...'
                },
                ajax: '{!! route('admin.feedbacks.index', [
                    'user_id' => request()->user_id,
                    'question_id' => request()->question_id,
                    'kpi_period_id' => request()->kpi_period_id,
                    'course_id' => request()->course_id,
                ]) !!}',
                lengthMenu: [
                    [10, 50, 100, 500, 1000],
                    [10, 50, 100, 500, 1000]
                ],
                columns: [{
                    defaultContent: ''
                }, {
                    data: 'question',
                    name: 'question'
                }, {
                    data: 'message',
                    name: 'message'
                }, {
                    data: 'point',
                    name: 'point'
                }, {
                    data: 'user.name',
                    name: 'user.name',
                    searchable: false
                }, {
                    data: 'course.name',
                    name: 'course.name',
                    searchable: false
                }, {
                    data: 'sender.name',
                    name: 'sender.name',
                    searchable: false
                }, {
                    data: 'options',
                    name: 'options'
                }],
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
                    targets: [1, 2, 4, 5, 6]
                }],
                order: [
                    // [1, 'asc']
                ]
            });

            datatable.on('draw', () => {
                deleteButtonInit('{{ csrf_token() }}');
            });
        });
    </script>
@endsection
