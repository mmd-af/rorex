@extends('auth.layouts.index')

@section('title')
    Register
@endsection
@section('content')
    <section style="background-color: #009799;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;">
                        @include('auth.layouts.partial.errors')
                        <form method="POST" action="{{ route('companies.register') }}">
                            @csrf
                            <div class="row g-0">
                                <div class="col-md-6 col-lg-6 d-flex align-items-center">
                                    <div class="card-body p-4 p-lg-5 text-black">
                                        <div class="d-flex align-items-center mb-3 pb-1">
                                            <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                            <span class="h1 fw-bold mb-0">
                                                <img src="{{ asset('build/img/logo.png') }}" alt="login form"
                                                    class="img-fluid" />
                                            </span>
                                        </div>
                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Register
                                            Company</h5>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="company_name">Company Name:*</label>
                                            <input type="text" name="company_name" id="company_name" class="form-control"
                                                value="{{ old('company_name') }}" required />
                                            @if ($errors->has('company_name'))
                                                <p style="color: red;">{{ $errors->first('company_name') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="activity_domain">Activity Domain:*</label>
                                            <select name="activity_domain" id="activity_domain" class="form-control"
                                                required>
                                                <option value="transporter" selected>Transporter</option>
                                            </select>
                                            @if ($errors->has('activity_domain'))
                                                <p style="color: red;">{{ $errors->first('activity_domain') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="vat_id">CUI / VAT ID:*</label>
                                            <input type="text" name="vat_id" id="vat_id" class="form-control"
                                                value="{{ old('vat_id') }}" required />
                                            @if ($errors->has('vat_id'))
                                                <p style="color: red;">{{ $errors->first('vat_id') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label"
                                                for="registration_number">Registration_Number:*</label>
                                            <input type="text" name="registration_number" id="registration_number"
                                                class="form-control" value="{{ old('registration_number') }}" required />
                                            @if ($errors->has('registration_number'))
                                                <p style="color: red;">{{ $errors->first('registration_number') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="country">Country:*</label>
                                            <input type="text" name="country" id="country" class="form-control"
                                                value="{{ old('country') }}" required />
                                            @if ($errors->has('country'))
                                                <p style="color: red;">{{ $errors->first('country') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="county">County:*</label>
                                            <input type="text" name="county" id="county" class="form-control"
                                                value="{{ old('county') }}" required />
                                            @if ($errors->has('county'))
                                                <p style="color: red;">{{ $errors->first('county') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="city">City:*</label>
                                            <input type="text" name="city" id="city" class="form-control"
                                                value="{{ old('city') }}" required />
                                            @if ($errors->has('city'))
                                                <p style="color: red;">{{ $errors->first('city') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="zip_code">Zip Code:</label>
                                            <input type="text" name="zip_code" id="zip_code" class="form-control"
                                                value="{{ old('zip_code') }}" />
                                            @if ($errors->has('zip_code'))
                                                <p style="color: red;">{{ $errors->first('zip_code') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="address">Address:*</label>
                                            <input type="text" name="address" id="address" class="form-control"
                                                value="{{ old('address') }}" required />
                                            @if ($errors->has('address'))
                                                <p style="color: red;">{{ $errors->first('address') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="building">Building name, floor, door, etc.
                                                :</label>
                                            <input type="text" name="building" id="building" class="form-control"
                                                value="{{ old('building') }}" />
                                            @if ($errors->has('building'))
                                                <p style="color: red;">{{ $errors->first('building') }}</p>
                                            @endif
                                        </div>
                                        {{--                                    <a class="small text-muted" href="#!">Forgot password?</a> --}}
                                        {{--                                    <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="#!" --}}
                                        {{--                                                                                                              style="color: #393f81;">Register --}}
                                        {{--                                            here</a></p> --}}
                                        {{--                                    <a href="#!" class="small text-muted">Terms of use.</a> --}}
                                        {{--                                    <a href="#!" class="small text-muted">Privacy policy</a> --}}
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="d-none d-md-block float-end">
                                        <img src="{{ asset('build/img/rorex-pipe.jpg') }}" alt="login form"
                                            class="img-fluid" style="border-radius: 1rem 1rem 1rem 5rem;" />
                                    </div>
                                    <div class="col-12 d-flex align-items-center">
                                        <div class="card-body p-4 p-lg-5 text-black">
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="person_name">Person Name:*</label>
                                                <input type="text" name="person_name" id="person_name"
                                                    class="form-control" value="{{ old('person_name') }}" required />
                                                @if ($errors->has('person_name'))
                                                    <p style="color: red;">{{ $errors->first('person_name') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="job_title">Job Title:</label>
                                                <input type="text" name="job_title" id="job_title"
                                                    class="form-control" value="{{ old('job_title') }}" />
                                                @if ($errors->has('job_title'))
                                                    <p style="color: red;">{{ $errors->first('job_title') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="phone_number">Phone Number:*</label>
                                                <input type="text" name="phone_number" id="phone_number"
                                                    class="form-control" value="{{ old('phone_number') }}" required />
                                                @if ($errors->has('phone_number'))
                                                    <p style="color: red;">{{ $errors->first('phone_number') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="email">Email address:*</label>
                                                <input type="text" name="email" id="email" class="form-control"
                                                    value="{{ old('email') }}" required />
                                                @if ($errors->has('email'))
                                                    <p style="color: red;">{{ $errors->first('email') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="password">Password:*</label>
                                                <input type="password" id="password" name="password" required
                                                    autocomplete="current-password" class="form-control" required />
                                                @if ($errors->has('password'))
                                                    <p style="color: red;">{{ $errors->first('password') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="password_confirmation">Password
                                                    Confirmation:*</label>
                                                <input type="password" id="password_confirmation"
                                                    name="password_confirmation" required
                                                    autocomplete="current-password_confirmation" class="form-control"
                                                    required />
                                                @if ($errors->has('password_confirmation'))
                                                    <p style="color: red;">{{ $errors->first('password_confirmation') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center p-3">
                                    <button class="btn btn-success btn-lg btn-block" type="submit">Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
