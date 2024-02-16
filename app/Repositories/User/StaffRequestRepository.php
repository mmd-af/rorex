<?php

namespace App\Repositories\User;

use App\Models\StaffRequest\StaffRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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
        $support->description = "<strong>Check For Date: " . $request->date . "</strong><br>" . $request->description;
        $support->organization = $request->organization;
        $support->cod_staff = (int)$request->cod_staff;
        $support->save();
        Session::flash('message', 'Your Message Send Successfully');
    }
}
