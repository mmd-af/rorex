@foreach ($transportations as $transport)
    <div class="row m-5 p-5 border border-1 rounded-3 shadow shadow-lg">
        <div class="col-sm-12 col-lg-4">
            <div class="list-group" id="list-tab" role="tablist">
                <a class="list-group-item list-group-item-action active" id="list-home-list" data-bs-toggle="list"
                    href="#description-{{ $transport->id }}" role="tab" aria-controls="list-home">Description</a>
                <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list"
                    href="#specifications-{{ $transport->id }}" role="tab"
                    aria-controls="list-profile">Specifications</a>
                <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list"
                    href="#trucks-{{ $transport->id }}" role="tab" aria-controls="list-profile">Trucks</a>
                @if (!$orders->where('transportation_id', $transport->id)->isEmpty())
                    <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list"
                        href="#order-{{ $transport->id }}" role="tab" aria-controls="list-profile"><b>Your
                            Offer</b></a>
                    @if ($orders->where('transportation_id', $transport->id)->first()->contract)
                        <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list"
                            href="#invoice-{{ $transport->id }}" role="tab"
                            aria-controls="list-profile"><b>Invoice</b></a>
                        <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list"
                            href="#cmr-{{ $transport->id }}" role="tab" aria-controls="list-profile"><b>CMR</b></a>
                        <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list"
                            href="#file-{{ $transport->id }}" role="tab"
                            aria-controls="list-profile"><b>Files</b></a>
                    @endif
                @endif
            </div>
        </div>
        <div class="col-sm-12 col-lg-8">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="description-{{ $transport->id }}" role="tabpanel"
                    aria-labelledby="list-home-list">
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
                            <h3>Invoice</h3>
                            <ol class="list-group list-group-numbered">
                                @foreach ($orders->where('transportation_id', $transport->id)->first()->invoiceOrders as $invoice)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">Tracking Number: {{ $invoice->id }}
                                            </div>
                                            <a href="{{ asset($invoice->invoice) }}"
                                                target="_blank">invoice-{{ $invoice->id }}</a>
                                            <form class="float-end"
                                                action="{{ route('company.dashboard.invoiceDestroy', $invoice->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this file?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>


                            <div class="mt-4">
                                <form action="{{ route('company.dashboard.uploadInvoice') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="order_id"
                                        value="{{ $orders->where('transportation_id', $transport->id)->first()->id }}">
                                    <div class="input-group mb-3">
                                        <input type="file" name="invoice" class="form-control form-control-sm"
                                            id="invoice" required>
                                        <label class="input-group-text" for="invoice">Add
                                            Invoice</label>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm">upload</button>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="cmr-{{ $transport->id }}" role="tabpanel"
                            aria-labelledby="list-home-list">
                            <h3>CMR</h3>
                            <ol class="list-group list-group-numbered">
                                @foreach ($orders->where('transportation_id', $transport->id)->first()->cmrOrders as $cmr)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">Tracking Number: {{ $cmr->id }}
                                            </div>
                                            <a href="{{ asset($cmr->cmr) }}"
                                                target="_blank">cmr-{{ $cmr->id }}</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                        <div class="tab-pane fade" id="file-{{ $transport->id }}" role="tabpanel"
                            aria-labelledby="list-home-list">
                            <h3>Files</h3>
                            <ol class="list-group list-group-numbered">
                                @foreach ($orders->where('transportation_id', $transport->id)->first()->fileOrders as $file)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div>Tracking Number: {{ $file->id }}
                                            </div>
                                            <h6>{{ $file->name }}</h6>
                                            <a href="{{ asset($file->file) }}"
                                                target="_blank">file-{{ $file->id }}</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                @endif
            </div>
            @if ($orders->where('transportation_id', $transport->id)->isEmpty())
                <button type="button" onclick="applyOrder({{ $transport }})"
                    class="btn btn-success m-3 float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Apply
                </button>
            @else
      
            @endif
        </div>
    </div>
@endforeach
