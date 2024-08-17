@extends('layouts.adminlte', [
    'title' => 'Report',
])

@section('head')
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp">
@endsection

@section('content')
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <div class="content-header">
        <div class="container-fluid">
            <x-adminlte.session-notifications />
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <h1 class="m-0">Report {{ $kpi_id->title }}</h1>
                    <a href="#" class="btn btn-sm btn-primary ml-2" onclick="print()">Print</a>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">KPI Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content pb-3">
        <div class="container-fluid">
            <a href="{{ route('admin.kpi.report', ['kpi_id' => $kpi_id]) }}" class="btn btn-sm {{ request()->show != 'tendik' ? 'btn-primary' : 'btn-light border' }} mb-2">Karyawan</a>
            <a href="{{ route('admin.kpi.report', ['kpi_id' => $kpi_id, 'show' => 'tendik']) }}" class="btn btn-sm {{ request()->show == 'tendik' ? 'btn-primary' : 'btn-light border' }} mb-2">Kategori Tendik</a>
            <div class="card m-0">
                @if (request()->show != 'tendik')
                <div class="card-body table-responsive">
                    <h3>Dosen</h3>
                    @foreach ($users->where('tendik_position_id', 1) as $dosen)
                        <table class="table table-striped table-bordered">
                            <tr>
                                <td class="text-bold">Nama</td>
                                <td class="text-bold">{{ $dosen->name }}</td>
                            </tr>
                            <tr>
                                <td>Persentase Kehadiran</td>
                                <td>{{ @$dosen->points[0]->presence_points ? number_format(@$dosen->points[0]->presence_points, 2) : '0' }}%</td>
                            </tr>
                            <tr>
                                <td>Rata-rata poin feedback</td>
                                <td>5/{{ @$dosen->points[0]->feedback_points ? number_format(@$dosen->points[0]->feedback_points, 2) : '-' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">Detail rata-rata nilai feedback</td>
                            </tr>
                        </table>
                        <div id="{{ 'detail-feedback-user-' . $dosen->id }}">
                        </div>
                        <script>
                            axios.get("{{ route('admin.kpi.leaderboard.detail', ['user_id' => $dosen->id, 'kpi_id' => $kpi_id->id]) }}").then(res => {
                                parseQuestion(res.data, "{{ 'detail-feedback-user-' . $dosen->id }}")
                            })
                        </script>
                        <hr>
                    @endforeach

                    <h3>TenDik</h3>
                    @foreach ($users->where('tendik_position_id', '!=', 1) as $tendik)
                        <table class="table table-striped table-bordered">
                            <tr>
                                <td class="text-bold">Nama</td>
                                <td class="text-bold">{{ $tendik->name }}</td>
                            </tr>
                            <tr>
                                <td>Divisi TenDik</td>
                                <td>{{ $tendik->position->division }}</td>
                            </tr>
                            <tr>
                                <td>Persentase Kehadiran</td>
                                <td>{{ @$tendik->points[0]->presence_points ? number_format(@$tendik->points[0]->presence_points) : '0' }}%</td>
                            </tr>
                        </table>
                        <hr>
                    @endforeach
                </div>
                @else
                    <div class="card-body table-responsive">
                        <h3>Kategori Tendik</h3>
                        @foreach ($tendiks as $categoryTendik)
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <td class="text-bold">Nama</td>
                                    <td class="text-bold">{{ $categoryTendik->division }}</td>
                                </tr>
                                {{-- <tr>
                                    <td>Persentase Kehadiran</td>
                                    <td>{{ @$categoryTendik->points[0]->presence_points ?? '0' }}%</td>
                                </tr> --}}
                                <tr>
                                    <td>Rata-rata poin feedback</td>
                                    <td>5/{{ @$categoryTendik->points[0]->feedback_points ? number_format(@$categoryTendik->points[0]->feedback_points, 2) : '-' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">Detail rata-rata nilai feedback</td>
                                </tr>
                            </table>
                            <div id="{{ 'detail-feedback-user-' . $categoryTendik->id }}">
                            </div>
                            <script>
                                axios.get("{{ route('admin.kpi.leaderboard.detail', ['tendik_id' => $categoryTendik->id, 'kpi_id' => $kpi_id->id]) }}").then(res => {
                                    parseQuestion(res.data, "{{ 'detail-feedback-user-' . $categoryTendik->id }}")
                                })
                            </script>
                            <hr>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    const parseQuestion = (questions, target) => {
            let html = '';
            for (const key in questions) {
                if (questions[key].responses.length > 0) {
                    let point = 0;
                    const question = questions[key].question;
                    questions[key].responses.forEach(el => {
                        point += el.point
                    });
                    // html += `<p>${question}</p><p><b>Rata-rata poin</b>: ${parseFloat(point/questions[key].responses.length).toFixed(2)} (dari ${questions[key].responses.length} feedback)</p><hr>`
                    html += `<tr>
                                <td>${question}</td>
                                <td>${parseFloat(point/questions[key].responses.length).toFixed(2)}</td>
                            </tr>`
                }
            }

            if (html == '') {
                document.querySelector(`#${target}`).innerHTML = `<table class="table table-striped table-bordered"><tr><td>Data tidak ditemukan</td></tr></table>`
                // console.log(html);

            } else {
                document.querySelector(`#${target}`).innerHTML = `<table class="table table-striped table-bordered">${html}</table>`
                // console.log(html);

            }
        }
</script>
@endsection
