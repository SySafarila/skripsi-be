@php
    function rolesProcessor($roles) {
        $string = '';
        foreach ($roles as $role) {
            $string = $role->name;
        }
        return $string;
    }
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div>
        <a href="{{ route('profile') }}">Profil</a>
        <a href="{{ route('leaderboard.index') }}">Leaderboard</a>
    </div>
    <br>
    <form action="">
        <select name="kpi_period_id" id="kpi_period_id">
            @foreach ($kpis as $kpi_loop)
                <option value="{{ $kpi_loop->id }}" {{ $kpi->id == $kpi_loop->id ? 'selected' : '' }}>{{ \Carbon\Carbon::parse($kpi_loop->start_date)->format('d/m/Y') }}{{ $kpi_loop->is_active ? ' (aktif)' : '' }}</option>
            @endforeach
        </select>
        <button type="submit">Filter</button>
    </form>
    <h1>Leaderboard {{ $kpi->title }} ({{ \Carbon\Carbon::parse($kpi->start_date)->format('d/m/Y') }} - {{
        \Carbon\Carbon::parse($kpi->end_date)->format('d/m/Y') }})</h1>
    <table border="1">
        <tr>
            <td>#</td>
            <td>Nama</td>
            <td>Point</td>
        </tr>
        @foreach ($points as $point)
        <tr>
            <td>{{ $points[0]->points == 0 ? '-' : $n++ }}</td>
            <td>{{ $point->user->name ?? '-' }} ({{ rolesProcessor($point->user->roles) }})
            </td>
            <td>{{ number_format($point->points, 2) }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>
