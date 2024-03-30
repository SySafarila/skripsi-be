<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Halaman Mahasiswa</h1>
    <a href="{{ route('student.index') }}">Halaman Utama</a>
    <a href="{{ route('student.courses') }}">Survey</a>
    <hr>
    <h2>Mata Kuliah - {{ $course->name }} - {{ $course->user->name }}</h2>

    <form action="{{ route('student.store', $course) }}" method="post">
        @csrf
        @foreach ($questions as $question)
            <div>
                <div>
                    <p>{{ $n++ }}. {{ $question->question }}</p>
                </div>
                <textarea name="messages[]" id="" cols="30" rows="10" required>{{ $question->responses[0]->message ?? '' }}</textarea>
                <br>
                <input type="number" name="points[]" id="" placeholder="1-5" min="1" max="5" value="{{ $question->responses[0]->point ?? '' }}" required>
                <input type="hidden" name="question_ids[]" value="{{ $question->id }}" required>
                <input type="hidden" name="questions[]" value="{{ $question->question }}" required>
            </div>
        @endforeach
        <button type="submit">Save</button>
    </form>
</body>
</html>
