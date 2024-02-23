<?php

namespace App\Http\Controllers\Admin\StaffRequest;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\StaffRequestRepository;

class StaffRequestController extends Controller
{
    protected $staffRequestRepository;

    public function __construct(StaffRequestRepository $staffRequestRepository)
    {
        $this->staffRequestRepository = $staffRequestRepository;
    }

    public function index()
    {
        return view('admin.staffRequests.index');
    }
}
