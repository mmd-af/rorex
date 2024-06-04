@extends('admin.layouts.index')

@section('title')
    Trucks
@endsection
@section('style')
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Trucks</li>
    </ol>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#createNewTruck"
                    data-info="Modal 1 Content">
                    Create Truck <i class="fa fa-plus" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
    @include('admin.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="truckTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>L - W - H</th>
                        <th>Total Height</th>
                        <th>Load Capacity</th>
                        <th>Covered</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>L - W - H</th>
                        <th>Total Height</th>
                        <th>Load Capacity</th>
                        <th>Covered</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </tfoot>

                <body>
                </body>
            </table>
        </div>
    </div>

    <div class="modal fade" id="createNewTruck" tabindex="-1" aria-labelledby="createNewTruckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createNewTruckLabel">Create New Truck</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.trucks.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group mt-2">
                            <label for="lwh">Length Width Height (LWH) (Cm):</label>
                            <div class="d-flex">
                                <input type="number" id="lwh" name="l" class="form-control"
                                    value="{{ old('l') }}" required>
                                <input type="number" id="lwh" name="w" class="form-control"
                                    value="{{ old('w') }}" required>
                                <input type="number" id="lwh" name="h" class="form-control"
                                    value="{{ old('h') }}" required>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label for="total_height">Total Height (Cm):</label>
                            <input type="number" id="total_height" name="total_height" class="form-control"
                                value="{{ old('total_height') }}" required>
                        </div>
                        <div class="form-group mt-2">
                            <label for="load_capacity">Load Capacity (Kg):</label>
                            <input type="number" id="load_capacity" name="load_capacity" class="form-control"
                                value="{{ old('load_capacity') }}" required>
                        </div>
                        <div class="form-group mt-2">
                            <label for="covered">Covered:</label>
                            <div class="form-switch">
                                <input type="hidden" name="covered" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="covered" name="covered"
                                    value="1">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showTrucks" tabindex="-1" aria-labelledby="truckShowLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="truckShowLabel">Truck</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="edit-name" name="name" class="form-control" value=""
                                required>
                        </div>
                        <div class="form-group mt-2">
                            <label for="lwh">Length Width Height (LWH) (Cm):</label>
                            <div class="d-flex">
                                <input type="text" id="edit-lwh" name="lwh" class="form-control" value=""
                                    required>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label for="total_height">Total Height (Cm):</label>
                            <input type="number" id="edit-total_height" name="total_height" class="form-control"
                                value="" required>
                        </div>
                        <div class="form-group mt-2">
                            <label for="load_capacity">Load Capacity (Kg):</label>
                            <input type="number" id="edit-load_capacity" name="load_capacity" class="form-control"
                                value="" required>
                        </div>
                        <div class="form-group mt-2">
                            <label for="covered">Covered:</label>
                            <div class="form-switch">
                                <input type="hidden" name="covered" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="edit-covered"
                                    name="covered" value="1">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
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
        $(document).ready(function() {
            $('#truckTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.trucks.ajax.getDataTable') }}",
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'lwh',
                        name: 'lwh'
                    },
                    {
                        data: 'total_height',
                        name: 'total_height'
                    },
                    {
                        data: 'load_capacity',
                        name: 'load_capacity'
                    },
                    {
                        data: 'covered',
                        name: 'covered'
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
            let url = "{{ route('admin.trucks.update', ':id') }}";
            url = url.replace(':id', id);
            editForm.setAttribute("action", url);
            let data = {
                id: id
            }
            axios.post("{{ route('admin.trucks.ajax.show') }}", data)
                .then(function(response) {
                    const data = response.data;
                    document.getElementById('edit-name').value = data.name;
                    document.getElementById('edit-lwh').value = data.lwh;
                    document.getElementById('edit-total_height').value = data.total_height;
                    document.getElementById('edit-load_capacity').value = data.load_capacity;
                    document.getElementById('edit-covered').checked = data.covered;
                })
                .catch(function(error) {
                    console.log(error);
                });
        }

        function handleActive(event, id) {
            event.preventDefault();
            axios.post("{{ route('admin.trucks.ajax.active') }}", {
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
