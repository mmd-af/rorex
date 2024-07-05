<?php

namespace App\Repositories\User;

use App\Models\Leave\Leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\User\LeaveRequestJob;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\LetterAssignment\LetterAssignment;
use App\Models\StaffRequest\StaffRequest;
use App\Models\User\User;
use App\Notifications\RequestRegisteredNotification;

class LeaveRepository extends BaseRepository
{
    public function __construct(Leave $model)
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
                'user_id',
                'request_id',
                'start_date',
                'end_date',
                'type',
                'file',
                'leave_time',
                'leave_days',
                'description',
                'remaining'
            ])
            ->where('user_id', $userId)
            ->with(['users', 'requests'])
            ->latest();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('start_date', function ($row) {
                    $originalDate = Carbon::parse($row->start_date);
                    if ($row->leave_days) {
                        return $originalDate->format('Y-m-d');
                    } else {
                        return $originalDate->format('Y-m-d H:i');
                    }
                })
                ->addColumn('end_date', function ($row) {
                    $originalDate = Carbon::parse($row->end_date);
                    if ($row->leave_days) {
                        return $originalDate->format('Y-m-d');
                    } else {
                        return $originalDate->format('Y-m-d H:i');
                    }
                })
                ->addColumn('value', function ($row) {
                    if ($row->leave_time) {
                        $originalDate = Carbon::parse($row->leave_time)->format('H:i');
                        return $originalDate . " hour";
                    }
                    if ($row->leave_days) {
                        return $row->leave_days . " days";
                    }
                })
                ->addColumn('file', function ($row) {
                    if ($row->file) {
                        return '<a href="' . asset($row->file) . '" target="_blank"><i class="fas fa-download fa-lg"></i></a>';
                    } else {
                        return '';
                    }
                })
                ->addColumn('status', function ($row) {
                    $row = $row->requests;
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
                        $status .= $assignment->assignedTo->name . ' ' . $assignment->assignedTo->first_name . $signedStatus . $condition . $description . '<hr>';
                    }
                    return $status;
                })
                ->rawColumns(['value', 'file', 'status'])
                ->make(true);
        }
        return false;
    }

    public function getLeavedDays($request)
    {
        $year = $request->input('year');
        $userId = Auth::id();
        $totalLeaveDays = $this->query()
            ->where('user_id', $userId)
            ->whereYear('start_date', $year)
            ->where('type', 'Allowed Leave')
            ->with('requests.assignments')
            ->whereHas('requests.assignments', function ($query) {
                $query->where('status', 'Accepted')
                    ->orderBy('created_at', 'desc')
                    ->take(1);
            })
            ->sum('leave_days');
        return response()->json(['total_leave_days' => $totalLeaveDays]);
    }

    public function getHourlyLeaved($request)
    {
        $year = $request->input('year');
        $userId = Auth::id();
        $leaveTimes = $this->query()
            ->where('user_id', $userId)
            ->whereYear('start_date', $year)
            ->where('type', 'Hourly Leave')
            ->with('requests.assignments')
            ->whereHas('requests.assignments', function ($query) {
                $query->where('status', 'Accepted')
                    ->orderBy('created_at', 'desc')
                    ->take(1);
            })
            ->pluck('leave_time');

        $totalSeconds = 0;
        foreach ($leaveTimes as $leaveTime) {
            if ($leaveTime) {
                $time = Carbon::parse($leaveTime);
                $totalSeconds += $time->hour * 3600 + $time->minute * 60 + $time->second;
            }
        }

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        $totalLeaveTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        return response()->json(['total_hour_leave' => $totalLeaveTime]);
    }

    public function store($request)
    {
        $userId = Auth::id();
        $file = null;
        $filename = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename =  '/specialEvents/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('specialEvents'), $filename);
        }
        $data = [
            'cod_staff' => $request->input('cod_staff'),
            'userId' => $userId,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'type' => $request->input('type'),
            'file' =>  $filename,
            'leave_time' => $request->input('leave_time'),
            'leave_days' => $request->input('leave_days'),
            'name' => $request->input('name'),
            'subject' => $request->input('subject'),
            'description' => $request->input('description'),
            'email' => $request->input('email'),
            'departamentRole' => (int)$request->input('departamentRole'),
            'assigned_to' => (int)$request->input('assigned_to'),
            'mobile_phone' => $request->input('mobile_phone'),
            'organization' => $request->input('organization'),
            'remaining' => $request->input('remaining')
        ];
        try {
            $staffRequest = new StaffRequest();
            $staffRequest->name = $data['name'];
            $staffRequest->user_id = $data['userId'];
            $staffRequest->email = $data['email'];
            $staffRequest->mobile_phone = $data['mobile_phone'];
            $staffRequest->subject = $data['subject'];
            $staffRequest->description = $data['description'];
            $staffRequest->organization = $data['organization'];
            $staffRequest->cod_staff = $data['cod_staff'];
            $staffRequest->vacation_day = isset($data['vacation_day']) ? $data['vacation_day'] : 0;
            $staffRequest->save();
            $staffRequestId = $staffRequest->id;

            $role = Role::query()
                ->select(['id', 'name'])
                ->where('name', $data['departamentRole'])
                ->first();

            $leave = new Leave();
            $leave->user_id = $data['userId'];
            $leave->request_id = $staffRequestId;
            $leave->start_date = $data['start_date'];
            $leave->end_date = $data['end_date'];
            $leave->type = $data['type'];
            $leave->file = $data['file'];
            $leave->leave_time = $data['leave_time'];
            $leave->leave_days = $data['leave_days'];
            $leave->description = $data['description'];
            $leave->remaining = $data['remaining'];
            $leave->save();

            $assignment = new LetterAssignment();
            $assignment->user_id = $data['userId'];
            $assignment->request_id = $staffRequestId;
            $assignment->role_id = $role->id;
            $assignment->assigned_to = $data['assigned_to'];
            $assignment->status = "waiting";
            $assignment->save();
            Session::flash('message', 'Your request has been submitted');
        } catch (\Exception $e) {
            Session::flash('error', 'Your request has not submitted');
            $users = User::role('support')->get();
            foreach ($users as $user) {
                $user->notify(new RequestRegisteredNotification("Error on user send leave request", $e->getMessage()));
            }
        }
        LeaveRequestJob::dispatch($data);
    }
}
