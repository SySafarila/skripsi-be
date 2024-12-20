<x-app-layout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Leaderboard</h1>
        <form class="flex flex-wrap justify-center gap-2 lg:justify-start">
            <div class="flex flex-grow flex-col gap-1.5 lg:flex-grow-0">
                <label for="kpi_period_id">Periode KPI</label>
                <select name="kpi_period_id" id="kpi_period_id"
                    onchange="event.preventDefault(); this.closest('form').submit();" class="rounded-md">
                    @foreach ($kpis as $kpi_loop)
                        <option value="{{ $kpi_loop->id }}" {{ $kpi->id == $kpi_loop->id ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($kpi_loop->start_date)->format('d/m/Y') }}{{ $kpi_loop->is_active ? ' (aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-grow flex-col gap-1.5 lg:flex-grow-0">
                <label for="filter">Kategori</label>
                <select name="filter" id="filter" onchange="event.preventDefault(); this.closest('form').submit();"
                    class="rounded-md">
                    {{-- <option value="all" {{ request()->filter == 'all' ? 'selected' : '' }}>Semua Karyawan
                    </option> --}}
                    <option value="dosen" {{ request()->filter == 'dosen' || !request()->filter ? 'selected' : '' }}>
                        Dosen</option>
                    <option value="tendik" {{ request()->filter == 'tendik' ? 'selected' : '' }}>Tendik</option>
                </select>
            </div>
        </form>
        {{-- <div class="mt-2 flex items-start gap-2 rounded-md p-2 bg-yellow-300">
            <img src="{{ asset('icons/warning.svg') }}" alt="warning">
            <span class="break-words">Periode KPI yang berlangsung akan berakhir dalam <b>9 Hari</b></span>
        </div> --}}
        <div class="flex flex-col divide-y">
            @forelse ($points as $point)
                <div class="py-4" id="user-points">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @if ($point->points > 0)
                                @if ($loop->index > 0 && $points[$loop->index - 1]->points > $point->points)
                                    @php
                                        $n++;
                                    @endphp
                                @endif
                                @switch($n)
                                    @case(1)
                                        <img src="{{ asset('icons/number-one.svg') }}" alt="" class="h-8 w-8">
                                    @break

                                    @case(2)
                                        <img src="{{ asset('icons/number-two.svg') }}" alt="" class="h-8 w-8">
                                    @break

                                    @case(3)
                                        <img src="{{ asset('icons/number-three.svg') }}" alt="" class="h-8 w-8">
                                    @break

                                    @default
                                        <div class="flex h-8 w-8 items-center justify-center font-semibold">{{ $n }}
                                        </div>
                                @endswitch
                            @else
                                <div class="flex h-8 w-8 items-center justify-center font-semibold">0</div>
                            @endif
                            <img src="{{ $point->user->image ? asset('storage/' . $point->user->image) : asset('images/profile.png') }}"
                                alt="profile" class="h-10 w-10 rounded-full">
                            <div class="flex flex-col">
                                <div class="flex flex-col">
                                    <a href="{{ route('employees.profile.show', $point->user->id) }}"
                                        class="line-clamp-1 font-semibold">{{ $point->user->name ?? '-' }}</a>
                                </div>
                            </div>
                        </div>
                        <button class="flex cursor-pointer items-center gap-2 rounded-md px-2 py-1 hover:bg-gray-100" id="expand-points">
                            <span>{{ number_format($point->points, 2) }} Poin</span>
                            <img src="{{ asset('icons/chevron.svg') }}" class="transition-transform ease-in-out"
                                alt="arrow">
                        </button>
                    </div>
                    <div class="flex hidden flex-col pr-10" id="detail-points">
                        <div class="flex items-center justify-end gap-2">
                            <span>Kehadiran</span>
                            <span>-</span>
                            <span>{{ number_format($point->presence_points, 2) }} Poin</span>
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <span>Umpan Balik</span>
                            <span>-</span>
                            <span>{{ number_format($point->feedback_points, 2) }} Poin</span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center lg:text-start">
                    Data tidak tersedia
                </p>
            @endforelse
        </div>
    </div>
</x-app-layout>
