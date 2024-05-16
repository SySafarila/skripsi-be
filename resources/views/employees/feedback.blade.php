<x-app-layout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Feedback</h1>
        <div class="flex flex-col">
            @forelse ($feedbacks as $key => $question)
                <div class="{{ $loop->index == 0 ? 'pb-2' : 'py-2' }} {{ $loop->last ? '' : 'border-b' }} flex flex-col gap-2"
                    id="question">
                    <div class="flex items-start justify-between gap-4">
                        {{-- <b>Kuesioner:</b> --}}
                        <span class="pt-1">{{ $key }}</span>
                        <button
                            class="flex h-fit flex-shrink-0 cursor-pointer items-center gap-2 rounded-md px-2 py-1 hover:bg-gray-100"
                            onclick="event.preventDefault(); this.closest('#question').querySelector('#responses').classList.toggle('hidden'); this.querySelector('img').classList.toggle('rotate-180')">
                            <span>{{ count($question) }} Respon</span>
                            <img src="{{ asset('icons/chevron.svg') }}" alt="arrow"
                                class="w-6 transition-all ease-in-out">
                        </button>
                    </div>
                    {{-- <b>Jawaban:</b> --}}
                    <div class="flex hidden flex-col gap-2" id="responses">
                        @foreach ($question as $feedback)
                            <div class="rounded-md border p-2 pb-3">
                                <div class="flex items-center justify-between gap-4">
                                    <p>"{{ $feedback->message }}"</p>
                                    <span class="flex-shrink-0 text-xs">Point: {{ $feedback->point }}/5</span>
                                </div>
                                <p class="text-xs">dikirim pada {{ $feedback->created_at->format('d/m/Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p>Belum ada feedback</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
