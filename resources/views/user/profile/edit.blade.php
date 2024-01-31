@extends('user.layouts.index')
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Profile</li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('user.profile.partials.update-profile-information-form')
                    </div>
                </div>
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('user.profile.partials.update-password-form')
                    </div>
                </div>
{{--                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">--}}
{{--                    <div class="max-w-xl">--}}
{{--                        @include('user.profile.partials.delete-user-form')--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection
