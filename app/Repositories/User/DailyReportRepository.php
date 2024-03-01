<?php

namespace App\Repositories\User;

use App\Models\DailyReport\DailyReport;
use App\Models\LetterAssignment\LetterAssignment;
use App\Models\StaffRequest\StaffRequest;
use App\Models\Support\Support;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('button', function ($row) {
                    return '<button onclick="requestForm(' . $row->id . ')" type="button"
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
        return $this->query()
            ->select([
                'id',
                'cod_staff',
                'nume',
                'data'
            ])
            ->where('id', $request->dailyReport_id)
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

    public function checkRequest($request)
    {
        $userId = Auth::id();
        DB::beginTransaction();
        try {
            $staffRequest = new StaffRequest();
            $staffRequest->name = $request->name;
            $staffRequest->user_id = $userId;
            $staffRequest->email = $request->email;
            $staffRequest->mobile_phone = $request->mobile_phone;
            $staffRequest->subject = $request->subject;
            $staffRequest->description = "Subject: " . $request->subject . "
                            <strong> Check For Date: " . $request->date . "
                            </strong><br>" . $request->description . "
                            <br> Refered to: " . $request->departament;
            $staffRequest->organization = $request->departament;
            $staffRequest->cod_staff = (int)$request->cod_staff;
            $staffRequest->vacation_day = (int)$request->vacation_day;
            $staffRequest->save();
            $role = Role::query()
                ->select(['id', 'name'])
                ->where('name', $request->departament)
                ->first();
            $assignment = new LetterAssignment();
            $assignment->user_id = $userId;
            $assignment->request_id = $staffRequest->id;
            $assignment->role_id = $role->id;
            $assignment->assigned_to = $request->assigned_to;
            $assignment->status = "waiting";
            $assignment->save();
            $staffRequest->description = $staffRequest->description . "<br> Assigned to: " . $assignment->assignedTo->name;
            $staffRequest->save();
            DB::commit();
            Session::flash('message', 'Your Request Send Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }
}
