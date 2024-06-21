<?php

namespace App\Http\Controllers\User\Leave;

use App\Http\Controllers\Controller;
use App\Repositories\User\LeaveRepository;
use Illuminate\Http\Request;

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
}
