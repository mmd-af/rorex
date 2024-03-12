<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 col-md-6">
        @csrf
        @method('patch')
        <div class="mt-3">
            <label for="first_name">First Name</label>
            <input id="first_name" name="first_name" type="text" class="form-control"
                   value="{{old('first_name', $user->first_name)}}"
                   required
                   autofocus autocomplete="first_name" disabled/>
        </div>
        <div class="mt-3">
            <label for="name">Last Name</label>
            <input id="name" type="text" class="form-control" value="{{old('name', $user->name)}}"
                   required
                   autofocus autocomplete="name" disabled/>
            <input type="hidden" name="name" value="{{old('name', $user->name)}}">
        </div>
        @if($errors->has('name'))
            <p class="mt-2 text-danger"> {{$errors->first('name')}}</p>
        @endif
        <div class="mt-3">
            <label for="email">{{__('Email')}}</label>
            @if(empty($user->email))
                <input id="email" name="email" type="email" class="form-control"
                       value="{{old('email', $user->email)}}" required autocomplete="email"/>
            @else
                <input id="email" type="email" class="form-control"
                       value="{{old('email', $user->email)}}" required autocomplete="email" disabled/>
                <input type="hidden" name="email" value="{{old('email', $user->email)}}"/>
            @endif
            @if($errors->has('email'))
                <p class="mt-2 text-danger"> {{$errors->first('email')}}</p>
            @endif
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-success mt-3">{{ __('Save') }}</button>
            @if (session('status') === 'profile-updated')
                <p class="text-sm text-success">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
