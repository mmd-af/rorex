<?php

namespace App\Http\Controllers\User\DailyReport;

use App\Http\Controllers\Controller;
use App\Repositories\User\DailyReportRepository;
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

    public function getRoles(Request $request)
    {
        return $this->dailyReportRepository->getRoles($request);
    }

    public function getUserWithRole(Request $request)
    {
        return $this->dailyReportRepository->getUserWithRole($request);
    }
    // public function getLastUpdate(Request $request)
    // {
    //     return $this->dailyReportRepository->getLastUpdate($request);
    // }
}
