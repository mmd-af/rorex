@extends('admin.layouts.index')

@section('title')
    Daily Reports
@endsection

@section('content')
    <h1 class="mt-4">Daily Reports</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daily Reports</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            <form action="{{ route('admin.dailyReports.filter') }}" method="post">
                @csrf
                <div class="d-flex p-3 m-3">
                <label for="start_date" class="px-4">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{old('start-date')}}">

                <label for="end_date" class="px-4">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{old('end_date')}}">

                <button type="submit" class="btn btn-info btn-sm">Apply filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Weeks</th>
                    <th>Shift</th>
                    <th>on_work1</th>
                    <th>off_work1</th>
                    <th>on_work2</th>
                    <th>off_work2</th>
                    <th>on_work3</th>
                    <th>off_work3</th>
                    <th>remarca</th>
                    <th>action</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Weeks</th>
                    <th>Shift</th>
                    <th>on_work1</th>
                    <th>off_work1</th>
                    <th>on_work2</th>
                    <th>off_work2</th>
                    <th>on_work3</th>
                    <th>off_work3</th>
                    <th>remarca</th>
                    <th>action</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($dailyReports as $dailyReport)
                    <tr>
                        <td>{{$dailyReport->nume}}</td>
                        <td>{{$dailyReport->data}}</td>
                        <td>{{$dailyReport->saptamana}}</td>
                        <td>{{$dailyReport->nume_schimb}}</td>
                        <td>{{$dailyReport->on_work1}}</td>
                        <td>{{$dailyReport->off_work1}}</td>
                        <td>{{$dailyReport->on_work2}}</td>
                        <td>{{$dailyReport->off_work2}}</td>
                        <td>{{$dailyReport->on_work3}}</td>
                        <td>{{$dailyReport->off_work3}}</td>
                        <td>{{$dailyReport->remarca}}</td>
                        <td><i class="fas fa-angle-down"></i></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let table = new DataTable('#datatablesSimple', {
            responsive: false
        });
    </script>
@endsection
