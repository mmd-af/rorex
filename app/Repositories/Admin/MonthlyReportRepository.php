<?php

namespace App\Repositories\Admin;

use App\Exports\MonthlyReportExport;
use App\Models\DailyReport\DailyReport;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
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
                'tarziu_minute',
                'devreme_minute',
                'lipsa_ceas_timpi'
            ])
            ->where('data', 'LIKE', "$monthDate%")
            ->where('cod_staff', $staffCode)
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
            } elseif ($dailyReport->nume_schimb == 'Plus-Day') {
                $hourPlusDay += $dailyReport->ot_ore;
            } elseif ($dailyReport->nume_schimb == 'Plus-Night') {
                $hourPlusNight += $dailyReport->ot_ore;
            } elseif ($dailyReport->nume_schimb == 'Tura implicita') {
                $turaImplicita += $dailyReport->munca_ore;
            } else {
                $hourUnknown += $dailyReport->munca_ore;
            }
            if (!is_null($dailyReport->lipsa_ceas_timpi)) {
                $lipsaCeasTimpi += $dailyReport->lipsa_ceas_timpi;
            }
            $plusWork += $dailyReport->ot_ore;
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
            'hourPlusDay' => number_format($hourPlusDay, 1),
            'hourPlusNight' => number_format($hourPlusNight, 1),
            'plusWork' => number_format($plusWork, 1),
            'delayWork' => $delayWork,
            'earlyExit' => $earlyExit,
            'dailyAbsence' => number_format($dailyAbsence, 1),
            'totalHours' => number_format($totalHours, 1),
            'hourUnknown' => $hourUnknown,
            'turaImplicita' => $turaImplicita,
            'forgotPunch' => $lipsaCeasTimpi
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
                    'id',
                    'cod_staff',
                    'data',
                    'nume_schimb',
                    'absenta_zile',
                    'munca_ore',
                    'ot_ore',
                    'tarziu_minute',
                    'devreme_minute',
                    'lipsa_ceas_timpi'
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
                } elseif ($dailyReport->nume_schimb == 'Plus-Day') {
                    $hourPlusDay += $dailyReport->ot_ore;
                } elseif ($dailyReport->nume_schimb == 'Plus-Night') {
                    $hourPlusNight += $dailyReport->ot_ore;
                } elseif ($dailyReport->nume_schimb == 'Tura implicita') {
                    $turaImplicita += $dailyReport->munca_ore;
                } else {
                    $hourUnknown += $dailyReport->munca_ore;
                }
                if (!is_null($dailyReport->lipsa_ceas_timpi)) {
                    $lipsaCeasTimpi += $dailyReport->lipsa_ceas_timpi;
                }
                $plusWork += $dailyReport->ot_ore;
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
                'hourPlusDay' => number_format($hourPlusDay, 1),
                'hourPlusNight' => number_format($hourPlusNight, 1),
                'plusWork' => number_format($plusWork, 1),
                'delayWork' => $delayWork,
                'earlyExit' => $earlyExit,
                'dailyAbsence' => number_format($dailyAbsence, 1),
                'totalHours' => number_format($totalHours, 1),
                'hourUnknown' => $hourUnknown,
                'turaImplicita' => $turaImplicita,
                'forgotPunch' => $lipsaCeasTimpi
            ];
        }
        return Excel::download(new MonthlyReportExport($data), "full-monthly-reports-" . $request->dateOfExport . ".xlsx");
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
                'tarziu_minute',
                'devreme_minute',
                'lipsa_ceas_timpi'
            ])
            ->where('data', 'LIKE', "$monthDate%")
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
            } elseif ($dailyReport->nume_schimb == 'Plus-Day') {
                $hourPlusDay += $dailyReport->ot_ore;
            } elseif ($dailyReport->nume_schimb == 'Plus-Night') {
                $hourPlusNight += $dailyReport->ot_ore;
            } elseif ($dailyReport->nume_schimb == 'Tura implicita') {
                $turaImplicita += $dailyReport->munca_ore;
            } else {
                $hourUnknown += $dailyReport->munca_ore;
            }
            if (!is_null($dailyReport->lipsa_ceas_timpi)) {
                $lipsaCeasTimpi += $dailyReport->lipsa_ceas_timpi;
            }
            $plusWork += $dailyReport->ot_ore;
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
            'hourPlusDay' => number_format($hourPlusDay, 1),
            'hourPlusNight' => number_format($hourPlusNight, 1),
            'plusWork' => number_format($plusWork, 1),
            'delayWork' => $delayWork,
            'earlyExit' => $earlyExit,
            'dailyAbsence' => number_format($dailyAbsence, 1),
            'totalHours' => number_format($totalHours, 1),
            'hourUnknown' => $hourUnknown,
            'turaImplicita' => $turaImplicita,
            'forgotPunch' => $lipsaCeasTimpi
        ];
        $fileName = $userName . "-reports-" . $monthDate . ".xlsx";
        return Excel::download(new MonthlyReportExport($data), $fileName);
    }
}
