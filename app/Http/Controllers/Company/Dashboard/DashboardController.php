<?php

namespace App\Http\Controllers\Company\Dashboard;

use App\Http\Controllers\Controller;
// use App\Repositories\Company\DashboardRepository;

class DashboardController extends Controller
{
    // protected $dashboardRepository;

    // public function __construct(DashboardRepository $dashboardRepository)
    // {
    //     $this->dashboardRepository = $dashboardRepository;
    // }

    public function index()
    {
        return view('company.dashboard.index');
    }
}
