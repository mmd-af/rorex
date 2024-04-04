<?php

namespace App\Http\Controllers\Admin\DailyReport;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\DailyReportRepository;
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
    public function renderForm(Request $request)
    {
        return response()->json([
            'data' => $this->dailyReportRepository->renderForm($request)
        ]);
    }
}
