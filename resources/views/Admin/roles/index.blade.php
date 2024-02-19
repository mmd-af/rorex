@extends('admin.layouts.index')

@section('title')
    Roles
@endsection
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Roles</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="d-flex justify-content-between">
        <div></div>
        <div>
            <button type="button"
                    class="btn btn-primary mx-3" data-bs-toggle="modal"
                    data-bs-target="#role">
                Create Role <i class="fa-solid fa-square-arrow-up-right"></i>
            </button>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="roleTable" class="table table-bordered table-striped text-center">
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
    <div class="modal fade" id="role" tabindex="-1" aria-labelledby="roleLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="roleLabel">Create Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.roles.store')}}" method="post">
                        @csrf
                        <div class="form-input">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" name="name" id="name" value="">
                            <input type="hidden" name="guard_name" value="web">
                        </div>
                        <div class="form-input mt-4">
                            <label for="name">Permission:</label>
                            <hr>
                            <div class="row p-4" id="permissions">
                            </div>
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
    <div class="modal fade" id="showRoles" tabindex="-1" aria-labelledby="roleShowLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="roleShowLabel">Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="myForm" action="{{ route('admin.roles.update', ['role' => 'ROLE_ID']) }}" method="post">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="roleId">
                        <div class="form-input">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" name="name" id="name_edit" value="">
                            <input type="hidden" name="guard_name" value="web">
                        </div>
                        <div class="form-input mt-2">
                            <label for="name">Permissions:</label>
                            <hr>
                            <div class="row p-3" id="permissions_edit">
                            </div>
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
            $('#roleTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.roles.ajax.getDataTable') }}",
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

            let permissions = document.getElementById('permissions');
            axios.get("{{route('admin.roles.ajax.getPermissions')}}")
                .then(response => {
                    response.data.forEach(function (item) {
                        permissions.innerHTML += `<div class="form-check form-switch col-md-12 mt-2">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="permission_${item.id}" name="${item.name}" value="${item.name}">
                                                    <label class="form-check-label mr-3 h6" for="permission_${item.id}">${item.name}</label>
                                                   </div>`;
                    })

                })
                .catch(error => {
                    console.error('Error deleting record:', error);
                });
        });

        function show(id) {
            let name_edit = document.getElementById('name_edit');
            let permissions_edit = document.getElementById('permissions_edit');
            document.getElementById('roleId').value = id;
            let data = {
                id: id
            }
            axios.post("{{route('admin.roles.ajax.show')}}", data)
                .then(response => {
                    // console.log(response.data.role.permissions)
                    name_edit.value = response.data.role.name;
                    response.data.permissions.forEach(function (item) {
                        permissions_edit.innerHTML += `<div class="form-check form-switch col-md-12 mt-2">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="permission_${item.id}" name="${item.name}" value="${item.name}">
                                                    <label class="form-check-label mr-3 h6" for="permission_${item.id}">${item.name}</label>
                                                   </div>`;
                    })
                    response.data.role.permissions.forEach(function (item) {
                        permissions_edit.innerHTML += `<div class="form-check form-switch col-md-12 mt-2">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="permission_${item.id}" name="${item.name}" value="${item.name}">
                                                   <label class="form-check-label mr-3 h6" for="permission_${item.id}">${item.name}</label>
                                                  </div>`;
                    })


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
                axios.post("{{route('admin.roles.ajax.destroy')}}", data)
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

