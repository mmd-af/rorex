<?php

namespace App\Http\Controllers\User\DailyReport;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\DailyReport\CheckRequest;
use App\Repositories\User\DailyReportRepository;
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
        return view('user.dailyReports.index');
    }

    public function checkRequest(CheckRequest $request)
    {
        $this->dailyReportRepository->checkRequest($request);
        return redirect()->route('user.dailyReports.index');
    }
}
