@extends('admin.layouts.index')

@section('title')
    Permissions
@endsection
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Permissions</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="d-flex justify-content-between">
        <div></div>
        <div>
            <button type="button"
                    class="btn btn-primary mx-3" data-bs-toggle="modal"
                    data-bs-target="#permission">
                Create Permission <i class="fa-solid fa-square-arrow-up-right"></i>
            </button>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="permissionTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>

                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                </tr>
                </tfoot>
                <body>
                </body>
            </table>
        </div>
    </div>
    <div class="modal fade" id="permission" tabindex="-1" aria-labelledby="permissionLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="permissionLabel">Create Permission</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.permissions.store')}}" method="post">
                        @csrf
                        <div class="form-input">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" name="name" id="name" value="">
                            <input type="hidden" name="guard_name" value="web">
                        </div>
                        <button type="submit" class="btn btn-success mt-3">Save</button>
                    </form>
                    <div class="modal-footer mt-3">
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
            $('#permissionTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.permissions.ajax.getDataTable') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'}
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

