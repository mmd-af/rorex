<?php

namespace App\Repositories\User;

use App\Models\DailyReport\DailyReport;
use App\Models\Support\Support;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DailyReportRepository extends BaseRepository
{
    public function __construct(DailyReport $model)
    {
        $this->setModel($model);
    }

    public function getOwnReport()
    {
        $userId = Auth::id();
        return $this->query()
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
            ->orderBy('data', 'DESC')
            ->paginate(10);
    }

    public function getOwnReportFiltered($request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $userId = Auth::id();
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
            ->where('cod_staff', $userId)
            ->whereBetween('data', [$startDate, $endDate])
            ->get();
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
        $support->description = "Check For Date: " . $request->date . "<br>" . $request->messageText;
        $support->organization = $request->organization;
        $support->cod_staff = (int)$request->cod_staff;
        $support->save();
    }
}
