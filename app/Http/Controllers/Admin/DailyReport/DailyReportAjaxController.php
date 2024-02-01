<?php

namespace App\Http\Controllers\Admin\DailyReport;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Repositories\Admin\DailyReportRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DailyReportAjaxController extends Controller
{
    protected $dailyReportRepository;

    public function __construct(DailyReportRepository $dailyReportRepository)
    {
        $this->dailyReportRepository = $dailyReportRepository;
    }

    public function getData(Request $request)
    {
        return response()->json([
            'data' => $this->dailyReportRepository->getData($request)
        ]);
    }

    public function getDataTable(Request $request)
    {
        return $this->dailyReportRepository->getDataTable($request);
    }
}
