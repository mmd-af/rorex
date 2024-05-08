<?php

namespace App\Http\Controllers\CompanyAuth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\Company\Company;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('company.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'activity_domain' => ['required', 'string', 'max:255'],
            'vat_id' => ['required', 'string', 'max:255'],
            'registration_number' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'county' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'building' => ['nullable', 'string', 'max:255'],
            'person_name' => ['required', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        DB::beginTransaction();
        try {
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => 0
            ]);
            Company::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                'activity_domain' => $request->activity_domain,
                'vat_id' => $request->vat_id,
                'registration_number' => $request->registration_number,
                'country' => $request->country,
                'county' => $request->county,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'address' => $request->address,
                'building' => $request->building,
                'person_name' => $request->person_name,
                'job_title' => $request->job_title,
                'phone_number' => $request->phone_number
            ]);
            $permission = Permission::where('name', 'companies')->first();
            if (!$permission) {
                $permission = new Permission();
                $permission->name = "companies";
                $permission->guard_name = "web";
                $permission->save();
            }
            $user->givePermissionTo($permission);
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
