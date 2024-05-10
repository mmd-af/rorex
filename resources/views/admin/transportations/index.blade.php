@extends('admin.layouts.index')

@section('title')
    Transportations
@endsection
@section('style')
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Transportations</li>
    </ol>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="mb-4">
                <button class="btn btn-primary px-5" onclick="getTruck()" style="cursor: pointer" data-bs-toggle="modal"
                    data-bs-target="#createNewTrasportation" data-info="Modal 1 Content">
                    Create Transportation <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
    @include('admin.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="transportationTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>From Date</th>
                        <th>Until Date</th>
                        <th>Destination Country</th>
                        <th>Destination City</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Product Name</th>
                        <th>From Date</th>
                        <th>Until Date</th>
                        <th>Destination Country</th>
                        <th>Destination City</th>
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
                    <h1 class="modal-title fs-5" id="showLabel">transportations</h1>
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
                                <tbody id="transportation_information">
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

    <div class="modal fade" id="createNewTrasportation" tabindex="-1" aria-labelledby="createNewTrasportationLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createNewTrasportationLabel">Create New Trasportation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.transportations.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name">
                        </div>
                        <div class="mb-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="from_date" name="from_date">
                        </div>
                        <div class="mb-3">
                            <label for="until_date" class="form-label">Until Date</label>
                            <input type="date" class="form-control" id="until_date" name="until_date">
                        </div>
                        <div class="mb-3">
                            <label for="country_of_origin" class="form-label">Country of Origin</label>
                            <input type="text" class="form-control" id="country_of_origin" name="country_of_origin">
                        </div>
                        <div class="mb-3">
                            <label for="city_of_origin" class="form-label">City of Origin</label>
                            <input type="text" class="form-control" id="city_of_origin" name="city_of_origin">
                        </div>
                        <div class="mb-3">
                            <label for="destination_country" class="form-label">Destination Country</label>
                            <input type="text" class="form-control" id="destination_country" name="destination_country">
                        </div>
                        <div class="mb-3">
                            <label for="destination_city" class="form-label">Destination City</label>
                            <input type="text" class="form-control" id="destination_city" name="destination_city">
                        </div>
                        <div class="mb-3 bg-info p-3">
                            <label for="truck_type" class="form-label">Truck</label>
                            <div class="p-3">
                                <div class="row rounded-3 shadow mt-2" id="addTruck">
                                    <div class="col-9">
                                        <select class="form-control" id="truck1" onchange="setQty1(event)">
                                        </select>
                                    </div>
                                    <div class="col-3" id="qty1">
                                    </div>
                                    <div class="col-9 mt-3">
                                        <select class="form-control" id="truck2" onchange="setQty2(event)">

                                        </select>
                                    </div>
                                    <div class="col-3 mt-3" id="qty2">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="weight_of_each_car" class="form-label">Weight of Each Truck</label>
                            <input type="text" class="form-control" id="weight_of_each_car"
                                name="weight_of_each_car">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div id="showAllowCompanies"></div>
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary">Submit</button>

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            $('#transportationTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.transportations.ajax.getDataTable') }}",
                columns: [{
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'from_date',
                        name: 'from_date'
                    },
                    {
                        data: 'until_date',
                        name: 'until_date'
                    },
                    {
                        data: 'destination_country',
                        name: 'destination_country'
                    },
                    {
                        data: 'destination_city',
                        name: 'destination_city'
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
            let transportationInformation = document.getElementById('transportation_information');
            transportationInformation.innerHTML = ``;
            let data = {
                id: id
            }
            axios.post("{{ route('admin.transportations.ajax.show') }}", data)
                .then(response => {
                    console.log(response.data);
                    transportationInformation.innerHTML = `
                                    <tr>
                                        <th scope="row">Product Name</th>
                                        <td>${response.data.product_name}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">From Date</th>
                                        <td>${response.data.from_date}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Until Date</th>
                                        <td>${response.data.until_date}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Country of Origin</th>
                                        <td>${response.data.country_of_origin}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">City of Origin</th>
                                        <td>${response.data.city_of_origin}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Destination Country</th>
                                        <td>${response.data.destination_country}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Destination City</th>
                                        <td>${response.data.destination_city}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Weight of each Truck</th>
                                        <td>${response.data.weight_of_each_car}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Description	</th>
                                        <td>${response.data.description	}</td>
                                    </tr>`;

                    response.data.trucks.forEach(element => {
                        transportationInformation.innerHTML += `
                                        <tr>
                                            <th class="bg-info" scope="row">${element.name} | ${element.lwh} | ${element.load_capacity} Kg</th>
                                            <td class="bg-info">${element.pivot.qty}</td>
                                        </tr>
                                        
                                        `;
                    });

                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function handleActive(event, id) {
            event.preventDefault();
            axios.post("{{ route('admin.transportations.ajax.active') }}", {
                    id: id
                })
                .then(response => {
                    location.reload();
                })
                .catch(error => {
                    console.error(error);
                });

        }

        function getTruck() {
            let truck1 = document.getElementById('truck1');
            truck1.innerHTML += `
                        <option value="">please select truck</option>`;
            let truck2 = document.getElementById('truck2');
            truck2.innerHTML += `
                        <option value="">please select truck</option>`;
            axios.get("{{ route('admin.transportations.ajax.getTrucks') }}")
                .then(response => {
                    response.data.forEach(element => {
                        truck1.innerHTML += `
                        <option value="${element.id}">${element.name} | ${element.lwh} |
                                                ${element.load_capacity} Kg</option>`;

                        truck2.innerHTML += `
                        <option value="${element.id}">${element.name} | ${element.lwh} |
                                                ${element.load_capacity} Kg</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function setQty1(event) {
            getCompaniesWithTruck(event.target.value)
            let qty1 = document.getElementById('qty1');
            qty1.innerHTML = `<input type="number" name="${event.target.value}" class="form-control" value=""
                                            min="0">`;
        }

        function setQty2(event) {
            let qty2 = document.getElementById('qty2');
            qty2.innerHTML = `<input type="number" name="${event.target.value}" class="form-control" value=""
                                        min="0">`;
        }

        function getCompaniesWithTruck(id) {
            let showAllowCompanies = document.getElementById('showAllowCompanies');
            showAllowCompanies.innerHTML = ``;
            let data = {
                id: id
            }
            axios.post("{{ route('admin.transportations.ajax.getCompaniesWithTruck') }}", data)
                .then(response => {
                    console.log(response);
                    response.data.forEach(element => {
                        // showAllowCompanies.innerHTML += ``;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        }
    </script>
@endsection
