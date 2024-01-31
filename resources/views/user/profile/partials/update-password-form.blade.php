<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 col-md-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password">{{__('Current Password')}}</div>
        <input id="update_password_current_password" name="current_password" type="password"
               class="form-control" autocomplete="current-password"/>
        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-danger" />
        <div>
            <label for="update_password_password">{{__('New Password')}}</div>
        <input id="update_password_password" name="password" type="password" class="form-control"
               autocomplete="new-password"/>
        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-danger" />
        <div>
            <label for="update_password_password_confirmation">{{__('Confirm Password')}}</div>
        <input id="update_password_password_confirmation" name="password_confirmation" type="password"
               class="form-control" autocomplete="new-password"/>
        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-danger" />
        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-success mt-3">{{ __('Save') }}</button>
            @if (session('status') === 'password-updated')
                <p class="text-sm text-success"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
