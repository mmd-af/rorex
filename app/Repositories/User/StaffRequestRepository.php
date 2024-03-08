<?php

namespace App\Repositories\User;

use App\Models\LetterAssignment\LetterAssignment;
use App\Models\StaffRequest\StaffRequest;
use App\Models\User\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            ->where('user_id', $userId)
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('created_at', function ($row) {
                    $originalDate = Carbon::parse($row->created_at);
                    return $originalDate->format('Y-m-d');
                })
                ->addColumn('status', function ($row) {
                    $status = '';
                    foreach ($row->assignments as $assignment) {
                        $signedStatus = $assignment->signed_by ? 'Signed' : 'Not signed';
                        $status .= $assignment->assignedTo->name . " - " . $signedStatus . " -> " . $assignment->status . '<br>';
                    }
                    return $status;
                })
                ->rawColumns(['description', 'status'])
                ->make(true);
        }
        return false;
    }

    public function store($request)
    {
        $userId = Auth::id();
        if ($request->subject === 'Leave Request for Rest') {
            $checkAssignment = LetterAssignment::query()
                ->where('user_id', $userId)
                ->where('status', 'waiting')
                ->first();
            if (!is_null($checkAssignment)) {
                Session::flash('error', 'You have a pending request');
                return false;
            }
        }
        DB::beginTransaction();
        try {
            $staffRequest = new StaffRequest();
            $staffRequest->name = $request->name;
            $staffRequest->user_id = $userId;
            $staffRequest->email = $request->email;
            $staffRequest->mobile_phone = $request->mobile_phone;
            $staffRequest->subject = $request->subject;
            $staffRequest->description = $request->description;
            $staffRequest->organization = $request->departament;
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
            DB::commit();
            Session::flash('message', 'Your Request Send Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
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
