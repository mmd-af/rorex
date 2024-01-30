<?php

namespace App\Http\Controllers\Admin\DailyReport;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\DailyReportRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function filter(Request $request)
    {
        $dailyReports = $this->dailyReportRepository->getOwnReportFiltered($request);
        return view('admin.dailyReports.index', compact('dailyReports'));
    }
}
