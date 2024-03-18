@extends('auth.layouts.index')

@section('title')
    Login
@endsection
@section('content')
    <section class="vh-100" style="background-color: #009799;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
{{--                                <img src="{{asset('build/img/rorex-pipe.jpg')}}"--}}
{{--                                     alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;"/>--}}
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="d-flex align-items-center mb-3 pb-1">
                                            <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                            <span class="h1 fw-bold mb-0">
                                            <img src="{{asset('build/img/logo.png')}}" alt="login form" class="img-fluid"/>
                                        </span>
                                        </div>
                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your
                                            account</h5>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="email">Email address or Staff Id</label>
                                            <input type="text" name="email" id="email" class="form-control form-control-lg"
                                                   value="{{old('email')}}"/>
                                            @if ($errors->has('email'))
                                                <p style="color: red;">{{ $errors->first('email') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form2Example27">Password</label>
                                            <input type="password" id="password" name="password" required
                                                   autocomplete="current-password" class="form-control form-control-lg"/>
                                            @if ($errors->has('password'))
                                                <p style="color: red;">{{ $errors->first('password') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-outline mb-4 d-flex">
                                          <input type="checkbox" name="remember" id="remember_me"
                                                   class="form-check"/>&nbsp;{{ __('Remember me') }}
                                        </div>

                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-dark btn-lg btn-block" type="submit">Login</button>
                                        </div>
                                        {{--                                    <a class="small text-muted" href="#!">Forgot password?</a>--}}
                                        {{--                                    <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="#!"--}}
                                        {{--                                                                                                              style="color: #393f81;">Register--}}
                                        {{--                                            here</a></p>--}}
                                        {{--                                    <a href="#!" class="small text-muted">Terms of use.</a>--}}
                                        {{--                                    <a href="#!" class="small text-muted">Privacy policy</a>--}}
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
