<?php

namespace App\Repositories\User;

use App\Exports\UserMonthlyReportExport;
use App\Models\DailyReport\DailyReport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyReportRepository extends BaseRepository
{
    public function monthlyReportWithDate($request)
    {
        $monthDate = $request->date;
        $staffCode = Auth::user()->employee->staff_code;
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
                'concediu_ore',
                'without_paid_leave'
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
        $without_paid_leave = 0;
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
            $without_paid_leave += $dailyReport->without_paid_leave;
            $dailyAbsence += $dailyReport->absenta_zile;
            $delayWork += $dailyReport->tarziu_minute;
            $earlyExit += $dailyReport->devreme_minute;
        }
        $remainNotAllowedPlusWork = $this->remainNotAllowedPlusWork($ot_ore, $plus_week_day, $plus_week_night, $plus_holiday_day, $plus_holiday_night);
        $compensation = $this->compensation($remainNotAllowedPlusWork, $delayWork, $earlyExit);
        $totalHours = $this->calcualteExactTotalWorkTime($hourNight, $hourMorning, $hourAfternoon, $hourDaily, $compensation);
        $result = [
            'codeStaff' => $staffCode,
            'monthDate' => $monthDate,
            'hourNight' => sprintf("%.2f", floor($hourNight * 100) / 100),
            'hourMorning' => sprintf("%.2f", floor($hourMorning * 100) / 100),
            'hourAfternoon' => sprintf("%.2f", floor($hourAfternoon * 100) / 100),
            'hourDaily' => sprintf("%.2f", floor($hourDaily * 100) / 100),
            'plus_week_day' => sprintf("%.2f", floor($plus_week_day * 100) / 100),
            'plus_week_night' => sprintf("%.2f", floor($plus_week_night * 100) / 100),
            'plus_holiday_day' => sprintf("%.2f", floor($plus_holiday_day * 100) / 100),
            'plus_holiday_night' => sprintf("%.2f", floor($plus_holiday_night * 100) / 100),
            'dailyAbsence' => sprintf("%.2f", floor($dailyAbsence * 100) / 100),
            'concediu_ore' => sprintf("%.2f", floor($concediu_ore * 100) / 100),
            'without_paid_leave' => sprintf("%.2f", floor($without_paid_leave * 100) / 100),
            'compensation' => sprintf("%.2f", floor($compensation * 100) / 100),
            'totalHours' => sprintf("%.2f", floor($totalHours * 100) / 100),
            'hourUnknown' => $hourUnknown,
            'turaImplicita' => $turaImplicita
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
                'concediu_ore',
                'without_paid_leave'
            ])
            ->where('data', 'LIKE', "$monthDate%")
            ->where('cod_staff', $staffCode)
            ->with('users.employee')
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
        $without_paid_leave = 0;
        $hourUnknown = 0;
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
            $without_paid_leave += $dailyReport->without_paid_leave;
            $dailyAbsence += $dailyReport->absenta_zile;
            $delayWork += $dailyReport->tarziu_minute;
            $earlyExit += $dailyReport->devreme_minute;
            $userName = $dailyReport->users->employee->last_name . " " . $dailyReport->users->employee->first_name;
        }
        $remainNotAllowedPlusWork = $this->remainNotAllowedPlusWork($ot_ore, $plus_week_day, $plus_week_night, $plus_holiday_day, $plus_holiday_night);
        $compensation = $this->compensation($remainNotAllowedPlusWork, $delayWork, $earlyExit);
        $totalHours = $this->calcualteExactTotalWorkTime($hourNight, $hourMorning, $hourAfternoon, $hourDaily, $compensation);
        $data[] = [
            'codeStaff' => $staffCode,
            'Name' => $userName,
            'hourNight' => sprintf("%.2f", floor($hourNight * 100) / 100),
            'hourMorning' => sprintf("%.2f", floor($hourMorning * 100) / 100),
            'hourAfternoon' => sprintf("%.2f", floor($hourAfternoon * 100) / 100),
            'hourDaily' => sprintf("%.2f", floor($hourDaily * 100) / 100),
            'plus_week_day' => sprintf("%.2f", floor($plus_week_day * 100) / 100),
            'plus_week_night' => sprintf("%.2f", floor($plus_week_night * 100) / 100),
            'plus_holiday_day' => sprintf("%.2f", floor($plus_holiday_day * 100) / 100),
            'plus_holiday_night' => sprintf("%.2f", floor($plus_holiday_night * 100) / 100),
            'compensation' => sprintf("%.2f", floor($compensation * 100) / 100),
            'dailyAbsence' => sprintf("%.2f", floor($dailyAbsence * 100) / 100),
            'concediu_ore' => sprintf("%.2f", floor($concediu_ore * 100) / 100),
            'without_paid_leave' => sprintf("%.2f", floor($without_paid_leave * 100) / 100),
            'totalHours' => sprintf("%.2f", floor($totalHours * 100) / 100),
            'hourUnknown' => $hourUnknown,
            'turaImplicita' => $turaImplicita
        ];
        $fileName = $userName . "-reports-" . $monthDate . ".xlsx";
        return Excel::download(new UserMonthlyReportExport($data), $fileName);
    }
    private function remainNotAllowedPlusWork($ot_ore, $plus_week_day, $plus_week_night, $plus_holiday_day, $plus_holiday_night)
    {
        return $ot_ore - ($plus_week_day + $plus_week_night + $plus_holiday_day + $plus_holiday_night);
    }

    private function compensation($remainNotAllowedPlusWork, $delayWork, $earlyExit)
    {
        $totalDelay = ($delayWork / 60) + ($earlyExit / 60);
        return $remainNotAllowedPlusWork - $totalDelay;
    }
    private function calcualteExactTotalWorkTime($hourNight, $hourMorning, $hourAfternoon, $hourDaily, $compensation)
    {
        $totalWorkTime = $hourNight + $hourMorning + $hourAfternoon + $hourDaily;
        if ($compensation < 0) {
            $totalWorkTime += $compensation;
        }
        return $totalWorkTime;
    }
}
