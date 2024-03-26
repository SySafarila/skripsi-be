<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>Leaderboard {{ $kpi->title }} ({{ \Carbon\Carbon::parse($kpi->start_date)->format('d-m-Y') }} - {{
        \Carbon\Carbon::parse($kpi->end_date)->format('d-m-Y') }})</h1>
    <table border="1">
        <tr>
            <td>#</td>
            <td>Nama</td>
            <td>Point</td>
        </tr>
        @foreach ($points as $point)
        <tr>
            <td>{{ $n++ }}</td>
            <td>{{ $point->user->name ?? '-' }} (@foreach ($point->user->roles as $role)
                <span>{{ $role->name }} </span>
                @endforeach)
            </td>
            <td>{{ $point->points }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>
