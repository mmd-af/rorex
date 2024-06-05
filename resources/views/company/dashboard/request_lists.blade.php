<style>
    .img__wrapper {
        height: 140px;
        position: relative;
        overflow: hidden;
        z-index: 9999;
    }

    .img__wrapper2 {
        position: absolute;
        overflow: hidden;
        z-index: -999999;
    }

    .sold_out {
        top: 2em;
        left: -4em;
        color: #fff;
        display: block;
        position: absolute;
        text-align: center;
        text-decoration: none;
        letter-spacing: .06em;
        background-color: #A00;
        padding: 0.5em 5em 0.4em 5em;
        text-shadow: 0 0 0.75em #444;
        box-shadow: 0 0 0.5em rgba(0, 0, 0, 0.5);
        font: bold 20px/1em Arial, Sans-Serif;
        -webkit-text-shadow: 0 0 0.75em #444;
        -webkit-box-shadow: 0 0 0.5em rgba(0, 0, 0, 0.5);
        -webkit-transform: rotate(-45deg) scale(0.75, 1);
        z-index: 9999;
    }

    .sold_out2 {
        color: #fff;
        display: block;
        padding: 30px 0px 5px 130px;
        text-shadow: 0 0 0.75em #444;
        font: bold 250px/1em Arial, Sans-Serif;
        opacity: 0.5;
        z-index: -999999;

    }

    .sold_out:before {
        content: '';
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        position: absolute;
        margin: -0.3em -5em;
        transform: scale(0.7);
        -webkit-transform: scale(0.7);
        border: 2px rgba(255, 255, 255, 0.7) dashed;
        z-index: 9999;
    }
</style>
@foreach ($transportations as $transport)
    @if (
        !$transport->orders->contains(function ($order) use ($company) {
            return $order->company_id == $company->id && !empty($order->contract);
        }))
        <div class="row m-5 border border-1 rounded-3 shadow shadow-lg">
            @if (
                $transport->orders->contains(function ($order) {
                    return !empty($order->contract);
                }))
                <div class="img__wrapper">
                    <p class="sold_out" href="">Finished</p>
                </div>

                <div class="img__wrapper2">
                    <p class="sold_out2" href="">Finished</p>
                </div>
            @endif
            <div class="col-sm-12 col-lg-4 py-3">
                <div class="list-group" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="list-home-list" data-bs-toggle="list"
                        href="#description-{{ $transport->id }}" role="tab"
                        aria-controls="list-home">Description</a>
                    <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list"
                        href="#specifications-{{ $transport->id }}" role="tab"
                        aria-controls="list-profile">Specifications</a>
                    <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list"
                        href="#trucks-{{ $transport->id }}" role="tab" aria-controls="list-profile">Trucks</a>
                </div>
            </div>
            <div class="col-sm-12 col-lg-8 py-5">
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
                                        <td>Product Number</td>
                                        <td>{{ $transport->product_number }}</td>
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
                </div>
                @if ($orders->where('transportation_id', $transport->id)->isEmpty())
                    @if (
                        !$transport->orders->contains(function ($order) {
                            return !empty($order->contract);
                        }))
                        <button type="button" onclick="applyOrder({{ $transport }})"
                            class="btn btn-success m-3 float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Apply
                        </button>
                    @endif
                @else
                    <div class="border shadow mt-5 p-3" id="order-{{ $transport->id }}">
                        <h5>Your Offer:</h5>
                        @foreach ($orders->where('transportation_id', $transport->id) as $order)
                            <div class="table-responsive">
                                <table class="table table-warning">
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
                @endif
            </div>
        </div>
    @endif
@endforeach
