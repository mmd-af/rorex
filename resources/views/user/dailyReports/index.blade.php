@extends('user.layouts.index')

@section('title')
    Daily Reports
@endsection
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daily Reports</li>
    </ol>
    @include('user.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="dailyReportTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>cod_staff</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Weeks</th>
                    <th>Shift</th>
                    <th>on_work1</th>
                    <th>off_work2</th>
                    <th>remarca</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>cod_staff</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Weeks</th>
                    <th>Shift</th>
                    <th>on_work1</th>
                    <th>off_work2</th>
                    <th>remarca</th>
                    <th>Action</th>
                </tr>
                </tfoot>
                <body>
                </body>
            </table>
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
                            <select name="organization" class="form-control" id="departamentRole">
                            </select>
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
        $(document).ready(function () {
            $('#dailyReportTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('user.dailyReports.ajax.getDataTable') }}",
                columns: [
                    {data: 'cod_staff', name: 'cod_staff'},
                    {data: 'nume', name: 'nume'},
                    {data: 'data', name: 'data'},
                    {data: 'saptamana', name: 'saptamana'},
                    {data: 'nume_schimb', name: 'nume_schimb'},
                    {data: 'on_work1', name: 'on_work1'},
                    {data: 'off_work2', name: 'off_work2'},
                    {data: 'remarca', name: 'remarca'},
                    {data: 'button', name: 'button', orderable: false, searchable: false}
                ],
                initComplete: function () {
                    var table = this;

                    this.api().columns().every(function () {
                        var column = this;
                        var header = $(column.header());

                        var filterRow = header.closest('thead').find('.filter-row');

                        if (!filterRow.length) {
                            filterRow = $('<tr class="filter-row"></tr>').appendTo(header.closest('thead'));
                        }

                        var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search...">')
                            .appendTo($('<th></th>').appendTo(filterRow))
                            .on('keyup change', function () {
                                if (column.search() !== this.value) {
                                    column
                                        .search(this.value)
                                        .draw();
                                }
                            });
                    });
                }
            });
        });

        $(document).ready(function () {
            let departamentRole = document.getElementById('departamentRole');
            axios.get('{{route('user.dailyReports.ajax.getRoles')}}')
                .then(function (response) {
                    response.data.forEach(function (item) {
                        departamentRole.innerHTML += `<option value="${item.name}">${item.name}</option>`;
                    });
                })
                .catch(function (error) {
                    console.error(error);
                });
        });

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

