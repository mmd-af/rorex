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
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#transportationInformation">
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
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#companyInformation">
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
@endsection

@section('script')
    <script></script>
@endsection
