<?php

namespace App\Http\Controllers\Admin\DailyReport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DailyReport\SupportRequest;
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

    public function filter(Request $request)
    {
        $dailyReports = $this->dailyReportRepository->getOwnReportFiltered($request);
        return view('admin.dailyReports.index', compact('dailyReports'));
    }

    public function supportRequest(SupportRequest $request)
    {
        $this->dailyReportRepository->supportRequest($request);
        return redirect()->route('admin.dailyReports.index')->with(['message' => 'Your request has been successfully registered']);
    }
}
