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
                <div>



                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                                type="button" role="tab" aria-controls="nav-home" aria-selected="true">Requests
                                List</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                                type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Contracts in
                                progress</button>
                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact"
                                type="button" role="tab" aria-controls="nav-contact"
                                aria-selected="false">Archive</button>
                        </div>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
                            tabindex="0">
                            @include('company.dashboard.request_lists')
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                            tabindex="0">...</div>
                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab"
                            tabindex="0">...</div>
                    </div>






                </div>
            </div>
            <div class="col-sm-12 col-md-3">
                @include('company.dashboard.sidebar')
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
