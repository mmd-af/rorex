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
                'name',
                'first_name'
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
        $monthDate = $request->date;
        $staffCode = $request->cod_staff;
        $dailyReports = DailyReport::query()
            ->select([
                'cod_staff',
                'data',
                'nume_schimb',
                'absenta_zile',
                'munca_ore',
                'ot_ore',
                'plus_week_day',
                'plus_week_night',
                'plus_holiday_day',
                'plus_holiday_night',
                'tarziu_minute',
                'devreme_minute',
                'lipsa_ceas_timpi',
                'concediu_ore'
            ])
            ->where('data', 'LIKE', "$monthDate%")
            ->where('cod_staff', $staffCode)
            ->get();

        $hourNight = 0;
        $hourMorning = 0;
        $hourAfternoon = 0;
        $hourDaily = 0;
        $ot_ore = 0;
        $plus_week_day = 0;
        $plus_week_night = 0;
        $plus_holiday_day = 0;
        $plus_holiday_night = 0;
        $concediu_ore = 0;
        $hourUnknown = 0;
        $dailyAbsence = 0;
        $delayWork = 0;
        $earlyExit = 0;
        $turaImplicita = 0;
        $lipsaCeasTimpi = 0;
        foreach ($dailyReports as $dailyReport) {
            if ($dailyReport->nume_schimb == 'Night') {
                $hourNight += $dailyReport->munca_ore;
            } elseif ($dailyReport->nume_schimb == 'Morning') {
                $hourMorning += $dailyReport->munca_ore;
            } elseif ($dailyReport->nume_schimb == 'Afternoon') {
                $hourAfternoon += $dailyReport->munca_ore;
            } elseif ($dailyReport->nume_schimb == 'Daily') {
                $hourDaily += $dailyReport->munca_ore;
            } elseif ($dailyReport->nume_schimb == 'Tura implicita') {
                $turaImplicita += $dailyReport->munca_ore;
            } else {
                $hourUnknown += $dailyReport->munca_ore;
            }
            if (!is_null($dailyReport->lipsa_ceas_timpi)) {
                $lipsaCeasTimpi += $dailyReport->lipsa_ceas_timpi;
            }
            $ot_ore += $dailyReport->ot_ore;
            $plus_week_day += $dailyReport->plus_week_day;
            $plus_week_night += $dailyReport->plus_week_night;
            $plus_holiday_day += $dailyReport->plus_holiday_day;
            $plus_holiday_night += $dailyReport->plus_holiday_night;
            $concediu_ore += $dailyReport->concediu_ore;
            $dailyAbsence += $dailyReport->absenta_zile;
            $delayWork += $dailyReport->tarziu_minute;
            $earlyExit += $dailyReport->devreme_minute;
        }
        $totalHours = $hourNight + $hourMorning + $hourAfternoon + $hourDaily;
        $result = [
            'codeStaff' => $staffCode,
            'monthDate' => $monthDate,
            'hourNight' => number_format($hourNight, 1),
            'hourMorning' => number_format($hourMorning, 1),
            'hourAfternoon' => number_format($hourAfternoon, 1),
            'hourDaily' => number_format($hourDaily, 1),
            'ot_ore' => number_format($ot_ore, 1),
            'plus_week_day' => number_format($plus_week_day, 1),
            'plus_week_night' => number_format($plus_week_night, 1),
            'plus_holiday_day' => number_format($plus_holiday_day, 1),
            'plus_holiday_night' => number_format($plus_holiday_night, 1),
            'delayWork' => number_format($delayWork / 60, 1),
            'earlyExit' => number_format($earlyExit / 60, 1),
            'dailyAbsence' => number_format($dailyAbsence, 1),
            'concediu_ore' => number_format($concediu_ore, 1),
            'totalHours' => number_format($totalHours, 1),
            'hourUnknown' => $hourUnknown,
            'turaImplicita' => $turaImplicita,
            'forgotPunch' => $lipsaCeasTimpi
        ];
        return $result;
    }

    public function userMonthlyReportExport($request)
    {
        $monthDate = $request->date;
        $staffCode = $request->cod_staff;
        $dailyReports = DailyReport::query()
            ->select([
                'cod_staff',
                'data',
                'nume_schimb',
                'absenta_zile',
                'munca_ore',
                'ot_ore',
                'plus_week_day',
                'plus_week_night',
                'plus_holiday_day',
                'plus_holiday_night',
                'tarziu_minute',
                'devreme_minute',
                'lipsa_ceas_timpi',
                'concediu_ore'
            ])
            ->where('data', 'LIKE', "$monthDate%")
            ->where('cod_staff', $staffCode)
            ->with('users')
            ->get();
        $hourNight = 0;
        $hourMorning = 0;
        $hourAfternoon = 0;
        $hourDaily = 0;
        $hourUnknown = 0;
        $ot_ore = 0;
        $plus_week_day = 0;
        $plus_week_night = 0;
        $plus_holiday_day = 0;
        $plus_holiday_night = 0;
        $concediu_ore = 0;
        $dailyAbsence = 0;
        $delayWork = 0;
        $earlyExit = 0;
        $turaImplicita = 0;
        $lipsaCeasTimpi = 0;
        $data = [];
        foreach ($dailyReports as $dailyReport) {
            if ($dailyReport->nume_schimb == 'Night') {
                $hourNight += $dailyReport->munca_ore;
            } elseif ($dailyReport->nume_schimb == 'Morning') {
                $hourMorning += $dailyReport->munca_ore;
            } elseif ($dailyReport->nume_schimb == 'Afternoon') {
                $hourAfternoon += $dailyReport->munca_ore;
            } elseif ($dailyReport->nume_schimb == 'Daily') {
                $hourDaily += $dailyReport->munca_ore;
            } elseif ($dailyReport->nume_schimb == 'Tura implicita') {
                $turaImplicita += $dailyReport->munca_ore;
            } else {
                $hourUnknown += $dailyReport->munca_ore;
            }
            if (!is_null($dailyReport->lipsa_ceas_timpi)) {
                $lipsaCeasTimpi += $dailyReport->lipsa_ceas_timpi;
            }
            $ot_ore += $dailyReport->ot_ore;
            $plus_week_day += $dailyReport->plus_week_day;
            $plus_week_night += $dailyReport->plus_week_night;
            $plus_holiday_day += $dailyReport->plus_holiday_day;
            $plus_holiday_night += $dailyReport->plus_holiday_night;
            $concediu_ore += $dailyReport->concediu_ore;
            $dailyAbsence += $dailyReport->absenta_zile;
            $delayWork += $dailyReport->tarziu_minute;
            $earlyExit += $dailyReport->devreme_minute;
            $userName = $dailyReport->users->name . " " . $dailyReport->users->prenumele_tatalui;
        }
        $totalHours = $hourNight + $hourMorning + $hourAfternoon + $hourDaily;
        $data[] = [
            'codeStaff' => $staffCode,
            'Name' => $userName,
            'hourNight' => number_format($hourNight, 1),
            'hourMorning' => number_format($hourMorning, 1),
            'hourAfternoon' => number_format($hourAfternoon, 1),
            'hourDaily' => number_format($hourDaily, 1),
            'ot_ore' => number_format($ot_ore, 1),
            'plus_week_day' => number_format($plus_week_day, 1),
            'plus_week_night' => number_format($plus_week_night, 1),
            'plus_holiday_day' => number_format($plus_holiday_day, 1),
            'plus_holiday_night' => number_format($plus_holiday_night, 1),
            'delayWork' => number_format($delayWork / 60, 1),
            'earlyExit' => number_format($earlyExit / 60, 1),
            'dailyAbsence' => number_format($dailyAbsence, 1),
            'concediu_ore' => number_format($concediu_ore, 1),
            'totalHours' => number_format($totalHours, 1),
            'hourUnknown' => $hourUnknown,
            'turaImplicita' => $turaImplicita,
            'forgotPunch' => $lipsaCeasTimpi,
        ];
        $fileName = $userName . "-reports-" . $monthDate . ".xlsx";
        return Excel::download(new MonthlyReportExport($data), $fileName);
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
                    'id',
                    'cod_staff',
                    'data',
                    'nume_schimb',
                    'absenta_zile',
                    'munca_ore',
                    'ot_ore',
                    'plus_week_day',
                    'plus_week_night',
                    'plus_holiday_day',
                    'plus_holiday_night',
                    'tarziu_minute',
                    'devreme_minute',
                    'lipsa_ceas_timpi',
                    'concediu_ore'
                ])
                ->where('data', 'LIKE', "$request->dateOfExport%")
                ->where('cod_staff', $staffCode)
                ->with('users')
                ->get();
            $hourNight = 0;
            $hourMorning = 0;
            $hourAfternoon = 0;
            $hourDaily = 0;
            $ot_ore = 0;
            $plus_week_day = 0;
            $plus_week_night = 0;
            $plus_holiday_day = 0;
            $plus_holiday_night = 0;
            $concediu_ore = 0;
            $hourUnknown = 0;
            $dailyAbsence = 0;
            $delayWork = 0;
            $earlyExit = 0;
            $turaImplicita = 0;
            $lipsaCeasTimpi = 0;
            foreach ($dailyReports as $dailyReport) {
                if ($dailyReport->nume_schimb == 'Night') {
                    $hourNight += $dailyReport->munca_ore;
                } elseif ($dailyReport->nume_schimb == 'Morning') {
                    $hourMorning += $dailyReport->munca_ore;
                } elseif ($dailyReport->nume_schimb == 'Afternoon') {
                    $hourAfternoon += $dailyReport->munca_ore;
                } elseif ($dailyReport->nume_schimb == 'Daily') {
                    $hourDaily += $dailyReport->munca_ore;
                } elseif ($dailyReport->nume_schimb == 'Tura implicita') {
                    $turaImplicita += $dailyReport->munca_ore;
                } else {
                    $hourUnknown += $dailyReport->munca_ore;
                }
                if (!is_null($dailyReport->lipsa_ceas_timpi)) {
                    $lipsaCeasTimpi += $dailyReport->lipsa_ceas_timpi;
                }
                $ot_ore += $dailyReport->ot_ore;
                $plus_week_day += $dailyReport->plus_week_day;
                $plus_week_night += $dailyReport->plus_week_night;
                $plus_holiday_day += $dailyReport->plus_holiday_day;
                $plus_holiday_night += $dailyReport->plus_holiday_night;
                $concediu_ore += $dailyReport->concediu_ore;
                $dailyAbsence += $dailyReport->absenta_zile;
                $delayWork += $dailyReport->tarziu_minute;
                $earlyExit += $dailyReport->devreme_minute;
                $userName = $dailyReport->users->name . " " . $dailyReport->users->first_name;
            }
            $totalHours = $hourNight + $hourMorning + $hourAfternoon + $hourDaily;
            $data[] = [
                'codeStaff' => $staffCode,
                'Name' => $userName,
                'hourNight' => number_format($hourNight, 1),
                'hourMorning' => number_format($hourMorning, 1),
                'hourAfternoon' => number_format($hourAfternoon, 1),
                'hourDaily' => number_format($hourDaily, 1),
                'ot_ore' => number_format($ot_ore, 1),
                'plus_week_day' => number_format($plus_week_day, 1),
                'plus_week_night' => number_format($plus_week_night, 1),
                'plus_holiday_day' => number_format($plus_holiday_day, 1),
                'plus_holiday_night' => number_format($plus_holiday_night, 1),
                'delayWork' => number_format($delayWork / 60, 1),
                'earlyExit' => number_format($earlyExit / 60, 1),
                'dailyAbsence' => number_format($dailyAbsence, 1),
                'concediu_ore' => number_format($concediu_ore, 1),
                'totalHours' => number_format($totalHours, 1),
                'hourUnknown' => $hourUnknown,
                'turaImplicita' => $turaImplicita,
                'forgotPunch' => $lipsaCeasTimpi
            ];
        }
        return Excel::download(new MonthlyReportExport($data), "full-monthly-reports-" . $request->dateOfExport . ".xlsx");
    }
}
