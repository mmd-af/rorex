<?php

namespace App\Repositories\User;

use App\Models\StaffRequest\StaffRequest;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\LetterAssignment\LetterAssignment;
use App\Notifications\RequestRegisteredNotification;

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
                        $status .= $assignment->assignedTo->employee->last_name . ' ' . $assignment->assignedTo->employee->first_name . $signedStatus . $assignment->status . '<hr>';
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
                        $status .= $assignment->assignedTo->employee->last_name . ' ' . $assignment->assignedTo->employee->first_name . $signedStatus . $condition . $description . '<hr>';
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
            Session::flash('error', 'There is a problem. Your request was not registered. Submit again');
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
                'id'
            ])
            ->role($request->role_name)
            ->with('employee')
            ->get();
    }
}
