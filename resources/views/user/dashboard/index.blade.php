@extends('user.layouts.index')

@section('title')
    {{ __('dashboard.dashboard') }}
@endsection

@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">{{ __('dashboard.user_dashboard') }}</li>
    </ol>

    @if (auth()->user()->id === 1001)
        <h6>Hello,<b> Engineer Amin </b>, this part is made just for you</h6>
        <div class="m-3">
            <h4 class="text-secondary">Random Useless Facts :) but true</h4>
            <p class="shadow p-3 text-info h6" style="background-color: aliceblue" id="uslessFact">
            </p>
            <div>
                <button class="btn btn-sm btn-info" onclick="fetchFact()">More</button>
            </div>
        </div>
    @endif

    @if (session('status') == 'verification-link-sent')
        <div class="text-success">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    @if (empty(auth()->user()->email))
        {{ __('dashboard.register_your_email') }}
        <form method="post" action="{{ route('profile.update') }}" class="mt-6 col-md-6">
            @csrf
            @method('patch')
            <div class="mt-3">
                <label for="first_name">{{ __('dashboard.first_name') }}</label>
                <input id="first_name" name="first_name" type="text" class="form-control"
                    value="{{ old('first_name', auth()->user()->first_name) }}" required autofocus autocomplete="first_name"
                    disabled />
            </div>
            <div class="mt-3">
                <label for="name">{{ __('dashboard.last_name') }}</label>
                <input id="name" type="text" class="form-control" value="{{ old('name', auth()->user()->name) }}"
                    required autofocus autocomplete="name" disabled />
                <input type="hidden" name="name" value="{{ old('name', auth()->user()->name) }}">
            </div>
            @if ($errors->has('name'))
                <p class="mt-2 text-danger"> {{ $errors->first('name') }}</p>
            @endif
            <div class="mt-3">
                <label for="email">{{ __('Email') }}</label>
                @if (empty(auth()->user()->email))
                    <input id="email" name="email" type="email" class="form-control"
                        value="{{ old('email', auth()->user()->email) }}" required autocomplete="email" />
                @else
                    <input id="email" type="email" class="form-control"
                        value="{{ old('email', auth()->user()->email) }}" required autocomplete="email" disabled />
                    <input type="hidden" name="email" value="{{ old('email', auth()->user()->email) }}" />
                @endif
                @if ($errors->has('email'))
                    <p class="mt-2 text-danger"> {{ $errors->first('email') }}</p>
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
@endsection

@section('script')
    <script>
        let uslessFact = document.getElementById('uslessFact');

        addEventListener("DOMContentLoaded", (event) => {
            const requestOptions = {
                method: "GET",
            };
            fetch("https://uselessfacts.jsph.pl/api/v2/facts/today", requestOptions)
                .then((response) => response.json())
                .then((result) => {
                    uslessFact.innerHTML = `<b class="text-secondary">Today Fact= </b>` + result.text;
                })
                .catch((error) => console.error(error));
        });

        function fetchFact() {
            const requestOptions = {
                method: "GET",
            };
            fetch("https://uselessfacts.jsph.pl/api/v2/facts/random", requestOptions)
                .then((response) => response.json())
                .then((result) => {
                    uslessFact.innerHTML = `<b class="text-secondary">Random Fact= </b>` + result.text;
                })
                .catch((error) => console.error(error));
        }
    </script>
@endsection
