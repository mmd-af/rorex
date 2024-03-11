<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rorex Pipe</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <img class="img-fluid w-25" src="{{asset('build/img/logo.png')}}" alt="ROREX - PIPE">
        @if (Route::has('login'))
            <div class="d-flex justify-content-center">
                @auth
                    <a href="{{ route('user.dashboard.index') }}"
                       class="btn btn-outline-info text-primary mx-2">Dashboard</a>
                @else
                    <a class="btn btn-outline-info text-primary mx-2" href="{{route('login')}}">Login</a>
                    @if (Route::has('register'))
                        <a class="btn btn-outline-info text-primary mx-2" href="{{route('register')}}">Register</a>
                    @endif
                @endauth
            </div>

    </div>
    @endif
</div>
</body>
</html>
