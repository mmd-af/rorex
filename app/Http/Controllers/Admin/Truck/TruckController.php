<?php

namespace App\Http\Controllers\Admin\Truck;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\TruckRepository;
use App\Http\Requests\Admin\Truck\StoreRequest;

class TruckController extends Controller
{
    protected $truckRepository;

    public function __construct(TruckRepository $truckRepository)
    {
        $this->truckRepository = $truckRepository;
    }

    public function index()
    {
        return view('admin.trucks.index');
    }

    public function store(StoreRequest $request)
    {
        $this->truckRepository->store($request);
        return redirect()->route('admin.trucks.index');
    }
}
