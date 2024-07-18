@extends('admin.layouts.index')

@section('title')
    Dashboard
@endsection

@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Admin Dashboard</li>
    </ol>
    @if (session('status') == 'verification-link-sent')
        <div class="text-success">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    @if (empty(auth()->user()->email))
        To receive notifications and access certain features, register your email in the system.
        <form method="post" action="{{ route('profile.update') }}" class="mt-6 col-md-6">
            @csrf
            @method('patch')
            <div class="mt-3">
                <label for="first_name">First Name</label>
                <input id="first_name" name="first_name" type="text" class="form-control"
                    value="{{ old('first_name', auth()->user()->first_name) }}" required autofocus autocomplete="first_name"
                    disabled />
            </div>
            <div class="mt-3">
                <label for="name">Last Name</label>
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
    @if (Auth::user()->rolles == 'admin')
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th scope="col">User ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Roles</th>
                        <th scope="col">Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                @if ($user->employee)
                                    {{ $user->employee->last_name }} {{ $user->employee->first_name }}
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                            <td>{{ implode(', ', $user->getPermissionNames()->toArray()) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
