@extends('layouts.adminlte')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Home</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ count($users) }}</h3>

                            <p>Users</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ count($roles) }}
                                {{-- <sup style="font-size: 20px">%</sup> --}}
                            </h3>

                            <p>Roles</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <a href="{{ route('admin.roles.index') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ count($permissions) }}</h3>

                            <p>Permissions</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-low-vision"></i>
                        </div>
                        <a href="{{ route('admin.permissions.index') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @if (Route::has('admin.blogs.index'))
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ count($blogs) }}</h3>

                                <p>Blogs</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-feather-alt"></i>
                            </div>
                            <a href="{{ route('admin.blogs.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                @endif
            </div>
            <div class="row">
                <div class="card w-100 mx-2">
                    <div class="card-body">
                        <canvas id="usersChart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card w-100 mx-2">
                    <div class="card-body">
                        <canvas id="kpi_chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- ChartJS -->
    <script src="{{ asset('js/chart-4.4.3.js') }}"></script>
    <script>
        $(function() {
            var areaChartData = {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                    'Oktober', 'November', 'Desember'
                ],
                datasets: [{
                        label: new Date().getFullYear(),
                        // backgroundColor: '#17a2b8',
                        // borderColor: 'rgba(60,141,188,0.8)',
                        // pointRadius: false,
                        // pointColor: '#3b8bba',
                        // pointStrokeColor: 'rgba(60,141,188,1)',
                        // pointHighlightFill: '#fff',
                        // pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: {{ Js::from($userResult) }},
                        borderWidth: 1,
                        pointRadius: 5,
                    },
                    // {
                    //     label: 'Electronics',
                    //     backgroundColor: 'rgba(210, 214, 222, 1)',
                    //     borderColor: 'rgba(210, 214, 222, 1)',
                    //     pointRadius: false,
                    //     pointColor: 'rgba(210, 214, 222, 1)',
                    //     pointStrokeColor: '#c1c7d1',
                    //     pointHighlightFill: '#fff',
                    //     pointHighlightStroke: 'rgba(220,220,220,1)',
                    //     data: [65, 59, 80, 81, 56, 55, 40]
                    // },
                ]
            }

            var barChartCanvas = $('#usersChart').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)
            var temp0 = areaChartData.datasets[0]
            // var temp1 = areaChartData.datasets[1]
            // barChartData.datasets[0] = temp1
            barChartData.datasets[0] = temp0

            var options = {
                responsive: true,
                // maintainAspectRatio: false,
                // datasetFill: false
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Data seluruh pengguna'
                    },
                    subtitle: {
                        display: true,
                        text: 'Diurutkan berdasarkan bulan'
                    }
                }
            }

            new Chart(barChartCanvas, {
                type: 'line',
                data: barChartData,
                options: options
            })
        })
    </script>
    <script>
        const ctx = document.getElementById('kpi_chart');
        const labels = [];
        const presencePoints = [];
        const feedbackPoints = [];
        const feedbackCount = [];
        const rawData = {{ Js::from($kpis) }}

        rawData.forEach(kpi => {
            const endDate = kpi.end_date;
            const date = new Date(endDate).getDate()
            const month = new Date(endDate).getMonth() + 1
            const year = new Date(endDate).getFullYear()
            labels.push(`${date}/${month}/${year}`)

            let presenceSum = 0;
            let feedbackSum = 0;
            kpi.points.forEach(point => {
                presenceSum = presenceSum + point.presence_points;
                feedbackSum = feedbackSum + point.feedback_points;
            });

            presencePoints.push(presenceSum)
            feedbackPoints.push(feedbackSum)
            feedbackCount.push(kpi.feedbacks_count ?? undefined)
        });

        labels.reverse();
        presencePoints.reverse();
        feedbackPoints.reverse();
        feedbackCount.reverse();

        while (labels.length < 12) {
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
                }, {
                    label: 'Jumlah Feedback',
                    data: feedbackCount,
                    borderWidth: 1,
                    pointRadius: 5
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
                        text: 'Statistik KPI'
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
