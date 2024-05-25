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
                    <h1 class="m-0">Edit Pertanyaan Umpan Balik</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Pertanyaan Umpan
                                Balik</a></li>
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
                    <form action="{{ route('admin.questions.update', $question) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="question" class="text-capitalize">Pertanyaan</label>
                            <input type="text" value="{{ $question->question }}" class="form-control" id="question"
                                name="question" required>
                            @error('question')
                                <div class="text-danger text-sm">{{ $message ?? 'Something error' }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tendik_position_id" class="text-capitalize">Tipe</label>
                            <select name="tendik_position_id" id="tendik_position_id" class="select2" style="width: 100%;" required>
                                <option value="" disabled hidden>Pilih</option>
                                @foreach ($tendikPositions as $position)
                                    @if ($position->division == 'Edukatif')
                                        <option value="{{ $position->id }}"
                                            {{ $question->tendik_position_id == $position->id ? 'selected' : '' }}>Mahasiswa
                                            Ke Dosen</option>
                                    @else
                                        <option value="{{ $position->id }}"
                                            {{ $question->tendik_position_id == $position->id ? 'selected' : '' }}>
                                            Mahasiswa
                                            Ke {{ $position->division }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('tendik_position_id')
                                <div class="text-danger text-sm">{{ $message ?? 'Something error' }}</div>
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
