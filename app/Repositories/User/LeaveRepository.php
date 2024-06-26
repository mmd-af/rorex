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
                'hour',
                'description',
                'remaining'
            ])
            ->where('user_id', $userId)
            ->with(['users', 'requests'])
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('start_date', function ($row) {
                    $originalDate = Carbon::parse($row->start_date);
                    return $originalDate->format('Y-m-d');
                })
                ->rawColumns(['start_date'])
                ->make(true);
        }
        return false;
    }
    public function store($request)
    {
        $userId = Auth::id();
        $hour = "8"; // TODO calculate here
        $file = null;
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
            'hour' => $hour,
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
