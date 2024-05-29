<?php

namespace App\Http\Controllers\Company\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\Company\DashboardRepository;
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
        $company = $this->dashboardRepository->getCompany();
        $allTrucks = $this->dashboardRepository->getTrucks();
        $transportations = $this->dashboardRepository->getTransportations();
        $orders = $this->dashboardRepository->getOrders($company);
        return view('company.dashboard.index', compact('company', 'allTrucks', 'transportations', 'orders'));
    }

    public function store(Request $request)
    {
        $this->dashboardRepository->storeTransportOrder($request);
        return redirect()->route('company.dashboard.index');
    }

    public function uploadInvoice(Request $request)
    {
        $this->dashboardRepository->uploadInvoice($request);
        return redirect()->route('company.dashboard.index');
    }
    public function invoiceDestroy($invoice)
    {
        $this->dashboardRepository->invoiceDestroy($invoice);
        return redirect()->route('company.dashboard.index');
    }
}
