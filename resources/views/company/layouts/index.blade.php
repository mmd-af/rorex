<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rorex - @yield('title')</title>
    <link href="{{ asset('admin-panel/css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/plug-ins/1.11.6/api/individual.columnFilter.js"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield('style')
    @laravelPWA
</head>

<body>
    <nav class="" style="background-color: #009799;">
        <div class="d-flex justify-content-between">
            <h3 class="mt-3"><a class="navbar-brand text-light m-3" href="{{ url('/') }}">rorex.ro</a></h3>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-light btn-sm m-3"
                    onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    <small>{{ __('Log Out') }}</small>
                </button>
            </form>
        </div>
    </nav>
    @yield('content')

    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; rorex.ro {{ date('Y') }}</div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('admin-panel/js/scripts.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    @yield('script')
</body>

</html>
