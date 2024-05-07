<?php

namespace App\Http\Controllers\Admin\Transportation;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\TransportationRepository;
use Illuminate\Http\Request;

class TransportationController extends Controller
{
    protected $transportationRepository;

    public function __construct(TransportationRepository $transportationRepository)
    {
        $this->transportationRepository = $transportationRepository;
    }

    public function index()
    {
        return view('admin.transportations.index');
    }

    public function store(Request $request)
    {
        $this->transportationRepository->store($request);
        return redirect()->route('admin.transportations.index');
    }
}
