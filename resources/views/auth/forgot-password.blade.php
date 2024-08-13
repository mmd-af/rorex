@extends('auth.layouts.index')

@section('title', 'Forgot Password')

@section('content')
    <div class="container-fluid" style="background-color: #009799;">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-center text-primary">{{ __('auth.forgot_your_password') }} </h5>
                            <p class="card-text text-center text-muted">
                                {{ __('auth.let_us_your_email_for_password_reset') }}
                            </p>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('auth.email') }}</label>
                                    <input id="email" class="form-control" type="email" name="email"
                                        value="{{ old('email') }}" required autofocus />
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('auth.email_password_reset_link') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
