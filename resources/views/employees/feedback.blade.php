<x-app-layout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Feedback</h1>
        <div class="flex flex-col">
            @forelse ($feedbacks as $key => $question)
                <div class="{{ $loop->index == 0 ? 'pb-2' : 'py-2' }} border-b">
                    <div class="flex flex-col">
                        <b>Kuesioner:</b>
                        <span>{{ $key }}</span>
                    </div>
                    <b>Jawaban:</b>
                    <div class="flex flex-col gap-2">
                        @foreach ($question as $feedback)
                            <div class="border-l-2 border-green-500 pl-2">
                                <p>{{ $feedback->message }}</p>
                                <p>Point: {{ $feedback->point }}/5 - {{ $feedback->created_at->format('d/m/Y') }}</p>
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
