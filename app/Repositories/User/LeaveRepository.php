<?php

namespace App\Repositories\User;

use App\Models\Leave\Leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\User\LeaveRequestJob;
use Illuminate\Support\Str;

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
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('value', function ($row) {
                    if ($row->leave_time) {
                        return $row->leave_time . " hour";
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
    public function store($request)
    {
        $userId = Auth::id();
        $file = null;
        $filename = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('specialEvents'), $filename);
        }
        $data = [
            'cod_staff' => $request->input('cod_staff'),
            'userId' => $userId,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'type' => $request->input('type'),
            'file' =>  '/specialEvents/' . $filename,
            'leave_time' => $request->input('leave_time'),
            'leave_days' => $request->input('leave_days'),
            'name' => $request->input('name'),
            'subject' => (int)$request->input('subject'),
            'description' => $request->input('description'),
            'email' => $request->input('email'),
            'departamentRole' => (int)$request->input('departamentRole'),
            'assigned_to' => (int)$request->input('assigned_to'),
            'mobile_phone' => $request->input('mobile_phone'),
            'organization' => $request->input('organization'),
            'vacation_day' => $request->input('vacation_day'),
            'remaining' => $request->input('remaining')
        ];
        LeaveRequestJob::dispatch($data);
        Session::flash('message', 'Your request has been submitted');
    }
}
