@extends('auth.layouts.index')

@section('title', 'Reset Password')

@section('content')
    <div class="container-fluid" style="background-color: #009799;">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-center text-primary"> {{ __('auth.reset_password') }}</h5>
                            <p class="card-text text-center text-muted">
                                {{ __('auth.enter_your_new_password') }}
                            </p>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.store') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('auth.email') }}</label>
                                    <input id="email" class="form-control" type="email" name="email"
                                        value="{{ old('email', $request->email) }}" readonly required autofocus
                                        autocomplete="username" />
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('auth.insert_password') }}</label>
                                    <input id="password" class="form-control" type="password" name="password" required
                                        autocomplete="new-password" />
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation"
                                        class="form-label">{{ __('auth.password_confirmation') }}</label>
                                    <input id="password_confirmation" class="form-control" type="password"
                                        name="password_confirmation" required autocomplete="new-password" />
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('auth.reset_password') }}
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
