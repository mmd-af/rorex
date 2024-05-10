<?php

namespace App\Http\Controllers\Company\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\Company\DashboardRepository;
use Illuminate\Http\Request;

class DashboardAjaxController extends Controller
{
    protected $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }
    // public function getTruck()
    // {
    //     return $this->dashboardRepository->getTruck();
    // }
}
