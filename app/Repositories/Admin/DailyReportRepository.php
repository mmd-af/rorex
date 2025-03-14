<?php

namespace App\Repositories\Admin;

use App\Imports\DailyReportExcel;
use App\Models\DailyReport\DailyReport;
use App\Models\StaffRequest\StaffRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DailyReportRepository extends BaseRepository
{
    public function __construct(DailyReport $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
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
                'edit_by',
                'remarca'
            ])
            ->where('data', 'LIKE', "$request->date%")
            ->with(['users.employee', 'editBy.employee'])
            ->orderByDesc('data')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('first_name', function ($row) {
                    if ($row->users->employee) {
                        return $row->users->employee->first_name;
                    } else return null;
                })
                ->addColumn('last_name', function ($row) {
                    if ($row->users->employee) {
                        return $row->users->employee->last_name;
                    } else return null;
                })
                ->addColumn('edit_by', function ($row) {
                    if ($row->editBy !== null) {
                        return $row->editBy->employee->first_name . " " . $row->editBy->employee->last_name;
                    } else {
                        return "";
                    }
                })
                ->addColumn('action', function ($row) {
                    return '
                    <button
                                onclick="checkRelatedLetter(' . $row->id . ', \'' . $row->cod_staff . '\', \'' . $row->data . '\')"
                                id="openCheckRelatedLetterModal"
                                type="button"
                                class="btn btn-primary btn-sm mx-2"
                                data-bs-toggle="modal"
                                data-bs-target="#checkRelatedLetterModal">
                                <i class="fa-solid fa-envelope-open"></i>
                            </button>
                    <button
                                onclick="openEditFormModal(' . $row->id . ')"
                                id="openEditFormModal"
                                type="button"
                                class="btn btn-info text-light btn-sm mx-2"
                                data-bs-toggle="modal"
                                data-bs-target="#editFormModal">
                                 <i class="fa-solid fa-pen-to-square"></i>
                            </button>';
                })
                ->rawColumns(['first_name', 'edit_by', 'action'])
                ->make(true);
        }
        return false;
    }
    public function getData($request)
    {
        return $this->query()
            ->select([
                'id',
                'nume_schimb',
                'on_work1',
                'off_work1',
                'on_work2',
                'off_work2',
                'on_work3',
                'off_work3',
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
                'without_paid_leave',
                'remarca'
            ])
            ->where('id', $request->id)
            ->first();
    }
    public function import($request)
    {
        $files = $request->file('file');
        $files = Excel::toArray(new DailyReportExcel, $files);
        $expectedHeaders = [
            "Cod staff",
            "Nume",
            "Data",
            "Remarca"
        ];
        $actualHeaders = $files[0][0];
        $missingHeaders = array_diff($expectedHeaders, $actualHeaders);
        if (!empty($missingHeaders)) {
            Session::flash('error', 'Invalid file format. Missing headers: ' . implode(', ', $missingHeaders));
            return false;
        }
        DB::beginTransaction();
        try {
            $yesterday = Carbon::yesterday()->toDateString();
            foreach ($files[0] as $key => $item) {
                if ($key === 0) {
                    continue;
                }
                $date = date('Y-m-d', strtotime($item[2]));
                if ($date > $yesterday) {
                    continue;
                }
                $codStaff = (int)$item[0];
                $data = [
                    'cod_staff' => $codStaff,
                    'nume' => $item[1],
                    'data' => $date,
                    'saptamana' => $item[3],
                    'nume_schimb' => $item[4],
                    'on_work1' => $item[5],
                    'off_work1' => $item[6],
                    'on_work2' => $item[7],
                    'off_work2' => $item[8],
                    'on_work3' => $item[9],
                    'off_work3' => $item[10],
                    'absenta_zile' => $item[11],
                    'munca_ore' => $item[12],
                    'ot_ore' => $item[13],
                    'tarziu_minute' => $item[14],
                    'devreme_minute' => $item[15],
                    'lipsa_ceas_timpi' => $item[16],
                    'sarbatoare_publica_ore' => $item[17],
                    'concediu_ore' => $item[18],
                    'remarca' => $item[19],
                    'updated_at' => Carbon::now()
                ];
                $condition = [
                    'cod_staff' => $codStaff,
                    'data' => $date,
                ];
                $existingRecord = DB::table('daily_reports')->where($condition)->first();
                if (!$existingRecord) {
                    DB::table('daily_reports')->insert($data);
                }
            }
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }

    public function update($request, $dailyID)
    {
        $userId = Auth()->id();
        DB::beginTransaction();
        try {
            $dailyID->nume_schimb = $request->nume_schimb;
            $dailyID->on_work1 = $request->on_work1;
            $dailyID->off_work1 = $request->off_work1;
            $dailyID->on_work2 = $request->on_work2;
            $dailyID->off_work2 = $request->off_work2;
            $dailyID->on_work3 = $request->on_work3;
            $dailyID->off_work3 = $request->off_work3;
            $dailyID->munca_ore = $request->munca_ore;
            $dailyID->ot_ore = $request->ot_ore;
            $dailyID->plus_week_day = $request->plus_week_day;
            $dailyID->plus_week_night = $request->plus_week_night;
            $dailyID->plus_holiday_day = $request->plus_holiday_day;
            $dailyID->plus_holiday_night = $request->plus_holiday_night;
            $dailyID->absenta_zile = $request->absenta_zile;
            $dailyID->tarziu_minute = $request->tarziu_minute;
            $dailyID->devreme_minute = $request->devreme_minute;
            $dailyID->lipsa_ceas_timpi = $request->lipsa_ceas_timpi;
            $dailyID->concediu_ore = $request->concediu_ore;
            $dailyID->without_paid_leave = $request->without_paid_leave;
            $dailyID->remarca = $request->remarca;
            $dailyID->edit_by = $userId;
            $dailyID->save();
            DB::commit();
            return response()->json(['message' => 'The Update Operation was Completed Successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    static function sumHourWork($start, $end)
    {
        if (!empty($start) && !empty($end)) {
            $start = Carbon::parse($start);
            $end = Carbon::parse($end);
            if ($end->gt($start)) {
                $diffInMinutes = $start->diffInMinutes($end);
                $hours = floor($diffInMinutes / 60);
                $minutes = $diffInMinutes % 60;
                $totalHours = $hours + ($minutes / 60);
                return number_format($totalHours, 2, '.', '');
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    public function renderTimeForm($request)
    {
        $sumWork1 = $this->sumHourWork($request->on_work1, $request->off_work1);
        $sumWork2 = $this->sumHourWork($request->on_work2, $request->off_work2);
        $sumWork3 = $this->sumHourWork($request->on_work3, $request->off_work3);
        return response()->json(['sumWork1' => $sumWork1, 'sumWork2' => $sumWork2, 'sumWork3' => $sumWork3]);
    }
    public function checkRelatedLetter($request)
    {
        $formattedDate = Carbon::parse($request->thisDay)->format('Y-m-d');
        $staffRequest = StaffRequest::query()
            ->select([
                'id',
                'user_id',
                'cod_staff',
                'description'
            ])
            ->where('cod_staff', $request->staffCode)
            ->where('description', 'LIKE', "%$formattedDate%")
            ->with(['assignments', 'assignments.assignedTo.employee'])
            ->get();
        return $staffRequest;
    }

    public function importSingleReport($request)
    {
        $files = $request->file('file');
        $files = Excel::toArray(new DailyReportExcel, $files);
        $expectedHeaders = [
            "Numar",
            "Cod staff",
            "Nume",
            "Departament",
            "ID utilizator",
            "Saptamana",
            "Data",
            "Timp",
            "Numar masina",
            "Remarca"
        ];
        $actualHeaders = $files[0][0];
        $missingHeaders = array_diff($expectedHeaders, $actualHeaders);
        if (!empty($missingHeaders)) {
            Session::flash('error', 'Invalid file format. Missing headers: ' . implode(', ', $missingHeaders));
            return false;
        }
        $entryTimes = [];
        foreach ($files[0] as $key => $item) {
            if ($key === 0) {
                continue;
            }
            $yesterday = Carbon::yesterday()->toDateString();
            $date = date('Y-m-d', strtotime($item[6]));
            if ($date > $yesterday) {
                continue;
            }
            if ($item[5] == "Sambata" or $item[5] == "Duminica") {
                $codStaff = (int)$item[1];
                if (!isset($entryTimes[$date][$codStaff])) {
                    $entryTimes[$date][$codStaff] = [
                        'first_entry' => $item[7],
                        'last_entry' => null
                    ];
                } else {
                    $entryTimes[$date][$codStaff]['last_entry'] = $item[7];
                }
            }
        }
        DB::beginTransaction();
        try {
            foreach ($entryTimes as $date => $employees) {
                foreach ($employees as $codStaff => $entryTime) {
                    $this->updateDailyReport($date, $codStaff, $entryTime['first_entry'], $entryTime['last_entry']);
                }
            }
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }

    public function updateDailyReport($date, $staffCode, $onWorkTime, $offWorkTime)
    {
        $date = Carbon::parse($date)->toDateString();
        $record = $this->query()
            ->where('data', $date)
            ->where('cod_staff', $staffCode)
            ->first();
        if ($record) {
            if (empty($record->on_work1)) {
                $record->on_work1 = $onWorkTime;
            }
            if (empty($record->off_work2)) {
                $record->off_work2 = $offWorkTime;
            }
            $record->save();
        }
    }
}
