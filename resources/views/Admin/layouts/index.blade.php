<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Rorex - @yield('title')</title>
    <link href="{{asset('admin-panel/css/styles.css')}}" rel="stylesheet"/>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="sb-nav-fixed">
@include('admin.layouts.partial.navbar')
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('admin.layouts.partial.sidebar')
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                @yield('content')
            </div>
        </main>
        @include('admin.layouts.partial.footer')
    </div>
</div>
<script src="{{asset('admin-panel/js/scripts.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="{{asset('admin-panel/assets/demo/chart-area-demo.js')}}"></script>
<script src="{{asset('admin-panel/assets/demo/chart-bar-demo.js')}}"></script>
@yield('script')
</body>
</html>
