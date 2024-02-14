<?php

namespace App\Http\Controllers\Admin\MonthlyReport;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\MonthlyReportRepository;
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
        return view('admin.monthlyReports.index');
    }

    public function fullExport(Request $request)
    {
        dd($request->all());
    }
}
