<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up - ARF &amp; MEOW CO.</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
</head>

<body>
    <div class="container-fluid signup mt-5">
        <div class="row justify-content-center text-center">
            <div class="col">
                <a href="{{ route('home') }}" class="d-block">
                    <img src="{{ asset('images/signup.png') }}" alt="ARF &amp; MEOW CO. Logo">
                </a>
                <p class="comp-name mt-2">ARF &amp; MEOW CO.</p>
                <h1>SIGN UP</h1>
                <p class="sub">Sign in to your account</p>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div>
                        <input type="email" name="email" class="form-control rounded-5 mb-3" placeholder="Email" required>
                    </div>
                    <div>
                        <input type="password" name="password" class="form-control rounded-5 mb-4" placeholder="Password" required>
                    </div>
                    <button type="submit">Sign In</button>


                    
                </form>
                
            </div>
        </div>
    </div>
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>

</html>
