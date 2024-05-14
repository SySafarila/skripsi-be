@php
    function isToday($date)
    {
        $date = \Carbon\Carbon::parse($date)->format('d/m/Y');
        if ($date == now()->format('d/m/Y')) {
            return true;
        } else {
            return false;
        }
    }
@endphp
<x-app-layout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Kehadiran - {{ $subject->name }}</h1>
        <table class="border-collapse border">
            <tr>
                <td class="border p-2">Tanggal</td>
                <td class="border p-2">Status</td>
                <td class="border p-2">Foto</td>
                <td class="border p-2">Aksi</td>
            </tr>
            @foreach ($presences as $presence)
                <tr>
                    <td class="border p-2">
                        @if (isToday($presence->created_at))
                            Hari ini - {{ $presence->created_at->format('H:i') }}
                        @else
                            {{ $presence->created_at->format('d/m/Y - H:i') }}
                        @endif
                    </td>
                    <td class="border p-2 capitalize">{{ $presence->status }}</td>
                    <td class="border p-2">
                        @if ($presence->image)
                            <a href="{{ asset('storage/' . $presence->image) }}"
                                class="block text-center text-blue-500 underline">Lihat</a>
                        @else
                            <span class="block text-center">-</span>
                        @endif
                    </td>
                    <td class="border p-2">
                        <form action="{{ route('presence.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="kpi_period_id" value="{{ $kpi->id }}">
                            <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                            <input type="hidden" name="presence_id" value="{{ $presence->id }}">
                            <input type="hidden" name="users_has_subject_id" value="{{ $userHasSubjectId }}">
                            <input type="hidden" name="control" value="-">
                            <button
                                class="block w-full rounded bg-red-500 px-2 py-1 text-center text-white hover:bg-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        <form action="{{ route('presence.store') }}" method="post" enctype="multipart/form-data"
            class="flex flex-col gap-2">
            @csrf
            <input type="hidden" name="kpi_period_id" value="{{ $kpi->id }}">
            <input type="hidden" name="subject_id" value="{{ $subject->id }}">
            <input type="hidden" name="control" value="+">
            <input type="hidden" name="users_has_subject_id" value="{{ $userHasSubjectId }}">
            <label for="image">Gambar</label>
            <input type="file" name="image" id="image" accept="image/*"
                {{ $image_presence_setting->value == 'true' ? 'required' : '' }}>
            <div>
                <button class="rounded bg-blue-500 px-2 py-1 text-white hover:bg-blue-600">Absen Masuk</button>
            </div>
        </form>
    </div>
</x-app-layout>
