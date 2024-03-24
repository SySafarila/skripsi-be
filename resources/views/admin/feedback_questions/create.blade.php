@extends('layouts.adminlte')

@section('head')
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp">
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <h1 class="m-0">Buat Pertanyaan Umpan Balik</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subjects.index') }}">Pertanyaan Umpan Balik</a></li>
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
                    <form action="{{ route('admin.questions.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="question" class="text-capitalize">Pertanyaan</label>
                            <input type="text" class="form-control" id="question" name="question" required>
                            @error('question')
                                <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="type" class="text-capitalize">Tipe</label>
                            <select name="type" id="type" class="custom-select" required>
                                <option value="" selected hidden>Pilih</option>
                                <option value="mahasiswa-to-dosen">Mahasiswa Ke Dosen</option>
                            </select>
                            @error('type')
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
@endsection
