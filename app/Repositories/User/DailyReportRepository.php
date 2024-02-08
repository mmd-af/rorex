<?php

namespace App\Repositories\User;

use App\Models\DailyReport\DailyReport;
use App\Models\Support\Support;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DailyReportRepository extends BaseRepository
{
    public function __construct(DailyReport $model)
    {
        $this->setModel($model);
    }

//    public function getOwnReport()
//    {
//        $userId = Auth::id();
//        return $this->query()
//            ->select([
//                'id',
//                'cod_staff',
//                'nume',
//                'data',
//                'saptamana',
//                'nume_schimb',
//                'on_work1',
//                'off_work2',
//                'remarca'
//            ])
//            ->where('cod_staff', $userId)
//            ->orderBy('data', 'DESC')
//            ->paginate(10);
//    }

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
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('button', function ($row) {
                    return '<button onclick="requestForm(' . $row->id . ')" type="button"
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
        return $this->query()
            ->select([
                'id',
                'cod_staff',
                'nume',
                'data'
            ])
            ->where('id', $request->dailyReport_id)
            ->first();
    }

    public function supportRequest($request)
    {
        $support = new Support();
        $support->name = $request->name;
        $support->email = $request->email;
        $support->mobile_phone = $request->mobile_phone;
        $support->subject = $request->subject;
        $support->description = "<strong>Check For Date: " . $request->date . "</strong><br>" . $request->description;
        $support->organization = $request->organization;
        $support->cod_staff = (int)$request->cod_staff;
        $support->save();
    }
}
