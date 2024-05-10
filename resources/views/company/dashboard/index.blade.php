@extends('company.layouts.index')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-9">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Company Dashboard</li>
                </ol>
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
            <div class="col-sm-12 col-md-3">
                <div class="alert alert-info">
                    Select your Trucks
                </div>
                <ol class="list-group list-group-numbered" id="truckList">
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let truckList = document.getElementById('truckList');
            truckList.innerHTML = ``;

            axios.get("{{ route('company.dashboard.ajax.getTruck') }}")
                .then(response => {
                    response.data.forEach(element => {
                        truckList.innerHTML += `
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">${element.name}</div>
                            ${element.lwh} | ${element.load_capacity} Kg
                        </div>
                        <div class="form-switch">
                            <input onclick="syncTruckForCompany(${element.id})" class="form-check-input" type="checkbox" role="switch"
                                id="signed_by" name="signed_by" value="">
                        </div>
                    </li>`;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        });

        function syncTruckForCompany(truckId) {
            alert(truckId)
        }
    </script>
@endsection
