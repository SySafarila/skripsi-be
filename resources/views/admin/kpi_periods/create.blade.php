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
                    <h1 class="m-0">Buat Periode KPI</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.kpi.index') }}">KPI</a></li>
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
                    <form action="{{ route('admin.kpi.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title" class="text-capitalize">Judul</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                            @error('title')
                                <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="start_date" class="text-capitalize">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                            @error('start_date')
                                <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="end_date" class="text-capitalize">Tanggal Berakhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required disabled>
                            @error('end_date')
                                <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label for="is_active" class="text-capitalize">Status Aktif</label>
                                <select name="is_active" id="is_active" class="custom-select" required>
                                    <option value="1" selected>Aktif</option>
                                    <option value="0">Tidak</option>
                                </select>
                                @error('is_active')
                                    <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                                @enderror
                            </div>
                            <div class="form-group col">
                                <label for="receive_feedback" class="text-capitalize">Terima Masukan Mahasiswa</label>
                                <select name="receive_feedback" id="receive_feedback" class="custom-select" required>
                                    <option value="1">Ya</option>
                                    <option value="0" selected>Tidak</option>
                                </select>
                                @error('receive_feedback')
                                    <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="align-items-center d-flex form-check">
                                <input type="checkbox" class="form-check-input" id="increment_semester" name="increment_semester" value="1">
                                <label for="increment_semester" class="text-capitalize form-check-label">Semester + 1</label>
                                @error('increment_semester')
                                    <div class="text-sm text-danger">{{ $message ?? 'Something error' }}</div>
                                @enderror
                            </div>
                            <small>*Data semester pada setiap mahasiswa akan ditambah 1 atau (+1) saat periode KPI dibuat</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const start_date = document.querySelector('#start_date');
        const end_date = document.querySelector('#end_date');

        start_date.addEventListener('change', (e) => {
            e.preventDefault();
            const value = e.target.value;

            if (!value) {
                end_date.disabled = true;
                end_date.min = '';
                end_date.value = '';
            } else {
                end_date.disabled = false;
                end_date.min = value;
            }
        })
    </script>
@endsection
