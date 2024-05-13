@php
function rolesProcessor($roles) {
$string = '';
foreach ($roles as $role) {
$string = $role->name;
}
return $string;
}
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    {{--
    <link rel="stylesheet" href="../dist/tailwind.css"> --}}
    @vite(['resources/css/app.css'])
</head>

<body>
    <nav id="navbar">
        <div class="max-w-screen-lg w-full mx-auto flex items-center justify-between px-4">
            <a href="#" id="brand" class="font-bold">BRAND</a>
            <button type="button" class="lg:hidden" id="hamburger">
                <img src="{{ asset('icons/menu.svg') }}" alt="menu">
            </button>
        </div>
    </nav>
    <main>
        <div id="sidebar">
            <a href="{{ route('profile') }}" class="account">
                <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('images/profile.png') }}"
                    alt="profile" class="w-10 h-10 rounded-full flex-shrink-0">
                <div class="flex flex-col w-full">
                    <span class="font-bold line-clamp-1 break-all">{{ Auth::user()->name }}</span>
                    @role('dosen')
                    <small class="line-clamp-1">NIDN: {{ Auth::user()->identifier_number }}</small>
                    @endrole
                    @role('mahasiswa')
                    <small class="line-clamp-1">NIM: {{ Auth::user()->identifier_number }}</small>
                    @endrole
                    @role('staff')
                    <small class="line-clamp-1">NIP: {{ Auth::user()->identifier_number }}</small>
                    @endrole
                    @role('tendik')
                    <small class="line-clamp-1">NIP: {{ Auth::user()->identifier_number }}</small>
                    @endrole
                </div>
            </a>
            <hr>
            <a href="{{ route('leaderboard.index') }}" class="px-4 py-3 hover:bg-gray-100 lg:rounded-md flex items-center gap-x-2 {{ request()->routeIs('leaderboard.index') ? 'bg-gray-100' : '' }}">
                <img src="{{ asset('icons/leaderboard.svg') }}" alt="leaderboard">
                <span>Leaderboard</span>
            </a>
            <a href="#" class="px-4 py-3 hover:bg-gray-100 lg:rounded-md flex items-center gap-x-2">
                <img src="{{ asset('icons/home.svg') }}" alt="home">
                <span>Menu</span>
            </a>
            <a href="#" class="px-4 py-3 hover:bg-gray-100 lg:rounded-md flex items-center gap-x-2">
                <img src="{{ asset('icons/home.svg') }}" alt="home">
                <span>Menu</span>
            </a>
            <a href="#" class="px-4 py-3 hover:bg-gray-100 lg:rounded-md flex items-center gap-x-2">
                <img src="{{ asset('icons/home.svg') }}" alt="home">
                <span>Menu</span>
            </a>
            <a href="#" class="px-4 py-3 hover:bg-gray-100 lg:rounded-md flex items-center gap-x-2">
                <img src="{{ asset('icons/home.svg') }}" alt="home">
                <span>Menu</span>
            </a>
            <hr>
            <p class="text-center py-3 px-4 text-xs">Program ini dibuat sebagai syarat untuk menyelesaikan pendidikan
                sarjana.</p>
        </div>
        <div id="content">
            <form class="flex justify-center gap-2 lg:justify-start flex-wrap">
                <select name="kpi_period_id" id="kpi_period_id" onchange="event.preventDefault(); this.closest('form').submit();" class="rounded-md">
                    @foreach ($kpis as $kpi_loop)
                    <option value="{{ $kpi_loop->id }}" {{ $kpi->id == $kpi_loop->id ? 'selected' : '' }}>{{
                        \Carbon\Carbon::parse($kpi_loop->start_date)->format('d/m/Y') }}{{ $kpi_loop->is_active ? ' (aktif)' :
                        '' }}</option>
                    @endforeach
                </select>
                <select name="filter" id="filter" onchange="event.preventDefault(); this.closest('form').submit();" class="rounded-md">
                    <option value="all" {{ request()->filter == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="dosen" {{ request()->filter == 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="tendik" {{ request()->filter == 'tendik' ? 'selected' : '' }}>Tendik</option>
                    <option value="staff" {{ request()->filter == 'staff' ? 'selected' : '' }}>Staff</option>
                </select>
            </form>
            {{-- <div class="mt-2 flex items-start gap-2 rounded-md p-2 bg-yellow-300">
                <img src="{{ asset('icons/warning.svg') }}" alt="warning">
                <span class="break-words">Periode KPI yang berlangsung akan berakhir dalam <b>9 Hari</b></span>
            </div> --}}
            <div class="flex flex-col py-2 divide-y">
                @foreach ($points as $point)
                <div class="py-4" id="user-points">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @switch($loop->index)
                            @case(0)
                            <img src="{{ asset('icons/number-one.svg') }}" alt="" class="w-8 h-8">
                            @break
                            @case(1)
                            <img src="{{ asset('icons/number-two.svg') }}" alt="" class="w-8 h-8">
                            @break
                            @case(2)
                            <img src="{{ asset('icons/number-three.svg') }}" alt="" class="w-8 h-8">
                            @break
                            @default
                            <div class="w-8 h-8 flex justify-center items-center font-semibold">{{ $n++ }}</div>
                            @endswitch
                            <img src="{{ $point->user->image ? asset('storage/' . $point->user->image) : asset('images/profile.png') }}" alt="profile" class="w-10 h-10 rounded-full">
                            <div class="flex flex-col">
                                <div class="flex flex-col">
                                    <a href="#" class="font-semibold line-clamp-1">{{ $point->user->name ?? '-' }}</a>
                                    @if (request()->filter == 'all' || request()->filter == null)
                                    <span class="text-xs bg-yellow-400 pb-0.5 px-3 rounded-lg uppercase w-fit">{{ rolesProcessor($point->user->roles) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 hover:bg-gray-100 px-2 py-1 rounded-md cursor-pointer"
                            onclick="event.preventDefault(); this.closest('#user-points').querySelector('#detail-points').classList.toggle('hidden'); this.querySelector('img').classList.toggle('rotate-180')">
                            <span>{{ number_format($point->points, 0) }} XP</span>
                            <img src="{{ asset('icons/chevron.svg') }}" class="transition-transform ease-in-out"
                                alt="arrow">
                        </div>
                    </div>
                    <div class="flex flex-col hidden pr-10" id="detail-points">
                        <div class="flex items-center gap-2 justify-end">
                            <span>Kehadiran</span>
                            <span>-</span>
                            <span>{{ number_format($point->presence_points, 0) }} XP</span>
                        </div>
                        <div class="flex items-center gap-2 justify-end">
                            <span>Umpan Balik</span>
                            <span>-</span>
                            <span>{{ number_format($point->feedback_points, 0) }} XP</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>

    <div id="nav-backdrop" class="hidden">
    </div>
    <script>
        const navbarActions = () => {
  const navHamburger = document.querySelector("nav #hamburger");
  const backdrop = document.querySelector("#nav-backdrop");
  const body = document.querySelector("body");
  const sidebar = document.querySelector("#sidebar");

  const menusToggle = () => {
    body.classList.toggle("overflow-y-hidden");
    sidebar.classList.toggle("active");

    if (backdrop.classList.contains("hidden")) {
      backdrop.classList.toggle("hidden");
      setTimeout(() => {
        backdrop.classList.toggle("bg-black/50");
      }, 50);
    } else {
      backdrop.classList.toggle("bg-black/50");
      setTimeout(() => {
        backdrop.classList.toggle("hidden");
      }, 150);
    }
  };

  navHamburger.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    menusToggle();
  });
  backdrop.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    menusToggle();
  });
};

navbarActions();

    </script>
</body>

</html>

{{-- @php
function rolesProcessor($roles) {
$string = '';
foreach ($roles as $role) {
$string = $role->name;
}
return $string;
}
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div>
        <a href="{{ route('profile') }}">Profil</a>
        <a href="{{ route('leaderboard.index') }}">Leaderboard</a>
    </div>
    <br>
    <form action="">
        <select name="kpi_period_id" id="kpi_period_id">
            @foreach ($kpis as $kpi_loop)
            <option value="{{ $kpi_loop->id }}" {{ $kpi->id == $kpi_loop->id ? 'selected' : '' }}>{{
                \Carbon\Carbon::parse($kpi_loop->start_date)->format('d/m/Y') }}{{ $kpi_loop->is_active ? ' (aktif)' :
                '' }}</option>
            @endforeach
        </select>
        <button type="submit">Filter</button>
    </form>
    <h1>Leaderboard {{ $kpi->title }} ({{ \Carbon\Carbon::parse($kpi->start_date)->format('d/m/Y') }} - {{
        \Carbon\Carbon::parse($kpi->end_date)->format('d/m/Y') }})</h1>
    <table border="1">
        <tr>
            <td>#</td>
            <td>Nama</td>
            <td>Point</td>
        </tr>
        @foreach ($points as $point)
        <tr>
            <td>{{ $points[0]->points == 0 ? '-' : $n++ }}</td>
            <td>{{ $point->user->name ?? '-' }} ({{ rolesProcessor($point->user->roles) }})
            </td>
            <td>{{ number_format($point->points, 2) }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html> --}}
