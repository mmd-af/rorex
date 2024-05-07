@extends('admin.layouts.index')

@section('title')
    Companies
@endsection
@section('style')
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Companies</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="companyTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Activity Domain</th>
                        <th>City</th>
                        <th>Email</th>
                        <th>is_active</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Company Name</th>
                        <th>Activity Domain</th>
                        <th>City</th>
                        <th>Email</th>
                        <th>is_active</th>
                        <th>action</th>
                    </tr>
                </tfoot>

                <body>
                </body>
            </table>
        </div>
    </div>
    <div class="modal fade" id="show" tabindex="-1" aria-labelledby="ShowLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="showLabel">Companies</h1>
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
        $(document).ready(function() {
            $('#companyTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.companies.ajax.getDataTable') }}",
                columns: [{
                        data: 'company_name',
                        name: 'company_name'
                    },
                    {
                        data: 'activity_domain',
                        name: 'activity_domain'
                    },
                    {
                        data: 'city',
                        name: 'city'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                initComplete: function() {
                    var table = this;

                    this.api().columns().every(function() {
                        var column = this;
                        var header = $(column.header());

                        var filterRow = header.closest('thead').find('.filter-row');

                        if (!filterRow.length) {
                            filterRow = $('<tr class="filter-row"></tr>').appendTo(header
                                .closest('thead'));
                        }

                        var input = $(
                                '<input type="text" class="form-control form-control-sm" placeholder="Search...">'
                            )
                            .appendTo($('<th></th>').appendTo(filterRow))
                            .on('keyup change', function() {
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
            let url = "{{ route('admin.companies.update', ':id') }}";
            url = url.replace(':id', id);
            editForm.setAttribute("action", url);
            let data = {
                id: id
            }
            axios.post("{{ route('admin.companies.ajax.show') }}", data)
                .then(response => {
                    console.log(response.data)
                    name.value = response.data.company.name;
                    email.value = response.data.company.email;
                    is_active.innerHTML =
                        `
                    <label for="is_active">Is Active:</label>
                    <input class="form-check-input mx-3" type="checkbox" role="switch" id="is_active" name="is_active" ${response.data.Companies.is_active ? 'checked' : ''}>`;
                    roles.innerHTML = '';
                    permissions.innerHTML = '';
                    response.data.roles.forEach(function(item) {
                        var isChecked = response.data.company.roles.some(function(companyRole) {
                            return companyRole.id === item.id;
                        });
                        roles.innerHTML += `<div class="form-check form-switch col-md-12 mt-2">
            <input class="form-check-input" type="checkbox" role="switch" id="role_${item.id}" name="roles[]" value="${item.name}" ${isChecked ? 'checked' : ''}>
            <label class="form-check-label mr-3 h6" for="role_${item.id}">${item.name}</label>
        </div>`;
                    });
                    response.data.permissions.forEach(function(item) {
                        var isChecked = response.data.company.permissions.some(function(companyPermission) {
                            return companyPermission.id === item.id;
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

        function handleActive(event, id) {
            event.preventDefault();
            axios.post("{{ route('admin.companies.ajax.active') }}", {
                    id: id
                })
                .then(response => {
                    location.reload();
                })
                .catch(error => {
                    console.error(error);
                });

        }
    </script>
@endsection