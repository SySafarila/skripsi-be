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
                    <h1 class="m-0">Performa {{ $user->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Karyawan & Dosen</a></li>
                        <li class="breadcrumb-item active">Performa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content pb-3">
        <div class="container-fluid">
            <div class="card m-0">
                <div class="card-body">
                    <canvas id="myChart"></canvas>
                    <h1 class="mt-3">Pencapaian</h1>

                    @if (count($achievements) == 0)
                        <p>Belum ada pencapaian</p>
                    @else
                        <ol class="">
                            @foreach ($achievements as $achievement)
                                <li>{{ $achievement->title ?? '-' }}</li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/chart-4.4.3.js') }}"></script>
    <script>
        const ctx = document.getElementById('myChart');
        const labels = [];
        const presencePoints = [];
        const feedbackPoints = [];
        const rawData = {{ Js::from($points) }}

        rawData.forEach(kpi => {
            const endDate = kpi.end_date;
            const date = new Date(endDate).getDate()
            const month = new Date(endDate).getMonth() + 1
            const year = new Date(endDate).getFullYear()
            labels.push(`${date}/${month}/${year}`)
            presencePoints.push(kpi.points[0]?.presence_points ?? undefined)
            feedbackPoints.push(kpi.points[0]?.feedback_points ?? undefined)
        });

        labels.reverse();
        presencePoints.reverse();
        feedbackPoints.reverse();

        while (labels.length < 5) {
            labels.push('-')
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Poin Kehadiran',
                    data: presencePoints,
                    borderWidth: 1,
                    pointRadius: 5,
                }, {
                    label: 'Poin Feedback',
                    data: feedbackPoints,
                    borderWidth: 1,
                    pointRadius: 5,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Performa dalam 5 periode KPI'
                    },
                    subtitle: {
                        display: true,
                        text: 'Diurutkan berdasarkan tanggal berakhirnya periode KPI'
                    }
                }
            }
        });
    </script>
@endsection
