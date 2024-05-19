<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    @vite(['resources/css/app.css'])
</head>

<body class="antialiased">
    <div class="flex min-h-screen w-full flex-row justify-center p-5">
        <div class="flex w-full max-w-screen-md flex-col items-center justify-center divide-y">
            <div class="w-full border-gray-400 px-4 py-2 text-center text-lg tracking-wider text-gray-500">
                @yield('code')
            </div>
            <div class="ml-4 w-full py-2 text-center text-lg uppercase tracking-wider text-gray-500">
                @yield('message')
            </div>
        </div>
    </div>
</body>

</html>
