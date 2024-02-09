<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Rorex - @yield('title')</title>
    <link href="{{asset('admin-panel/css/styles.css')}}" rel="stylesheet"/>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/plug-ins/1.11.6/api/individual.columnFilter.js"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield('style')
</head>
<body class="sb-nav-fixed">
@include('user.layouts.partial.navbar')
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('user.layouts.partial.sidebar')
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                @yield('content')
            </div>
        </main>
        @include('user.layouts.partial.footer')
    </div>
</div>
<script src="{{asset('admin-panel/js/scripts.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
@yield('script')
</body>
</html>
