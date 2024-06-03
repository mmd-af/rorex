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
            <img class="img-fluid w-25" src="{{ asset('build/img/logo.png') }}" alt="ROREX - PIPE">
            @if (Route::has('login'))
                <div class="row text-center">
                    @auth
                        <div class="col-12">
                            <h3 class="text-danger">Your account is under review</h3>
                        </div>

                        @if (session('status') == 'verification-link-sent')
                            <div class="text-success">
                                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                            </div>
                        @endif
                        @if (!auth()->user()->hasVerifiedEmail())
                            <div class="bg-warning p-3">
                                <div class="alert alert-warning" role="alert">
                                    {{ __('Your email address is not verified. Please verify your email address.') }}
                                </div>
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <div>
                                        <button type="submit"
                                            class="btn btn-success">{{ __('Send Verification Email') }}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        <div class="d-flex justify-content-center mt-5">
                            <a href="{{ route('login') }}" class="btn btn-outline-info text-primary mx-2">
                                try to login
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-outline-info text-primary mx-2"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</body>

</html>
