@extends('user.layouts.index')

@section('title')
    Daily Reports
@endsection

@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daily Reports</li>
    </ol>
    <div class="card mb-4">
        @include('user.layouts.partial.errors')
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            <form action="{{ route('user.dailyReports.filter') }}" method="post">
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
                            <button onclick="requestForm({{$dailyReport->id}})" type="button"
                                    class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#forgetRequest">
                                <i class="fa-solid fa-square-arrow-up-right"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                {{ $dailyReports->links() }}
            </table>
            {{ $dailyReports->links() }}

        </div>
    </div>
    <div class="modal fade" id="forgetRequest" tabindex="-1" aria-labelledby="forgetRequestLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="forgetRequestLabel">Request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="alert">
                        <div class="row justify-content-center my-3">
                            <div class="spinner-grow text-primary mx-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow text-secondary mx-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="row justify-content-center my-3">
                            <div class="spinner-grow text-secondary mx-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow text-primary mx-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{route('user.dailyReports.supportRequest')}}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="cod_staff" class="col-form-label">Staff:
                                <div class="text-info" id="name_show"></div>
                                <div class="text-info" id="cod_staff_show"></div>
                            </label>
                            <input type="hidden" id="name" name="name" value="">
                            <input type="hidden" id="cod_staff" name="cod_staff" value="">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="col-form-label">Date:
                                <div class="text-info" id="date_show"></div>
                            </label>
                            <input type="hidden" id="date" name="date" value="">
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="col-form-label">Subject:</label>
                            <input type="text" class="form-control" id="subject" name="subject" value="">
                        </div>
                        <div class="mb-3">
                            <label for="organization" class="col-form-label">Organization:</label>
                            <input type="text" class="form-control" value="accounting" disabled>
                            <input type="hidden" id="organization" name="organization" value="accounting">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="col-form-label">Message:</label>
                            <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Send message</button>

                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function requestForm(id) {
            let alert = document.getElementById('alert');
            let name = document.getElementById('name');
            let name_show = document.getElementById('name_show');
            let cod_staff = document.getElementById('cod_staff');
            let cod_staff_show = document.getElementById('cod_staff_show');
            let date = document.getElementById('date');
            let date_show = document.getElementById('date_show');
            let configInformation = {
                dailyReport_id: id
            }
            axios.post('{{ route('user.dailyReports.ajax.getData') }}', configInformation)
                .then(function (response) {
                    alert.innerHTML = ``;
                    name.value = response.data.data.nume;
                    name_show.innerHTML = response.data.data.nume;
                    cod_staff.value = response.data.data.cod_staff;
                    cod_staff_show.innerHTML = response.data.data.cod_staff;
                    date.value = response.data.data.data;
                    date_show.innerHTML = response.data.data.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        }
    </script>
@endsection
