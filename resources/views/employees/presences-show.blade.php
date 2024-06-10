<x-app-layout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Kehadiran - {{ $subject->name }}</h1>
        <x-app.session-notifications />
        <table class="border-collapse border">
            <tr>
                <td class="border p-2">Tanggal</td>
                <td class="border p-2">Status</td>
                <td class="border p-2">Foto</td>
                <td class="border p-2">Aksi</td>
            </tr>
            @forelse ($presences as $key => $presence)
                <tr>
                    <td class="border p-2">
                        @if ($isTodays[$key])
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
                    <td class="border p-2 text-center">
                        <form action="{{ route('employees.presence.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="kpi_period_id" value="{{ $kpi->id }}">
                            <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                            <input type="hidden" name="presence_id" value="{{ $presence->id }}">
                            {{-- <input type="hidden" name="users_has_subject_id"
                                value="{{ Auth::user()->subjects->where('subject_id', $subject->id)->firstOrFail()->id }}"> --}}
                            <input type="hidden" name="control" value="-">
                            <button class="btn bg-red-500 text-white hover:bg-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="p-2 text-center" colspan="4">Kosong</td>
                </tr>
            @endforelse
        </table>
        @if ($valid_kpi !== true)
            <div>
                <p class="text-center">{{ $valid_kpi }}, absensi belum bisa diisi</p>
                <p class="text-center">Tanggal KPI - ({{ \Carbon\Carbon::parse($kpi->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($kpi->end_date)->format('d/m/Y') }})</p>
            </div>
        @else
            <form action="{{ route('employees.presence.store') }}" method="post" enctype="multipart/form-data"
                class="flex flex-col gap-2">
                @csrf
                <input type="hidden" name="kpi_period_id" value="{{ $kpi->id }}">
                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                <input type="hidden" name="control" value="+">
                {{-- <input type="hidden" name="users_has_subject_id"
                    value="{{ Auth::user()->subjects->where('subject_id', $subject->id)->firstOrFail()->id }}"> --}}
                <label for="image">Gambar{{ $image_presence_setting->value == 'true' ? '*' : '' }}</label>
                <input type="file" name="image" id="image" accept="image/*"
                    {{ $image_presence_setting->value == 'true' ? 'required' : '' }}>
                <div>
                    <button class="btn bg-blue-500 text-white hover:bg-blue-600" {{ $valid_kpi !== true ? 'disabled' : '' }}>Absen Masuk</button>
                </div>
            </form>
            <div>
                <p class="text-center">Tanggal KPI ({{ \Carbon\Carbon::parse($kpi->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($kpi->end_date)->format('d/m/Y') }})</p>
            </div>
        @endif
    </div>
</x-app-layout>
