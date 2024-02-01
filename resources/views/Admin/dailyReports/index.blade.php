@extends('admin.layouts.index')

@section('title')
    Daily Reports
@endsection

@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daily Reports</li>
    </ol>
    <div class="card mb-4">
        @include('admin.layouts.partial.errors')
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            <form action="{{ route('admin.dailyReports.filter') }}" method="post">
                @csrf
                <div class="row p-3 m-3">
                    <div class="col-sm-12 col-lg-6">
                        <label for="start_date" class="px-4">Start Date:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm"
                               value="{{old('start-date')}}">
                    </div>
                    <div class="col-sm-12 col-lg-6">

                        <label for="end_date" class="px-4">End Date:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm"
                               value="{{old('end_date')}}">
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-info btn-sm">Apply filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <table id="dailyReportTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>cod_staff</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Weeks</th>
                    <th>Shift</th>
                    <th>on_work1</th>
                    <th>off_work2</th>
                    <th>remarca</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>cod_staff</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Weeks</th>
                    <th>Shift</th>
                    <th>on_work1</th>
                    <th>off_work2</th>
                    <th>remarca</th>
                </tr>
                </tfoot>
                <body>
                </body>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function requestForm(id) {
            {{--let alert = document.getElementById('alert');--}}
            {{--let name = document.getElementById('name');--}}
            {{--let name_show = document.getElementById('name_show');--}}
            {{--let cod_staff = document.getElementById('cod_staff');--}}
            {{--let cod_staff_show = document.getElementById('cod_staff_show');--}}
            {{--let date = document.getElementById('date');--}}
            {{--let date_show = document.getElementById('date_show');--}}
            {{--let configInformation = {--}}
            {{--    dailyReport_id: id--}}
            {{--}--}}
            {{--axios.post('{{ route('admin.dailyReports.ajax.getData') }}', configInformation)--}}
            {{--    .then(function (response) {--}}
            {{--        alert.innerHTML = ``;--}}
            {{--        name.value = response.data.data.nume;--}}
            {{--        name_show.innerHTML = response.data.data.nume;--}}
            {{--        cod_staff.value = response.data.data.cod_staff;--}}
            {{--        cod_staff_show.innerHTML = response.data.data.cod_staff;--}}
            {{--        date.value = response.data.data.data;--}}
            {{--        date_show.innerHTML = response.data.data.data;--}}
            {{--    })--}}
            {{--    .catch(function (error) {--}}
            {{--        console.error(error);--}}
            {{--    });--}}
        }
    </script>
    <script>
        $(document).ready(function () {
            $('#dailyReportTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.dailyReports.ajax.getDataTable') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'cod_staff', name: 'cod_staff'},
                    {data: 'nume', name: 'nume'},
                    {data: 'data', name: 'data'},
                    {data: 'saptamana', name: 'saptamana'},
                    {data: 'nume_schimb', name: 'nume_schimb'},
                    {data: 'on_work1', name: 'on_work1'},
                    {data: 'off_work2', name: 'off_work2'},
                    {data: 'remarca', name: 'remarca'}
                ]
            });
        });
    </script>
@endsection

