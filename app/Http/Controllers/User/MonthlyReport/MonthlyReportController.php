<?php

namespace App\Http\Controllers\User\MonthlyReport;

use App\Http\Controllers\Controller;
use App\Repositories\User\MonthlyReportRepository;
use Illuminate\Http\Request;

class MonthlyReportController extends Controller
{
    protected $monthlyReportRepository;

    public function __construct(MonthlyReportRepository $monthlyReportRepository)
    {
        $this->monthlyReportRepository = $monthlyReportRepository;
    }

    public function index()
    {
        return view('user.monthlyReports.index');
    }

    public function userMonthlyReportExport(Request $request)
    {
        return $this->monthlyReportRepository->userMonthlyReportExport($request);
    }
}
