@extends('admin.layouts.index')

@section('title')
    Orders
@endsection
@section('style')
    <style>
    </style>
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Orders</li>
    </ol>
    @include('admin.layouts.partial.errors')
    {{-- @dd($order->transportation,$order->company,$order->truck,$order) --}}


    <!-- Transportation Button trigger modal -->
    <button type="button" class="btn btn-outline-primary mx-3 p-3 mt-2" data-bs-toggle="modal"
        data-bs-target="#transportationInformation">
        Show Transportation
    </button>

    <!-- Transportation Modal -->
    <div class="modal fade" id="transportationInformation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="transportationInformationLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="transportationInformationLabel">Transportation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th colspan="2">Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Product Name</th>
                                    <td>{{ $order->transportation->product_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Product Number</th>
                                    <td>{{ $order->transportation->product_number }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">From Date</th>
                                    <td>{{ $order->transportation->from_date }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Until Date</th>
                                    <td>{{ $order->transportation->until_date }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Country of Origin</th>
                                    <td>{{ $order->transportation->country_of_origin }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">City of Origin</th>
                                    <td>{{ $order->transportation->city_of_origin }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Destination Country</th>
                                    <td>{{ $order->transportation->destination_country }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Destination City</th>
                                    <td>{{ $order->transportation->destination_city }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Weight of each Truck</th>
                                    <td>{{ $order->transportation->weight_of_each_car }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Description </th>
                                    <td>{{ $order->transportation->description }}</td>
                                </tr>
                                @foreach ($order->transportation->trucks as $truck)
                                    <tr>
                                        <th class="bg-info" scope="row">{{ $truck->name }} | {{ $truck->lwh }} |
                                            {{ $truck->load_capacity }} Kg</th>
                                        <td class="bg-info">{{ $truck->pivot->qty }}</td>
                                    </tr>
                                @endforeach
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

    <!-- Company Button trigger modal -->
    <button type="button" class="btn btn-outline-primary mx-3 p-3 mt-2" data-bs-toggle="modal"
        data-bs-target="#companyInformation">
        Show Company
    </button>

    <!-- Company Modal -->
    <div class="modal fade" id="companyInformation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="companyInformationLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="companyInformationLabel">Company</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ol class="list-group list-group-numbered" id="truckList">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Company Name</div>
                                {{ $order->company->company_name }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Activity Domain</div>
                                {{ $order->company->activity_domain }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Vat Id</div>
                                {{ $order->company->vat_id }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Registration Number</div>
                                {{ $order->company->registration_number }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Country | County | City</div>
                                {{ $order->company->country }} | {{ $order->company->county }} |
                                {{ $order->company->city }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Address | Zip Code | Building </div>
                                {{ $order->company->address }} | {{ $order->company->zip_code }} |
                                {{ $order->company->building }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Phone Number</div>
                                {{ $order->company->phone_number }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Person Name | Job Title</div>
                                {{ $order->company->person_name }} | {{ $order->company->job_title }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Email:</div>
                                {{ $order->company->users->email }}
                            </div>
                        </li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Truck Button trigger modal -->
    <button type="button" class="btn btn-outline-primary mx-3 p-3 mt-2" data-bs-toggle="modal"
        data-bs-target="#truckInformation">
        Show Truck
    </button>

    <!-- Truck Modal -->
    <div class="modal fade" id="truckInformation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="truckInformationLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="truckInformationLabel">Truck</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ol class="list-group list-group-numbered" id="truckList">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Truck Name</div>
                                {{ $order->truck->name }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">LWH</div>
                                {{ $order->truck->lwh }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Total Height</div>
                                {{ $order->truck->total_height }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Load Capacity</div>
                                {{ $order->truck->load_capacity }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Covered</div>
                                {{ $order->truck->covered }}
                            </div>
                        </li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <hr style=" border-top: 6px solid #000;">

    <div class="row">
        <div class="col-sm-12 col-lg-4 m-2 p-3 shadow" style="background-color: beige">
            <ol class="list-group" id="truckList">
                <li class="list-group-item">
                    <div class="ms-2 me-auto">
                        <h3>Information</h3>
                        <h6>Price for each Truck</h6>
                        @if ($order->last_price === null)
                            <span class="text-success"><b>{{ $order->price }} €</b></span>
                        @else
                            <span class="text-decoration-line-through text-danger">{{ $order->price }} €</span>
                            <span class="text-success"><b>{{ $order->last_price }}
                                    €</b></span>
                        @endif

                    </div>
                    <a href="{{ asset($order->contract) }}" class="btn btn-outline-info m-3 p-3"
                        target="_blank">Contract</a>

                </li>
            </ol>
        </div>
        <div class="col-sm-12 col-lg-4 m-2 p-3 shadow" style="background-color: beige">
            <h3>Invoice</h3>
            <ol class="list-group list-group-numbered">
                @foreach ($order->invoiceOrders as $invoice)
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Tracking Number: {{ $invoice->id }}</div>
                            <a href="{{ asset($invoice->invoice) }}" target="_blank">invoice-{{ $invoice->id }}</a>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
        <div class="col-sm-12 col-lg-4 m-2 p-3 shadow" style="background-color: beige">
            <h3>CMR</h3>
            <ol class="list-group list-group-numbered">
                @foreach ($order->cmrOrders as $cmr)
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Tracking Number: {{ $cmr->id }}</div>
                            <a href="{{ asset($cmr->cmr) }}" target="_blank">cmr-{{ $cmr->id }}</a>
                            <form class="float-end" action="{{ route('admin.orders.cmrDestroy', $cmr->id) }}"
                                method="POST" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"> <i class="fa fa-trash"
                                        aria-hidden="true"></i> </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ol>
            <div class="mt-4">
                <form action="{{ route('admin.orders.uploadCmr') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="input-group mb-3">
                        <input type="file" name="cmr" class="form-control form-control-sm" id="cmr"
                            required>
                        <label class="input-group-text" for="cmr">Add CMR</label>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">upload</button>
                </form>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4 m-2 p-3 shadow" style="background-color: beige">
            <h3>Files</h3>
            <ol class="list-group list-group-numbered">
                @foreach ($order->fileOrders as $file)
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div>Tracking Number: {{ $file->id }}</div>
                            <h6>{{ $file->name }}</h6>
                            <a href="{{ asset($file->file) }}" target="_blank">file-{{ $file->id }}</a>
                            <form class="float-end" action="{{ route('admin.orders.fileDestroy', $file->id) }}"
                                method="POST" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"> <i class="fa fa-trash"
                                        aria-hidden="true"></i> </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ol>
            <div class="mt-4">
                <form action="{{ route('admin.orders.uploadFile') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <input type="text" class="form-control form-control-sm" name="name" id="name" placeholder="file name"
                        value="{{ old('name') }}" required>
                    <div class="input-group mb-3">
                        <input type="file" name="file" class="form-control form-control-sm" id="file"
                            required>
                        <label class="input-group-text" for="file">Add File</label>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">upload</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script></script>
@endsection
