@extends('user.layouts.index')

@section('title')
    Dashboard
@endsection

@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">User Dashboard</li>
    </ol>

    @if (session('status') == 'verification-link-sent')
        <div class="text-success">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    @if(empty(auth()->user()->email))
        To receive notifications and access certain features, register your email in the system.
        <form method="post" action="{{ route('profile.update') }}" class="mt-6 col-md-6">
            @csrf
            @method('patch')
            <div class="mt-3">
                <label for="first_name">First Name</label>
                <input id="first_name" name="first_name" type="text" class="form-control"
                       value="{{old('first_name', auth()->user()->first_name)}}"
                       required
                       autofocus autocomplete="first_name" disabled/>
            </div>
            <div class="mt-3">
                <label for="name">Last Name</label>
                <input id="name" type="text" class="form-control" value="{{old('name', auth()->user()->name)}}"
                       required
                       autofocus autocomplete="name" disabled/>
                <input type="hidden" name="name" value="{{old('name', auth()->user()->name)}}">
            </div>
            @if($errors->has('name'))
                <p class="mt-2 text-danger"> {{$errors->first('name')}}</p>
            @endif
            <div class="mt-3">
                <label for="email">{{__('Email')}}</label>
                @if(empty(auth()->user()->email))
                    <input id="email" name="email" type="email" class="form-control"
                           value="{{old('email', auth()->user()->email)}}" required autocomplete="email"/>
                @else
                    <input id="email" type="email" class="form-control"
                           value="{{old('email', auth()->user()->email)}}" required autocomplete="email" disabled/>
                    <input type="hidden" name="email" value="{{old('email', auth()->user()->email)}}"/>
                @endif
                @if($errors->has('email'))
                    <p class="mt-2 text-danger"> {{$errors->first('email')}}</p>
                @endif
            </div>
            <div class="flex items-center gap-4">
                <button type="submit" class="btn btn-success mt-3">{{ __('Save') }}</button>
                @if (session('status') === 'profile-updated')
                    <p class="text-sm text-success">{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>

    @elseif(!auth()->user()->hasVerifiedEmail())
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

    {{--    <div class="row">--}}
    {{--        <div class="col-xl-3 col-md-6">--}}
    {{--            <div class="card bg-primary text-white mb-4">--}}
    {{--                <div class="card-body">Primary Card</div>--}}
    {{--                <div class="card-footer d-flex align-items-center justify-content-between">--}}
    {{--                    <a class="small text-white stretched-link" href="#">View Details</a>--}}
    {{--                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="col-xl-3 col-md-6">--}}
    {{--            <div class="card bg-warning text-white mb-4">--}}
    {{--                <div class="card-body">Warning Card</div>--}}
    {{--                <div class="card-footer d-flex align-items-center justify-content-between">--}}
    {{--                    <a class="small text-white stretched-link" href="#">View Details</a>--}}
    {{--                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="col-xl-3 col-md-6">--}}
    {{--            <div class="card bg-success text-white mb-4">--}}
    {{--                <div class="card-body">Success Card</div>--}}
    {{--                <div class="card-footer d-flex align-items-center justify-content-between">--}}
    {{--                    <a class="small text-white stretched-link" href="#">View Details</a>--}}
    {{--                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="col-xl-3 col-md-6">--}}
    {{--            <div class="card bg-danger text-white mb-4">--}}
    {{--                <div class="card-body">Danger Card</div>--}}
    {{--                <div class="card-footer d-flex align-items-center justify-content-between">--}}
    {{--                    <a class="small text-white stretched-link" href="#">View Details</a>--}}
    {{--                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div class="row">--}}
    {{--        <div class="col-xl-6">--}}
    {{--            <div class="card mb-4">--}}
    {{--                <div class="card-header">--}}
    {{--                    <i class="fas fa-chart-area me-1"></i>--}}
    {{--                    Area Chart Example--}}
    {{--                </div>--}}
    {{--                <div class="card-body">--}}
    {{--                    <canvas id="myAreaChart" width="100%" height="40"></canvas>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="col-xl-6">--}}
    {{--            <div class="card mb-4">--}}
    {{--                <div class="card-header">--}}
    {{--                    <i class="fas fa-chart-bar me-1"></i>--}}
    {{--                    Bar Chart Example--}}
    {{--                </div>--}}
    {{--                <div class="card-body">--}}
    {{--                    <canvas id="myBarChart" width="100%" height="40"></canvas>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
@endsection

@section('script')
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>--}}
    {{--    <script src="{{asset('admin-panel/assets/demo/chart-area-demo.js')}}"></script>--}}
    {{--    <script src="{{asset('admin-panel/assets/demo/chart-bar-demo.js')}}"></script>--}}

@endsection
