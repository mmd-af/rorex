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
        <div class="card-body table-responsive">
            <table id="userTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>Cod Staff</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Departament</th>
                    <th>Card Number</th>
                    <th>Email</th>
                    <th>action</th>
                    <th>is_active</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Cod Staff</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Departament</th>
                    <th>Card Number</th>
                    <th>Email</th>
                    <th>action</th>
                    <th>is_active</th>
                </tr>
                </tfoot>
                <body>
                </body>
            </table>
        </div>
    </div>
    <div class="modal fade" id="show" tabindex="-1" aria-labelledby="ShowLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="showLabel">Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="" method="post">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" disabled>
                            </div>
                            <div class="form-input mt-4 p-1 bg-secondary-subtle" id="is_active">
                            </div>
                            <div class="form-input mt-4">
                                <label for="name">Role:</label>
                                <hr>
                                <div class="row p-2" id="roles">
                                </div>
                            </div>
                            <div class="form-input mt-4">
                                <label for="name">Permission:</label>
                                <hr>
                                <div class="row p-2" id="permissions">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
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
                    {data: 'prenumele_tatalui', name: 'prenumele_tatalui'},
                    {data: 'name', name: 'name'},
                    {data: 'departament', name: 'departament'},
                    {data: 'numar_card', name: 'numar_card'},
                    {data: 'email', name: 'email'},
                    {data: 'show', name: 'show'},
                    {data: 'status', name: 'status'}
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
            let name = document.getElementById('name');
            let email = document.getElementById('email');
            let is_active = document.getElementById('is_active');
            let roles = document.getElementById('roles');
            let permissions = document.getElementById('permissions');
            let editForm = document.getElementById("editForm");
            let url = "{{ route('admin.users.update',':id') }}";
            url = url.replace(':id', id);
            editForm.setAttribute("action", url);
            let data = {
                id: id
            }
            axios.post("{{route('admin.users.ajax.show')}}", data)
                .then(response => {
                    console.log(response.data)
                    name.value = response.data.user.name;
                    email.value = response.data.user.email;
                    is_active.innerHTML = `
                    <label for="is_active">Is Active:</label>
                    <input class="form-check-input mx-3" type="checkbox" role="switch" id="is_active" name="is_active" ${response.data.user.is_active ? 'checked' : ''}>`;
                    roles.innerHTML = '';
                    permissions.innerHTML = '';
                    response.data.roles.forEach(function (item) {
                        var isChecked = response.data.user.roles.some(function (userRole) {
                            return userRole.id === item.id;
                        });
                        roles.innerHTML += `<div class="form-check form-switch col-md-12 mt-2">
            <input class="form-check-input" type="checkbox" role="switch" id="role_${item.id}" name="roles[]" value="${item.name}" ${isChecked ? 'checked' : ''}>
            <label class="form-check-label mr-3 h6" for="role_${item.id}">${item.name}</label>
        </div>`;
                    });
                    response.data.permissions.forEach(function (item) {
                        var isChecked = response.data.user.permissions.some(function (userPermission) {
                            return userPermission.id === item.id;
                        });
                        permissions.innerHTML += `<div class="form-check form-switch col-md-12 mt-2">
            <input class="form-check-input" type="checkbox" role="switch" id="permission_${item.id}" name="permissions[]" value="${item.name}" ${isChecked ? 'checked' : ''}>
            <label class="form-check-label mr-3 h6" for="permission_${item.id}">${item.name}</label>
        </div>`;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
@endsection

