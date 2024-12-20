@if (!$orders->isEmpty())
    <div class="accordion" id="orderAccordion">
        <div class="row p-3 m-3">
            @foreach ($orders as $order)
                @if (!empty($order->contract))
                    <div class="col-12 m-2 p-2" style="background-color: antiquewhite">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $order->id }}">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $order->id }}" aria-expanded="true"
                                    aria-controls="collapse{{ $order->id }}">
                                    #{{ $order->transportation->product_name }} |
                                    {{ $order->transportation->product_number }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |
                                    &nbsp;&nbsp;&nbsp;<strong> Truck: </strong>
                                    &nbsp;&nbsp;&nbsp; {{ $order->truck->name }} &nbsp;&nbsp;&nbsp;|
                                    &nbsp;&nbsp;&nbsp;{{ $order->truck->lwh }}
                                </button>
                            </h2>
                            <div id="collapse{{ $order->id }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ $order->id }}" data-bs-parent="#orderAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-6">
                                            <!-- Contract Section -->
                                            <div class="border shadow-lg p-3">
                                                <ol class="list-group" id="truckList">
                                                    <li class="list-group-item">
                                                        <div class="ms-2 me-auto">
                                                            <h3>Information</h3>
                                                            <h6>Price for each Truck</h6>
                                                            @if ($order->last_price === null)
                                                                <span class="text-success"><b>{{ $order->price }}
                                                                        €</b></span>
                                                            @else
                                                                <span
                                                                    class="text-decoration-line-through text-danger">{{ $order->price }}
                                                                    €</span>
                                                                <span class="text-success"><b>{{ $order->last_price }}
                                                                        €</b></span>
                                                            @endif

                                                        </div>
                                                        <a href="{{ asset($order->contract) }}"
                                                            class="btn btn-outline-info m-3 p-3"
                                                            target="_blank">Contract</a>
                                                    </li>
                                                </ol>
                                            </div>
                                            <!-- Invoice Section -->
                                            <div class="border shadow-lg p-3 mt-4">
                                                <h4>Invoice</h4>
                                                <ol class="list-group list-group-numbered mb-4">
                                                    @foreach ($order->invoiceOrders as $invoice)
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-start">
                                                            <div class="ms-2 me-auto">
                                                                <div class="fw-bold">Tracking Number:
                                                                    {{ $invoice->id }}</div>
                                                                <a href="{{ asset($invoice->invoice) }}"
                                                                    target="_blank">invoice-{{ $invoice->id }}</a>
                                                            </div>
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
                                                        </li>
                                                    @endforeach
                                                </ol>
                                                <div class="mt-4">
                                                    <form action="{{ route('company.dashboard.uploadInvoice') }}"
                                                        method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="order_id"
                                                            value="{{ $order->id }}">
                                                        <div class="input-group mb-3">
                                                            <input type="file" name="invoice"
                                                                class="form-control form-control-sm" id="invoice"
                                                                required>
                                                            <label class="input-group-text" for="invoice">Add
                                                                Invoice</label>
                                                        </div>
                                                        <button type="submit"
                                                            class="btn btn-success btn-sm">Upload</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- CMR Section -->
                                            <div class="border shadow-lg p-3 mt-4">
                                                <h4>CMR</h4>
                                                <ol class="list-group list-group-numbered mb-4">
                                                    @foreach ($order->cmrOrders as $cmr)
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-start">
                                                            <div class="ms-2 me-auto">
                                                                <div class="fw-bold">Tracking Number:
                                                                    {{ $cmr->id }}</div>
                                                                <a href="{{ asset($cmr->cmr) }}"
                                                                    target="_blank">cmr-{{ $cmr->id }}</a>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            </div>
                                            <!-- Files Section -->
                                            <div class="border shadow-lg p-3 mt-4">
                                                <h4>Files</h4>
                                                <ol class="list-group list-group-numbered mb-4">
                                                    @foreach ($order->fileOrders as $file)
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-start">
                                                            <div class="ms-2 me-auto">
                                                                <div>Tracking Number: {{ $file->id }}</div>
                                                                <h6>{{ $file->name }}</h6>
                                                                <a href="{{ asset($file->file) }}"
                                                                    target="_blank">file-{{ $file->id }}</a>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-6">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="2">Shipping information</th>
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
                                                            @if ($truck->name === $order->truck->name)
                                                                <tr>
                                                                    <th class="bg-info" scope="row">
                                                                        {{ $truck->name }}
                                                                        {{ $truck->load_capacity }} Kg</th>
                                                                    <td class="bg-info">QTY= <h5>
                                                                            {{ $truck->pivot->qty }}</h5>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <ol class="list-group list-group-numbered mt-3" id="truckList">
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-start">
                                                    <div class="ms-2 me-auto">
                                                        <div class="fw-bold">Truck Name</div>
                                                        {{ $order->truck->name }}
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-start">
                                                    <div class="ms-2 me-auto">
                                                        <div class="fw-bold">LWH</div>
                                                        {{ $order->truck->lwh }}
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-start">
                                                    <div class="ms-2 me-auto">
                                                        <div class="fw-bold">Total Height</div>
                                                        {{ $order->truck->total_height }}
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-start">
                                                    <div class="ms-2 me-auto">
                                                        <div class="fw-bold">Load Capacity</div>
                                                        {{ $order->truck->load_capacity }}
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-start">
                                                    <div class="ms-2 me-auto">
                                                        <div class="fw-bold">Covered</div>
                                                        {{ $order->truck->covered }}
                                                    </div>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif
