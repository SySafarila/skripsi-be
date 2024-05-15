<div id="sidebar">
    <a href="{{ route('employees.profile') }}" class="account">
        <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('images/profile.png') }}"
            alt="profile" class="h-10 w-10 flex-shrink-0 rounded-full">
        <div class="flex w-full flex-col">
            <span class="line-clamp-1 break-all font-bold">{{ Auth::user()->name }}</span>
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
    <a href="{{ route('employees.profile') }}"
        class="{{ request()->routeIs('profile') ? 'bg-gray-100' : '' }} flex items-center gap-x-2 px-4 py-3 hover:bg-gray-100 lg:rounded-md">
        <img src="{{ asset('icons/account.svg') }}" alt="home">
        <span>Profil</span>
    </a>
    <a href="{{ route('employees.presence.index') }}"
        class="{{ request()->routeIs('presence.*') ? 'bg-gray-100' : '' }} flex items-center gap-x-2 px-4 py-3 hover:bg-gray-100 lg:rounded-md">
        <img src="{{ asset('icons/presence.svg') }}" alt="home">
        <span>Kehadiran</span>
    </a>
    <a href="{{ route('employees.feedback.index') }}"
        class="flex items-center gap-x-2 px-4 py-3 hover:bg-gray-100 lg:rounded-md">
        <img src="{{ asset('icons/feedback.svg') }}" alt="home">
        <span>Lihat Feedback</span>
    </a>
    <a href="{{ route('employees.leaderboard.index') }}"
        class="{{ request()->routeIs('leaderboard.index') ? 'bg-gray-100' : '' }} flex items-center gap-x-2 px-4 py-3 hover:bg-gray-100 lg:rounded-md">
        <img src="{{ asset('icons/leaderboard.svg') }}" alt="leaderboard">
        <span>Leaderboard</span>
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
