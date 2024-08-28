<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\User\DashboardRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function index()
    {
        return view('user.dashboard.index');
    }
    
    public function support(Request $request)
    {
        $this->dashboardRepository->support($request);
        return view('user.dashboard.index');
    }
}
