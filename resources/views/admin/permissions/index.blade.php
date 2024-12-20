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
                    <th>Action</th>

                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action</th>
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
    <div class="modal fade" id="showPermissions" tabindex="-1" aria-labelledby="permissionShowLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="permissionShowLabel">Permission</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-input">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" name="name" id="name_edit" value="">
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
                    {data: 'id', name: 'id', width: '10%'},
                    {data: 'name', name: 'name', width: '80%'},
                    {data: 'button', name: 'button', width: '10%', orderable: false, searchable: false}
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
        function show(id) {
            let name_edit = document.getElementById('name_edit');
            let editForm = document.getElementById("editForm");
            let url = "{{ route('admin.permissions.update',':id') }}";
            url = url.replace(':id', id);
            editForm.setAttribute("action", url);
            let data = {
                id: id
            }
            axios.post("{{route('admin.permissions.ajax.show')}}", data)
                .then(response => {
                    name_edit.value = response.data.name;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
        function destroy(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                let data = {
                    id: id
                }
                axios.post("{{route('admin.permissions.ajax.destroy')}}", data)
                    .then(response => {
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error deleting record:', error);
                    });
            }
        }
    </script>
@endsection

