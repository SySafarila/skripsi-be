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
                    <h1 class="m-0">Edit Jabatan Tendik</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tendik-positions.index') }}">Jabatan Tendik</a></li>
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
                    <form action="{{ route('admin.tendik-positions.update', $tendik) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="division" class="text-capitalize">Divisi</label>
                            <input type="text" class="form-control" id="division" name="division" value="{{ $tendik->division }}" required>
                            @error('division')
                                <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name" class="text-capitalize">Nama Jabatan</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $tendik->name }}" required>
                            @error('name')
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
