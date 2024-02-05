<?php

namespace App\Http\Controllers\Admin\MonthlyReport;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\MonthlyReportRepository;

class MonthlyReportController extends Controller
{
    protected $monthlyReportRepository;

    public function __construct(MonthlyReportRepository $monthlyReportRepository)
    {
        $this->monthlyReportRepository = $monthlyReportRepository;
    }

    public function index()
    {
        return view('admin.monthlyReports.index');
    }
}
