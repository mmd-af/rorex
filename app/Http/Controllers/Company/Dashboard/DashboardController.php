<?php

namespace App\Http\Controllers\Company\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\Company\DashboardRepository;

class DashboardController extends Controller
{
    protected $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function index()
    {
        $company = $this->dashboardRepository->getCompany();
        $companyTrucks = $company->trucks;
        $allTrucks = $this->dashboardRepository->getTrucks();
        $transportations = $this->dashboardRepository->getTransportations();
        return view('company.dashboard.index', compact('companyTrucks', 'allTrucks', 'transportations'));
    }
}
