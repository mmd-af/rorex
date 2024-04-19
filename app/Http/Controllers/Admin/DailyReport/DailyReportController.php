<?php

namespace App\Http\Controllers\Admin\DailyReport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DailyReport\UpdateRequest;
use App\Repositories\Admin\DailyReportRepository;
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
        return view('admin.dailyReports.index');
    }

    public function import(UpdateRequest $request)
    {
        $this->dailyReportRepository->import($request);
        return redirect()->route('admin.dailyReports.index')->with([
            'error' => session('error'),
            'message' => session('message'),
        ]);
    }
    public function indexSingleReport()
    {
        return view('admin.dailyReports.singleReports.index');
    }
    public function importSingleReport(Request $request)
    {
        $this->dailyReportRepository->importSingleReport($request);
        return redirect()->route('admin.dailyReports.singleReports.index')->with([
            'error' => session('error'),
            'message' => session('message'),
        ]);
    }
}
