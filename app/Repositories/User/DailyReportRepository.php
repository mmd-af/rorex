<?php

namespace App\Repositories\User;

use App\Mail\RequestMail;
use App\Models\DailyReport\DailyReport;
use App\Models\LetterAssignment\LetterAssignment;
use App\Models\StaffRequest\StaffRequest;
use App\Models\User\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

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
        return User::query()
            ->select([
                'id',
                'cod_staff',
                'name',
                'first_name',
                'departament',
                'email',
            ])
            ->where('cod_staff', $request->id)
            ->first();
    }

    public function getRoles($request)
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
                'id',
                'first_name',
                'name'
            ])
            ->role($request->role_name)
            ->get();
    }

    public function checkRequest($request)
    {
        $userId = Auth::id();
        $dateOfRequest = Carbon::now()->format('Y/m/d');
        DB::beginTransaction();
        try {
            $staffRequest = new StaffRequest();
            $staffRequest->name = $request->name;
            $staffRequest->user_id = $userId;
            $staffRequest->email = $request->email;
            $staffRequest->mobile_phone = $request->mobile_phone;
            $staffRequest->subject = $request->subject;
            $staffRequest->description =
                'Date: ' . $dateOfRequest .
                '<br><div id="box">Name: ' . $request->name . ' ' . $request->first_name . '<br>' .
                'Code Staff: ' . $request->cod_staff . '</div><br>' .
                'Check For Date: ' . $request->date . '</div><br>' .
                '<div id="alignCenter"><b>' . $request->subject . '</b></div><br>as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' . $request->departament .
                '<br>' . $request->description . '<br>Email: ' . $request->email . '<hr>';

            $staffRequest->organization = $request->departamentRole;
            $staffRequest->cod_staff = (int)$request->cod_staff;
            $staffRequest->vacation_day = (int)$request->vacation_day;
            $staffRequest->save();
            $role = Role::query()
                ->select(['id', 'name'])
                ->where('name', $request->departamentRole)
                ->first();
            $assignment = new LetterAssignment();
            $assignment->user_id = $userId;
            $assignment->request_id = $staffRequest->id;
            $assignment->role_id = $role->id;
            $assignment->assigned_to = $request->assigned_to;
            $assignment->status = "waiting";
            $assignment->save();
            $assignedTo = User::query()
                ->select([
                    'id',
                    'email',
                    'email_verified_at'
                ])
                ->where('id', $assignment->assigned_to)
                ->first();
            if ($assignedTo->email_verified_at !== null) {
                Mail::to($assignedTo->email)->send(new RequestMail($request->subject, $staffRequest->description));
            }
            DB::commit();
            Session::flash('message', 'Your Request Send Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }

    public function getLastUpdate($request)
    {
        return $this->query()
            ->select(['updated_at'])
            ->orderBy('updated_at', 'desc')
            ->first();
    }
}
