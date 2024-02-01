<?php

namespace App\Repositories\Admin;

use App\Imports\DailyReportExcel;
use App\Models\DailyReport\DailyReport;
use App\Models\Support\Support;
use App\Repositories\User\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public function getOwnReportFiltered($request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        return $this->query()
            ->select([
                'id',
                'cod_staff',
                'nume',
                'data',
                'saptamana',
                'nume_schimb',
                'on_work1',
                'off_work1',
                'on_work2',
                'off_work2',
                'on_work3',
                'off_work3',
                'remarca'
            ])
            ->whereBetween('data', [$startDate, $endDate])
            ->orderBy('data', 'DESC')
            ->paginate(10);
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

    public function import($request)
    {
        $files = $request->file('file');
        $files = Excel::toArray(new DailyReportExcel, $files);

        foreach ($files[0] as $item) {
            if (
                $item[0] !== "Cod staff" ||
                $item[1] !== "Nume" ||
                $item[2] !== "Data" ||
                $item[3] !== "Saptamana" ||
                $item[4] !== "Nume schimb" ||
                $item[5] !== "On Work1" ||
                $item[6] !== "Off Work1" ||
                $item[7] !== "On Work2" ||
                $item[8] !== "Off Work2" ||
                $item[9] !== "On Work3" ||
                $item[10] != "Off Work3" ||
                $item[19] !== "Remarca"
            ) {
                return response()->json(['error' => 'Invalid file format']);
            }
            break;
        }
        DB::beginTransaction();
        try {
            foreach ($files[0] as $key => $item) {
                if ($key === 0) {
                    continue;
                }
                $date = date('Y-m-d', strtotime($item[2]));
                $codStaff = (int)$item[0];
                $report = new DailyReport();
                $report->cod_staff = $codStaff;
                $report->nume = $item[1];
                $report->data = $date;
                $report->saptamana = $item[3];
                $report->nume_schimb = $item[4];
                $report->on_work1 = $item[5];
                $report->off_work1 = $item[6];
                $report->on_work2 = $item[7];
                $report->off_work2 = $item[8];
                $report->on_work3 = $item[9];
                $report->off_work3 = $item[10];
                $report->absenta_zile = $item[11];
                $report->munca_ore = $item[12];
                $report->ot_ore = $item[13];
                $report->tarziu_minute = $item[14];
                $report->devreme_minute = $item[15];
                $report->lipsa_ceas_timpi = $item[16];
                $report->sarbatoare_publica_ore = $item[17];
                $report->concediu_ore = $item[18];
                $report->remarca = $item[19];
                $report->save();
            }
            DB::commit();
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }

    }
}
