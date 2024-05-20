<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rorex Pipe</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <a href="https://rorexpipe.com/" class="text-center" target="_blank">
                <img class="img-fluid w-25" src="{{ asset('build/img/logo.png') }}" alt="ROREX - PIPE">
            </a>
        </div>
        @if (Route::has('login'))
            <div class="row justify-content-center">
                @auth
                    <div class="card text-center text-white m-3 p-3" style="width: 18rem; background-color: #009799">
                        <div class="card-body">
                            <h5 class="card-title">Dashboard</h5>
                            {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                            <a href="{{ route('user.dashboard.index') }}" class="btn btn-info px-5 py-3">Dashboard</a>
                        </div>
                    </div>
                    <div class="card text-center m-3 p-3" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Log out</h5>
                            {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-primary"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="card text-center m-3 p-3" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">User Register</h5>
                            {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                            <a href="{{ route('register') }}" class="btn btn-primary">User Register</a>
                        </div>
                    </div>
                    <div class="card text-center text-white m-3 p-3" style="width: 18rem; background-color: #009799">
                        <div class="card-body">
                            <h5 class="card-title">Login</h5>
                            {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                            <a href="{{ route('login') }}" class="btn btn-info px-5 py-3">Login</a>
                        </div>
                    </div>
                    <div class="card text-center m-3 p-3" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Company Register</h5>
                            {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                            <a href="{{ route('companies.register') }}" class="btn btn-primary">Company Register</a>
                        </div>
                    </div>
                @endauth
            </div>
        @endif
    </div>
</body>

</html>
