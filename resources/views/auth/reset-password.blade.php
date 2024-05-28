@extends('auth.layouts.index')

@section('title')
    Reset password
@endsection
@section('content')
    <div class="container-fluid" style="background-color: #009799;">
        <div class="container py-5">
            <div class="row justify-content-center">
                <form method="POST" class="form-control" action="{{ route('password.store') }}">
                    @csrf
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <!-- Email Address -->
                    <div>
                        <label for="email">{{ __('Email') }}</label>
                        <input id="email" class="form-control" type="email" name="email"
                            value="{{ old('email', $request->email) }}" readonly required autofocus autocomplete="username" />
                    </div>
                    <!-- Password -->
                    <div class="mt-4">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" class="form-control" type="password" name="password" required
                            autocomplete="new-password" />
                    </div>
                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <label for="password_confirmation">{{ __('Confirm Password') }}</label>

                        <input id="password_confirmation" class="form-control" type="password"
                            name="password_confirmation" required autocomplete="new-password" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="btn btn-success">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
