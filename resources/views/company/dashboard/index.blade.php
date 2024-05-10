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
