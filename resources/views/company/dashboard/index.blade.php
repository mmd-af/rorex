@extends('company.layouts.index')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-9">
                <ol class="breadcrumb mb-4 mt-3">
                    <li class="breadcrumb-item active"><strong>Company Dashboard</strong></li>
                </ol>
                <div id="showTransportation">
                    @foreach ($transportations as $transport)
                        <div class="row m-5 p-5 border border-1 rounded-3 shadow shadow-lg">
                            <div class="col-sm-12 col-lg-4">
                                <div class="list-group" id="list-tab" role="tablist">
                                    <a class="list-group-item list-group-item-action active" id="list-home-list"
                                        data-bs-toggle="list" href="#description-{{ $transport->id }}" role="tab"
                                        aria-controls="list-home">Description</a>
                                    <a class="list-group-item list-group-item-action" id="list-profile-list"
                                        data-bs-toggle="list" href="#specifications-{{ $transport->id }}" role="tab"
                                        aria-controls="list-profile">Specifications</a>
                                    <a class="list-group-item list-group-item-action" id="list-profile-list"
                                        data-bs-toggle="list" href="#trucks-{{ $transport->id }}" role="tab"
                                        aria-controls="list-profile">Trucks</a>
                                    @if (!$orders->where('transportation_id', $transport->id)->isEmpty())
                                        <a class="list-group-item list-group-item-action" id="list-profile-list"
                                            data-bs-toggle="list" href="#order-{{ $transport->id }}" role="tab"
                                            aria-controls="list-profile"><b>Your Offer</b></a>
                                        @if ($orders->where('transportation_id', $transport->id)->first()->contract)
                                            <a class="list-group-item list-group-item-action" id="list-profile-list"
                                                data-bs-toggle="list" href="#invoice-{{ $transport->id }}" role="tab"
                                                aria-controls="list-profile"><b>Invoice</b></a>
                                        @endif

                                        {{-- <div class="mt-4">
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
                                            </div>                                            --}}
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-8">
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="description-{{ $transport->id }}"
                                        role="tabpanel" aria-labelledby="list-home-list">
                                        <h3>{{ $transport->product_name }}</h3>
                                        {!! $transport->description !!}
                                    </div>
                                    <div class="tab-pane fade" id="specifications-{{ $transport->id }}" role="tabpanel"
                                        aria-labelledby="list-profile-list">
                                        <div class="table-responsive">
                                            <table class="table table-primary">
                                                <tbody>
                                                    <tr>
                                                        <td>Product Name</td>
                                                        <td>{{ $transport->product_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>From Date</td>
                                                        <td>{{ $transport->from_date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Until Date</td>
                                                        <td>{{ $transport->until_date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Country of Origin</td>
                                                        <td>{{ $transport->country_of_origin }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>City of Origin</td>
                                                        <td>{{ $transport->city_of_origin }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Destination Country</td>
                                                        <td>{{ $transport->destination_country }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Destination City</td>
                                                        <td>{{ $transport->destination_city }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Weight of each Car (Kg)</td>
                                                        <td>{{ $transport->weight_of_each_car }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="trucks-{{ $transport->id }}" role="tabpanel"
                                        aria-labelledby="list-home-list">
                                        @foreach ($transport->trucks as $truck)
                                            <div class="table-responsive">
                                                <table class="table table-primary">
                                                    <thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="bg-info "><strong>QTY</strong></td>
                                                            <td class="bg-info "><strong>{{ $truck->pivot->qty }}</strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Truck Name</td>
                                                            <td>{{ $truck->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>L W H</td>
                                                            <td>{{ $truck->lwh }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Height</td>
                                                            <td>{{ $truck->total_height }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Load Capacity</td>
                                                            <td>{{ $truck->load_capacity }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Covered</td>
                                                            <td>{{ $truck->covered }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if (!$orders->where('transportation_id', $transport->id)->isEmpty())
                                        <div class="tab-pane fade" id="order-{{ $transport->id }}" role="tabpanel"
                                            aria-labelledby="list-home-list">
                                            @foreach ($orders->where('transportation_id', $transport->id) as $order)
                                                <div class="table-responsive">
                                                    <table class="table table-primary">
                                                        <tbody>
                                                            <tr>
                                                                <td><b>Price: </b></td>
                                                                <td>for <b>{{ $order->truck->name }}</b></td>
                                                                <td>{{ $order->price }} €</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endforeach
                                        </div>
                                        @if ($orders->where('transportation_id', $transport->id)->first()->contract)
                                            <div class="tab-pane fade" id="invoice-{{ $transport->id }}" role="tabpanel"
                                                aria-labelledby="list-home-list">
                                                <div class="mt-4">
                                                    <form action="{{ route('company.dashboard.uploadInvoice') }}" method="post"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="order_id"
                                                            value="{{ $orders->where('transportation_id', $transport->id)->first()->id }}">
                                                        <div class="input-group mb-3">
                                                            <input type="file" name="invoice"
                                                                class="form-control form-control-sm" id="invoice"
                                                                required>
                                                            <label class="input-group-text" for="invoice">Add Invoice</label>
                                                        </div>
                                                        <button type="submit"
                                                            class="btn btn-success btn-sm">upload</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                @if ($orders->where('transportation_id', $transport->id)->isEmpty())
                                    <button type="button" onclick="applyOrder({{ $transport }})"
                                        class="btn btn-success m-3 float-end" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        Apply
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-sm-12 col-md-3">
                @if (session('status') == 'verification-link-sent')
                    <div class="text-success">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif
                @if (!auth()->user()->hasVerifiedEmail())
                    <div class="bg-warning p-3">
                        <div class="alert alert-warning" role="alert">
                            {{ __('Your email address is not verified. Please verify your email address.') }}
                        </div>
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <div>
                                <button type="submit"
                                    class="btn btn-success">{{ __('Send Verification Email') }}</button>
                            </div>
                        </form>
                    </div>
                @endif

                <div class="bg-secondary p-3 mt-3">
                    <div class="alert alert-light">
                        Your profile
                    </div>
                    <ol class="list-group list-group-numbered" id="truckList">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Company Name</div>
                                {{ $company->company_name }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Activity Domain</div>
                                {{ $company->activity_domain }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Vat Id</div>
                                {{ $company->vat_id }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Registration Number</div>
                                {{ $company->registration_number }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Phone Number</div>
                                {{ $company->phone_number }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Email:</div>
                                {{ $company->users->email }}
                            </div>
                        </li>
                    </ol>
                </div>

                <div class="bg-info p-3 mt-3">
                    <div class="alert alert-info">
                        Active your Trucks
                    </div>
                    <ol class="list-group list-group-numbered" id="truckList">
                        @foreach ($allTrucks as $truck)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">{{ $truck->name }}</div>
                                    {{ $truck->lwh }} | {{ $truck->load_capacity }} Kg
                                </div>
                                <div class="form-switch">
                                    <input onclick="syncTruckForCompany({{ $truck->id }})" class="form-check-input"
                                        type="checkbox" role="switch" id="signed_by" name="signed_by" value=""
                                        {{ $company->trucks->contains($truck->id) ? 'checked' : '' }}>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Apply</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('company.dashboard.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                        <b>If you do not have the corresponding truck, enter the number 0.</b>
                        <div id="suggestOrder">
                        </div>
                        <div class="alert alert-success text-center" id="totalPrice"></div>
                        <button type="submit" class="btn btn-success float-end mt-5">Send Request</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function syncTruckForCompany(truckId) {
            let data = {
                truckId: truckId
            }
            axios.post("{{ route('company.dashboard.ajax.syncTruckForCompany') }}", data)
                .then(response => {
                    alert(response.data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function applyOrder(transfer) {
            let suggestOrder = document.getElementById('suggestOrder');
            suggestOrder.innerHTML = ``;
            suggestOrder.innerHTML = `<input type="hidden" name="transportationId" value="${transfer.id}">`;
            transfer.trucks.forEach(element => {
                let truckId = element.id;
                suggestOrder.innerHTML += `
            <div class="mb-3 mt-3">
                <label for="suggestPrice_${truckId}" class="form-label">Suggest price for each truck <b>${element.name}</b> ------------ total QTY= <b>${element.pivot.qty}</b></label>
                <div class="d-flex jusdtify-content-center">  
                    <div class="col-8"><input type="number" class="form-control" name="${truckId}" id="suggestPrice_${truckId}" value="" oninput="calculateResult(${element.pivot.qty}, ${truckId})" required /></div>
                    <div class="col-4"><div class="alert alert-warning text-center p-1 m-2" id="showResult_${truckId}"></div></div>
                </div>
            </div>`;
            });
        }

        function calculateResult(qty, truckId) {
            var input = document.getElementById(`suggestPrice_${truckId}`).value;
            var showResult = document.getElementById(`showResult_${truckId}`);
            let result = qty * input;
            showResult.innerText = result + "€";

            var totalPrice = 0;
            document.querySelectorAll('[id^="showResult_"]').forEach(element => {
                totalPrice += parseFloat(element.innerText) || 0;
            });

            var totalPriceElement = document.getElementById("totalPrice");
            totalPriceElement.innerText = "Total Price= " + totalPrice + "€";
        }
    </script>
@endsection
