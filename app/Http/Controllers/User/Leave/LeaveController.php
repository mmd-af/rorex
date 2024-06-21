<?php

namespace App\Http\Controllers\User\Leave;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Leave\LeaveRequest;
use App\Repositories\User\LeaveRepository;

class LeaveController extends Controller
{
    protected $leaveRepository;

    public function __construct(LeaveRepository $leaveRepository)
    {
        $this->leaveRepository = $leaveRepository;
    }

    public function index()
    {
        return view('user.leaves.index');
    }


    public function store(LeaveRequest $request)
    {
        $this->leaveRepository->store($request);
    }
}
