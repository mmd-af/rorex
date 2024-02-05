<?php

namespace App\Http\Controllers\Admin\MonthlyReport;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\MonthlyReportRepository;
use Illuminate\Http\Request;

class MonthlyReportAjaxController extends Controller
{
    protected $monthlyReportRepository;

    public function __construct(MonthlyReportRepository $monthlyReportRepository)
    {
        $this->monthlyReportRepository = $monthlyReportRepository;
    }

    public function getUserTable(Request $request)
    {
        return $this->monthlyReportRepository->getUserTable($request);
    }
}
