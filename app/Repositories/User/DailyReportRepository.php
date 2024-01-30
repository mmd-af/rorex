<?php

namespace App\Repositories\User;

use App\Models\DailyReport\DailyReport;
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
//            ->latest()
            ->get();
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
}
