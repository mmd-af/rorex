<?php

namespace App\Http\Controllers\Admin\Transportation;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\TransportationRepository;
use Illuminate\Http\Request;

class TransportationAjaxController extends Controller
{
    protected $transportationRepository;

    public function __construct(TransportationRepository $transportationRepository)
    {
        $this->transportationRepository = $transportationRepository;
    }

    public function getDataTable(Request $request)
    {
        return $this->transportationRepository->getDataTable($request);
    }

    public function show(Request $request)
    {
        return $this->transportationRepository->show($request);
    }
    public function showCompaniesOrder(Request $request)
    {
        return $this->transportationRepository->showCompaniesOrder($request);
    }
    public function active(Request $request)
    {
        return $this->transportationRepository->active($request);
    }
    public function getTrucks()
    {
        return $this->transportationRepository->getTrucks();
    }
    public function getCompaniesWithTruck(Request $request)
    {
        return $this->transportationRepository->getCompaniesWithTruck($request);
    }
}
