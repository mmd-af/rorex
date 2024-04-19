<?php

namespace App\Http\Controllers\Admin\DailyReport;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\DailyReportRepository;
use App\Models\DailyReport\DailyReport;
use Illuminate\Http\Request;

class DailyReportAjaxController extends Controller
{
    protected $dailyReportRepository;

    public function __construct(DailyReportRepository $dailyReportRepository)
    {
        $this->dailyReportRepository = $dailyReportRepository;
    }

    public function getDataTable(Request $request)
    {
        return $this->dailyReportRepository->getDataTable($request);
    }
    public function getData(Request $request)
    {
        return response()->json([
            'data' => $this->dailyReportRepository->getData($request)
        ]);
    }
    public function renderTimeForm(Request $request)
    {
        return response()->json([
            'data' => $this->dailyReportRepository->renderTimeForm($request)
        ]);
    }
    function update(Request $request, DailyReport $dailyID)
    {
        return response()->json([
            'data' => $this->dailyReportRepository->update($request, $dailyID)
        ]);
    }
}
