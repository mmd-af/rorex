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
                                                        <td>Weight of each Car</td>
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
                                </div>
                                <button type="submit" class="btn btn-success m-3 float-end">Apply</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-sm-12 col-md-3">
                <div class="bg-warning p-3">
                    @if (session('status') == 'verification-link-sent')
                        <div class="text-success">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif
                    @if (!auth()->user()->hasVerifiedEmail())
                        <div class="alert alert-warning" role="alert">
                            {{ __('Your email address is not verified. Please verify your email address.') }}
                        </div>
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <div>
                                <button type="submit" class="btn btn-success">{{ __('Send Verification Email') }}</button>
                            </div>
                        </form>
                    @endif
                </div>
                <div class="bg-info p-3 mt-3">
                    <div class="alert alert-info">
                        Select your Trucks
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
                                        {{ $companyTrucks->contains($truck->id) ? 'checked' : '' }}>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
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
    </script>
@endsection