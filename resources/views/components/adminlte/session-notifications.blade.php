@if (session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{-- <h5><i class="icon fas fa-check"></i> Alert!</h5> --}}
        {{ session('success') }}
    </div>
@endif
@if (session('warning'))
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{-- <h5><i class="icon fas fa-check"></i> Alert!</h5> --}}
        {{ session('warning') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{-- <h5><i class="icon fas fa-check"></i> Alert!</h5> --}}
        {{ session('error') }}
    </div>
@endif
