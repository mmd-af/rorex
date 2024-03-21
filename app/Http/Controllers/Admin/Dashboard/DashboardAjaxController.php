<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\DashboardRepository;
use Illuminate\Http\Request;

class DashboardAjaxController extends Controller
{
    protected $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function checkNewNotification(Request $request)
    {
        return $this->dashboardRepository->checkNewNotification($request);
    }
    public function getNewNotifications(Request $request)
    {
        return $this->dashboardRepository->getNewNotifications($request);
    }
}
