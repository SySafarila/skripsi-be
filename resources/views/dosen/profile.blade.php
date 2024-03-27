<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Periode KPI Aktif: {{ $kpi->title }} ({{ \Carbon\Carbon::parse($kpi->start_date)->format('d/m/Y') }} - {{
        \Carbon\Carbon::parse($kpi->end_date)->format('d/m/Y') }})</p>
    <h2>Daftar hadir - {{ $user->name }}</h2>
    <table border="1">
        <tr>
            <th>Mata Kuliah</th>
            <th>Kehadiran</th>
            <th>Aksi</th>
        </tr>
        @foreach ($user->subjects as $user_has_subject)
        <tr>
            <td>{{ $user_has_subject->subject->name }}</td>
            <td>{{ $presences->where('subject_id', $user_has_subject->subject_id)->count() }}/{{ $user_has_subject->quota }} ({{ number_format(($presences->where('subject_id', $user_has_subject->subject_id)->count() * 100) / $user_has_subject->quota, 2) }}%)</td>
            <td>
                <a href="{{ route('dosen.subject', $user_has_subject->subject_id) }}">Absen Masuk</a>
            </td>
        </tr>
        @endforeach
        <tr>
            <td>Total Hadir:</td>
            @if ($presences->count() > 0)
                <td colspan="2">{{ $presences->count() }}/{{ array_sum($user->subjects->pluck('quota')->toArray()) }} ({{ number_format(($presences->count() * 100)/ array_sum($user->subjects->pluck('quota')->toArray()), 2) }}%)</td>
            @else
                <td colspan="2">0</td>
            @endif
        </tr>
    </table>
    <p>Point: {{ $point ? number_format($point->points, 2) : '-' }} ({{ $point ? $point->updated_at->format('d/m/Y - H:i') : '-' }})</p>
</body>

</html>
