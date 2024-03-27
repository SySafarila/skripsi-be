<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Periode KPI: {{ $kpi->title }} ({{ \Carbon\Carbon::parse($kpi->start_date)->format('d/m/Y') }} - {{
        \Carbon\Carbon::parse($kpi->end_date)->format('d/m/Y') }})</p>
    <h2>Daftar hadir - {{ $user->name }}</h2>
    <table border="1">
        <tr>
            <th>Mata Kuliah</th>
            <th>Kehadiran</th>
            <th>Aksi</th>
        </tr>
        @foreach ($user->subjects as $subject)
        <tr>
            <td>{{ $subject->subject->name }}</td>
            <td>{{ $presences->where('subject_id', $subject->subject_id)->count() }}/{{ $subject->quota }} ({{
                ($presences->where('subject_id', $subject->subject_id)->count() * 100) / $subject->quota }}%)</td>
            <td>
                <a href="{{ route('dosen.subject', $subject->id) }}">Absen Masuk</a>
            </td>
        </tr>
        @endforeach
        <tr>
            <td>Total Hadir:</td>
            <td colspan="2">{{ $presences->count() }}/{{ array_sum($user->subjects->pluck('quota')->toArray()) }} ({{
                ($presences->count() * 100)/ array_sum($user->subjects->pluck('quota')->toArray()) }}%)</td>
        </tr>
    </table>
    <p>Point: {{ $point->points }} ({{ $point->updated_at->format('d/m/Y - H:i') }})</p>
</body>

</html>
