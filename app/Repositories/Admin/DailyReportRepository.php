<?php

namespace App\Repositories\Admin;

use App\Imports\DailyReportExcel;
use App\Models\DailyReport\DailyReport;
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
                'remarca'
            ])
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->make(true);
        }
        return false;
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
                ];
                $condition = [
                    'cod_staff' => $codStaff,
                    'data' => $date,
                ];
                DB::table('daily_reports')->updateOrInsert($condition, $data);
            }
            DB::commit();
            Session::flash('message', 'The update operation was completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }
}
