@extends('admin.layouts.index')

@section('title')
    Employees
@endsection
@section('style')
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Employees</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="employeeTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Staff Code</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Card Number</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>is_active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Staff Code</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Card Number</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>is_active</th>
                        <th>Action</th>
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
                    <h1 class="modal-title fs-5" id="showLabel">Employees</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <form id="editForm" action="" method="post">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="staff_code">Staff Code:</label>
                                <input type="text" id="edit_staff_code" name="staff_code" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="last_name">Last Name:</label>
                                <input type="text" id="edit_last_name" name="last_name" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="first_name">First Name:</label>
                                <input type="text" id="edit_first_name" name="first_name" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="cart_number">Cart Number:</label>
                                <input type="text" id="edit_cart_number" name="cart_number" class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label for="department">Department:</label>
                                <input type="text" id="edit_department" name="department" class="form-control"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4">Submit</button>
                        </form>
                    </div>
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
        $(document).ready(function() {
            $('#employeeTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.employees.ajax.getDataTable') }}",
                columns: [{
                        data: 'staff_code',
                        name: 'staff_code'
                    },
                    {
                        data: 'last_name',
                        name: 'last_name'
                    },
                    {
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        data: 'cart_number',
                        name: 'cart_number'
                    },
                    {
                        data: 'department',
                        name: 'department'
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
            let editForm = document.getElementById("editForm");
            let url = "{{ route('admin.employees.update', ':id') }}";
            url = url.replace(':id', id);
            editForm.setAttribute("action", url);
            let data = {
                id: id
            }
            axios.post("{{ route('admin.employees.ajax.show') }}", data)
                .then(function(response) {
                    const data = response.data;
                    document.getElementById('edit_staff_code').value = data.staff_code;
                    document.getElementById('edit_first_name').value = data.first_name;
                    document.getElementById('edit_last_name').value = data.last_name;
                    document.getElementById('edit_cart_number').value = data.cart_number;
                    document.getElementById('edit_department').value = data.department;
                })
                .catch(function(error) {
                    console.log(error);
                });
        }

        function handleActive(event, id) {
            event.preventDefault();
            axios.post("{{ route('admin.employees.ajax.active') }}", {
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
