<?php

namespace App\Repositories\User;

use App\Models\DailyReport\DailyReport;
use App\Models\Employee\Employee;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use App\Models\StaffRequest\StaffRequest;
use Illuminate\Support\Facades\DB;
use App\Models\LetterAssignment\LetterAssignment;
use App\Notifications\RequestRegisteredNotification;

class DailyReportRepository extends BaseRepository
{
    public function __construct(DailyReport $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
        $userId = Auth::id();
        $data = $this->query()
            ->select([
                'id',
                'cod_staff',
                'nume',
                'data',
                'saptamana',
                'nume_schimb',
                'on_work1',
                'off_work2',
                'remarca'
            ])
            ->where('cod_staff', $userId)
            ->where('data', 'LIKE', "$request->date%")
            ->orderByDesc('data')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('button', function ($row) {
                    return '<button onclick="requestForm(' . $row->cod_staff . ', \'' . $row->data . '\')" type="button"
                                    class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#forgetRequest">
                                <i class="fa-solid fa-square-arrow-up-right"></i>
                            </button>';
                })
                ->rawColumns(['button'])
                ->make(true);
        }
        return false;
    }

    public function getData($request)
    {
        return Employee::query()
            ->select([
                'id',
                'user_id',
                'staff_code',
                'last_name',
                'first_name',
                'department'
            ])
            ->where('staff_code', $request->id)
            ->with('users')
            ->first();
    }

    public function getRoles()
    {
        return Role::query()
            ->select([
                'id',
                'name'
            ])
            ->get();
    }

    public function getUserWithRole($request)
    {
        return User::query()
            ->select([
                'id'
            ])
            ->role($request->role_name)
            ->with('employee')
            ->get();
    }

    public function checkRequest($request)
    {
        if (is_array($request->description)) {
            $description = $request->description[0] . ' between ' . $request->description[1];
        } else {
            $description = $request->description . " hour";
        }
        $userId = Auth::id();
        $dateOfRequest = Carbon::now()->format('Y/m/d');
        $description =
            'Date: ' . $dateOfRequest .
            '<br><div id="box">Name: ' . $request->last_name . ' ' . $request->first_name . '<br>' .
            'Code Staff: ' . $request->staff_code . '</div><br>' .
            '<div id="alignCenter"><b>' . $request->subject . '</b></div><br>' .
            'Check For Date: ' . $request->check_date . '<br>' .
            'as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' . $request->department .
            '<br><h3>' . $description . '</h3><br>Email: ' . $request->email . '<hr><small>send from: Daily Report</small>';

        $data = [
            'userId' => $userId,
            'last_name' => $request->input('last_name'),
            'first_name' => $request->input('first_name'),
            'email' => $request->input('email'),
            'mobile_phone' => $request->input('mobile_phone'),
            'subject' => $request->input('subject'),
            'description' => $description,
            'organization' => $request->input('department'),
            'staff_code' => (int)$request->input('staff_code'),
            'vacation_day' => (int)$request->input('vacation_day'),
            'departmentRole' => $request->input('departmentRole'),
            'assigned_to' => (int)$request->input('assigned_to')
        ];

        DB::beginTransaction();
        try {
            $staffRequest = new StaffRequest();
            $staffRequest->user_id = $data['userId'];
            $staffRequest->name = $data['last_name'] . ' ' . $data['first_name'];
            $staffRequest->email = $data['email'];
            $staffRequest->mobile_phone = $data['mobile_phone'];
            $staffRequest->subject = $data['subject'];
            $staffRequest->description = $data['description'];
            $staffRequest->organization = $data['organization'];
            $staffRequest->cod_staff = $data['staff_code'];
            $staffRequest->vacation_day = $data['vacation_day'];
            $staffRequest->save();

            $role = Role::query()
                ->select(['id', 'name'])
                ->where('name', $data['departmentRole'])
                ->firstOrFail();

            $assignment = new LetterAssignment();
            $assignment->user_id = $data['userId'];
            $assignment->request_id = $staffRequest->id;
            $assignment->role_id = $role->id;
            $assignment->assigned_to = $data['assigned_to'];
            $assignment->status = "waiting";
            $assignment->save();

            $user = User::whereHas('employee', function ($query) use ($data) {
                $query->where('staff_code', $data['assigned_to']);
            })->firstOrFail();
            if ($user->email_verified_at && $user->receive_notifications) {
                $user->notify(new RequestRegisteredNotification("New Request", $data['description']));
            }
            DB::commit();
            Session::flash('message', 'Your request has been submitted');
        } catch (\Exception $e) {
            DB::rollBack();
            $users = User::role('support')->get();
            foreach ($users as $user) {
                $user->notify(new RequestRegisteredNotification("Error on user send request", $e->getMessage()));
            }
            Session::flash('error', 'There is a problem. Your request was not registered. Submit again' . $e->getMessage());
        }
    }
}
