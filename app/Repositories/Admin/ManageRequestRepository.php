<?php

namespace App\Repositories\Admin;

use App\Models\LetterAssignment\LetterAssignment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
//            ->with(['assignments'])
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('created_at', function ($row) {
                    $originalDate = Carbon::parse($row->created_at);
                    return $originalDate->format('Y-m-d');
                })
                ->addColumn('status', function ($row) {
                    $signedStatus = $row->signed_by ? 'Signed' : 'Not signed';
                    return $signedStatus;
                })
                ->rawColumns(['created_at', 'status'])
                ->make(true);
        }
        return false;
    }
}
