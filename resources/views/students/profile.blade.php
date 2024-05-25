<x-app-layout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Profil</h1>
        <div class="flex flex-col items-center gap-2">
            <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('images/profile.png') }}"
                alt="profil" class="w-20">
            <div class="flex w-full flex-col items-center">
                <p class="font-semibold capitalize">{{ Auth::user()->name }}</p>
                <small class="line-clamp-1 uppercase">{{ Auth::user()->identifier }}:
                    {{ Auth::user()->identifier_number }}</small>
                <div class="mt-1.5 flex gap-2">
                    @foreach (Auth::user()->roles as $role)
                        <small class="rounded-full bg-yellow-400 px-2 pb-0.5 capitalize">{{ $role->name }}</small>
                    @endforeach
                    <small
                        class="rounded-full bg-yellow-400 px-2 pb-0.5 capitalize">{{ Auth::user()->hasMajor->major->major }}</small>
                    <small class="rounded-full bg-yellow-400 px-2 pb-0.5 capitalize">Semester
                        {{ Auth::user()->hasMajor->semester }}</small>
                </div>
            </div>
        </div>
        <h1 class="text-2xl font-bold">Mata Kuliah</h1>
        <table class="w-full border-collapse border">
            <tr>
                <th class="border p-2 text-left">Mata Kuliah</th>
                {{-- <th class="border p-2">Semester</th> --}}
                <th class="border p-2 text-left">Dosen</th>
            </tr>
            @forelse ($courses as $course)
                <tr>
                    <td class="border p-2">{{ $course->name }}</td>
                    {{-- <td class="border p-2 text-center">{{ $course->semester }}</td> --}}
                    <td class="border p-2">{{ $course->user->name }}</td>
                </tr>
            @empty
                <tr>
                    <td class="border p-2" colspan="2">Data tidak ada</td>
                </tr>
            @endforelse
        </table>
    </div>
</x-app-layout>
