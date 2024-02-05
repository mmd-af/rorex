<?php

namespace App\Repositories\Admin;

use App\Models\DailyReport\DailyReport;
use App\Models\User\User;
use Yajra\DataTables\Facades\DataTables;

class MonthlyReportRepository extends BaseRepository
{
    public function getUserTable($request)
    {
        $data = User::query()
            ->select([
                'id',
                'cod_staff',
                'name'
            ])
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('button', function ($row) {
                    return '<button class="btn btn-info btn-sm" onclick="showReportModal(' . $row->id . ')"><i class="fa-solid fa-newspaper"></i></button>';
                })
                ->rawColumns(['button'])
                ->make(true);
        }
        return false;
    }

    public function monthlyReportWithDate($request)
    {
        $dailyReports = DailyReport::query()
            ->select([
                'cod_staff',
                'data',
                'nume_schimb',
                'munca_ore'
            ])
            ->where('data', 'LIKE', "$request->date%")
            ->where('nume_schimb', "Night")
            ->where('cod_staff', $request->cod_staff)
            ->get();
        $plusNight = 0;
        foreach ($dailyReports as $dailyReport) {
            $plusNight += $dailyReport->munca_ore;
        }
        return $plusNight;
    }
}
