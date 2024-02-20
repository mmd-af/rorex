<?php

namespace App\Http\Controllers\User\StaffRequest;

use App\Http\Controllers\Controller;
use App\Repositories\User\StaffRequestRepository;
use Illuminate\Http\Request;

class StaffRequestAjaxController extends Controller
{
    protected $staffRequestRepository;

    public function __construct(StaffRequestRepository $staffRequestRepository)
    {
        $this->staffRequestRepository = $staffRequestRepository;
    }

    public function getDataTable(Request $request)
    {
        return $this->staffRequestRepository->getDataTable($request);
    }

    public function getRoles(Request $request)
    {
        return $this->staffRequestRepository->getRoles($request);
    }

    public function getUserWithRole(Request $request)
    {
        return $this->staffRequestRepository->getUserWithRole($request);
    }
}
