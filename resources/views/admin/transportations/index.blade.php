@extends('admin.layouts.index')

@section('title')
    Transportations
@endsection
@section('style')
    <style>
        .make-box {
            width: auto;
            height: auto;
            border: 1px solid #ddd;
            padding: 10px;
            margin: 0 auto;
        }
    </style>
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
    <div class="modal fade" id="showOrder" tabindex="-1" aria-labelledby="ShowOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="showLabel">Companies Request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="row mb-4" id="companies_information">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#saveOrder" onClick="acceptOrder()">
                            Submit Select Order <i style="cursor:pointer;"
                                class="fa-solid fa-square-check mx-auto fa-2xl shadow-lg text-info"></i>
                        </button>
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
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="product_number" class="form-label">Product Number</label>
                            <input type="number" class="form-control" id="product_number" name="product_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="until_date" class="form-label">Until Date</label>
                            <input type="date" class="form-control" id="until_date" name="until_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="country_of_origin" class="form-label">Country of Origin</label>
                            <input type="text" class="form-control" id="country_of_origin" name="country_of_origin"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="city_of_origin" class="form-label">City of Origin</label>
                            <input type="text" class="form-control" id="city_of_origin" name="city_of_origin"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="destination_country" class="form-label">Destination Country</label>
                            <input type="text" class="form-control" id="destination_country"
                                name="destination_country" required>
                        </div>
                        <div class="mb-3">
                            <label for="destination_city" class="form-label">Destination City</label>
                            <input type="text" class="form-control" id="destination_city" name="destination_city"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="weight_of_each_car" class="form-label">Weight of Each Truck (Kg)</label>
                            <input type="text" class="form-control" id="weight_of_each_car" name="weight_of_each_car"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
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
                        <div id="showAllowCompanies">
                        </div>
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary">Submit</button>

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade saveOrderModal" id="saveOrder" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="saveOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="saveOrderLabel">Save Order</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.transportations.acceptOrder') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" id="order_id" value="">
                    <div class="modal-body">
                        <div id="orderContent">
                            <div class="input-group mt-3">
                                <input type="file" name="contract" class="form-control" id="contract" required>
                                <label class="input-group-text" for="contract">Upload Contract</label>
                            </div>
                            <div class="input-group mt-4 justify-content-center" id="orderInformations">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th>Truck</th>
                                                <th>Last Price (per truck)</th>
                                            </tr>
                                        </tbody>
                                        <tbody id="selectOrderInformation">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
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
                    transportationInformation.innerHTML = `
                                    <tr>
                                        <th scope="row">Product Name</th>
                                        <td>${response.data.product_name}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Product Name</th>
                                        <td>${response.data.product_number}</td>
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
                    response.data.companies.forEach(element => {
                        const isActive = element.pivot.is_active ? 'True' : 'False';
                        transportationInformation.innerHTML += `
                                        <tr>
                                           <th class="border-success" scope="row">${element.company_name}</th>
                                           <td class="border-success">${isActive}</td>
                                          </tr>`;
                    });

                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function showOrder(id) {
            const companiesInformation = document.getElementById('companies_information');
            companiesInformation.innerHTML = ``;
            const data = {
                id: id
            };

            axios.post("{{ route('admin.transportations.ajax.showCompaniesOrder') }}", data)
                .then(response => {
                    const groupedCompanies = response.data.reduce((acc, element) => {
                        const companyId = element.company_id;
                        if (!acc[companyId]) {
                            acc[companyId] = {
                                companyName: element.company.company_name,
                                trucks: [],
                                totalPrice: 0,
                                companyDetails: element.company
                            };
                        }
                        const truckQty = element.transportation.trucks.find(truck => truck.id === element
                            .truck_id)?.pivot.qty || 1;
                        const price = element.last_price !== null ? element.last_price : element.price;
                        const originalTotalPrice = truckQty * element.price;
                        const discountedTotalPrice = truckQty * price;

                        acc[companyId].trucks.push({
                            orderId: element.id,
                            truckName: element.truck.name,
                            lwh: element.truck.lwh,
                            qty: truckQty,
                            price: element.price,
                            lastPrice: element.last_price,
                            contract: element.contract,
                            totalPrice: discountedTotalPrice,
                            originalTotalPrice: originalTotalPrice
                        });
                        acc[companyId].totalPrice += discountedTotalPrice;
                        return acc;
                    }, {});

                    const sortedCompanies = Object.values(groupedCompanies).sort((a, b) => a.totalPrice - b.totalPrice);

                    sortedCompanies.forEach(company => {
                        let trucksDetails = ``;
                        company.trucks.forEach(truck => {
                            trucksDetails += `
                <p><b>Truck:</b> ${truck.truckName} (${truck.lwh})</p>
                <p><b>Quantity:</b> ${truck.qty}</p>
                <p><b>Price per truck:</b>
                    ${truck.lastPrice === null ? `${truck.price.toLocaleString('en-US')} €` : `<span class="text-decoration-line-through">${truck.price.toLocaleString('en-US')} €</span>
                                                                <b> ${truck.lastPrice.toLocaleString('en-US')} €`}</b></p>
                <p><b>Total price for this truck:</b>
                    ${truck.lastPrice === null ? `${truck.totalPrice.toLocaleString('en-US')} €` : `<span class="text-decoration-line-through">${truck.originalTotalPrice.toLocaleString('en-US')} €</span>
                                                                <b> ${truck.totalPrice.toLocaleString('en-US')} €`}</b></p>
                <div class="form-check d-flex justify-content-center">
                    <div class="bg-warning rounded-3 px-5">    
                        <input class="form-check-input allSelectOrder" type="checkbox" name="order[]" value="${truck.orderId}" id="order-${truck.orderId}">
                        <label class="form-check-label" for="order-${truck.orderId}">
                            select
                        </label>
                    </div>
                    ${truck.contract ? `<a class="btn btn-info mx-2" href="${truck.contract}" target="_blank">Show Contract</a>
                                                                            <a class="btn btn-danger mx-2" href="#" onClick="destroyOrderContract(${truck.orderId})">Delete Contract</a>
                                                        ` : ''}
                </div> 
                <hr>`;
                        });

                        companiesInformation.innerHTML += `
            <div class="col-12 mt-3">
                <div class="accordion make-box bg-primary" id="accordion-${company.companyDetails.id}">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-${company.companyDetails.id}" aria-expanded="false" aria-controls="collapseTwo">
                                ${company.companyName} 
                                <span style="margin-left: 10px;">|</span>  
                                <h6 class="text-success" style="margin-left: 10px;">${company.totalPrice.toLocaleString('en-US')} €</h6>
                            </button>
                        </h2>
                        <div id="collapse-${company.companyDetails.id}" class="accordion-collapse collapse" data-bs-parent="#accordion-${company.companyDetails.id}">
                            <div class="accordion-body">
                                ${trucksDetails}
                                <div class="col-12 border p-1"><b>Representative's name:</b> ${company.companyDetails.person_name}</div>
                                <div class="col-12 border p-1"><b>Phone:</b> ${company.companyDetails.phone_number}</div>
                                <div class="col-12 border p-1"><b>Email:</b> ${company.companyDetails.users.email}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
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
            let qty1 = document.getElementById('qty1');
            qty1.innerHTML = `<input type="number" name="${event.target.value}" class="form-control truckId" value=""
                                            min="0" required>`;
            getCompaniesWithTruck()

        }

        function setQty2(event) {
            let qty2 = document.getElementById('qty2');
            qty2.innerHTML = `<input type="number" name="${event.target.value}" class="form-control truckId" value=""
                                        min="0" required>`;
            getCompaniesWithTruck()

        }

        function getCompaniesWithTruck() {
            var elements = document.getElementsByClassName("truckId");
            var names = [];
            for (var i = 0; i < elements.length; i++) {
                names.push(elements[i].name);
            }
            let showAllowCompanies = document.getElementById('showAllowCompanies');
            showAllowCompanies.innerHTML = ``;
            let data = {
                id: names
            }
            axios.post("{{ route('admin.transportations.ajax.getCompaniesWithTruck') }}", data)
                .then(response => {
                    response.data.forEach(element => {
                        showAllowCompanies.innerHTML +=
                            `<div class="alert alert-warning d-flex justify-content-between">${element.company_name} (${element.vat_id})
                                <input name="authorized_company[]" type="hidden" value="${element.id}"/>
                                <div class="form-switch">
                                <input class="form-check-input"
                                    type="checkbox" role="switch" name="is_active[]" value="${element.id}" checked>
                            </div>
                                </div>`;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        }

        function acceptOrder() {
            let orderIdElement = document.getElementById('order_id');
            let orderInformations = document.getElementById('orderInformations');
            let selectOrderInformation = document.getElementById('selectOrderInformation');
            selectOrderInformation.innerHTML = ``;
            const checkboxes = document.querySelectorAll('.allSelectOrder');
            let selectedValues = [];
            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    selectedValues.push(checkbox.value);
                }
            });
            if (selectedValues.length === 0) {
                alert('You have not selected any options.');
            } else {
                let data = {
                    id: selectedValues
                }
                axios.post("{{ route('admin.transportations.ajax.getOrderInformations') }}", data)
                    .then(response => {
                        let responseData = response.data;
                        let companyIds = responseData.map(item => item.company_id);
                        let allSameCompanyId = companyIds.every(id => id === companyIds[0]);
                        if (allSameCompanyId) {
                            let newHTML = ` <thead>
                        <tr>
                            <td>Company Name</td>
                            <td>
                               <strong class="text-success"> ${responseData[0].company.company_name} </strong>
                                </td>
                            </tr>
                            <tr>
                            <td><small>If the contract price is different from the offer price, enter the new price.</small></td>
                            <td>
                               
                                </td>
                            </tr>
                        </thead>
                        `;
                            selectOrderInformation.insertAdjacentHTML('beforebegin', newHTML);
                            responseData.forEach(element => {
                                selectOrderInformation.innerHTML += ` <tr>
                            <td>${element.truck.name}</td>
                                    <td>
                                        <label><small>Offer Price= <b>${element.price}</b></small></label>
                                        <input type="hidden" name="order_id[]" id="" value="${element.id}" />
                                        <input type="number" name="last_price[]" class="form-control" placeholder ="new price" value="" /></td> 
                                    </tr>`;
                            });

                        } else {
                            alert('Please select items with the same company ID.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        }

        function destroyOrderContract(orderId) {
            if (confirm("Are you sure you want to delete this order contract?")) {
                let data = {
                    orderId: orderId
                }
                axios.post("{{ route('admin.transportations.ajax.destroyOrderContract') }}", data)
                    .then(response => {
                        alert(response.data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                alert("Delete operation canceled.");
            }
        }
    </script>
@endsection
