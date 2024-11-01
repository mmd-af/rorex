<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('profile.profile_information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('profile.update_account_profile') }}
        </p>
    </header>
    {{--    <form id="send-verification" method="post" action="{{ route('verification.send') }}"> --}}
    {{--        @csrf --}}
    {{--    </form> --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 col-md-6">
        @csrf
        @method('patch')
        <div class="mt-3">
            <label for="first_name">{{ __('profile.first_name') }}</label>
            <input id="first_name" name="first_name" type="text" class="form-control"
                value="{{ old('first_name', $user->employee->first_name) }}" required autofocus
                autocomplete="first_name" disabled />
        </div>
        <div class="mt-3">
            <label for="last_name">{{ __('profile.last_name') }}</label>
            <input id="last_name" type="text" class="form-control"
                value="{{ old('last_name', $user->employee->last_name) }}" required autofocus autocomplete="last_name"
                disabled />
            <input type="hidden" name="last_name" value="{{ old('last_name', $user->employee->last_name) }}">
        </div>
        @if ($errors->has('last_name'))
            <p class="mt-2 text-danger"> {{ $errors->first('last_name') }}</p>
        @endif
        <div class="mt-3">
            <label for="email">{{ __('profile.email') }}</label>
            @if (empty($user->email))
                <input id="email" name="email" type="email" class="form-control"
                    value="{{ old('email', $user->email) }}" required autocomplete="email" />
            @else
                <input id="email" type="email" class="form-control" value="{{ old('email', $user->email) }}"
                    required autocomplete="email" disabled />
                <input type="hidden" name="email" value="{{ old('email', $user->email) }}" />
            @endif
            @if ($errors->has('email'))
                <p class="mt-2 text-danger"> {{ $errors->first('email') }}</p>
            @endif
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('profile.your_email_unverified') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('profile.click_here_resend_verification') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('profile.verification_link_sent_your_email') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" id="receive_notifications" name="receive_notifications"
                {{ auth()->user()->receive_notifications ? 'checked' : '' }}>
            <label class="form-check-label" for="receive_notifications">
                Receive notification emails
            </label>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-success mt-3">{{ __('profile.save') }}</button>
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success my-2">{{ __('profile.saved') }}</div>
            @endif
        </div>
    </form>
</section>
