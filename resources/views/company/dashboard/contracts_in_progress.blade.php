@if (!$orders->isEmpty())
    @foreach ($orders as $order)
        @if (!empty($order->contract))
            <div class="card mb-4" style="background-color: #f8f9fa;">
                <div class="card-header">
                    <h3 class="mb-0">Order #{{ $order->id }}</h3>
                </div>
                <div class="card-body">
                    <!-- Invoice Section -->
                    <div id="invoice-{{ $order->id }}">
                        <h4>Invoice</h4>
                        <ol class="list-group list-group-numbered mb-4">
                            @foreach ($order->invoiceOrders as $invoice)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">Tracking Number: {{ $invoice->id }}</div>
                                        <a href="{{ asset($invoice->invoice) }}" target="_blank">invoice-{{ $invoice->id }}</a>
                                    </div>
                                    <form class="float-end" action="{{ route('company.dashboard.invoiceDestroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?');">
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
                            <form action="{{ route('company.dashboard.uploadInvoice') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <div class="input-group mb-3">
                                    <input type="file" name="invoice" class="form-control form-control-sm" id="invoice" required>
                                    <label class="input-group-text" for="invoice">Add Invoice</label>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm">Upload</button>
                            </form>
                        </div>
                    </div>

                    <!-- CMR Section -->
                    <div id="cmr-{{ $order->id }}" class="mt-4">
                        <h4>CMR</h4>
                        <ol class="list-group list-group-numbered mb-4">
                            @foreach ($order->cmrOrders as $cmr)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">Tracking Number: {{ $cmr->id }}</div>
                                        <a href="{{ asset($cmr->cmr) }}" target="_blank">cmr-{{ $cmr->id }}</a>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    <!-- Files Section -->
                    <div id="file-{{ $order->id }}" class="mt-4">
                        <h4>Files</h4>
                        <ol class="list-group list-group-numbered mb-4">
                            @foreach ($order->fileOrders as $file)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div>Tracking Number: {{ $file->id }}</div>
                                        <h6>{{ $file->name }}</h6>
                                        <a href="{{ asset($file->file) }}" target="_blank">file-{{ $file->id }}</a>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif
