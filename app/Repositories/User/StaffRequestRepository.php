<?php

namespace App\Repositories\User;

use App\Models\LetterAssignment\LetterAssignment;
use App\Models\StaffRequest\StaffRequest;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\User\DailyReportSendRequestJob;

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
                'description',
                'cod_staff',
                'created_at',
                'is_archive'
            ])
            ->where('user_id', $userId)
            ->where('is_archive', 0)
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
                        $signedStatus = $assignment->signed_by ? '<div class="bg-success rounded-3 text-light">Signed</div>' : '<div class="bg-warning rounded-3">Not signed</div>';
                        $status .= $assignment->assignedTo->employee->last_name . $assignment->assignedTo->employee->first_name . $signedStatus . $assignment->status . '<hr>';
                    }
                    return $status;
                })
                ->rawColumns(['description', 'status'])
                ->make(true);
        }
        return false;
    }
    public function getArchiveDataTable($request)
    {
        $userId = Auth::id();
        $data = $this->query()
            ->select([
                'id',
                'email',
                'description',
                'cod_staff',
                'created_at',
                'is_archive'
            ])
            ->where('user_id', $userId)
            ->where('is_archive', 1)
            ->latest();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('created_at', function ($row) {
                    $originalDate = Carbon::parse($row->created_at);
                    return $originalDate->format('Y-m-d');
                })
                ->addColumn('status', function ($row) {
                    $status = '';
                    foreach ($row->assignments as $assignment) {
                        $signedStatus = $assignment->signed_by ? '<div class="bg-success rounded-3 text-light">Signed</div>' : '<div class="bg-warning rounded-3">Not signed</div>';
                        if ($assignment->description && ($assignment->status === "Accepted" || $assignment->status === "Rejected")) {
                            $description = Str::limit($assignment->description, 45);
                            $description = $assignment->description != $description ? '<div class="bg-info rounded-3">' . $description . '<details>
                                        <summary>Show Full Message</summary>' . $assignment->description . '</div>
                                    </details>' : '<div class="bg-info rounded-3">' . $assignment->description . '</div>';
                        } else {
                            $description = '';
                        }
                        $condition = $assignment->status;
                        if ($assignment->status == 'Rejected') {
                            $condition = '<div class="bg-danger rounded-3 text-light">' . $assignment->status . '</div>';
                        }
                        $status .= $assignment->assignedTo->employee->name . ' ' . $assignment->assignedTo->employee->first_name . $signedStatus . $condition . $description . '<hr>';
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
        $data = [
            'userId' => $userId,
            'last_name' => $request->input('last_name'),
            'first_name' => $request->input('first_name'),
            'email' => $request->input('email'),
            'mobile_phone' => $request->input('mobile_phone'),
            'subject' => $request->input('subject'),
            'description' => $request->description,
            'organization' => $request->input('department'),
            'departmentRole' => $request->input('departmentRole'),
            'staff_code' => (int)$request->input('staff_code'),
            'vacation_day' => (int)$request->input('vacation_day'),
            'assigned_to' => (int)$request->input('assigned_to')
        ];
        DailyReportSendRequestJob::dispatch($data);
        Session::flash('message', 'Your request has been submitted');
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
                'id'
            ])
            ->role($request->role_name)
            ->with('employee')
            ->get();
    }
}
