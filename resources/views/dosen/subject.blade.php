@php
    function isToday($date) {
        $date = \Carbon\Carbon::parse($date)->format('d/m/Y');
        if ($date == now()->format('d/m/Y')) {
            return true;
        } else {
            return false;
        }
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
    <h1>Daftar Hadir - {{ $subject->name }}</h1>
    <table border="1">
        <tr>
            <td>Tanggal</td>
            <td>Status</td>
            <td>Foto</td>
            <td>Aksi</td>
        </tr>
        @foreach ($presences as $presence)
        <tr>
            <td>{{ $presence->created_at->format('d/m/Y - H:i') }} {{ isToday($presence->created_at) ? '(Hari Ini)': '' }}</td>
            <td>{{ $presence->status }}</td>
            <td>
                @if ($presence->image)
                <a href="{{ asset('storage/' . $presence->image) }}">Lihat</a>
                @else
                <span>-</span>
                @endif
            </td>
            <td>
                <form action="{{ route('presence.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="kpi_period_id" value="{{ $kpi->id }}">
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    <input type="hidden" name="presence_id" value="{{ $presence->id }}">
                    <input type="hidden" name="control" value="-">
                    <button>Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    <br>
    <form action="{{ route('presence.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="kpi_period_id" value="{{ $kpi->id }}">
        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
        <input type="hidden" name="control" value="+">
        <input type="hidden" name="users_has_subject_id" value="{{ $userHasSubjectId }}">
        <input type="file" name="image" id="image" accept="image/*">
        <br>
        <button>Absen Masuk</button>
    </form>

</body>

</html>
