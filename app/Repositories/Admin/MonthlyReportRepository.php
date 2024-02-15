<?php

namespace App\Repositories\Admin;

use App\Exports\MonthlyReportExport;
use App\Models\DailyReport\DailyReport;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class MonthlyReportRepository extends BaseRepository
{
    public function getUserTable($request)
    {
        $data = User::query()
            ->select([
                'id',
                'cod_staff',
                'prenumele_tatalui',
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
                'absenta_zile',
                'munca_ore',
                'ot_ore',
                'tarziu_minute',
                'devreme_minute'
            ])
            ->where('data', 'LIKE', "$request->date%")
            ->where('cod_staff', $request->cod_staff)
            ->get();

        $hourNight = 0;
        $hourMorning = 0;
        $hourAfternoon = 0;
        $hourDaily = 0;
        $hourPlusDay = 0;
        $hourPlusNight = 0;
        $hourUnknown = 0;
        $plusWork = 0;
        $dailyAbsence = 0;
        $delayWork = 0;
        $earlyExit = 0;
        foreach ($dailyReports as $dailyReport) {
            if ($dailyReport->nume_schimb == 'Night') {
                $hourNight += $dailyReport->munca_ore;
            } elseif ($dailyReport->nume_schimb == 'Morning') {
                $hourMorning += $dailyReport->munca_ore;
            } elseif ($dailyReport->nume_schimb == 'Afternoon') {
                $hourAfternoon += $dailyReport->munca_ore;
            } elseif ($dailyReport->nume_schimb == 'Daily') {
                $hourDaily += $dailyReport->munca_ore;
            } elseif ($dailyReport->nume_schimb == 'Plus-Day') {
                $hourPlusDay += $dailyReport->munca_ore;
            } elseif ($dailyReport->nume_schimb == 'Plus-Night') {
                $hourPlusNight += $dailyReport->munca_ore;
            } else {
                $hourUnknown += $dailyReport->munca_ore;
            }
            $plusWork += $dailyReport->ot_ore;
            $dailyAbsence += $dailyReport->absenta_zile;
            $delayWork += $dailyReport->tarziu_minute;
            $earlyExit += $dailyReport->devreme_minute;
        }
        $totalHours = $hourNight + $hourMorning + $hourAfternoon + $hourDaily + $hourPlusDay + $hourPlusNight;
        $result = [
            'hourNight' => $hourNight,
            'hourMorning' => $hourMorning,
            'hourAfternoon' => $hourAfternoon,
            'hourDaily' => $hourDaily,
            'hourPlusDay' => $hourPlusDay,
            'hourPlusNight' => $hourPlusNight,
            'plusWork' => $plusWork,
            'dailyAbsence' => $dailyAbsence,
            'delayWork' => $delayWork,
            'earlyExit' => $earlyExit,
            'hourUnknown' => $hourUnknown,
            'totalHours' => $totalHours,
        ];
        return $result;
    }

    public function fullExport($request)
    {
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $staffCodes = DailyReport::query()
            ->select('cod_staff')
            ->groupBy('cod_staff')
            ->pluck('cod_staff');
        $data = [];
        foreach ($staffCodes as $staffCode) {
            $dailyReports = DailyReport::query()
                ->select([
                    'cod_staff',
                    'data',
                    'nume_schimb',
                    'absenta_zile',
                    'munca_ore',
                    'ot_ore',
                    'tarziu_minute',
                    'devreme_minute'
                ])
                ->where('data', 'LIKE', "$request->dateOfExport%")
                ->where('cod_staff', $staffCode)
                ->with('users')
                ->get();
            $hourNight = 0;
            $hourMorning = 0;
            $hourAfternoon = 0;
            $hourDaily = 0;
            $hourPlusDay = 0;
            $hourPlusNight = 0;
            $hourUnknown = 0;
            $plusWork = 0;
            $dailyAbsence = 0;
            $delayWork = 0;
            $earlyExit = 0;
            foreach ($dailyReports as $dailyReport) {
                if ($dailyReport->nume_schimb == 'Night') {
                    $hourNight += $dailyReport->munca_ore;
                } elseif ($dailyReport->nume_schimb == 'Morning') {
                    $hourMorning += $dailyReport->munca_ore;
                } elseif ($dailyReport->nume_schimb == 'Afternoon') {
                    $hourAfternoon += $dailyReport->munca_ore;
                } elseif ($dailyReport->nume_schimb == 'Daily') {
                    $hourDaily += $dailyReport->munca_ore;
                } elseif ($dailyReport->nume_schimb == 'Plus-Day') {
                    $hourPlusDay += $dailyReport->munca_ore;
                } elseif ($dailyReport->nume_schimb == 'Plus-Night') {
                    $hourPlusNight += $dailyReport->munca_ore;
                } else {
                    $hourUnknown += $dailyReport->munca_ore;
                }
                $plusWork += $dailyReport->ot_ore;
                $dailyAbsence += $dailyReport->absenta_zile;
                $delayWork += $dailyReport->tarziu_minute;
                $earlyExit += $dailyReport->devreme_minute;
                $userName = $dailyReport->users->name." ".$dailyReport->users->prenumele_tatalui;
            }
            $totalHours = $hourNight + $hourMorning + $hourAfternoon + $hourDaily + $hourPlusDay + $hourPlusNight;
            $data[] = [
                'codeStaff' => $staffCode,
                'Name' => $userName,
                'hourNight' => $hourNight,
                'hourMorning' => $hourMorning,
                'hourAfternoon' => $hourAfternoon,
                'hourDaily' => $hourDaily,
                'hourPlusDay' => $hourPlusDay,
                'hourPlusNight' => $hourPlusNight,
                'plusWork' => $plusWork,
                'dailyAbsence' => $dailyAbsence,
                'delayWork' => $delayWork,
                'earlyExit' => $earlyExit,
                'hourUnknown' => $hourUnknown,
                'totalHours' => $totalHours
            ];
        }
        return Excel::download(new MonthlyReportExport($data), "full-monthly-reports-" . $request->dateOfExport . ".xlsx");
    }
}
