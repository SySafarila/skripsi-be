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
                    <h1 class="m-0">Edit Mahasiswa</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Mahasiswa</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content pb-3">
        <div class="container-fluid">
            <div class="card m-0">
                <div class="card-body">
                    <form action="{{ route('admin.students.update', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="name" class="text-capitalize">nama</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Syahrul Safarila"
                                value="{{ $user->name }}" required>
                            @error('name')
                                <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                            @enderror
                        </div>
                        {{-- <div class="form-group">
                            <label for="email" class="text-capitalize">email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="mail@mail.com"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                            @enderror
                        </div> --}}
                        {{-- <div class="form-group">
                            <label for="role" class="text-capitalize">tipe</label>
                            <select class="select2 form-control" name="role"
                                data-placeholder="Select role" style="width: 100%;">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ Str::ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                            @enderror
                        </div> --}}
                        <div class="row">
                            <div class="form-group col">
                                <label for="identifier" class="text-capitalize">identitas</label>
                                <select class="select2 form-control" name="identifier"
                                    data-placeholder="Select identifier" style="width: 100%;">
                                    <option value="nim">NIM</option>
                                </select>
                                @error('identifier')
                                    <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                                @enderror
                            </div>
                            <div class="form-group col">
                                <label for="identifier_number" class="text-capitalize">Nomor Registrasi</label>
                                <input type="number" class="form-control" id="identifier_number" name="identifier_number" value="{{ $user->identifier_number }}" required>
                                @error('identifier_number')
                                    <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label for="major_id" class="text-capitalize">Jurusan</label>
                                <select class="select2 form-control" name="major_id"
                                    data-placeholder="Select major_id" style="width: 100%;">
                                    @foreach ($majors as $major)
                                        <option value="{{ $major->id }}" {{ $user->hasMajor ? $user->hasMajor->major_id == $major->id ? 'selected' : '' : '' }}>{{ $major->major }}</option>
                                    @endforeach
                                </select>
                                @error('major_id')
                                    <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                                @enderror
                            </div>
                            <div class="form-group col">
                                <label for="semester" class="text-capitalize">Semester</label>
                                <input type="number" class="form-control" id="semester" name="semester" value="{{ $user->hasMajor ? $user->hasMajor->semester : '' }}" required>
                                @error('semester')
                                    <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_active" class="text-capitalize">Status Aktif</label>
                            <select class="select2 form-control" name="is_active"
                                data-placeholder="Select Status Aktif" style="width: 100%;">
                                <option value="1" {{ $user->is_active == true ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $user->is_active == false ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('is_active')
                                <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-capitalize">password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small>*Abaikan jika tidak ingin mengganti password</small>
                            @error('password')
                                <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="text-capitalize">password confirmation</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                                <small>*Abaikan jika tidak ingin mengganti password</small>
                            @error('password_confirmation')
                                <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                    </form>
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
