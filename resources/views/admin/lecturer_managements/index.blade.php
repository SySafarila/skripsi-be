@extends('layouts.adminlte', [
    'title' => 'Pengelolaan Dosen'
])

@section('head')
    <link rel="stylesheet" href="{{ asset('adminlte-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte-3.2.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte-3.2.0/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.4/css/select.dataTables.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp">
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <x-adminlte.session-notifications />
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <h1 class="m-0">Pengelolaan Dosen</h1>
                    @can('lecturer-managements-create')
                        <a href="{{ route('admin.lecturer-managements.create') }}" class="btn btn-sm btn-primary ml-2">Add New</a>
                    @endcan
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Pengelolaan Dosen</li>
                    </ol>
                </div>
            </div>
            <form class="d-flex" style="gap: 8px;">
                <select name="user_id" id="user_id" class="custom-select" style="max-width: 10rem;">
                    <option value="">Semua Dosen</option>
                    @foreach ($lecturers as $lecturer)
                        <option value="{{ $lecturer->id }}" {{ request()->user_id == $lecturer->id ? 'selected' : '' }}>{{ $lecturer->name }}</option>
                    @endforeach
                </select>
                <select name="subject_id" id="subject_id" class="custom-select" style="max-width: 13rem;">
                    <option value="">Semua Mata Kuliah</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request()->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
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
                    <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th style="cursor: pointer" id="selector">
                                    <input type="checkbox" class="w-100" style="cursor: pointer">
                                    <span style="display: none;">Selector</span>
                                </th>
                                <th>Nama Dosen</th>
                                <th>Mata Kuliah</th>
                                <th>Quota Hadir</th>
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
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
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
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <script src="{{ asset('js/datatables/select-deselect-all.js') }}"></script>
    <script src="{{ asset('js/datatables/delete-button-init.js') }}"></script>
    <script src="{{ asset('js/datatables/bulk-delete.js') }}"></script>
    <script>
        $(document).ready(function() {
            const exportOption = [1, 2, 3];
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
                    startBulkDelete('{{ csrf_token() }}', '{{ route('admin.lecturer-managements.massDestroy') }}')
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
                ajax: '{!! route('admin.lecturer-managements.index', ['user_id' => request()->user_id, 'subject_id' => request()->subject_id]) !!}',
                lengthMenu: [
                    [10, 50, 100, 500, 1000, -1],
                    [10, 50, 100, 500, 1000, 'All']
                ],
                columns: [{
                    defaultContent: ''
                }, {
                    data: 'user.name',
                    name: 'user_id'
                }, {
                    data: 'subject.name',
                    name: 'subject_id'
                }, {
                    data: 'quota',
                    name: 'quota'
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
                    targets: [4, 1, 2]
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