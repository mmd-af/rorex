<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('general.app_name') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="welcome-page">
<div class="container-fluid text-center">
    <div class="container mt-5 bg-white text-dark py-4 rounded-4 shadow">
        <!-- Logo Section -->
        <div class="logo mb-4">
            <a href="https://rorexpipe.com" target="_blank">
                <img src="{{ asset('build/img/logo.png') }}" alt="ROREX - PIPE SRL">
            </a>
        </div>

        <!-- Language Selector -->
        @php
            $currentLanguage = session('locale', 'en');
            $languages = [
                'en' => ['flag' => 'https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg', 'name' => 'English'],
                'ro' => ['flag' => 'https://upload.wikimedia.org/wikipedia/commons/7/73/Flag_of_Romania.svg', 'name' => 'Romanian'],
                'fa' => ['flag' => 'https://upload.wikimedia.org/wikipedia/commons/c/ca/Flag_of_Iran.svg', 'name' => 'فارسی'],
            ];
        @endphp
        <div class="language-selector d-flex justify-content-center gap-3 mb-4">
            @foreach ($languages as $lang => $data)
                <a href="{{ route('lang.switch', $lang) }}" class="text-decoration-none text-dark text-center">
                    <img src="{{ $data['flag'] }}" alt="{{ $data['name'] }} Flag"
                         class="border border-secondary shadow-sm">
                    <span class="d-block mt-1">{{ $data['name'] }}</span>
                </a>
            @endforeach
        </div>

        <!-- Buttons Section -->
        <div class="buttons mb-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ route('user.dashboard.index') }}"
                       class="btn button px-4 py-2">{{ __('general.dashboard') }}</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn button px-4 py-2">{{ __('general.log_out') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn button px-4 py-2">{{ __('general.login') }}</a>
                @endauth
            @endif
        </div>

        <!-- Register Links -->
        @unless (auth()->check())

            <div class="d-flex flex-column align-items-center justify-content-center">
                <a href="{{ route('register') }}" class="text-decoration-none">{{ __('general.user_register') }}</a>
                <span></span>
                <a href="{{ route('companies.register') }}"
                   class="text-decoration-none">{{ __('general.company_register') }}</a>
            </div>
    @endunless

    <!-- Footer Section -->
        <footer class="mt-4">
            <p>
                <a href="https://rorexpipe.com/" target="_blank"
                   class="text-decoration-none">{{ __('general.company_website') }}</a>
            </p>
            <small>{{ __('layouts.copyright') }} &copy; {{ __('layouts.rorex_ro') }} {{ date('Y') }}</small>
        </footer>
    </div>
</div>
</body>

</html>
