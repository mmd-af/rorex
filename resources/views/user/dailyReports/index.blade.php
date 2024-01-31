@extends('user.layouts.index')

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
            <form action="{{ route('user.dailyReports.filter') }}" method="post">
                @csrf
                <div class="d-flex p-3 m-3">
                    <label for="start_date" class="px-4">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control form-control-sm"
                           value="{{old('start-date')}}">

                    <label for="end_date" class="px-4">End Date:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control form-control-sm"
                           value="{{old('end_date')}}">

                    <button type="submit" class="btn btn-info btn-sm">Apply filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <table id="datatablesSimple1" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Weeks</th>
                    <th>Shift</th>
                    <th>on_work1</th>
                    <th>off_work2</th>
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
                    <th>off_work2</th>
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
                        <td @if(empty($dailyReport->on_work1)) class="bg-danger" @endif>{{$dailyReport->on_work1}}</td>
                        <td @if(empty($dailyReport->off_work2)) class="bg-danger" @endif>{{$dailyReport->off_work2}}</td>
                        <td @if(isset($dailyReport->remarca)) class="bg-warning" @endif>{{$dailyReport->remarca}}</td>
                        <td>
                            {{--                            <i class="fas fa-angle-down"></i>--}}
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#forgetRequest">
                                <i class="fa-solid fa-square-arrow-up-right"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="forgetRequest" tabindex="-1" aria-labelledby="forgetRequestLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="forgetRequestLabel">New message</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Recipient:</label>
                            <input type="text" class="form-control" id="recipient-name">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Message:</label>
                            <textarea class="form-control" id="message-text"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Send message</button>
                </div>
            </div>
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
