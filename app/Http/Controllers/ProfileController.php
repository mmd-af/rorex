<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('user.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $request->user()->receive_notifications = $request->has('receive_notifications');
        $request->user()->save();
        return back()->with('status', 'profile-updated');
        //        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    //    public function destroy(Request $request): RedirectResponse
    //    {
    //        $request->validateWithBag('userDeletion', [
    //            'password' => ['required', 'current_password'],
    //        ]);
    //
    //        $user = $request->user();
    //
    //        Auth::logout();
    //
    //        $user->delete();
    //
    //        $request->session()->invalidate();
    //        $request->session()->regenerateToken();
    //
    //        return Redirect::to('/');
    //    }
}
