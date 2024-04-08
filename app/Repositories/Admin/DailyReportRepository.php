<?php

namespace App\Repositories\Admin;

use App\Imports\DailyReportExcel;
use App\Models\DailyReport\DailyReport;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
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
            ->orderByDesc('data')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('first_name', function ($row) {
                    return $row->users->first_name;
                })
                ->addColumn('edit', function ($row) {
                    return '<button
                                onclick="openEditFormModal(' . $row->id . ')"
                                id="openEditFormModal"
                                type="button"
                                class="btn btn-info text-light btn-sm mx-2"
                                data-bs-toggle="modal"
                                data-bs-target="#editFormModal">
                                 <i class="fa-solid fa-pen-to-square"></i>
                            </button>';
                })
                ->addColumn('edit_by', function ($row) {
                    if ($row->editBy !== null) {
                        return $row->editBy->first_name . " " . $row->editBy->name;
                    } else {
                        return "";
                    }
                })

                ->rawColumns(['first_name', 'edit', 'edit_by'])
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
            foreach ($files[0] as $key => $item) {
                if ($key === 0) {
                    continue;
                }
                $date = date('Y-m-d', strtotime($item[2]));
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
                DB::table('daily_reports')->updateOrInsert($condition, $data);
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
            $dailyID->remarca = $request->remarca;
            $dailyID->edit_by = $userId;
            $dailyID->save();
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
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
    function renderTimeForm($request)
    {
        $sumWork1 = $this->sumHourWork($request->on_work1, $request->off_work1);
        $sumWork2 = $this->sumHourWork($request->on_work2, $request->off_work2);
        $sumWork3 = $this->sumHourWork($request->on_work3, $request->off_work3);
        return response()->json(['sumWork1' => $sumWork1, 'sumWork2' => $sumWork2, 'sumWork3' => $sumWork3]);
    }
}
