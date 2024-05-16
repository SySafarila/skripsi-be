<x-app-layout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Kirim Feedback</h1>
        <div class="rounded-md border p-4">
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
        </div>
        <form action="{{ route('student.store', $course) }}" method="post" class="flex flex-col gap-4">
            @csrf
            @foreach ($questions as $key => $question)
                <div>
                    <div class="flex gap-2">
                        <span class="w-4">{{ $n++ }}.</span>
                        <p>{{ $question->question }}</p>
                    </div>
                    <div class="pl-6 pt-2">
                        <textarea name="messages[{{ $key }}]" id="" class="w-full rounded-md"
                            placeholder="Tulis masukan kamu disini..." required>{{ $question->responses[0]->message ?? '' }}</textarea>
                        {{-- <input type="number" name="points[{{ $key }}]" id="" placeholder="1-5" min="1" max="5" class="rounded-md" value="{{ $question->responses[0]->point ?? '' }}" required> --}}
                        <span>Point:</span>
                        <div class="flex flex-col lg:flex-row lg:gap-4">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="points[{{ $key }}]"
                                        id="{{ $key }}-{{ $i }}" value="{{ $i }}"
                                        {{ @$question->responses[0]->point == $i ? 'checked' : '' }}>
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
                <button type="submit"
                    class="rounded-md bg-blue-500 px-2 py-1 text-white hover:bg-blue-600">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
