<?php

namespace App\Http\Controllers\Admin\DailyReport;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\DailyReportRepository;

class DailyReportController extends Controller
{
    protected $dailyReportRepository;

    public function __construct(DailyReportRepository $dailyReportRepository)
    {
        $this->dailyReportRepository = $dailyReportRepository;
    }

    public function index()
    {
        $dailyReports = $this->dailyReportRepository->getOwnReport();
        return view('admin.dailyReports.index', compact('dailyReports'));
    }
}
