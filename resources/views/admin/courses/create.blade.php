@extends('layouts.adminlte')

@section('head')
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp">
    {{-- Select2 --}}
    <link rel="stylesheet" href="{{ asset('adminlte-3.2.0/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte-3.2.0/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <h1 class="m-0">Buat Mata Kuliah</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Mata Kuliah</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content pb-3">
        <div class="container-fluid">
            <div class="card m-0">
                <div class="card-body">
                    @if (request()->type == 'import')
                        <form action="{{ route('admin.courses.store', ['type' => 'import']) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="excel" class="text-capitalize">Excel</label>
                                <input type="file" class="form-control border-0 p-0" id="excel" name="excel" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                                @error('excel')
                                    <div class="text-danger text-sm">{{ $message ?? 'Something error' }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Import</button>
                            <a href="{{ route('admin.download-sample-courses') }}" class="btn btn-sm btn-success">Download Sample Excel</a>
                        </form>
                    @else
                        <form action="{{ route('admin.courses.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="text-capitalize">Mata Kuliah</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="semester" class="text-capitalize">Semester</label>
                                <input type="number" class="form-control" id="semester" name="semester" value="{{ old('semester') }}" required>
                                @error('semester')
                                    <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="user_id" class="text-capitalize">Dosen</label>
                                <select class="select2 form-control" name="user_id"
                                    data-placeholder="Pilih Dosen" style="width: 100%;">
                                    <option value="">Pilih</option>
                                    <option value="-">TIDAK ADA DOSEN</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->id }}" {{ old('user_id') == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="major_id" class="text-capitalize">Jurusan</label>
                                <select class="select2 form-control" name="major_id"
                                    data-placeholder="Pilih Jurusan" style="width: 100%;" required>
                                    <option value="">Pilih</option>
                                    @foreach ($majors as $major)
                                        <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>{{ $major->major }}</option>
                                    @endforeach
                                </select>
                                @error('major_id')
                                    <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- jQuery --}}
    <script src="{{ asset('adminlte-3.2.0/plugins/jquery/jquery.min.js') }}"></script>
    {{-- Bootstrap 4 --}}
    <script src="{{ asset('adminlte-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- Select2 --}}
    <script src="{{ asset('adminlte-3.2.0/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4',
            closeOnSelect: true
        })
    </script>
@endsection
