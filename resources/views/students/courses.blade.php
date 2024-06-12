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
                    <td class="border p-2">{{ $course->user->name ?? '-' }}</td>
                    <td class="border p-2 text-center">
                        <a href="{{ route('student.courses.feedback', ['course_id' => $course->id]) }}"
                            class="btn whitespace-nowrap bg-blue-500 text-white hover:bg-blue-600">Masukan
                            {{ $sent_feedbacks->where('course_id', $course->id)->count() }}/{{ $eduQuestions->count() }}</a>
                    </td>
                </tr>
            @endforeach
            @empty($courses->count())
                <tr>
                    <td class="border p-2 text-center" colspan="4">Kosong</td>
                </tr>
            @endempty
        </table>
        <table class="w-full border-collapse border">
            <tr>
                <th class="border p-2">#</th>
                <th class="border p-2 text-left">Bagian</th>
                <th class="border p-2">Feedback</th>
            </tr>
            @foreach ($nonEduQuestions as $tendik_position_id)
                @foreach ($tendik_position_id as $key => $nonEduQuestion)
                    @if ($key == 0)
                        <tr>
                            <td class="border p-2 text-center">{{ $nn++ }}</td>
                            <td class="border p-2">{{ $nonEduQuestion->to->division }}</td>
                            <td class="border p-2 text-center">
                                <a href="{{ route('student.courses.feedback.nonedu', ['tendik_position_id' => $nonEduQuestion->to->id]) }}"
                                    class="btn whitespace-nowrap bg-blue-500 text-white hover:bg-blue-600">Masukan
                                    {{ $sent_feedbacks->where('tendik_position_id', $nonEduQuestion->to->id)->count() }}/{{ $tendik_position_id->where('tendik_position_id', $nonEduQuestion->to->id)->count() }}</a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </table>
        @if ($valid_kpi !== true)
            <div>
                <p class="text-center">Tanggal KPI ({{ \Carbon\Carbon::parse($active_kpi->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($active_kpi->end_date)->format('d/m/Y') }})</p>
            </div>
        @else
            <div>
                <p class="text-center">Tanggal KPI ({{ \Carbon\Carbon::parse($active_kpi->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($active_kpi->end_date)->format('d/m/Y') }})</p>
            </div>
        @endif
    </div>
</x-app-layout>
