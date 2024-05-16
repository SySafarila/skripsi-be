<x-app-layout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Kehadiran</h1>
        <table class="w-full border-collapse border">
            <tr>
                <th class="border p-2">Absensi</th>
                <th class="border p-2">Kehadiran</th>
                <th class="border p-2">Aksi</th>
            </tr>
            @foreach (Auth::user()->subjects()->with('subject')->get() as $user_has_subject)
                <tr>
                    <td class="border p-2">{{ $user_has_subject->subject->name }}</td>
                    <td class="border p-2">
                        {{ Auth::user()->presences->where('subject_id', $user_has_subject->subject_id)->where('kpi_period_id', $kpi->id)->count() }}/{{ $user_has_subject->quota }}
                        ({{ number_format((Auth::user()->presences->where('subject_id', $user_has_subject->subject_id)->where('kpi_period_id', $kpi->id)->count() *100) /$user_has_subject->quota,2) }}%)
                    </td>
                    <td class="border p-2">
                        <a href="{{ route('employees.presence.show', $user_has_subject->subject_id) }}"
                            class="block rounded bg-blue-500 px-2 py-1 text-center text-xs text-white hover:bg-blue-600">Absen
                            Masuk</a>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td class="border p-2">Total Kehadiran</td>
                @if (Auth::user()->presences->where('kpi_period_id', $kpi->id)->count() > 0)
                    <td colspan="2" class="border p-2">
                        {{ Auth::user()->presences->where('kpi_period_id', $kpi->id)->count() }}/{{ array_sum(Auth::user()->subjects->pluck('quota')->toArray()) }}
                        ({{ number_format((Auth::user()->presences->where('kpi_period_id', $kpi->id)->count() *100) /array_sum(Auth::user()->subjects->pluck('quota')->toArray()),2) }}%)
                    </td>
                @else
                    <td colspan="2" class="border p-2">0</td>
                @endif
            </tr>
        </table>
        <p class="text-center">KPI Aktif - <b>({{ \Carbon\Carbon::parse($kpi->start_date)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($kpi->end_date)->format('d/m/Y') }})</b></p>
    </div>
</x-app-layout>
