@extends('admin.layouts.index')

@section('title')
    Users
@endsection
@section('style')

@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Users</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="card mb-4">
        <div class="row">
            <div class="col-sm-12 col-md-6 bg-warning">
                <form class="form-control bg-warning" action="{{ route('admin.users.import') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="file">Update Users <small>support: xlsx,xls</small></label>
                        <input type="file" name="file" id="file" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary mt-2">Import</button>
                </form>
            </div>
            <div class="col-sm-12 col-md-6 bg-danger-subtle">
                <strong>Important:</strong>
                <ol>
                    <li>The first row of the file is not added because it is the name of the column.</li>
                    <li>Only Sheet1 will be uploaded.</li>
                    <li>Duplicate data <strong>is updated</strong></li>
                    <li>Be patient while uploading until the operation is finished and the page is refreshed.</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <table id="userTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>Cod Staff</th>
                    <th>Name</th>
                    <th>Departament</th>
                    <th>Card Number</th>
                    <th>Email</th>
                    <th>is_active</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Cod Staff</th>
                    <th>Name</th>
                    <th>Departament</th>
                    <th>Card Number</th>
                    <th>Email</th>
                    <th>is_active</th>
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
        $(document).ready(function () {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.users.ajax.getDataTable') }}",
                columns: [
                    {data: 'cod_staff', name: 'cod_staff'},
                    {data: 'name', name: 'name'},
                    {data: 'departament', name: 'departament'},
                    {data: 'numar_card', name: 'numar_card'},
                    {data: 'email', name: 'email'},
                    {data: 'is_active', name: 'is_active'}
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
    </script>
@endsection

