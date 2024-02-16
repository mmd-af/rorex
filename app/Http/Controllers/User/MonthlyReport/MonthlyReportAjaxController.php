<?php

namespace App\Http\Controllers\User\MonthlyReport;

use App\Http\Controllers\Controller;
use App\Repositories\User\MonthlyReportRepository;
use Illuminate\Http\Request;

class MonthlyReportAjaxController extends Controller
{
    protected $monthlyReportRepository;

    public function __construct(MonthlyReportRepository $monthlyReportRepository)
    {
        $this->monthlyReportRepository = $monthlyReportRepository;
    }
    public function monthlyReportWithDate(Request $request)
    {
        return $this->monthlyReportRepository->monthlyReportWithDate($request);
    }
}
