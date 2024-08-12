<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('profile.update_password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('profile.esure_account_using_long') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 col-md-6">
        @csrf
        @method('put')
        <div>
            <label for="update_password_current_password">{{ __('profile.current_password') }}
        </div>
        <input id="update_password_current_password" name="current_password" type="password" class="form-control"
            autocomplete="current-password" />
        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-danger" />
        <div>
            <label for="update_password_password">{{ __('profile.new_password') }}
        </div>
        <input id="update_password_password" name="password" type="password" class="form-control"
            autocomplete="new-password" />
        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-danger" />
        <div>
            <label for="update_password_password_confirmation">{{ __('profile.confirm_password') }}
        </div>
        <input id="update_password_password_confirmation" name="password_confirmation" type="password"
            class="form-control" autocomplete="new-password" />
        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-danger" />
        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-success mt-3">{{ __('profile.save') }}</button>
            @if (session('status') === 'password-updated')
                <div class="alert alert-success my-2">{{ __('profile.saved') }}</div>
            @endif
        </div>
    </form>
</section>
