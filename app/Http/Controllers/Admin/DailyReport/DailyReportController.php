<?php

namespace App\Http\Controllers\Admin\DailyReport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DailyReport\UpdateRequest;
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

}
