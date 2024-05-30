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
                <button type="submit" class="btn btn-success">{{ __('Send Verification Email') }}</button>
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
                    <input onclick="syncTruckForCompany({{ $truck->id }})" class="form-check-input" type="checkbox"
                        role="switch" id="signed_by" name="signed_by" value=""
                        {{ $company->trucks->contains($truck->id) ? 'checked' : '' }}>
                </div>
            </li>
        @endforeach
    </ol>
</div>
