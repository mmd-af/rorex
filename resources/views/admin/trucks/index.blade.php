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
    <div class="modal fade" id="show" tabindex="-1" aria-labelledby="ShowLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="showLabel">trucks</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="2">Information</th>
                                    </tr>
                                </thead>
                                <tbody id="truck_information">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
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
                                <input class="form-check-input" type="checkbox" role="switch" id="covered"
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
            let truckInformation = document.getElementById('truck_information');
            truckInformation.innerHTML = ``;
            let data = {
                id: id
            }
            axios.post("{{ route('admin.trucks.ajax.show') }}", data)
                .then(response => {
                    truckInformation.innerHTML = `<tr>
                                        <th scope="row">truck Name</th>
                                        <td>${response.data.truck_name}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Activity Domain</th>
                                        <td>${response.data.activity_domain}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Vat id</th>
                                        <td>${response.data.vat_id}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Registration Number</th>
                                        <td>${response.data.registration_number}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Country</th>
                                        <td>${response.data.country}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">County</th>
                                        <td>${response.data.county}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">City</th>
                                        <td>${response.data.city}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Zip Code</th>
                                        <td>${response.data.zip_code}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Address</th>
                                        <td>${response.data.address}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Building</th>
                                        <td>${response.data.building}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Person Name</th>
                                        <td>${response.data.person_name}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Job Title</th>
                                        <td>${response.data.job_title}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Phone Number</th>
                                        <td>${response.data.phone_number}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Email</th>
                                        <td>${response.data.users.email}</td>
                                    </tr>`;

                })
                .catch(error => {
                    console.error('Error:', error);
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
