<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Halaman Feedback Mahasiswa</h1>
    <hr>
    <h2>Mata Kuliah</h2>
    <table border="1">
        <tr>
            <th>#</th>
            <th>Mata Kuliah</th>
            <th>Dosen</th>
            <th>Feedback</th>
        </tr>
        @foreach ($courses as $course)
            <tr>
                <td>{{ $n++ }}</td>
                <td>{{ $course->name }}</td>
                <td>{{ $course->user->name }}</td>
                <td>
                    @if ($sent_feedbacks->where('course_id', $course->id)->count() == 0)
                        <a href="#">Beri Masukan</a>
                    @else
                        <a href="#">Lihat Masukan</a>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</body>
</html>
