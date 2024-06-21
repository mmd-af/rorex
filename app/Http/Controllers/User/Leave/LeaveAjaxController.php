<?php

namespace App\Http\Controllers\User\Leave;

use App\Http\Controllers\Controller;
use App\Repositories\User\LeaveRepository;
use Illuminate\Http\Request;
use App\Http\Requests\User\Leave\LeaveRequest;

class LeaveAjaxController extends Controller
{
    protected $leaveRepository;

    public function __construct(LeaveRepository $leaveRepository)
    {
        $this->leaveRepository = $leaveRepository;
    }

    public function getDataTable(Request $request)
    {
        return $this->leaveRepository->getDataTable($request);
    }
    public function store(LeaveRequest $request)
    {
        $this->leaveRepository->store($request);
    }
}
