<x-app-layout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Kirim Feedback</h1>
        @if (!$active_kpi->receive_feedback)
            <x-app.session-notifications type="warning" message="Pengisian feedback/masukan belum dibuka." />
        @endif
        <div class="rounded-md border p-4">
            @if (@$course)
                <div class="flex flex-col rounded-md">
                    <div class="flex items-center gap-2">
                        <img src="{{ $course->user->image ? asset('storage/' . $course->user->image) : asset('images/profile.png') }}" class="w-10 h-10 rounded-full" alt="Photo profile">
                        <div class="flex flex-col w-full">
                            <span class="font-semibold line-clamp-1 break-all">{{ $course->user->name }}</span>
                            <span class="-mt-1 flex justify-between items-center">
                                <span class="w-full line-clamp-1 break-all">{{ $course->name }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex flex-col rounded-md">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('icons/division.svg') }}" class="w-10 h-10 bg-black rounded-full p-1.5" alt="Photo profile">
                        <div class="flex flex-row justify-between items-center w-full">
                            <span class="font-semibold line-clamp-2 leading-tight">{{ $tendik_position->division }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <form action="{{ @$course ? route('student.store', $course) : route('student.store.nonedu', $tendik_position->id) }}"
            method="post" class="flex flex-col gap-4">
            @csrf
            @foreach ($questions as $key => $question)
                <div>
                    <div class="flex gap-2">
                        <span class="w-4 flex-shrink-0">{{ $n++ }}.</span>
                        <label for="{{ $key }}-{{ $question->question }}">{{ $question->question }}</label>
                    </div>
                    <div class="pl-6 pt-2 flex flex-col gap-2">
                        <div class="flex flex-col gap-1">
                            <textarea name="messages[{{ $key }}]" id="{{ $key }}-{{ $question->question }}"
                                class="w-full rounded-md" placeholder="Tulis masukan kamu disini..." required
                                {{ !$active_kpi->receive_feedback ? ' disabled' : '' }}>{{ $question->responses[0]->message ?? '' }}</textarea>
                            <small>Isi "-" jika tidak ingin mengirim masukan</small>
                        </div>
                        <div class="flex flex-col gap-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="points[{{ $key }}]"
                                        id="{{ $key }}-{{ $i }}" value="{{ $i }}"
                                        {{ @$question->responses[0]->point == $i ? 'checked' : '' }}
                                        {{ !$active_kpi->receive_feedback ? ' disabled' : '' }}>
                                    <label for="{{ $key }}-{{ $i }}">{{ $points_detail[$i -1 ] }}</label>
                                </div>
                            @endfor
                        </div>
                    </div>
                    <input type="hidden" name="question_ids[{{ $key }}]" value="{{ $question->id }}"
                        required>
                    <input type="hidden" name="questions[{{ $key }}]" value="{{ $question->question }}"
                        required>
                </div>
            @endforeach
            <div class="pl-6">
                <button type="submit" class="btn bg-blue-500 text-white hover:bg-blue-600"
                    {{ !$active_kpi->receive_feedback || $valid_kpi !== true ? ' disabled' : '' }}>Kirim</button>
            </div>
        </form>
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
