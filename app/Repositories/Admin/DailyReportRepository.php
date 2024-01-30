<?php

namespace App\Repositories\Admin;

use App\Models\DailyReport\DailyReport;
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
                'off_work1',
                'on_work2',
                'off_work2',
                'on_work3',
                'off_work3',
                'remarca'
            ])
            ->where('cod_staff', $userId)
//            ->latest()
            ->get();
    }
}
