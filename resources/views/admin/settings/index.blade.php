@extends('layouts.adminlte')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <x-adminlte.session-notifications />
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengaturan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Pengaturan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card m-0">
                <div class="card-body">
                    <form action="{{ route('admin.settings.update', 'update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="" class="text-capitalize d-block">Absensi Dengan Foto</label>
                            <div class="align-items-center d-flex" style="gap: 14px;">
                                <div class="align-items-center d-flex" style="gap: 5px;">
                                    <input type="radio" name="image_presence" id="image_presence_true" value="1"
                                        {{ $image_presence->value === 'true' ? 'checked' : '' }}>
                                    <label class="font-weight-normal m-0" for="image_presence_true">YA</label>
                                </div>
                                <div class="align-items-center d-flex" style="gap: 5px;">
                                    <input type="radio" name="image_presence" id="image_presence_false" value="0"
                                        {{ $image_presence->value === 'false' ? 'checked' : '' }}>
                                    <label class="font-weight-normal m-0" for="image_presence_false">TIDAK</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="text-capitalize d-block">Pengaturan Semester per Mahasiswa</label>
                            <div class="d-flex flex-column" style="gap: 4px;">
                                <div class="align-items-center d-flex" style="gap: 5px;">
                                    <input type="radio" name="semester_settings" id="semester_settings_netral" value="n" required checked>
                                    <label class="font-weight-normal m-0" for="semester_settings_netral">Tetap</label>
                                </div>
                                <div class="align-items-center d-flex" style="gap: 5px;">
                                    <input type="radio" name="semester_settings" id="semester_settings_+" value="+" required>
                                    <label class="font-weight-normal m-0" for="semester_settings_+">Tambah 1 / (+1)</label>
                                </div>
                                <div class="align-items-center d-flex" style="gap: 5px;">
                                    <input type="radio" name="semester_settings" id="semester_settings_-" value="-" required>
                                    <label class="font-weight-normal m-0" for="semester_settings_-">Kurangi 1 / (-1)</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
