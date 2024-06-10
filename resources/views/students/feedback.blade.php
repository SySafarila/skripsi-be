<x-app-layout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Kirim Feedback</h1>
        @if (!$active_kpi->receive_feedback)
            <x-app.session-notifications type="warning" message="Pengisian feedback/masukan belum dibuka." />
        @endif
        <div class="rounded-md border p-4">
            @if (@$course)
                <table>
                    <tr>
                        <td class="font-semibold">Mata Kuliah</td>
                        <td class="px-2">:</td>
                        <td>{{ $course->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Dosen</td>
                        <td class="px-2">:</td>
                        <td>{{ $course->user->name }}</td>
                    </tr>
                </table>
            @else
                <table>
                    <tr>
                        <td class="font-semibold">Bagian</td>
                        <td class="px-2">:</td>
                        <td>{{ $tendik_position->division }}</td>
                    </tr>
                </table>
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
                    <div class="pl-6 pt-2">
                        <textarea name="messages[{{ $key }}]" id="{{ $key }}-{{ $question->question }}"
                            class="w-full rounded-md" placeholder="Tulis masukan kamu disini..." required
                            {{ !$active_kpi->receive_feedback ? ' disabled' : '' }}>{{ $question->responses[0]->message ?? '' }}</textarea>
                        {{-- <input type="number" name="points[{{ $key }}]" id="" placeholder="1-5" min="1" max="5" class="rounded-md" value="{{ $question->responses[0]->point ?? '' }}" required> --}}
                        <span>Point:</span>
                        <div class="flex flex-col gap-2 lg:flex-row lg:gap-4">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="points[{{ $key }}]"
                                        id="{{ $key }}-{{ $i }}" value="{{ $i }}"
                                        {{ @$question->responses[0]->point == $i ? 'checked' : '' }}
                                        {{ !$active_kpi->receive_feedback ? ' disabled' : '' }}>
                                    <label for="{{ $key }}-{{ $i }}">{{ $i }}
                                        Point</label>
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
                    {{ !$active_kpi->receive_feedback || $valid_kpi !== true ? ' disabled' : '' }}>Save</button>
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
