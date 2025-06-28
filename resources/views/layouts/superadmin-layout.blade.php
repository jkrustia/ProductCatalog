<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Arf & Meow Co.')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script defer src="{{ asset('js/sidenav.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
</head>

<body>
    <nav class="d-nav navbar navbar-expand-lg">
        <div class="container">
            @include('partials._brand')
            <div class="d-flex align-items-center">
                
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="d-block me-4">Sign Out</button>
                </form>
                <div class="me-2">
                    <img src="{{ asset('images/pic.png') }}" alt="User Image" width="30">
                    <p class="username text-center fw-bold">PM</p>
                </div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        @yield('content') <!-- Page-specific content will be injected here -->
    </main>
    </div>
</body>

</html>
