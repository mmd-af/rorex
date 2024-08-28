<?php

namespace App\Repositories\User;

use App\Models\StaffRequest\StaffRequest;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\LetterAssignment\LetterAssignment;
use App\Notifications\RequestRegisteredNotification;
use Carbon\Carbon;

class DashboardRepository extends BaseRepository
{
    public function support($request)
    {
        $user = Auth::user();
        $dateOfRequest = Carbon::now()->format('Y/m/d');
        if ($user->company) {
            Session::flash('error', 'There is a No Support for Companies');
            return false;
        } elseif ($user->employee) {
            $user = $user->load('employee');
            $employee = $user->employee;
            DB::beginTransaction();
            $description =
                'Date: ' . $dateOfRequest .
                '<br><div id="box">Name: ' . $employee->last_name . ' ' . $employee->first_name . '<br>' .
                'Code Staff: ' . $employee->staff_code . '</div><br>' .
                '<div id="alignCenter"><b> User Assistant (Support) </b></div><br>' .
                'as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' . $employee->department .
                '<br><h3>' . $request->description . '</h3><br>Email: ' . $user->email . '<hr><small>send from: user/dashboard/ Support Form</small>';


            try {
                $staffRequest = new StaffRequest();
                $staffRequest->user_id = $user->id;
                $staffRequest->name = $employee->last_name . ' ' . $employee->first_name;
                $staffRequest->email = $user->email;
                $staffRequest->mobile_phone = $employee->phone;
                $staffRequest->subject = "User Assistant (Support)";
                $staffRequest->description = $description;
                $staffRequest->organization = $employee->department;
                $staffRequest->cod_staff =  (int)$employee->staff_code;
                $staffRequest->save();

                $role = Role::query()
                    ->select(['id', 'name'])
                    ->where('name', "Support")
                    ->firstOrFail();
                if ($role) {
                    $userIds = $role->users->pluck('id');
                    foreach ($userIds as $assigned) {
                        $assignment = new LetterAssignment();
                        $assignment->user_id = $user->id;
                        $assignment->request_id = $staffRequest->id;
                        $assignment->role_id = $role->id;
                        $assignment->assigned_to = $assigned;
                        $assignment->status = "waiting";
                        $assignment->save();

                        $user = User::find($assigned);
                        if ($user->email_verified_at && $user->receive_notifications) {
                            $user->notify(new RequestRegisteredNotification("New Support Request", $description));
                        }
                        DB::commit();
                        Session::flash('message', 'Your request has been submitted');
                    }
                }
            } catch (\Exception $e) {
                DB::rollBack();
                $users = User::role('support')->get();
                foreach ($users as $user) {
                    $user->notify(new RequestRegisteredNotification("Error on user send request", $e->getMessage()));
                }
                Session::flash('error', 'There is a problem. Your request was not registered. Submit again');
            }
        }
    }
}
