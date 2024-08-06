<?php

namespace App\Repositories\Admin;

use App\Models\LetterAssignment\LetterAssignment;
use App\Models\StaffRequest\StaffRequest;
use App\Models\User\User;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\RequestRegisteredNotification;

class ManageRequestRepository extends BaseRepository
{
    public function __construct(LetterAssignment $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
        $userId = Auth::id();
        $data = $this->query()
            ->select([
                'id',
                'request_id',
                'role_id',
                'assigned_to',
                'signed_by',
                'status',
                'created_at'
            ])
            ->where('is_archive', 0)
            ->where('assigned_to', $userId)
            ->with(['request', 'request.leave'])
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('requests', function ($row) {
                    return "<p class=\"h5 bg-secondary text-light\">Tracking Number= " . $row->request->id . "</p><br>" . $row->request->description;
                })
                ->addColumn('file', function ($row) {
                    if (!empty($row->request->leave->file)) {
                        return '<a href="' . asset($row->request->leave->file) . '" target="_blank"><i class="fas fa-download fa-lg"></i></a>';
                    }
                })
                ->addColumn('progress', function ($row) {
                    $status = '';
                    $allRelatedRequest = $this->query()->where('request_id', $row->request_id)->with(['assignedTo', 'assignedTo.employee'])->get();
                    foreach ($allRelatedRequest as $assignment) {
                        $signedStatus = $assignment->signed_by ? '<div class="bg-success rounded-3 text-light">Signed</div>' : '<div class="bg-warning rounded-3">Not signed</div>';
                        if ($assignment->description) {
                            $description = Str::limit($assignment->description, 45);
                            $description = $assignment->description != $description ? '<div class="bg-info rounded-3">' . $description . '<details>
                                        <summary>Show Full Message</summary>' . $assignment->description . '</div>
                                    </details>' : '<div class="bg-info rounded-3">' . $assignment->description . '</div>';
                        } else {
                            $description = '';
                        }

                        $status .= $assignment->assignedTo->employee->last_name . ' ' . $assignment->assignedTo->employee->first_name . $signedStatus . $assignment->status . $description . '<br><small class="text-info">last update: ' . $assignment->updated_at . '</small><hr>';
                    }
                    return $status;
                })
                ->addColumn('sign', function ($row) {
                    return '
                    <div class="form-switch bg-secondary rounded-3">
                    <input role="button" onclick="handleSign(event, ' . $row->id . ')" class="form-check-input" type="checkbox" role="switch" id="signed_by" name="signed_by" value="' . $row->signed_by . '" ' . ($row->signed_by ? 'checked disabled' : '') . '>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $url = route('admin.manageRequests.exportPDF');
                    return '
                    <form action="' . $url . '" method="post">
                    ' . csrf_field() . '
                    <input type="hidden" name="printInformation" id="printInformation" value="' . $row->request->id . '">
                             <button type="submit" class="btn btn-light btn-sm m-2" title="Export PDF">
                            <i class="fa-solid fa-file-pdf fa-2x"></i>
                            </button>
                    </form>
                            <button title="Referred" onclick="setReferred(' . $row->id . ')" type="button"
                                    class="btn btn-primary btn-sm m-2" data-bs-toggle="modal"
                                    data-bs-target="#setReferred">
                                <i class="fa-solid fa-square-arrow-up-right"></i>
                            </button>
                            ' . ($row->signed_by ? '
                            <button title="Accept and close" onclick="setPass(' . $row->id . ')" type="button"
                                    class="btn btn-success btn-sm m-2">
                                <i class = "fa-solid fa-file-zipper"></i>
                            </button>
                            ' : '
                            <button title="Reject and close" onclick="setRejected(' . $row->id . ')" type="button"
                                    class="btn btn-danger btn-sm m-2">
                                <i class="fa-solid fa-square-xmark"></i>
                            </button>
                            ');
                })
                ->rawColumns(['requests', 'file', 'progress', 'sign', 'action'])
                ->make(true);
        }
        return false;
    }
    public function getFullDataTable($request)
    {
        $data = $this->query()
            ->select([
                'id',
                'request_id',
                'role_id',
                'assigned_to',
                'status'
            ])
            ->with(['request', 'request.leave'])
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('letter_assignments')
                    ->groupBy('request_id');
            })
            ->latest();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('requests', function ($row) {
                    return "<p class=\"h5 bg-secondary text-light\">Tracking Number= " . $row->request->id . "</p><br>" . $row->request->description;
                })
                ->addColumn('file', function ($row) {
                    if (!empty($row->request->leave->file)) {
                        return '<a href="' . asset($row->request->leave->file) . '" target="_blank"><i class="fas fa-download fa-lg"></i></a>';
                    }
                })
                ->addColumn('progress', function ($row) {
                    $status = '';
                    $allRelatedRequest = $this->query()->where('request_id', $row->request_id)->with(['assignedTo', 'assignedTo.employee'])->get();
                    foreach ($allRelatedRequest as $assignment) {
                        $signedStatus = $assignment->signed_by ? '<div class="bg-success rounded-3 text-light">Signed</div>' : '<div class="bg-warning rounded-3">Not signed</div>';
                        if ($assignment->description) {
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
                        $status .= $assignment->assignedTo->employee->last_name . ' ' . $assignment->assignedTo->employee->first_name . $signedStatus . $condition . $description . '<br><small class="text-info">last update: ' . $assignment->updated_at . '</small><hr>';
                    }
                    return $status;
                })
                ->addColumn(
                    'action',
                    function ($row) {
                        $url = route('admin.manageRequests.exportPDF');
                        return '
                    <form action="' . $url . '" method="post">
                    ' . csrf_field() . '
                    <input type="hidden" name="printInformation" id="printInformation" value="' . $row->request->id . '">
                             <button type="submit" class="btn btn-light btn-sm m-2" title="Export PDF">
                            <i class="fa-solid fa-file-pdf fa-2x"></i>
                            </button>
                    </form>';
                    }
                )
                ->rawColumns(['requests', 'file', 'progress', 'action'])
                ->make(true);
        }
        return false;
    }

    public function sign($request)
    {
        DB::beginTransaction();
        try {
            $userId = Auth::id();
            $letterAssignment = $this->find($request->id);
            $letterAssignment->signed_by = $userId;
            $letterAssignment->save();
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
        return false;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $role = Role::query()
                ->select(['id', 'name'])
                ->where('name', $request->departamentRole)
                ->first();
            $old_letterAssignment = $this->findBy('id', $request->letter_assign_id);
            $old_letterAssignment->status = "Referred to: " . $request->departamentRole;
            $old_letterAssignment->description = $request->description;
            $old_letterAssignment->is_archive = 1;
            $old_letterAssignment->save();
            $letterAssignment = new LetterAssignment();
            $letterAssignment->user_id = Auth::id();
            $letterAssignment->request_id = $old_letterAssignment->request_id;
            $letterAssignment->role_id = $role->id;
            $letterAssignment->assigned_to = $request->assigned_to;
            $letterAssignment->status = "waiting";
            $letterAssignment->save();
            $assignedTo = User::query()
                ->select([
                    'id',
                    'email',
                    'email_verified_at',
                    'receive_notifications'
                ])
                ->where('id', $letterAssignment->assigned_to)
                ->first();

            if ($assignedTo->email_verified_at !== null &&  $assignedTo->receive_notifications) {
                $assignedTo->notify(new RequestRegisteredNotification($letterAssignment->request->subject, $letterAssignment->request->description));
            }

            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }

    public function setPass($request)
    {

        DB::beginTransaction();
        try {
            $letterAssignment = $this->find($request->id);
            $letterAssignment->description = $request->confirmationMessage;
            $letterAssignment->status = "Accepted";
            $letterAssignment->is_archive = 1;
            $letterAssignment->save();

            $staffRequest = StaffRequest::find($letterAssignment->request_id);
            $staffRequest->is_archive = 1;
            $staffRequest->save();

            $staffCode = $staffRequest->cod_staff;
            $user = User::whereHas('employee', function ($query) use ($staffCode) {
                $query->where('staff_code', $staffCode);
            })->with('employee')->first();

            $leave = $staffRequest->leave;

            if ($leave !== null) {
                $employee = $user->employee;
                $leaveBalance = $employee->leave_balance;

                if ($leave->type === "Allowed Leave") {
                    $leaveBalanceDays = $leaveBalance / 8;
                    $vacationDays = $leave->leave_days;

                    if ($vacationDays <= floor($leaveBalanceDays)) {
                        $employee->leave_balance = ($leaveBalanceDays - $vacationDays) * 8;
                        $employee->save();
                    } else {
                        DB::rollBack();
                        Session::flash('error', "The applicant's leave balance has changed and is less than the requested leaves.");
                        return false;
                    }
                }
                if ($leave->type === "Hourly Leave") {
                    list($hours, $minutes) = explode(':', $leave->leave_time);
                    $decimalTime = $hours + ($minutes / 60);
                    $vacationHours = number_format($decimalTime, 2);

                    if ($vacationHours <= $leaveBalance) {
                        $employee->leave_balance = $leaveBalance - $vacationHours;
                        $employee->save();
                    } else {
                        DB::rollBack();
                        Session::flash('error', "The applicant's leave balance has changed and is less than the requested leaves.");
                        return false;
                    }
                }
                $remainingLeaveHours = $employee->leave_balance;
                $remainingLeaveDays = number_format($remainingLeaveHours / 8, 2);

                $staffRequest->description = $staffRequest->description . "<hr>Remaining allowable leave after accepted= " .
                    $remainingLeaveDays . " days (" . $remainingLeaveHours . " hours)" .
                    "<hr>Accept time: " . Carbon::now();
                $staffRequest->save();
            }

            if ($user->email_verified_at !== null && $user->receive_notifications) {
                $message = $request->confirmationMessage ? "Your Request Accepted with a Message" : "Your Request Accepted";
                $user->notify(new RequestRegisteredNotification($message, $request->confirmationMessage));
            }
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
        return false;
    }

    public function setReject($request)
    {
        DB::beginTransaction();
        try {
            $letterAssignment = $this->find($request->id);
            $letterAssignment->description = $request->confirmationMessage;
            $letterAssignment->status = "Rejected";
            $letterAssignment->is_archive = 1;
            $letterAssignment->save();
            $staffRequest = StaffRequest::query()
                ->select([
                    'id',
                    'user_id',
                    'is_archive'
                ])
                ->where('id', $letterAssignment->request_id)
                ->with('user')
                ->first();
            if ($staffRequest) {
                $staffRequest->is_archive = 1;
                $staffRequest->save();
            }
            $user = User::find($staffRequest->user->id);
            if ($user->email_verified_at !== null && $user->receive_notifications) {
                $meesage = $request->confirmationMessage ? "Your Request Was rejected with a Message" : "Your Request Was rejected";
                $user->notify(new RequestRegisteredNotification($meesage, $request->confirmationMessage));
            }
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
        return false;
    }

    public function getArchiveDataTable($request)
    {
        $userId = Auth::id();
        $data = $this->query()
            ->select([
                'id',
                'request_id',
                'role_id',
                'assigned_to',
                'signed_by',
                'created_at'
            ])
            ->where('is_archive', 1)
            ->where('assigned_to', $userId)
            ->with(['request'])
            ->latest();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('requests', function ($row) {
                    return "<p class=\"h5 bg-secondary text-light\">Tracking Number= " . $row->request->id . "</p><br>" . $row->request->description;
                })
                ->addColumn('file', function ($row) {
                    if (!empty($row->request->leave->file)) {
                        return '<a href="' . asset($row->request->leave->file) . '" target="_blank"><i class="fas fa-download fa-lg"></i></a>';
                    }
                })
                ->addColumn('progress', function ($row) {
                    $status = '';
                    $allRelatedRequest = $this->query()->where('request_id', $row->request_id)->with(['assignedTo', 'assignedTo.employee'])->get();
                    foreach ($allRelatedRequest as $assignment) {
                        $signedStatus = $assignment->signed_by ? '<div class="bg-success rounded-3 text-light">Signed</div>' : '<div class="bg-warning rounded-3">Not signed</div>';
                        if ($assignment->description) {
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
                        $status .= $assignment->assignedTo->employee->last_name . ' ' . $assignment->assignedTo->employee->first_name . $signedStatus . $condition . $description . '<br><small class="text-info">last update: ' . $assignment->updated_at . '</small><hr>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $url = route('admin.manageRequests.exportPDF');
                    return '
                    <form action="' . $url . '" method="post">
                    ' . csrf_field() . '
                    <input type="hidden" name="printInformation" id="printInformation" value="' . $row->request->id . '">
                             <button type="submit" class="btn btn-light btn-sm m-2" title="Export PDF">
                            <i class="fa-solid fa-file-pdf fa-2x"></i>
                            </button>
                    </form>';
                })
                ->rawColumns(['requests', 'file', 'progress', 'action'])
                ->make(true);
        }
        return false;
    }

    public function exportPDF($request)
    {
        $data = $this->query()
            ->select([
                'id',
                'user_id',
                'request_id',
                'role_id',
                'assigned_to',
                'signed_by',
                'status'
            ])
            ->where('request_id', $request->printInformation)
            ->with(['user', 'request', 'request.user', 'role', 'assignedTo', 'signedBy'])
            ->get();
        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $description = $data[0]->request->description;
        $pdf = Pdf::loadView('pdf.template', compact(['data', 'description']))->setPaper('a5', 'portrait')->setWarnings(false);
        return $pdf->download($data[0]->request->user->name . '-' . $data[0]->request->user->first_name . '-' . $data[0]->request_id . '.pdf');
    }
}
