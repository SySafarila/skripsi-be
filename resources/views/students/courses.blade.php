<x-app-layout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Feedback</h1>
        <x-app.session-notifications />
        <table class="w-full border-collapse border">
            <tr>
                <th class="border p-2">#</th>
                <th class="border p-2 text-left">Mata Kuliah</th>
                <th class="border p-2 text-left">Dosen</th>
                <th class="border p-2">Feedback</th>
            </tr>
            @foreach ($courses as $course)
                <tr>
                    <td class="border p-2 text-center">{{ $n++ }}</td>
                    <td class="border p-2">{{ $course->name }}</td>
                    <td class="border p-2">{{ $course->user->name }}</td>
                    <td class="border p-2 text-center">
                        @if ($sent_feedbacks->where('course_id', $course->id)->count() == 0)
                            <a href="{{ route('student.courses.feedback', ['course_id' => $course->id]) }}"
                                class="btn whitespace-nowrap bg-blue-500 text-white hover:bg-blue-600">Masukan
                                {{ $sent_feedbacks->where('course_id', $course->id)->count() }}/{{ $questions->count() }}</a>
                        @else
                            <a href="{{ route('student.courses.feedback', ['course_id' => $course->id]) }}"
                                class="btn whitespace-nowrap bg-blue-500 text-white hover:bg-blue-600">Masukan
                                {{ $sent_feedbacks->where('course_id', $course->id)->count() }}/{{ $questions->count() }}</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</x-app-layout>
