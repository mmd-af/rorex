<?php

namespace App\Repositories\User;

use App\Jobs\User\DailyReportSendRequestJob;
use App\Mail\RequestMail;
use App\Models\DailyReport\DailyReport;
use App\Models\User\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;

class DailyReportRepository extends BaseRepository
{
    public function __construct(DailyReport $model)
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
                'nume',
                'data',
                'saptamana',
                'nume_schimb',
                'on_work1',
                'off_work2',
                'remarca'
            ])
            ->where('cod_staff', $userId)
            ->where('data', 'LIKE', "$request->date%")
            ->orderByDesc('data')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('button', function ($row) {
                    return '<button onclick="requestForm(' . $row->cod_staff . ', \'' . $row->data . '\')" type="button"
                                    class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#forgetRequest">
                                <i class="fa-solid fa-square-arrow-up-right"></i>
                            </button>';
                })
                ->rawColumns(['button'])
                ->make(true);
        }
        return false;
    }


    public function getData($request)
    {
        return User::query()
            ->select([
                'id',
                'cod_staff',
                'name',
                'first_name',
                'departament',
                'email',
            ])
            ->where('cod_staff', $request->id)
            ->first();
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
                'first_name',
                'name'
            ])
            ->role($request->role_name)
            ->get();
    }

    public function checkRequest($request)
    {
        if (is_array($request->description)) {
            $description = $request->description[0] . ' between ' . $request->description[1];
        } else {
            $description = $request->description . " hour";
        }
        $userId = Auth::id();
        $dateOfRequest = Carbon::now()->format('Y/m/d');
        $description =
            'Date: ' . $dateOfRequest .
            '<br><div id="box">Name: ' . $request->name . ' ' . $request->first_name . '<br>' .
            'Code Staff: ' . $request->cod_staff . '</div><br>' .
            'Check For Date: ' . $request->check_date . '</div><br>' .
            '<div id="alignCenter"><b>' . $request->subject . '</b></div><br>as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' . $request->departament .
            '<br><h3>' . $description . '</h3><br>Email: ' . $request->email . '<hr>';

        $data = [
            'userId' => $userId,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile_phone' => $request->input('mobile_phone'),
            'subject' => $request->input('subject'),
            'description' => $description,
            'organization' => $request->input('departament'),
            'departamentRole' => $request->input('departamentRole'),
            'cod_staff' => (int)$request->input('cod_staff'),
            'vacation_day' => (int)$request->input('vacation_day'),
            'assigned_to' => (int)$request->input('assigned_to')
        ];
        DailyReportSendRequestJob::dispatch($data);
        Session::flash('message', 'Your request has been submitted');
    }

    // public function getLastUpdate($request)
    // {
    //     return $this->query()
    //         ->select(['updated_at'])
    //         ->orderBy('updated_at', 'desc')
    //         ->first();
    // }
}
