<?php

namespace App\Repositories\Admin;

use App\Models\LetterAssignment\LetterAssignment;
use App\Models\StaffRequest\StaffRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

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
            ->where('assigned_to', $userId)
            ->where('status', "waiting")
            ->where('is_archive', 0)
            ->with(['request'])
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('created_at', function ($row) {
                    $originalDate = Carbon::parse($row->created_at);
                    return $originalDate->format('Y-m-d');
                })
                ->addColumn('requests', function ($row) {
                    return $row->request->description;
                })
                ->addColumn('sign', function ($row) {
                    return '
                    <div class="form-switch">
                    <input onclick="handleSign(event, ' . $row->id . ')" class="form-check-input" type="checkbox" role="switch" id="signed_by" name="signed_by" value="' . $row->signed_by . '" ' . ($row->signed_by ? 'checked disabled' : '') . '>
                    </div>';

                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-light btn-sm mx-2" onclick="showReportModal(' . $row->id . ')">
                            <i class="fa-solid fa-print"></i>
                            </button>
                            <button onclick="requestForm(' . $row->id . ')" type="button"
                                    class="btn btn-primary btn-sm mx-2" data-bs-toggle="modal"
                                    data-bs-target="#forgetRequest">
                                <i class="fa-solid fa-square-arrow-up-right"></i>
                            </button>
                            ' . ($row->signed_by ? '
                            <button onclick="setPass(' . $row->id . ')" type="button"
                                    class="btn btn-success btn-sm mx-2">
                                <i class = "fa-solid fa-file-zipper"></i>
                            </button>
                            ' : '
                            <button onclick="setRejected(' . $row->id . ')" type="button"
                                    class="btn btn-danger btn-sm mx-2">
                                <i class="fa-solid fa-square-xmark"></i>
                            </button>
                            ') . '


                            ';
                })
                ->rawColumns(['created_at', 'requests', 'sign', 'action'])
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
            $staffRequest = StaffRequest::find($letterAssignment->request_id);
            $staffRequest->description = $staffRequest->description . "<br> Signed_by: " . $letterAssignment->assignedTo->name;
            $staffRequest->save();
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
        return false;
    }

    public function setPass($request)
    {
        DB::beginTransaction();
        try {
            $letterAssignment = $this->find($request->id);
            $letterAssignment->status = "Accepted";
            $letterAssignment->save();
            $staffRequest = StaffRequest::find($letterAssignment->request_id);
            $staffRequest->description = $staffRequest->description . "<br> status: Accepted";
            $staffRequest->save();
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
        return false;
    }
}
