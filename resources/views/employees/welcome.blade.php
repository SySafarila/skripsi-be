<x-app-layout>
    <div class="flex flex-col gap-4">
        <x-app.session-notifications />
        <h1 class="text-2xl font-bold">Halo {{ Auth::user()->name ?? '-' }}...</h1>
        <div class="grid grid-cols-3 gap-4 md:grid-cols-4">
            {{-- <div class="flex flex-col items-center">
                <img src="{{ asset('icons/home.svg') }}" alt="" class="w-12">
                <span class="block text-center">Beranda</span>
            </div> --}}
            <a href="{{ route('employees.profile') }}"
                class="flex flex-col items-center justify-center gap-2 rounded-lg border p-4 hover:bg-gray-100">
                <img src="{{ asset('icons/account.svg') }}" alt="" class="w-12">
                <span class="block text-center">Profil</span>
            </a>
            <a href="{{ route('employees.presence.index') }}"
                class="flex flex-col items-center justify-center gap-2 rounded-lg border p-4 hover:bg-gray-100">
                <img src="{{ asset('icons/presence.svg') }}" alt="" class="w-12">
                <span class="block text-center">Kehadiran</span>
            </a>
            {{-- <a href="{{ route('employees.feedback.index') }}"
                class="flex flex-col items-center justify-center gap-2 rounded-lg border p-4 hover:bg-gray-100">
                <img src="{{ asset('icons/feedback.svg') }}" alt="" class="w-12">
                <span class="block text-center">Feedback</span>
            </a> --}}
            <a href="{{ route('employees.leaderboard.index') }}"
                class="flex flex-col items-center justify-center gap-2 rounded-lg border p-4 hover:bg-gray-100">
                <img src="{{ asset('icons/leaderboard.svg') }}" alt="" class="w-12">
                <span class="block text-center">Leaderboard</span>
            </a>
            <a href="{{ route('settings.index') }}"
                class="flex flex-col items-center justify-center gap-2 rounded-lg border p-4 hover:bg-gray-100">
                <img src="{{ asset('icons/settings.svg') }}" alt="" class="w-12">
                <span class="block text-center">Pengaturan</span>
            </a>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault();document.querySelector('#logoutForm').submit()"
                class="flex flex-col items-center justify-center gap-2 rounded-lg border p-4 hover:bg-gray-100">
                <img src="{{ asset('icons/logout.svg') }}" alt="" class="w-12">
                <span class="block text-center">Logout</span>
            </a>
        </div>
    </div>
</x-app-layout>
