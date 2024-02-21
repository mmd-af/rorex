<?php

namespace App\Http\Controllers\User\StaffRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StaffRequest\StaffRequestRequest;
use App\Repositories\User\StaffRequestRepository;

class StaffRequestController extends Controller
{
    protected $staffRequestRepository;

    public function __construct(StaffRequestRepository $staffRequestRepository)
    {
        $this->staffRequestRepository = $staffRequestRepository;
    }

    public function index()
    {
        return view('user.staffRequests.index');
    }
}
