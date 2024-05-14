{{-- @php
    function rolesProcessor($roles)
    {
        $string = '';
        foreach ($roles as $role) {
            $string = $role->name;
        }
        return $string;
    }
@endphp --}}

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
            <a href="#" id="brand" class="font-bold">BRAND</a>
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
