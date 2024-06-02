<!-- Dibuat oleh Syahrul Safarila untuk Universitas Putra Indonesia -->
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
        <div class="mx-auto flex w-full max-w-screen-lg items-center justify-between px-4">
            <a href="/" id="brand" class="font-bold">BRAND</a>
            <button type="button" class="lg:hidden" id="hamburger">
                <img src="{{ asset('icons/menu.svg') }}" alt="menu">
            </button>
        </div>
    </nav>
    <main>
        <x-app.sidebar />
        <div id="content">
            {{ $slot }}
        </div>
    </main>

    <div id="nav-backdrop" class="hidden">
    </div>
    <form action="{{ route('logout') }}" method="post" id="logoutForm">
        @csrf
    </form>
    @vite(['resources/js/app.js'])
</body>

</html>
