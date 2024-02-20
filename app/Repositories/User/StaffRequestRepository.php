<?php

namespace App\Repositories\User;

use App\Models\LetterAssignment\LetterAssignment;
use App\Models\StaffRequest\StaffRequest;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class StaffRequestRepository extends BaseRepository
{
    public function __construct(StaffRequest $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
        $userId = Auth::id();
        $userEmail = Auth::user()->email;
        $data = $this->query()
            ->select([
                'id',
                'email',
                'subject',
                'description',
                'organization',
                'cod_staff',
                'read_at',
                'created_at',
                'is_archive'
            ])
            ->where('cod_staff', $userId)
            ->orWhere('email', $userEmail)
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('created_at', function ($row) {
                    $originalDate = Carbon::parse($row->created_at);
                    return $originalDate->format('Y-m-d');
                })
                ->rawColumns(['description', 'action'])
                ->make(true);
        }
        return false;
    }

    public function store($request)
    {
        $support = new StaffRequest();
        $support->name = $request->name;
        $support->email = $request->email;
        $support->mobile_phone = $request->mobile_phone;
        $support->subject = $request->subject;
        $support->description =
            "Name: " . $request->name . " " . $request->prenumele_tatalui . "<br>with Code Staff: " . $request->cod_staff .
            "<br><br>as an employee of S.C. ROREX PIPE S.R.L.<br>in the Departament of: " . $request->departament .
            "<br>please approve my request for vacation during the period: " . $request->start_date . " end days " . $request->end_date .
            "<br>" . $request->vacation_day . " days <br> Referred to:" . $request->departamentRole;
        $support->organization = $request->departament;
        $support->cod_staff = (int)$request->cod_staff;
        $support->save();
        $role = Role::query()
            ->select(['id', 'name'])
            ->where('name', $request->departamentRole)
            ->first();
        $assignment = new LetterAssignment();
        $assignment->request_id = $support->id;
        $assignment->role_id = $role->id;
        $assignment->assigned_to = $request->assigned_to;
        $assignment->status = "waiting";
        $assignment->save();


        $assignment->save();
        Session::flash('message', 'Your Request Send Successfully');
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
                'name'
            ])
            ->role($request->role_name)
            ->get();
    }
}
