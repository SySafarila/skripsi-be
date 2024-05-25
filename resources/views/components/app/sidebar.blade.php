<div id="sidebar">
    <a href="{{ Auth::user()->hasRole('mahasiswa') ? route('student.profile') : route('employees.profile') }}"
        class="account">
        <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('images/profile.png') }}"
            alt="profile" class="h-10 w-10 flex-shrink-0 rounded-full">
        <div class="flex w-full flex-col">
            <span class="line-clamp-1 break-all font-bold capitalize">{{ Auth::user()->name }}</span>
            <small class="line-clamp-1 uppercase">{{ Auth::user()->identifier }}:
                {{ Auth::user()->identifier_number }}</small>
        </div>
    </a>
    <hr>
    @role(['dosen', 'tendik'])
        <a href="{{ route('employees.welcome') }}"
            class="{{ request()->routeIs('employees.welcome') ? 'bg-gray-100' : '' }} flex items-center gap-x-2 px-4 py-3 hover:bg-gray-100 lg:rounded-md">
            <img src="{{ asset('icons/home.svg') }}" alt="home">
            <span>Beranda</span>
        </a>
        <a href="{{ route('employees.presence.index') }}"
            class="{{ request()->routeIs('employees.presence.*') ? 'bg-gray-100' : '' }} flex items-center gap-x-2 px-4 py-3 hover:bg-gray-100 lg:rounded-md">
            <img src="{{ asset('icons/presence.svg') }}" alt="presence">
            <span>Kehadiran</span>
        </a>
        {{-- <a href="{{ route('employees.feedback.index') }}"
            class="{{ request()->routeIs('employees.feedback.index') ? 'bg-gray-100' : '' }} flex items-center gap-x-2 px-4 py-3 hover:bg-gray-100 lg:rounded-md">
            <img src="{{ asset('icons/feedback.svg') }}" alt="feedback">
            <span>Lihat Feedback</span>
        </a> --}}
        <a href="{{ route('employees.leaderboard.index') }}"
            class="{{ request()->routeIs('employees.leaderboard.index') ? 'bg-gray-100' : '' }} flex items-center gap-x-2 px-4 py-3 hover:bg-gray-100 lg:rounded-md">
            <img src="{{ asset('icons/leaderboard.svg') }}" alt="leaderboard">
            <span>Leaderboard</span>
        </a>
    @endrole
    @role('mahasiswa')
        <a href="{{ route('student.index') }}"
            class="{{ request()->routeIs('student.index') ? 'bg-gray-100' : '' }} flex items-center gap-x-2 px-4 py-3 hover:bg-gray-100 lg:rounded-md">
            <img src="{{ asset('icons/home.svg') }}" alt="home">
            <span>Beranda</span>
        </a>
        <a href="{{ route('student.courses.index') }}"
            class="{{ request()->routeIs('student.courses.*') ? 'bg-gray-100' : '' }} flex items-center gap-x-2 px-4 py-3 hover:bg-gray-100 lg:rounded-md">
            <img src="{{ asset('icons/feedback.svg') }}" alt="feedback">
            <span>Feedback</span>
        </a>
    @endrole
    <a href="#" class="flex items-center gap-x-2 px-4 py-3 hover:bg-gray-100 lg:rounded-md">
        <img src="{{ asset('icons/settings.svg') }}" alt="settings">
        <span>Pengaturan</span>
    </a>
    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.querySelector('#logoutForm').submit()"
        class="flex items-center gap-x-2 px-4 py-3 hover:bg-gray-100 lg:rounded-md">
        <img src="{{ asset('icons/logout.svg') }}" alt="logout">
        <span>Logout</span>
    </a>
    <hr>
    <p class="px-4 py-3 text-center text-xs">Program ini dibuat sebagai syarat untuk menyelesaikan pendidikan
        sarjana.</p>
</div>
