<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Rorex - @yield('title')</title>
    <link href="{{asset('admin-panel/css/styles.css')}}" rel="stylesheet"/>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css"/>
    <script src="https://cdn.datatables.net/plug-ins/1.11.6/api/individual.columnFilter.js"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield('style')
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
@yield('script')
<script>
    $(document).ready(function () {
        let alertNotification = document.getElementById('alertNotification');
        axios.post('{{route('admin.dashboard.ajax.checkNewNotification')}}')
            .then(function (response) {
                alertNotification.innerHTML = `
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            +${response.data.qty} <span class="visually-hidden">unread messages</span>
        </span>`;
            })
            .catch(function (error) {
                console.error(error);
            });
    });

    function getMessages() {
        let setMessage = document.getElementById('getMessages');
        setMessage.innerHTML = `
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>
        </div>`;
        axios.post('{{route('admin.dashboard.ajax.getNewNotifications')}}')
            .then(function (response) {
                console.log(response.data)
                setMessage.innerHTML = ``;
                let url = "{{route('admin.manageRequests.index')}}";
                response.data.staffRequest.forEach(function (item) {
                    setMessage.innerHTML += `<a href="${url}">
                                                <div class="alert alert-primary" role="alert">
                                                ${item.subject}
                                            </div></a>`;
                });
            })
            .catch(function (error) {
                console.error(error);
            });
    }
</script>
</body>
</html>
