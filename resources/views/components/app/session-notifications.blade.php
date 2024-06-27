@if (session('success'))
    <p class="rounded-md bg-green-500 px-3 py-2 text-white">{{ session('success') }}</p>
@endif
@if (session('warning'))
    <p class="rounded-md bg-yellow-400 px-3 py-2">{{ session('warning') }}</p>
@endif
@if (session('error'))
    <p class="rounded-md bg-red-500 px-3 py-2 text-white">{{ session('error') }}</p>
@endif

@if (@$type && @$message)
    @if ($type == 'warning')
        <p class="rounded-md bg-yellow-400 px-3 py-2">{{ $message }}</p>
    @endif
@endif
