<x-app-layout>
    <div class="flex flex-col gap-4">
        <x-app.session-notifications />
        <h1 class="text-2xl font-bold">Feedback</h1>
        <div class="-mt-2 flex flex-col gap-2">
            <h2 class="text-xl font-bold">Dosen / Mata Kuliah</h2>
            <div class="divide-y">
                @foreach ($courses as $course)
                    <a href="{{ route('student.courses.feedback', ['course_id' => $course->id]) }}" class="flex flex-col p-2 rounded-md hover:bg-gray-100">
                        <div class="flex items-center gap-2">
                            <img src="{{ @$course->user->image ? asset('storage/' . @$course->user->image) : asset('images/profile.png') }}" class="w-10 h-10 rounded-full" alt="Photo profile">
                            <div class="flex items-center justify-between w-full">
                                <div class="flex flex-col pr-5 w-full">
                                    <span class="font-semibold line-clamp-1 break-all">{{ $course->user->name ?? '-' }}</span>
                                    <span class="w-full line-clamp-1 break-all">{{ $course->name }}</span>
                                </div>
                                <small class="shrink-0 line-clamp-1 break-all"><b>{{ $eduQuestions->count() }}/{{ $sent_feedbacks->where('course_id', $course->id)->count() }}</b> terkirim</small>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <h2 class="text-xl font-bold">TenDik / Divisi</h2>
            <div class="divide-y">
                @foreach ($nonEduQuestions as $tendik_position_id)
                    @foreach ($tendik_position_id as $key => $nonEduQuestion)
                        @if ($key == 0)
                            <a href="{{ route('student.courses.feedback.nonedu', ['tendik_position_id' => $nonEduQuestion->to->id]) }}" class="flex flex-col p-2 rounded-md hover:bg-gray-100">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('icons/division.svg') }}" class="w-10 h-10 bg-black rounded-full p-1.5" alt="Photo profile">
                                    <div class="flex flex-row justify-between items-center w-full">
                                        <span class="font-semibold line-clamp-2 leading-tight pr-5 w-full">{{ $nonEduQuestion->to->division }}</span>
                                        <small class="line-clamp-1 shrink-0 break-all"><b>{{ $tendik_position_id->where('tendik_position_id', $nonEduQuestion->to->id)->count() }}/{{ $sent_feedbacks->where('tendik_position_id', $nonEduQuestion->to->id)->count() }}</b> terkirim</small>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
        @if ($valid_kpi !== true)
            <div class="flex flex-col items-center">
                <p>{{ $valid_kpi }}</p>
                <p class="text-center">Tanggal KPI
                    ({{ \Carbon\Carbon::parse($active_kpi->start_date)->format('d/m/Y') }} -
                    {{ \Carbon\Carbon::parse($active_kpi->end_date)->format('d/m/Y') }})</p>
            </div>
        @else
            <div>
                <p class="text-center">Tanggal KPI
                    ({{ \Carbon\Carbon::parse($active_kpi->start_date)->format('d/m/Y') }} -
                    {{ \Carbon\Carbon::parse($active_kpi->end_date)->format('d/m/Y') }})</p>
            </div>
        @endif
    </div>
</x-app-layout>
