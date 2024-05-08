<?php

namespace App\Http\Controllers\Admin\Truck;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\TruckRepository;
use Illuminate\Http\Request;

class TruckAjaxController extends Controller
{
    protected $truckRepository;

    public function __construct(TruckRepository $truckRepository)
    {
        $this->truckRepository = $truckRepository;
    }

    public function getDataTable(Request $request)
    {
        return $this->truckRepository->getDataTable($request);
    }

    public function show(Request $request)
    {
        return $this->truckRepository->show($request);
    }
    public function active(Request $request)
    {
        return $this->truckRepository->active($request);
    }
}
